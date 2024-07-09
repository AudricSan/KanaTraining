<?php
if (isset($_SESSION["email"])) {
    if (!empty($_SESSION["email"])) {
        return true;
    } else {
        header('Location: /');
    }
} else {
    header('Location: /');
}

