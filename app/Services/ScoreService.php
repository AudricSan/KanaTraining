<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\CharacterSet;
use App\Models\CharacterStat;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Records one quiz answer: XP, daily streak, scores, per-character stats,
 * then unlocks the achievements the user just reached.
 */
class ScoreService
{
    public function __construct(private AchievementService $achievements) {}

    /**
     * $good is the running count of correct answers of the current browser session.
     *
     * @return array{globalXp: int, streakDays: int, highScore: int, lastScore: int, newAchievements: Collection<int, Achievement>}
     */
    public function recordAnswer(User $user, bool $correct, int $good, ?CharacterSet $set = null, ?string $character = null): array
    {
        return DB::transaction(function () use ($user, $correct, $good, $set, $character) {
            $user = User::whereKey($user->getKey())->lockForUpdate()->firstOrFail();

            $this->applyAnswer($user, $correct, $good, $set?->xp_value ?? 1);
            $user->save();

            if ($set !== null && $character !== null && $character !== '') {
                $this->recordAttempt($user, $set, $character, $correct);
            }

            return [
                'globalXp' => $user->global_xp,
                'streakDays' => $user->streak_days,
                'highScore' => $user->high_score,
                'lastScore' => $user->last_score,
                'newAchievements' => $this->achievements->checkAndUnlock($user),
            ];
        });
    }

    /**
     * A correct answer earns the set's XP and keeps the streak alive (+1 if the
     * user last played yesterday, reset to 1 after a gap); a wrong one costs the
     * same XP, never going below 0.
     */
    public function applyAnswer(User $user, bool $correct, int $good, int $points): void
    {
        if ($correct) {
            $user->global_xp += $points;

            $today = today();
            $last = $user->last_played_date;

            if ($last === null || ! $last->isSameDay($today)) {
                $user->streak_days = $last !== null && $last->isSameDay($today->copy()->subDay())
                    ? $user->streak_days + 1
                    : 1;
            }

            $user->last_played_date = $today;
        } else {
            $user->global_xp = max(0, $user->global_xp - $points);
        }

        $user->high_score = max($user->high_score, $good);
        $user->last_score = $good;
    }

    private function recordAttempt(User $user, CharacterSet $set, string $character, bool $correct): void
    {
        $stat = CharacterStat::firstOrCreate([
            'user_id' => $user->id,
            'character_set_id' => $set->id,
            'character' => $character,
        ]);

        $stat->increment($correct ? 'correct_count' : 'wrong_count');
    }
}
