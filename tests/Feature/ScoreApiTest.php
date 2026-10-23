<?php

namespace Tests\Feature;

use App\Models\CharacterSet;
use App\Models\CharacterStat;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScoreApiTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    protected $seeder = DatabaseSeeder::class;

    private function answer(User $user, array $payload)
    {
        return $this->actingAs($user)->postJson('/api/score', $payload + ['good' => 0, 'total' => 0]);
    }

    public function test_guests_get_a_401(): void
    {
        $this->postJson('/api/score', ['correct' => true, 'good' => 1, 'total' => 1])->assertUnauthorized();
    }

    public function test_correct_answer_earns_the_set_xp_and_starts_a_streak(): void
    {
        $user = User::factory()->create();

        $this->answer($user, ['correct' => true, 'good' => 1, 'total' => 1, 'type' => 'kanjiN1', 'character' => '凜'])
            ->assertOk()
            ->assertJson(['globalXp' => 8, 'streakDays' => 1, 'highScore' => 1, 'lastScore' => 1]);

        $this->assertSame(today()->toDateString(), $user->fresh()->last_played_date->toDateString());
    }

    public function test_unknown_or_missing_type_is_worth_one_xp(): void
    {
        $user = User::factory()->create();

        $this->answer($user, ['correct' => true])->assertJson(['globalXp' => 1]);
        $this->answer($user, ['correct' => true, 'type' => 'nope'])->assertUnprocessable();
    }

    public function test_wrong_answer_costs_xp_but_never_below_zero(): void
    {
        $user = User::factory()->create(['global_xp' => 3]);

        $this->answer($user, ['correct' => false, 'type' => 'hiraganaDakuonCombo'])->assertJson(['globalXp' => 0]);
        $this->answer($user, ['correct' => false, 'type' => 'kanjiN1'])->assertJson(['globalXp' => 0]);
    }

    public function test_streak_grows_when_playing_the_next_day_and_resets_after_a_gap(): void
    {
        $user = User::factory()->create(['streak_days' => 4, 'last_played_date' => today()->subDay()]);
        $this->answer($user, ['correct' => true])->assertJson(['streakDays' => 5]);

        // Second correct answer the same day: unchanged
        $this->answer($user, ['correct' => true])->assertJson(['streakDays' => 5]);

        $other = User::factory()->create(['streak_days' => 9, 'last_played_date' => today()->subDays(3)]);
        $this->answer($other, ['correct' => true])->assertJson(['streakDays' => 1]);
    }

    public function test_wrong_answers_do_not_touch_the_streak(): void
    {
        $user = User::factory()->create(['streak_days' => 4, 'last_played_date' => today()->subDays(5)]);

        $this->answer($user, ['correct' => false])->assertJson(['streakDays' => 4]);
        $this->assertTrue($user->fresh()->last_played_date->isSameDay(today()->subDays(5)));
    }

    public function test_high_score_keeps_the_best_session_and_last_score_the_latest(): void
    {
        $user = User::factory()->create(['high_score' => 30]);

        $this->answer($user, ['correct' => true, 'good' => 12, 'total' => 15])->assertJson(['highScore' => 30, 'lastScore' => 12]);
        $this->answer($user, ['correct' => true, 'good' => 31, 'total' => 40])->assertJson(['highScore' => 31, 'lastScore' => 31]);
    }

    public function test_each_attempt_is_counted_per_character(): void
    {
        $user = User::factory()->create();

        $this->answer($user, ['correct' => false, 'type' => 'hiragana', 'character' => 'あ']);
        $this->answer($user, ['correct' => false, 'type' => 'hiragana', 'character' => 'あ']);
        $this->answer($user, ['correct' => true, 'type' => 'hiragana', 'character' => 'あ']);

        $stat = CharacterStat::sole();
        $this->assertSame([2, 1], [$stat->wrong_count, $stat->correct_count]);
        $this->assertSame(['hiragana' => ['あ' => 2]], $user->weakCharacterWeights());
    }

    public function test_reached_achievements_are_unlocked_once_and_returned(): void
    {
        $user = User::factory()->create(['global_xp' => 99, 'streak_days' => 2, 'last_played_date' => today()->subDay()]);

        $this->answer($user, ['correct' => true, 'good' => 20, 'total' => 20])
            ->assertJsonPath('newAchievements', [
                ['name' => 'Premier pas', 'icon' => '🥉'],
                ['name' => 'Sur la lancée', 'icon' => '🔥'],
                ['name' => '100 XP', 'icon' => '⭐'],
                ['name' => 'Bon score', 'icon' => '🎯'],
            ]);

        $this->answer($user, ['correct' => true, 'good' => 21, 'total' => 21])->assertJsonPath('newAchievements', []);
        $this->assertSame(4, $user->achievements()->count());
    }

    public function test_category_achievement_counts_correct_answers_of_its_set_only(): void
    {
        $user = User::factory()->create();
        $set = CharacterSet::where('slug', 'hiragana')->sole();
        CharacterStat::create(['user_id' => $user->id, 'character_set_id' => $set->id, 'character' => 'い', 'correct_count' => 49]);

        $this->answer($user, ['correct' => true, 'type' => 'hiraganaCombo', 'character' => 'きゃ'])
            ->assertJsonMissing(['name' => 'Maître Hiragana']);

        $this->answer($user, ['correct' => true, 'type' => 'hiragana', 'character' => 'あ'])
            ->assertJsonFragment(['name' => 'Maître Hiragana']);
    }
}
