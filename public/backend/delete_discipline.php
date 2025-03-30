<?php
require 'db.php';
require 'auth.php';

if (!isAuthorized() || $_SESSION['access'] == 'user') {
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = trim($_GET['id']);

    try {
        $stmt = $pdo->prepare("DELETE FROM disciplines WHERE id = ?");
        $stmt->execute([$id]);

        header('Location: ../disciplines.php');
        exit();
    } catch (PDOException $e) {
        echo "Ошибка удаления: " . $e->getMessage();
        exit();
    }
} else {
    echo "Неверный запрос.";
    exit();
}
?>