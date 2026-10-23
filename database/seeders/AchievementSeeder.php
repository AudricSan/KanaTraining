<?php

namespace Database\Seeders;

use App\Enums\AchievementMetric;
use App\Models\Achievement;
use App\Models\CharacterSet;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Same achievements as the former achievements_seed.sql + achievements_seed_v2.sql.
     * [name, icon, description, metric, threshold, character set slug (category only)]
     */
    public const ACHIEVEMENTS = [
        ['Premier pas', '🥉', 'Répondre correctement pour la première fois', AchievementMetric::Xp, 1, null],
        ['Sur la lancée', '🔥', '3 jours d\'affilée avec au moins une bonne réponse', AchievementMetric::Streak, 3, null],
        ['Une semaine de suite', '🔥', '7 jours d\'affilée avec au moins une bonne réponse', AchievementMetric::Streak, 7, null],
        ['Un mois de suite', '🔥', '30 jours d\'affilée avec au moins une bonne réponse', AchievementMetric::Streak, 30, null],
        ['100 XP', '⭐', 'Atteindre 100 XP au total', AchievementMetric::Xp, 100, null],
        ['500 XP', '🌟', 'Atteindre 500 XP au total', AchievementMetric::Xp, 500, null],
        ['1000 XP', '💫', 'Atteindre 1000 XP au total', AchievementMetric::Xp, 1000, null],
        ['Bon score', '🎯', '20 bonnes réponses dans une même session', AchievementMetric::HighScore, 20, null],
        ['Excellent score', '🏆', '50 bonnes réponses dans une même session', AchievementMetric::HighScore, 50, null],
        ['2500 XP', '🎖️', 'Atteindre 2500 XP au total', AchievementMetric::Xp, 2500, null],
        ['5000 XP', '👑', 'Atteindre 5000 XP au total', AchievementMetric::Xp, 5000, null],
        ['10000 XP', '🚀', 'Atteindre 10000 XP au total', AchievementMetric::Xp, 10000, null],
        ['Deux semaines de suite', '🔥', '14 jours d\'affilée avec au moins une bonne réponse', AchievementMetric::Streak, 14, null],
        ['Deux mois de suite', '🔥', '60 jours d\'affilée avec au moins une bonne réponse', AchievementMetric::Streak, 60, null],
        ['100 jours de suite', '💯', '100 jours d\'affilée avec au moins une bonne réponse', AchievementMetric::Streak, 100, null],
        ['Très bon score', '🎯', '75 bonnes réponses dans une même session', AchievementMetric::HighScore, 75, null],
        ['Score parfait', '🏅', '100 bonnes réponses dans une même session', AchievementMetric::HighScore, 100, null],
        ['Maître Hiragana', 'あ', '50 bonnes réponses en Hiragana', AchievementMetric::Category, 50, 'hiragana'],
        ['Maître Katakana', 'ア', '50 bonnes réponses en Katakana', AchievementMetric::Category, 50, 'katakana'],
        ['Maître Kanji N5', '漢', '1000 caractères validés en Kanji N5', AchievementMetric::Category, 1000, 'kanjiN5'],
        ['Maître Kanji N4', '漢', '1000 caractères validés en Kanji N4', AchievementMetric::Category, 1000, 'kanjiN4'],
        ['Maître Kanji N3', '漢', '1000 caractères validés en Kanji N3', AchievementMetric::Category, 1000, 'kanjiN3'],
        ['Maître Kanji N2', '漢', '1000 caractères validés en Kanji N2', AchievementMetric::Category, 1000, 'kanjiN2'],
        ['Maître Kanji N1', '漢', '1000 caractères validés en Kanji N1', AchievementMetric::Category, 1000, 'kanjiN1'],
    ];

    public function run(): void
    {
        $setIds = CharacterSet::pluck('id', 'slug');

        foreach (self::ACHIEVEMENTS as $position => [$name, $icon, $description, $metric, $threshold, $setSlug]) {
            Achievement::updateOrCreate(['name' => $name], [
                'icon' => $icon,
                'description' => $description,
                'metric' => $metric,
                'threshold' => $threshold,
                'character_set_id' => $setSlug !== null ? $setIds[$setSlug] : null,
                'position' => $position,
            ]);
        }
    }
}
