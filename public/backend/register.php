<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_repeat = $_POST['password_repeat'];

    if (empty($username) || empty($email) || empty($password) || empty($password_repeat)) {
        redirectWithMessage("../register.php", "Заполните все поля", false);
    }
    if ($password != $password_repeat) {
        redirectWithMessage("../register.php", "Пароли не совпадают", false);
    }

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
             redirectWithMessage("../register.php", "Пользователь с таким логином или email уже существует.", false);
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password]);
         redirectWithMessage("../login.php", "Регистрация прошла успешно. Теперь вы можете авторизоваться.", true);

    } catch (PDOException $e) {
        redirectWithMessage("../register.php", "Ошибка регистрации: " . $e->getMessage(), false);
    }
} else {
    redirectWithMessage("../register.php", "Неверный метод запроса", false);
}

function redirectWithMessage($url, $message, $success) {
     header("Location: ".$url."?message=".urlencode($message)."&success=".$success);
     exit();
}
?>