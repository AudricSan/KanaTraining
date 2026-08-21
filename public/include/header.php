<?php
\Kanatraining\env::startSession();

$weakCharactersJson = '{}';
if (!empty($_SESSION['student_id'])) {
    $headerEnv = new \Kanatraining\env();
    $characterStatsDAO = new \Kanatraining\DAO\CharacterStatsDAO(\Kanatraining\Database::get($headerEnv));
    $weakCharactersJson = json_encode($characterStatsDAO->weightsFor((int) $_SESSION['student_id']));
}

// Navigation persistante, identique sur toutes les pages
$topnavHtml = "
    <div class='topnav' aria-label='Navigation principale'>
        <a href='/'><span class='material-icons-round'>school</span> Entraînement</a>
        <a href='/classement'><span class='material-icons-round'>leaderboard</span> Classement</a>";

if (!empty($_SESSION['student_id'])) {
    $topnavHtml .= "
        <a href='/student'><span class='material-icons-round'>person</span> Profil</a>
        <a href='/logout'><span class='material-icons-round'>logout</span> Déconnexion</a>";
} else {
    $topnavHtml .= "
        <a href='/login'><span class='material-icons-round'>person</span> Connexion</a>";
}

$topnavHtml .= "
        <button type='button' class='theme-toggle-btn' id='themeToggleBtn' onclick='selectTheme()' aria-label='Changer de thème' aria-pressed='false'>
            <span class='material-icons-round' id='themeToggleIcon'>dark_mode</span>
        </button>
    </div>";

// Cache-busting : force le navigateur à recharger les CSS/JS après un déploiement
// au lieu de garder une version en cache (source du bug \"ça ne marche toujours pas\").
function assetVersion(string $relativePath): int
{
    $path = __DIR__ . '/' . $relativePath;
    return file_exists($path) ? filemtime($path) : time();
}

$cssVersion = max(
    assetVersion('../css/reset.css'),
    assetVersion('../css/index.css'),
    assetVersion('../css/menu.css'),
    assetVersion('../css/small.css')
);

echo "
<!DOCTYPE html>
<html lang='fr'>

<head>
    <meta charset='utf-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1' />
    <title>Entraînez vos Kanas</title>

    <!-- Applique le thème sauvegardé avant le premier rendu, pour éviter un flash
         de thème clair suivi d'un bascule vers le thème sombre. -->
    <script>
        (function () {
            try {
                var t = localStorage.getItem('theme');
                document.documentElement.setAttribute('data-theme', t === 'dark' ? 'dark' : 'light');
            } catch (e) {}
        })();
    </script>

    <!-- Meta Application -->
    <meta name='application-name' content='KanaTraining' />
    <meta name='creator' content='Audric Rosier' />
    <meta name='publisher' content='Audric Rosier' />
    <meta name='author' content='https://github.com/AudricSan' />
    <meta name='description' content='Application simple pour apprendre gratuitement les Kanas.' />

    <!-- Meta Robots -->
    <meta name='robots' content='index,follow,noodp,noimageindex' />

    <!-- CSS des icônes -->
    <link href='https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded' rel='stylesheet' />
    <link href='https://fonts.googleapis.com/css2?family=Material+Icons+Round' rel='stylesheet' />
    <link href='https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined' rel='stylesheet' />

    <!-- Favicon -->
    <link rel='apple-touch-icon' sizes='180x180' href='view/image/favicon/apple-touch-icon.png' />
    <link rel='icon' type='image/png' sizes='32x32' href='view/image/favicon/favicon-32x32.png' />
    <link rel='icon' type='image/png' sizes='194x194' href='view/image/favicon/favicon-194x194.png' />
    <link rel='icon' type='image/png' sizes='192x192' href='view/image/favicon/android-chrome-192x192.png' />
    <link rel='icon' type='image/png' sizes='16x16' href='view/image/favicon/favicon-16x16.png' />
    <link rel='manifest' href='view/image/favicon/site.webmanifest' />
    <link rel='mask-icon' href='view/image/favicon/safari-pinned-tab.svg' color='#ffffff' />
    <link rel='shortcut icon' href='view/image/favicon/favicon.ico' />
    <meta name='msapplication-TileColor' content='#ffffff' />
    <meta name='msapplication-TileImage' content='view/image/favicon/mstile-144x144.png' />
    <meta name='msapplication-config' content='view/image/favicon/browserconfig.xml' />
    <meta name='theme-color' content='#ffffff' />

    <!-- Favicon en SVG -->
    <link rel='icon' type='image/svg+xml' href='view/image/favicon/svg/favicon.svg' />
    <link rel='icon' type='image/png' href='view/image/favicon/svg/favicon.png' />

    <!-- CSS -->
    <link href='/public/css/reset.css?v={$cssVersion}' rel='stylesheet' />
    <link href='/public/css/index.css?v={$cssVersion}' rel='stylesheet' />
    <link href='/public/css/menu.css?v={$cssVersion}' rel='stylesheet' />
    <link href='/public/css/small.css?v={$cssVersion}' rel='stylesheet' />
</head>

<body onload='getSave()'>
    <script>
        const isLoggedIn = " . (!empty($_SESSION['student_id']) ? 'true' : 'false') . ";
        const weakCharacters = {$weakCharactersJson};
    </script>
    <div id='blur' class=''></div>

    <header>
        <a href='/' class='logo-link'>
            <img class='logo' src='/image/logo.png' alt='Logo KanaTraining' />
            <h1>Entraînez vos Kanas</h1>
        </a>
        <button class='navbtn'> Menu <span class='menuicon material-icons-round'>expand_more</span> </button>
    </header>
    {$topnavHtml}
";