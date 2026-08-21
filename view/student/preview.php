<?php

// Page de test (donnees factices, sans BDD) pour visualiser la mise en page
// de /student sans avoir a etre connecte. Accessible via /student/preview.

$name = 'Audric_San';
$avatar = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="96" height="96"><rect width="96" height="96" fill="#9146ff"/><text x="48" y="62" font-size="42" text-anchor="middle" fill="#fff" font-family="sans-serif">A</text></svg>');

$student = [
    'student_GlobalXp' => 12480,
    'student_StreakDays' => 27,
    'student_HighScore' => 98,
    'student_LastScore' => 84,
];

$badges = [
    ['icon' => '🥉', 'name' => 'Premier pas', 'desc' => 'Répondre correctement pour la première fois', 'unlocked' => true],
    ['icon' => '🔥', 'name' => 'Sur la lancée', 'desc' => "3 jours d'affilée", 'unlocked' => true],
    ['icon' => '🔥', 'name' => 'Une semaine de suite', 'desc' => "7 jours d'affilée", 'unlocked' => true],
    ['icon' => '🔥', 'name' => 'Un mois de suite', 'desc' => "30 jours d'affilée", 'unlocked' => false],
    ['icon' => '⭐', 'name' => '100 XP', 'desc' => 'Atteindre 100 XP au total', 'unlocked' => true],
    ['icon' => '🌟', 'name' => '500 XP', 'desc' => 'Atteindre 500 XP au total', 'unlocked' => true],
    ['icon' => '💫', 'name' => '1000 XP', 'desc' => 'Atteindre 1000 XP au total', 'unlocked' => true],
    ['icon' => '🎖️', 'name' => '2500 XP', 'desc' => 'Atteindre 2500 XP au total', 'unlocked' => false],
    ['icon' => '👑', 'name' => '5000 XP', 'desc' => 'Atteindre 5000 XP au total', 'unlocked' => false],
    ['icon' => '🚀', 'name' => '10000 XP', 'desc' => 'Atteindre 10000 XP au total', 'unlocked' => false],
    ['icon' => '🎯', 'name' => 'Bon score', 'desc' => '20 bonnes réponses en une session', 'unlocked' => true],
    ['icon' => '🏆', 'name' => 'Excellent score', 'desc' => '50 bonnes réponses en une session', 'unlocked' => false],
    ['icon' => '🎯', 'name' => 'Très bon score', 'desc' => '75 bonnes réponses en une session', 'unlocked' => false],
    ['icon' => '🏅', 'name' => 'Score parfait', 'desc' => '100 bonnes réponses en une session', 'unlocked' => false],
    ['icon' => 'あ', 'name' => 'Maître Hiragana', 'desc' => '50 bonnes réponses en Hiragana', 'unlocked' => true],
    ['icon' => 'ア', 'name' => 'Maître Katakana', 'desc' => '50 bonnes réponses en Katakana', 'unlocked' => false],
    ['icon' => '漢', 'name' => 'Maître Kanji N5', 'desc' => '1000 caractères validés en Kanji N5', 'unlocked' => false],
    ['icon' => '漢', 'name' => 'Maître Kanji N4', 'desc' => '1000 caractères validés en Kanji N4', 'unlocked' => false],
    ['icon' => '漢', 'name' => 'Maître Kanji N3', 'desc' => '1000 caractères validés en Kanji N3', 'unlocked' => false],
    ['icon' => '漢', 'name' => 'Maître Kanji N2', 'desc' => '1000 caractères validés en Kanji N2', 'unlocked' => false],
    ['icon' => '漢', 'name' => 'Maître Kanji N1', 'desc' => '1000 caractères validés en Kanji N1', 'unlocked' => false],
];

$badgesHtml = '';
foreach ($badges as $a) {
    $state = $a['unlocked'] ? 'unlocked' : 'locked';
    $badgesHtml .= "<li class='badge {$state}' title=\"{$a['desc']}\"><span class='badge-icon'>{$a['icon']}</span><span class='badge-name'>{$a['name']}</span></li>";
}

$weak = [
    ['char' => 'づ', 'label' => 'Hiragana Dakuon', 'count' => 12],
    ['char' => 'ヴ', 'label' => 'Katakana Dakuon', 'count' => 9],
    ['char' => '猫', 'label' => 'Kanji N4', 'count' => 7],
    ['char' => 'ぴ', 'label' => 'Hiragana Combo', 'count' => 5],
    ['char' => 'を', 'label' => 'Hiragana', 'count' => 4],
];

$weakHtml = '';
foreach ($weak as $w) {
    $weakHtml .= "<li><span class='weak-char' lang='ja'>{$w['char']}</span><span class='weak-type'>{$w['label']}</span><span class='weak-count'>{$w['count']}×</span></li>";
}

$weakBodyHtml = $weakHtml !== ''
    ? "<ul class='weak-list'>{$weakHtml}</ul>"
    : "<p class='weak-empty'>Pas encore de statistiques.</p>";

echo "
    <main>
        <section class='_1'></section>

        <section class='_2 student-profile'>
            <div class='profile-grid'>
                <div class='profile-card profile-identity'>
                    <img class='student-avatar' src='{$avatar}' alt='Avatar Twitch de {$name}' width='96' height='96' />
                    <h2>{$name}</h2>

                    <ul class='student-stats'>
                        <li><span>XP total</span> <strong>{$student['student_GlobalXp']}</strong></li>
                        <li><span>Série de jours</span> <strong>{$student['student_StreakDays']}</strong></li>
                        <li><span>Meilleur score</span> <strong>{$student['student_HighScore']}</strong></li>
                        <li><span>Dernier score</span> <strong>{$student['student_LastScore']}</strong></li>
                    </ul>
                </div>

                <div class='profile-card profile-achievements'>
                    <h3 class='achievements-title'>Succès</h3>
                    <ul class='achievements-list'>{$badgesHtml}</ul>
                </div>

                <div class='profile-card profile-weak'>
                    <h3 class='achievements-title'>À travailler</h3>
                    {$weakBodyHtml}
                </div>
            </div>
        </section>
    </main>
";
