<?php
require 'db.php';
require 'auth.php';

if (!isAuthorized() || $_SESSION['access'] == 'user') {
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (!isset($_SESSION['access']) && ($_SESSION['access'] != 'admin' || $_SESSION['access'] != 'employee')) {
    echo "У вас нет прав для выполнения этой операции.";
    exit();
}

try {
    $stmt = $pdo->query("DELETE FROM employees");
    $stmt->execute();

    header('Location: ../index.php');
    exit();
} catch (PDOException $e) {
    echo "Ошибка очистки таблицы: " . $e->getMessage();
    exit();
}
?>