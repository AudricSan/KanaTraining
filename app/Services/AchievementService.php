<?php

namespace App\Services;

use App\Enums\AchievementMetric;
use App\Models\Achievement;
use App\Models\User;
use Illuminate\Support\Collection;

class AchievementService
{
    /**
     * Unlocks every achievement the user now meets and doesn't have yet.
     *
     * @return Collection<int, Achievement> the newly unlocked achievements
     */
    public function checkAndUnlock(User $user): Collection
    {
        $locked = Achievement::whereDoesntHave('users', fn ($query) => $query->whereKey($user->id))
            ->orderBy('position')
            ->get();

        // Category achievements need per-set correct totals: fetched lazily and only once
        $categoryTotals = null;

        $unlocked = $locked->filter(function (Achievement $achievement) use ($user, &$categoryTotals) {
            if ($achievement->metric === AchievementMetric::Category) {
                $categoryTotals ??= $this->correctTotalsBySet($user);
            }

            return $this->conditionMet($achievement, $user, $categoryTotals ?? []);
        })->values();

        if ($unlocked->isNotEmpty()) {
            $user->achievements()->syncWithoutDetaching(
                $unlocked->mapWithKeys(fn (Achievement $a) => [$a->id => ['unlocked_at' => now()]])->all()
            );
        }

        return $unlocked;
    }

    /**
     * Every achievement, each flagged with whether the user unlocked it.
     *
     * @return Collection<int, Achievement>
     */
    public function allWithStatus(User $user): Collection
    {
        $unlockedIds = $user->achievements()->pluck('achievements.id')->flip();

        return Achievement::orderBy('position')->get()
            ->each(fn (Achievement $a) => $a->setAttribute('unlocked', $unlockedIds->has($a->id)));
    }

    /**
     * @param  array<int, int>  $categoryTotals  correct answers by character_set_id
     */
    private function conditionMet(Achievement $achievement, User $user, array $categoryTotals): bool
    {
        return match ($achievement->metric) {
            AchievementMetric::Xp => $user->global_xp >= $achievement->threshold,
            AchievementMetric::Streak => $user->streak_days >= $achievement->threshold,
            AchievementMetric::HighScore => $user->high_score >= $achievement->threshold,
            AchievementMetric::Category => ($categoryTotals[$achievement->character_set_id] ?? 0) >= $achievement->threshold,
        };
    }

    /**
     * @return array<int, int>
     */
    private function correctTotalsBySet(User $user): array
    {
        return $user->characterStats()
            ->selectRaw('character_set_id, SUM(correct_count) as total')
            ->groupBy('character_set_id')
            ->pluck('total', 'character_set_id')
            ->map(fn ($total) => (int) $total)
            ->all();
    }
}
