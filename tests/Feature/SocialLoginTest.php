<?php

namespace Tests\Feature;

use App\Enums\SocialProvider;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class SocialLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (SocialProvider::cases() as $provider) {
            config(["services.{$provider->value}" => [
                'client_id' => 'abc',
                'client_secret' => 'x',
                'redirect' => "http://localhost/login/{$provider->value}/callback",
            ]]);
        }
    }

    private function identity(string $id, string $name, string $avatar = 'https://cdn/a.png'): SocialiteUser
    {
        return (new SocialiteUser)->map([
            'id' => $id,
            'nickname' => strtolower($name),
            'name' => $name,
            'email' => 'kitsune@example.com',
            'avatar' => $avatar,
        ]);
    }

    /**
     * Each callback for this provider receives the next of the given identities.
     */
    private function fakeProvider(string $driver, SocialiteUser ...$identities): void
    {
        $provider = Mockery::mock(Provider::class);
        $provider->shouldReceive('user')->andReturn(...$identities);
        Socialite::shouldReceive('driver')->with($driver)->andReturn($provider);
    }

    public function test_login_page_lists_the_configured_providers(): void
    {
        config(['services.discord.client_id' => null]);

        $this->get('/login')->assertOk()
            ->assertSee('Se connecter avec Twitch')
            ->assertSee('Se connecter avec Google')
            ->assertDontSee('Se connecter avec Discord');
    }

    public function test_redirects_to_each_provider(): void
    {
        $this->get('/login/twitch')->assertRedirectContains('id.twitch.tv/oauth2/authorize');
        $this->get('/login/google')->assertRedirectContains('accounts.google.com');
        $this->get('/login/discord')->assertRedirectContains('discord.com');
    }

    public function test_unknown_or_unconfigured_provider_is_not_found(): void
    {
        config(['services.google.client_id' => null]);

        $this->get('/login/github')->assertNotFound();
        $this->get('/login/google')->assertNotFound();
    }

    public function test_first_login_creates_the_student_and_next_ones_refresh_the_profile(): void
    {
        $this->fakeProvider('twitch', $this->identity('123456', 'Kitsune'), $this->identity('123456', 'Kitsune2', 'https://cdn/b.png'));
        $this->get('/callback?code=x&state=y')->assertRedirect('/student');
        $this->assertAuthenticated();

        $user = User::sole();
        $this->assertSame(['Kitsune', 'https://cdn/a.png'], [$user->name, $user->avatar]);
        $this->assertSame(['twitch', '123456'], [$user->socialAccounts->sole()->provider->value, $user->socialAccounts->sole()->provider_id]);

        auth()->logout();
        $user->update(['global_xp' => 77]);
        $this->get('/callback?code=x&state=y');

        $this->assertSame(1, User::count());
        $this->assertSame(['Kitsune2', 'https://cdn/b.png', 77], [$user->fresh()->name, $user->fresh()->avatar, $user->fresh()->global_xp]);
    }

    public function test_google_and_discord_create_their_own_student(): void
    {
        $this->fakeProvider('google', $this->identity('g-1', 'Kitsune'));
        $this->get('/login/google/callback?code=x&state=y')->assertRedirect('/student');
        auth()->logout();

        $this->fakeProvider('discord', $this->identity('d-1', 'Kitsune'));
        $this->get('/login/discord/callback?code=x&state=y')->assertRedirect('/student');

        // Same email, but accounts are never merged without the student linking them
        $this->assertSame(2, User::count());
    }

    public function test_logged_in_student_links_another_provider(): void
    {
        $user = User::factory()->create(['name' => 'Kitsune']);
        $user->socialAccounts()->create(['provider' => SocialProvider::Twitch, 'provider_id' => '1']);

        $this->fakeProvider('discord', $this->identity('d-1', 'Autre nom', 'https://cdn/d.png'));
        $this->actingAs($user)->get('/login/discord/callback?code=x&state=y')
            ->assertRedirect('/student')->assertSessionHas('status', 'Compte Discord lié.');

        $this->assertEqualsCanonicalizing(['twitch', 'discord'], $user->socialAccounts()->pluck('provider')->map->value->all());
        // The profile keeps the name of the provider the student signed up with
        $this->assertSame('Kitsune', $user->fresh()->name);

        auth()->logout();
        $this->get('/login/discord/callback?code=x&state=y');
        $this->assertAuthenticatedAs($user);
    }

    public function test_cannot_link_an_identity_owned_by_another_student(): void
    {
        $other = User::factory()->create();
        $other->socialAccounts()->create(['provider' => SocialProvider::Google, 'provider_id' => 'g-1']);
        $user = User::factory()->create();

        $this->fakeProvider('google', $this->identity('g-1', 'Kitsune'));
        $this->actingAs($user)->get('/login/google/callback?code=x&state=y')
            ->assertRedirect('/student')->assertSessionHas('error', 'Ce compte Google est déjà lié à un autre profil.');

        $this->assertSame($other->id, SocialAccount::sole()->user_id);
    }

    public function test_unlink_keeps_at_least_one_provider(): void
    {
        $user = User::factory()->create();
        $user->socialAccounts()->create(['provider' => SocialProvider::Twitch, 'provider_id' => '1']);
        $user->socialAccounts()->create(['provider' => SocialProvider::Google, 'provider_id' => 'g-1']);

        $this->actingAs($user)->from('/student')->delete('/login/google')->assertRedirect('/student');
        $this->actingAs($user)->from('/student')->delete('/login/twitch')->assertSessionHas('error');

        $this->assertSame(['twitch'], $user->socialAccounts()->pluck('provider')->map->value->all());
    }

    public function test_denied_authorization_goes_back_to_login_with_a_message(): void
    {
        $this->get('/callback?error=access_denied')->assertRedirect('/login')->assertSessionHas('error', 'Connexion à Twitch annulée.');
        $this->assertGuest();
    }

    public function test_expired_state_and_provider_errors_show_a_message(): void
    {
        $provider = Mockery::mock(Provider::class);
        $errors = [new InvalidStateException, new RuntimeException('down')];
        $provider->shouldReceive('user')->andReturnUsing(function () use (&$errors) {
            throw array_shift($errors);
        });
        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $this->get('/login/google/callback?code=x&state=y')->assertSessionHas('error', 'La demande de connexion a expiré, réessaie.');
        $this->get('/login/google/callback?code=x&state=y')->assertSessionHas('error', "Google n'a pas répondu, réessaie dans un instant.");
        $this->assertGuest();
    }
}
