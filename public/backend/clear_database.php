<?php
require_once 'auth.php';
require_once 'db.php';

if (!isAuthorized()) {
    header('Location: ../login.html');
    exit();
}

if (isset($_POST['confirm_clear']) && $_POST['confirm_clear'] == 'yes') {

    try {
        $pdo->exec("SET foreign_key_checks = 0");

        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Очищаем каждую таблицу
        foreach ($tables as $table) {
            $pdo->exec("TRUNCATE TABLE `$table`");
        }

        $pdo->exec("SET foreign_key_checks = 1");

        echo "Все таблицы в базе данных были успешно очищены.";
         echo "<a href='../index.php'>Вернуться на главную</a>";
        exit();

    } catch (PDOException $e) {
        echo "Ошибка очистки базы данных: " . $e->getMessage();
        exit();
    }
} else {
     header("Location: ../index.php");
     exit();
}

?>