<?php
require_once 'auth.php';
require_once 'db.php';

if (!isAuthorized()) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = trim($_GET['id']);

    try {
        $stmt = $pdo->prepare("DELETE FROM educationProgramms WHERE id = ?");
        $stmt->execute([$id]);

        header('Location: ../education_programs.php');
        exit();
    } catch (PDOException $e) {
        echo "Ошибка удаления: " . $e->getMessage();
        exit();
    }
} else {
    echo "Неверный запрос.";
    exit();
}