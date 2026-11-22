<?php

namespace Kanatraining\DAO;

class AchievementDAO
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * All achievements, each flagged with whether the given student already unlocked it.
     */
    public function allWithStatus(int $studentId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT a.*, (sa.sa_ID IS NOT NULL) AS unlocked
             FROM achievements a
             LEFT JOIN student_achievements sa
                ON sa.achievements_ID = a.achievements_ID AND sa.student_ID = :studentId
             ORDER BY a.achievements_ID'
        );
        $stmt->execute(['studentId' => $studentId]);

        return $stmt->fetchAll();
    }

    /**
     * Checks the given stats (globalXp, streakDays, highScore) against every achievement's
     * condition and unlocks the ones the student just reached for the first time.
     * Returns the list of newly unlocked achievements.
     */
    public function checkAndUnlock(int $studentId, array $stats): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT a.* FROM achievements a
             WHERE a.achievements_ID NOT IN (
                 SELECT achievements_ID FROM student_achievements WHERE student_ID = :studentId
             )'
        );
        $stmt->execute(['studentId' => $studentId]);
        $locked = $stmt->fetchAll();

        $categoryCorrect = null;

        $newlyUnlocked = [];
        foreach ($locked as $achievement) {
            $condition = $achievement['achievements_Condition'];

            // Category achievements need the per-type correct counts; fetched lazily
            // and only once, since most conditions (xp/streak/highscore) don't need them.
            if (str_starts_with($condition, 'category:') && $categoryCorrect === null) {
                $categoryCorrect = $this->categoryCorrectTotals($studentId);
            }

            if ($this->conditionMet($condition, $stats, $categoryCorrect ?? [])) {
                $this->unlock($studentId, (int) $achievement['achievements_ID']);
                $newlyUnlocked[] = $achievement;
            }
        }

        return $newlyUnlocked;
    }

    /**
     * Total correct answers per character_type, for category-mastery achievements
     * (e.g. "50 bonnes réponses en Hiragana").
     */
    private function categoryCorrectTotals(int $studentId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT character_type, SUM(correct_count) AS total
             FROM student_character_stats
             WHERE student_ID = :studentId
             GROUP BY character_type'
        );
        $stmt->execute(['studentId' => $studentId]);

        $totals = [];
        foreach ($stmt->fetchAll() as $row) {
            $totals[$row['character_type']] = (int) $row['total'];
        }

        return $totals;
    }

    private function conditionMet(string $condition, array $stats, array $categoryCorrect = []): bool
    {
        $parts = explode(':', $condition);
        $metric = $parts[0];

        switch ($metric) {
            case 'xp':
                return $stats['globalXp'] >= (int) ($parts[1] ?? 0);
            case 'streak':
                return $stats['streakDays'] >= (int) ($parts[1] ?? 0);
            case 'highscore':
                return $stats['highScore'] >= (int) ($parts[1] ?? 0);
            case 'category':
                $type = $parts[1] ?? '';
                $threshold = (int) ($parts[2] ?? 0);

                return ($categoryCorrect[$type] ?? 0) >= $threshold;
            default:
                return false;
        }
    }

    private function unlock(int $studentId, int $achievementId): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT IGNORE INTO student_achievements (student_ID, achievements_ID) VALUES (:studentId, :achievementId)'
        );
        $stmt->execute(['studentId' => $studentId, 'achievementId' => $achievementId]);
    }
}
