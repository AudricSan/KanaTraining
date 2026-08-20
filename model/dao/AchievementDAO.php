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

        $newlyUnlocked = [];
        foreach ($locked as $achievement) {
            if ($this->conditionMet($achievement['achievements_Condition'], $stats)) {
                $this->unlock($studentId, (int) $achievement['achievements_ID']);
                $newlyUnlocked[] = $achievement;
            }
        }

        return $newlyUnlocked;
    }

    private function conditionMet(string $condition, array $stats): bool
    {
        [$metric, $threshold] = array_pad(explode(':', $condition, 2), 2, null);
        $threshold = (int) $threshold;

        switch ($metric) {
            case 'xp':
                return $stats['globalXp'] >= $threshold;
            case 'streak':
                return $stats['streakDays'] >= $threshold;
            case 'highscore':
                return $stats['highScore'] >= $threshold;
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
