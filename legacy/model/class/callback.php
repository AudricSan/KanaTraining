<?php

namespace Kanatraining;

use Kanatraining\DAO\StudentDAO;

include_once __DIR__ . '/env.php';
include_once __DIR__ . '/Database.php';
include_once __DIR__ . '/TwitchOAuth.php';
include_once __DIR__ . '/../dao/StudentDAO.php';

env::startSession();

$env = new env();

if (isset($_GET['error'])) {
    header('Location: /login');
    exit;
}

$state = $_GET['state'] ?? null;
$expectedState = $_SESSION['oauth_state'] ?? null;
unset($_SESSION['oauth_state']);

if ($state === null || $expectedState === null || !hash_equals($expectedState, $state)) {
    header('Location: /login');
    exit;
}

$code = $_GET['code'] ?? null;
if ($code === null) {
    header('Location: /login');
    exit;
}

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$redirectUri = $scheme . '://' . $_SERVER['HTTP_HOST'] . '/callback';

$oauth = new TwitchOAuth($env, $redirectUri);
$accessToken = $oauth->exchangeCodeForToken($code);

if ($accessToken === null) {
    header('Location: /login');
    exit;
}

$profile = $oauth->fetchUserProfile($accessToken);

if ($profile === null) {
    header('Location: /login');
    exit;
}

$pdo = Database::get($env);
$studentDAO = new StudentDAO($pdo);

$twitchId = $profile['id'];
$name = $profile['display_name'] ?? $profile['login'];
$avatar = $profile['profile_image_url'] ?? '';
$email = $profile['email'] ?? null;

$student = $studentDAO->findByTwitchId($twitchId);

if ($student === null) {
    $studentId = $studentDAO->create($twitchId, $name, $avatar, $email);
} else {
    $studentId = (int) $student['student_ID'];
    $studentDAO->updateProfile($studentId, $name, $avatar, $email);
}

session_regenerate_id(true);
$_SESSION['student_id'] = $studentId;

header('Location: /student');
exit;
