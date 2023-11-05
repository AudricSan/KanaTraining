<?php
session_start();
require_once('config.php');

$oauth->set_headers($_SESSION['token']);
$res = $oauth->get_user_info();

if ($res->error === 'Unauthorized') {
    session_destroy();
    header('Location: /');

} else {
    $_SESSION['connected'] = $res['data'][0]['email'];
    header('Location: /');
}