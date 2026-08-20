<?php

// Auth guard already enforced by the /student route before head() is called.
$env = new \Kanatraining\env();
$pdo = \Kanatraining\Database::get($env);
$studentDAO = new \Kanatraining\DAO\StudentDAO($pdo);
$achievementDAO = new \Kanatraining\DAO\AchievementDAO($pdo);
$studentId = (int) $_SESSION['student_id'];
$student = $studentDAO->find($studentId);

if ($student === null) {
    // Session refers to a student that no longer exists in the database
    unset($_SESSION['student_id']);
    echo "<main><section class='_2'><p>Session invalide. <a href='/login'>Se reconnecter</a></p></section></main>";
    return;
}

$name = htmlspecialchars($student['student_Name']);
$avatar = htmlspecialchars($student['student_Avatar']);

$badgesHtml = '';
foreach ($achievementDAO->allWithStatus($studentId) as $a) {
    $state = $a['unlocked'] ? 'unlocked' : 'locked';
    $icon = htmlspecialchars($a['achievements_Icon']);
    $badgeName = htmlspecialchars($a['achievements_Name']);
    $desc = htmlspecialchars($a['achievements_Description']);

    $badgesHtml .= "<li class='badge {$state}' title=\"{$desc}\"><span class='badge-icon'>{$icon}</span><span class='badge-name'>{$badgeName}</span></li>";
}

echo "
    <main>
        <section class='_1'>
            <nav></nav>
        </section>

        <section class='_2 student-profile'>
            <img class='student-avatar' src='{$avatar}' alt='Avatar Twitch de {$name}' width='96' height='96' />
            <h2>{$name}</h2>

            <ul class='student-stats'>
                <li><span>XP total</span> <strong>{$student['student_GlobalXp']}</strong></li>
                <li><span>Série de jours</span> <strong>{$student['student_StreakDays']}</strong></li>
                <li><span>Meilleur score</span> <strong>{$student['student_HighScore']}</strong></li>
                <li><span>Dernier score</span> <strong>{$student['student_LastScore']}</strong></li>
            </ul>

            <h3 class='achievements-title'>Succès</h3>
            <ul class='achievements-list'>{$badgesHtml}</ul>

            <p><a href='/'>Retour à l'entraînement</a></p>
            <p><a href='/logout'>Se déconnecter</a></p>
        </section>
    </main>
";
