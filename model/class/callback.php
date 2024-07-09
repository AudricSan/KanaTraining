<?php
session_start();
require_once('config.php');

if (empty($_SESSION['token']) or !isset($_SESSION['token'])) {
    header("Location: /");
}

require_once('../model/class/config.php');
require_once('../model/class/oauthtwitch.php');
require_once('../model/class/student.php');
require_once('../model/dao/studentDAO.php');

$oauth->set_headers($_SESSION['token']);
$twitch = $oauth->get_user_info();

$twitch = $twitch['data'][0];

$data = [
    "Student_ID"           => $twitch['id'],
    "Student_Name"         => $twitch['display_name'],
    "Student_type"         => $twitch['broadcaster_type'],
    "Student_Avatar"       => $twitch['profile_image_url'],
    "Student_viewer"       => $twitch['view_count'],
    "Student_mail"         => $twitch['email'],
    "Student_ScoreHighest" => 0,
    "Student_ScoreLower"   => 0
];

$StudentDao = new StudentDAO;
$Student = $StudentDao->create($data);

$exist = $StudentDao->fetch($Student->_id);

if (!$exist) {
    $StudentDao->store($Student);
}

$_SESSION['student'] = $Student->_id;
header('Location: /Student');
die();