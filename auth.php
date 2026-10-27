<?php
session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: login.html");
    exit;
}

$timeout_duration = 1800;

if (isset($_SESSION["last_activity"])) {
    if (time() - $_SESSION["last_activity"] > $timeout_duration) {
        session_unset();
        session_destroy();
        header("Location: login.html");
        exit;
    }
}

$_SESSION["last_activity"] = time();
?>