<?php
session_start();

use kanatraining\User;
use kanatraining\Env;

require_once('../model/class/config.php');
$link = $oauth->get_link_connect();
$_SESSION['TLink'] = $link;

if (!empty($_GET['code'])) {
  $code  = htmlspecialchars($_GET['code']);
  $token = $oauth->get_token($code);

  $_SESSION['token'] = $token;

  header('Location: /callback');
  die();
}

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
</head>

<body onload='getSave()'>
    <header>
        <img class='logo' src='image/logo.png' />
        <h1>Entraînez vos Kanas</h1>
    </header>
";

// if (empty($_SESSION['user']) and empty($_SESSION['token'])) {
//   echo "
//    <a href='$link'>
//           <!-- <a href='#' onClick=\"createNotification('On an Future Update', '!')\"> -->
//             <span class='fa-solid fa-person'></span>
//             Connection
//           </a>";
// } else {
//   if ($_SERVER["REQUEST_URI"] === "/user") {
//     echo "
//       <a href='/'>
//         <span class='fa-solid fa-house'></span>
//         Home
//       </a>
      
//       <a href='/logout'>
//         <span class='fa-solid fa-person-running'></span>
//         Disconected
//       </a>";
//   } else {
//     echo "
//           <a href='/user'>
//             <span class='fa-solid fa-address-card'></span>
//             Profil 
//           </a>
          
//           <a href='/logout'>
//             <span class='fa-solid fa-person-running'></span>
//             Disconected
//           </a> ";
//   }
// }

// echo "
// </div>

// <p class='divider'>---------------------------------------</p>

// <div class='theme'>
//   <input type='checkbox' class='switch theme' id='theme' name='cc' onchange='selectTheme()' />
//   <label for='theme'>theme-color</label>
// </div>

// <p class='divider'>---------------------------------------</p>";