<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class TwitchLoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Each call to /callback receives the next of the given [name, avatar] profiles.
     */
    private function fakeTwitchUsers(array ...$profiles): void
    {
        $users = array_map(fn ($p) => (new SocialiteUser)->map([
            'id' => '123456',
            'nickname' => strtolower($p[0]),
            'name' => $p[0],
            'email' => 'kitsune@example.com',
            'avatar' => $p[1],
        ]), $profiles);

        $provider = Mockery::mock(Provider::class);
        $provider->shouldReceive('user')->andReturn(...$users);
        Socialite::shouldReceive('driver')->with('twitch')->andReturn($provider);
    }

    public function test_login_page(): void
    {
        $this->get('/login')->assertOk()->assertSee('Se connecter avec Twitch');
    }

    public function test_redirects_to_twitch(): void
    {
        config(['services.twitch.client_id' => 'abc', 'services.twitch.client_secret' => 'x', 'services.twitch.redirect' => 'http://localhost/callback']);

        $this->get('/login/twitch')->assertRedirectContains('id.twitch.tv/oauth2/authorize');
    }

    public function test_first_login_creates_the_student_and_next_ones_refresh_the_profile(): void
    {
        $this->fakeTwitchUsers(['Kitsune', 'https://cdn/a.png'], ['Kitsune2', 'https://cdn/b.png']);
        $this->get('/callback?code=x&state=y')->assertRedirect('/student');
        $this->assertAuthenticated();

        $user = User::sole();
        $this->assertSame(['123456', 'Kitsune', 'https://cdn/a.png'], [$user->twitch_id, $user->name, $user->avatar]);

        auth()->logout();
        $user->update(['global_xp' => 77]);
        $this->get('/callback?code=x&state=y');

        $this->assertSame(1, User::count());
        $this->assertSame(['Kitsune2', 'https://cdn/b.png', 77], [$user->fresh()->name, $user->fresh()->avatar, $user->fresh()->global_xp]);
    }

    public function test_denied_authorization_goes_back_to_login(): void
    {
        $this->get('/callback?error=access_denied')->assertRedirect('/login');
        $this->assertGuest();
    }
}
