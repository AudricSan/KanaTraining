<?php

$env = new \Kanatraining\env();
$pdo = \Kanatraining\Database::get($env);
$studentDAO = new \Kanatraining\DAO\StudentDAO($pdo);

$currentStudentId = !empty($_SESSION['student_id']) ? (int) $_SESSION['student_id'] : null;

$rowsHtml = '';
$rank = 0;
foreach ($studentDAO->topByXp(20) as $s) {
    $rank++;
    $isCurrent = $currentStudentId !== null && (int) $s['student_ID'] === $currentStudentId;
    $rowClass = $isCurrent ? 'leaderboard-row current' : 'leaderboard-row';

    $avatar = htmlspecialchars($s['student_Avatar']);
    $name = htmlspecialchars($s['student_Name']);
    $xp = (int) $s['student_GlobalXp'];
    $streak = (int) $s['student_StreakDays'];

    $rowsHtml .= "<li class='{$rowClass}'>
        <span class='leaderboard-rank'>{$rank}</span>
        <img class='leaderboard-avatar' src='{$avatar}' alt='' width='36' height='36' />
        <span class='leaderboard-name'>{$name}</span>
        <span class='leaderboard-xp'><span class='material-icons-round'>bolt</span> {$xp}</span>
        <span class='leaderboard-streak'>🔥 {$streak}</span>
    </li>";
}

if ($rowsHtml === '') {
    $rowsHtml = "<li class='leaderboard-empty'>Aucun élève classé pour le moment.</li>";
}

echo "
    <main>
        <section class='_1'></section>

        <section class='_2 leaderboard'>
            <h2>Classement</h2>

            <ol class='leaderboard-list'>{$rowsHtml}</ol>
        </section>
    </main>
";
