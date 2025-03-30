<?php
session_start();

function isAuthorized() {
    return isset($_SESSION['user_id']);
}

if (!isAuthorized() && basename($_SERVER['PHP_SELF']) != "login.php" && basename($_SERVER['PHP_SELF']) != "register.php" && basename($_SERVER['PHP_SELF']) != "auth.php") {
    header('Location: login.php');
    exit();
}
?>