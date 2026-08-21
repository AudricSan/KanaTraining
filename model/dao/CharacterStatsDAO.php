<?php

namespace Kanatraining\DAO;

class CharacterStatsDAO
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function recordAttempt(int $studentId, string $type, string $character, bool $correct): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO student_character_stats (student_ID, character_type, character_key, wrong_count, correct_count)
             VALUES (:studentId, :type, :character, :wrong, :correct)
             ON DUPLICATE KEY UPDATE
                 wrong_count = wrong_count + :wrongInc,
                 correct_count = correct_count + :correctInc'
        );
        $stmt->execute([
            'studentId'   => $studentId,
            'type'        => $type,
            'character'   => $character,
            'wrong'       => $correct ? 0 : 1,
            'correct'     => $correct ? 1 : 0,
            'wrongInc'    => $correct ? 0 : 1,
            'correctInc'  => $correct ? 1 : 0,
        ]);
    }

    /**
     * Returns { character_type: { character_key: wrong_count } } for every character
     * the student has ever missed at least once — used client-side to bias the next
     * character drawn towards the ones they struggle with.
     */
    public function weightsFor(int $studentId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT character_type, character_key, wrong_count
             FROM student_character_stats
             WHERE student_ID = :studentId AND wrong_count > 0'
        );
        $stmt->execute(['studentId' => $studentId]);

        $weights = [];
        foreach ($stmt->fetchAll() as $row) {
            $weights[$row['character_type']][$row['character_key']] = (int) $row['wrong_count'];
        }

        return $weights;
    }

    /**
     * The student's most-missed characters, worst first — for display on their profile.
     */
    public function topWeak(int $studentId, int $limit = 5): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT character_type, character_key, wrong_count, correct_count
             FROM student_character_stats
             WHERE student_ID = :studentId AND wrong_count > 0
             ORDER BY wrong_count DESC
             LIMIT :limit'
        );
        $stmt->bindValue('studentId', $studentId, \PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
