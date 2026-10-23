<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\CharacterSet;
use App\Models\CharacterStat;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    protected $seeder = DatabaseSeeder::class;

    public function test_quiz_page_lists_every_set_and_shows_live_stats_when_logged_in(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('id="kanjiN1"', false)
            ->assertSee('const isLoggedIn = false', false)
            ->assertDontSee('liveStats');

        $user = User::factory()->create(['global_xp' => 42, 'streak_days' => 3]);
        $set = CharacterSet::where('slug', 'katakana')->sole();
        CharacterStat::create(['user_id' => $user->id, 'character_set_id' => $set->id, 'character' => 'ア', 'wrong_count' => 2]);

        $this->actingAs($user)->get('/')
            ->assertOk()
            ->assertSee('<span id="liveXp">42</span>', false)
            ->assertSee('const weakCharacters = '.json_encode(['katakana' => ['ア' => 2]], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT), false);
    }

    public function test_characters_endpoint_returns_kana_and_kanji_in_the_quiz_format(): void
    {
        $response = $this->getJson('/api/characters')->assertOk();

        $this->assertSame(['あ', 'a', true], $response->json('kana.hiragana.0'));
        $this->assertSame(['ゐ', 'wi', false], collect($response->json('kana.hiragana'))->firstWhere(0, 'ゐ'));
        $this->assertSame(['kanji' => '一', 'read' => 'いち', 'translate' => 'one'], $response->json('kanji.kanjiN5.0'));
        $this->assertCount(1232, $response->json('kanji.kanjiN1'));
    }

    public function test_profile_requires_login(): void
    {
        $this->get('/student')->assertRedirect('/login');
    }

    public function test_profile_shows_stats_achievements_and_weak_characters(): void
    {
        $user = User::factory()->create(['name' => 'Kitsune', 'global_xp' => 1234]);
        $user->achievements()->attach(Achievement::where('name', 'Premier pas')->sole());
        $set = CharacterSet::where('slug', 'kanjiN4')->sole();
        CharacterStat::create(['user_id' => $user->id, 'character_set_id' => $set->id, 'character' => '猫', 'wrong_count' => 7]);

        $this->actingAs($user)->get('/student')
            ->assertOk()
            ->assertSee('Kitsune')
            ->assertSee('<strong>1234</strong>', false)
            ->assertSee('badge unlocked', false)
            ->assertSee('猫')
            ->assertSee('7×');
    }

    public function test_profile_without_stats_shows_the_mascot(): void
    {
        $this->actingAs(User::factory()->create())->get('/student')->assertSee('weak-empty-mascot');
    }

    public function test_leaderboard_orders_by_xp_then_streak_and_highlights_current_user(): void
    {
        $a = User::factory()->create(['name' => 'Alpha', 'global_xp' => 10, 'streak_days' => 1]);
        User::factory()->create(['name' => 'Bravo', 'global_xp' => 50]);
        User::factory()->create(['name' => 'Charlie', 'global_xp' => 10, 'streak_days' => 5]);

        $this->actingAs($a)->get('/classement')
            ->assertOk()
            ->assertSeeInOrder(['Bravo', 'Charlie', 'Alpha'])
            ->assertSee('leaderboard-row current', false);
    }

    public function test_empty_leaderboard_shows_the_mascot(): void
    {
        $this->get('/classement')->assertSee('leaderboard-empty-mascot');
    }

    public function test_unknown_page_renders_the_404_mascot(): void
    {
        $this->get('/nope')->assertNotFound()->assertSee('http-error/404.png');
    }

    public function test_logout(): void
    {
        $this->actingAs(User::factory()->create())->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_logout_no_longer_answers_get(): void
    {
        $this->actingAs(User::factory()->create())->get('/logout')->assertMethodNotAllowed();
        $this->assertAuthenticated();
    }
}
