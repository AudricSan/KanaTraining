<?php
\Kanatraining\env::startSession();

echo "
<!DOCTYPE html>
<html lang='fr'>

<head>
    <meta charset='utf-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1' />
    <title>Entraînez vos Kanas</title>

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
    <script src='https://kit.fontawesome.com/eb747bd21c.js' crossorigin='anonymous'></script>

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
    <link href='public/css/reset.css' rel='stylesheet' />
    <link href='public/css/index.css' rel='stylesheet' />
    <link href='public/css/menu.css' rel='stylesheet' />
    <link href='public/css/small.css' rel='stylesheet' />
</head>

<body onload='getSave()'>
    <script>const isLoggedIn = " . (!empty($_SESSION['student_id']) ? 'true' : 'false') . ";</script>
    <div id='blur' class=''></div>

    <header>
        <img class='logo' src='image/logo.png' alt='Logo KanaTraining' />
        <h1>Entraînez vos Kanas</h1>
        <button class='navbtn'> Menu <i class='menuicon fa-solid fa-caret-down'></i> </button>
    </header>
";