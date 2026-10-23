<?php

namespace Kanatraining\DAO;

class StudentDAO
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByTwitchId(string $twitchId): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM student WHERE student_TwitchID = :twitchId');
        $stmt->execute(['twitchId' => $twitchId]);
        $row = $stmt->fetch();

        return $row !== false ? $row : null;
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM student WHERE student_ID = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row !== false ? $row : null;
    }

    public function create(string $twitchId, string $name, string $avatar, ?string $email): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO student (student_TwitchID, student_Name, student_Avatar, student_Email)
             VALUES (:twitchId, :name, :avatar, :email)'
        );
        $stmt->execute([
            'twitchId' => $twitchId,
            'name'     => $name,
            'avatar'   => $avatar,
            'email'    => $email,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function updateProfile(int $id, string $name, string $avatar, ?string $email): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE student SET student_Name = :name, student_Avatar = :avatar, student_Email = :email
             WHERE student_ID = :id'
        );
        $stmt->execute([
            'name'   => $name,
            'avatar' => $avatar,
            'email'  => $email,
            'id'     => $id,
        ]);
    }

    /**
     * The top students by XP, best first — for the public leaderboard.
     */
    public function topByXp(int $limit = 20): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT student_ID, student_Name, student_Avatar, student_GlobalXp, student_StreakDays
             FROM student
             ORDER BY student_GlobalXp DESC, student_StreakDays DESC
             LIMIT :limit'
        );
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * XP at stake for one answer, by difficulty — harder content is worth more,
     * both gained on a correct answer and lost on a wrong one.
     */
    private const XP_BY_TYPE = [
        'hiragana'             => 1,
        'katakana'             => 1,
        'hiraganaCombo'        => 2,
        'katakanaCombo'        => 2,
        'hiraganaDakuon'       => 2,
        'katakanaDakuon'       => 2,
        'hiraganaDakuonCombo'  => 3,
        'katakanaDakuonCombo'  => 3,
        'kanjiN5'              => 3,
        'kanjiN4'              => 4,
        'kanjiN3'              => 5,
        'kanjiN2'              => 6,
        'kanjiN1'              => 8,
    ];

    /**
     * Records the outcome of one answer and updates XP / streak / score.
     * $good/$total are the running counters of the current browser session.
     * Returns the updated stats.
     */
    public function recordAnswer(int $id, bool $correct, int $good, int $total, string $type = ''): array
    {
        $student = $this->find($id);
        if ($student === null) {
            throw new \RuntimeException('Unknown student: ' . $id);
        }

        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        $xp = (int) $student['student_GlobalXp'];
        $streak = (int) $student['student_StreakDays'];
        $lastPlayedDate = $student['student_LastPlayedDate'];
        $points = self::XP_BY_TYPE[$type] ?? 1;

        if ($correct) {
            $xp += $points;

            if ($lastPlayedDate === $today) {
                // already played today, streak unchanged
            } elseif ($lastPlayedDate === $yesterday) {
                $streak += 1;
            } else {
                $streak = 1;
            }

            $lastPlayedDate = $today;
        } else {
            $xp = max(0, $xp - $points);
        }

        $highScore = max((int) $student['student_HighScore'], $good);
        $lastScore = $good;

        $stmt = $this->pdo->prepare(
            'UPDATE student SET
                student_GlobalXp = :xp,
                student_StreakDays = :streak,
                student_HighScore = :highScore,
                student_LastScore = :lastScore,
                student_LastPlayedDate = :lastPlayedDate
             WHERE student_ID = :id'
        );
        $stmt->execute([
            'xp'             => $xp,
            'streak'         => $streak,
            'highScore'      => $highScore,
            'lastScore'      => $lastScore,
            'lastPlayedDate' => $lastPlayedDate,
            'id'             => $id,
        ]);

        return [
            'globalXp'   => $xp,
            'streakDays' => $streak,
            'highScore'  => $highScore,
            'lastScore'  => $lastScore,
        ];
    }
}
