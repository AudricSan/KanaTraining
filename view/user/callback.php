<?php
session_start();
if (empty($_SESSION['token']) or !isset($_SESSION['token'])) {
    header("Location: /");
}

require_once('../model/class/config.php');
require_once('../model/class/oauthtwitch.php');
require_once('../model/class/User.php');
require_once('../model/dao/userDAO.php');

$oauth->set_headers($_SESSION['token']);
$twitch = $oauth->get_user_info();

$twitch = $twitch['data'][0];

$data = [
    "User_ID"           => $twitch['id'],
    "User_Name"         => $twitch['display_name'],
    "user_type"         => $twitch['broadcaster_type'],
    "User_Avatar"       => $twitch['profile_image_url'],
    "user_viewer"       => $twitch['view_count'],
    "user_mail"         => $twitch['email'],
    "User_ScoreHighest" => 0,
    "User_ScoreLower"   => 0
];

$userDao = new UserDAO;
$user = $userDao->create($data);

$exist = $userDao->fetch($user->_id);

if (!$exist) {
    $userDao->store($user);
}


$_SESSION['user'] = $user->_id;
header('Location: /user');
die();