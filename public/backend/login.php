<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['login_username']);
    $password = $_POST['login_password'];

    if (empty($username) || empty($password)) {
        redirectWithMessage("../login.php", "Заполните все поля", false);
    }

    try {
        $stmt = $pdo->prepare("SELECT id, username, password, access FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['access'] = $user['access'];
            header('Location: ../index.php');
            exit();
        } else {
            redirectWithMessage("../login.php", "Неверный логин или пароль", false);
        }
    } catch (PDOException $e) {
        redirectWithMessage("../login.php", "Ошибка авторизации: " . $e->getMessage(), false);
    }
} else {
    redirectWithMessage("../login.php", "Неверный метод запроса", false);
}

function redirectWithMessage($url, $message, $success) {
    header("Location: ".$url."?message=".urlencode($message)."&success=".$success);
    exit();
}
?>