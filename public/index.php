<?php

// Use this namespace
namespace kanatraining;

use Kanatraining\Route;
use LessonDAO;
use StudentDAO;
use StudentAchievementDAO;
use StudentLessonDAO;
use OAuthTwitch;

// Include class
include '../model/class/Route.php';
include '../model/class/env.php';

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

Route::add('/callback', function () {
  include_once('../model/class/callback.php');
});

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

Route::add('/php.info', function () {
  phpinfo();
});

Route::add('/debug', function () {
  //STUDENT
  echo 'STUDENT';
  include '../model/class/Student.php';
  include '../model/dao/StudentDAO.php';
  $StudentDAO = new StudentDAO;
  $Students = $StudentDAO->fetchAll();
  $Student = $StudentDAO->fetch(1);
  var_dump($Students);
  var_dump($Student);

  // LESSONS
  echo 'LESSON';
  include '../model/class/lesson.php';
  include '../model/dao/lessonDAO.php';
  $lessonDAO = new LessonDAO;
  $lessons = $lessonDAO->fetchAll();
  $lesson = $lessonDAO->fetch(1);
  var_dump($lessons);
  var_dump($lesson);

  //Student Achievement
  echo 'STUDENT ACHIEVEMENT';
  include '../model/class/studentAchievement.php';
  include '../model/dao/studentAchievementDAO.php';
  $studentAchievementDAO = new StudentAchievementDAO;
  $studentAchievements = $studentAchievementDAO->fetchAll();
  $studentAchievement = $studentAchievementDAO->fetch(1);
  var_dump($studentAchievements);
  var_dump($studentAchievement);

  //Student Lesson
  echo 'STUDENT LESSON';
  include '../model/class/studentLesson.php';
  include '../model/dao/studentLessonDAO.php';
  $studentLessonDAO = new studentLessonDAO;
  $studentLessons = $studentLessonDAO->fetchAll();
  $studentLesson = $studentLessonDAO->fetch(1);
  var_dump($studentLessons);
  var_dump($studentLesson);
});

// Exécuter le routeur avec le chemin de base donné
Route::run(BASEPATH);

// Activez le mode sensible à la casse, les barres obliques de fin de ligne et le mode de correspondance multiple en définissant les paramètres à true.
// Route::run(BASEPATH, true, true, true) ;