<?php

// NOTE Use this namespace
use kanatraining\Route;
// use kanatraining\UserDAO;

// NOTE Include class
include '../model/class/Route.php';
include '../model/class/env.php';
// include '../model/dao/UserDAO.php';

// NOTE Define a global basepath
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

function core()
{
  include_once('include/core.php');
}
// End operator function.

// SECTION Base Route
Route::add('/', function () {
  head();
  core();
  foot();
});

Route::add('/newweb', function () {
  include_once('include/index.html');
});

Route::add('/newcore', function () {
  include_once('include/index2.html');
});

Route::add('/logout', function () {
  session_start();
  session_unset();
  header('location: /');
});

Route::add('/callback', function () {
  include_once('../view/user/callback.php');
});

Route::add('/user', function () {
  head();
  foot();
  include_once('../view/user/user.php');
});

Route::add('/leaderboard', function () {
  head();
  foot();
  include_once('../view/user/board.php');
});

// Files ROOT
Route::add('/kana', function () {
  include_once('../model/class/kana.js');
});

Route::add('/config', function () {
  require_once('../model/class/config.php');
});

Route::add('/oauthtwitch', function () {
  require_once('../model/class/oauthtwitch.php');
});

Route::add('/update', function () {
  session_start();
  require_once('../model/dao/UserDAO.php');
  require_once('../model/class/User.php');
  $userDAO = new UserDAO;
  $userDAO->scoreUpdate($_SESSION['user']);
  header('location: /user');
});

//SECTION ERROR
// ANCHOR 404 not found route
Route::pathNotFound(function ($path) {
  head();
  include('../view/error/404.php');
  echo 'The requested path "' . $path . '" was not found!';
  foot();
});

// ANCHOR 405 method not allowed route
Route::methodNotAllowed(function ($path, $method) {
  head();
  include('../view/error/405.php');
  echo 'The requested path "' . $path . '" exists. But the request method "' . $method . '" is not allowed on this path!';
  foot();
});
//!SECTION

// SECTION This route is for debugging only
// ANCHOR Return all known routes
Route::add('/routes', function () {
  $routes = Route::getAll();
  echo '<ul>';
  foreach ($routes as $route) {
    echo '<li>' . $route['expression'] . ' (' . $route['method'] . ')</li>';
  }
  echo '</ul>';

  phpinfo();
});
//!SECTION

// ANCHOR Run the Router with the given Basepath
Route::run(BASEPATH);
// ANCHOR Activez le mode sensible à la casse, les barres obliques de fin de ligne et le mode de correspondance multiple en définissant les paramètres à true.
// Route::run(BASEPATH, true, true, true) ;