<?php
session_start();

require_once('model/class/oauthtwitch.php');
require_once('model/class/config.php');

// var_dump($_SESSION);
$oauth->set_headers($_SESSION['token']);
$res = $oauth->get_user_info();

if ($res->error === 'Unauthorized') {
    echo 'Unauthorized';
    session_destroy();
    header('Location: index.php');
}

$_SESSION['connected'] = $res['data'][0]['email'];
header('Location: index.php');