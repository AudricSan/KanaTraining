<?php

// Use this namespace
namespace Kanatraining;

use Kanatraining\Route;
use Kanatraining\DAO\StudentDAO;
use Kanatraining\DAO\AchievementDAO;
use Kanatraining\DAO\CharacterStatsDAO;

// Include class
include '../model/class/Route.php';
include '../model/class/env.php';
include '../model/class/Database.php';
include '../model/class/TwitchOAuth.php';
include '../model/dao/StudentDAO.php';
include '../model/dao/AchievementDAO.php';
include '../model/dao/CharacterStatsDAO.php';

// Define a global basepath
define('BASEPATH', '/');

// Basic function for the operation of the website.
function head()
{
  include_once('include/header.php');
}

function foot()
{
  include_once('include/footer.php');
}

// Base Route

Route::add('/', function () {
  head();
  include('../view/index.php');
  foot();
});

Route::add('/student', function () {
  env::startSession();
  if (empty($_SESSION['student_id'])) {
    header('Location: /login');
    exit;
  }
  head();
  include('../view/student/index.php');
  foot();
});

Route::add('/classement', function () {
  env::startSession();
  head();
  include('../view/classement/index.php');
  foot();
});

Route::add('/login', function () {
  head();
  include('../view/student/login.php');
  foot();
});

Route::add('/callback', function () {
  include_once('../model/class/callback.php');
});

Route::add('/login/twitch', function () {
  env::startSession();

  $env = new env();
  $state = bin2hex(random_bytes(16));
  $_SESSION['oauth_state'] = $state;

  $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
  $redirectUri = $scheme . '://' . $_SERVER['HTTP_HOST'] . '/callback';

  $oauth = new TwitchOAuth($env, $redirectUri);
  header('Location: ' . $oauth->buildAuthorizeUrl($state));
  exit;
});

Route::add('/logout', function () {
  env::startSession();
  $_SESSION = [];
  session_destroy();
  header('Location: /');
  exit;
});

Route::add('/api/score', function () {
  env::startSession();
  header('Content-Type: application/json');

  if (empty($_SESSION['student_id'])) {
    http_response_code(401);
    return json_encode(['error' => 'not authenticated']);
  }

  $input = json_decode(file_get_contents('php://input'), true) ?? [];

  $pdo = Database::get(new env());
  $studentDAO = new StudentDAO($pdo);
  $achievementDAO = new AchievementDAO($pdo);
  $characterStatsDAO = new CharacterStatsDAO($pdo);
  $studentId = (int) $_SESSION['student_id'];
  $correct = !empty($input['correct']);
  $type = (string) ($input['type'] ?? '');

  try {
    $stats = $studentDAO->recordAnswer(
      $studentId,
      $correct,
      (int) ($input['good'] ?? 0),
      (int) ($input['total'] ?? 0),
      $type
    );
  } catch (\RuntimeException $e) {
    unset($_SESSION['student_id']);
    http_response_code(404);
    return json_encode(['error' => 'student not found']);
  }

  if (!empty($input['type']) && !empty($input['character'])) {
    $characterStatsDAO->recordAttempt($studentId, (string) $input['type'], (string) $input['character'], $correct);
  }

  $newAchievements = $achievementDAO->checkAndUnlock($studentId, $stats);
  $stats['newAchievements'] = array_map(fn ($a) => [
    'name' => $a['achievements_Name'],
    'icon' => $a['achievements_Icon'],
  ], $newAchievements);

  return json_encode($stats);
}, 'post');

// ERROR
// 404 not found route
Route::pathNotFound(function ($path) {
  head();
  include_once('../view/error/404.php');
  foot();
});

// 405 method not allowed route
Route::methodNotAllowed(function ($path, $method) {
  head();
  include_once('../view/error/405.php');
  foot();
});

// This route is for debugging only
// Return all known routes
Route::add('/php.routes', function () {
  $routes = Route::getAll();
  echo '<ul>';
  foreach ($routes as $route) {
    echo '<li>' . $route['expression'] . ' (' . $route['method'] . ')</li>';
  }
  echo '</ul>';
});

// Exécuter le routeur avec le chemin de base donné
Route::run(BASEPATH);

// Activez le mode sensible à la casse, les barres obliques de fin de ligne et le mode de correspondance multiple en définissant les paramètres à true.
// Route::run(BASEPATH, true, true, true) ;