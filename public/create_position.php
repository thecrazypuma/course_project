<?php
require_once "backend/auth.php";
if (!isAuthorized() || $_SESSION['access'] == 'user') {
    header('HTTP/1.1 403 Forbidden');
    exit();
}
require_once "backend/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    try {
        $stmt = $pdo->prepare("INSERT INTO positions (name, description) VALUES (?, ?)");
        $stmt->execute([$name, $description]);

        header('Location: positions.php');
        exit();
    } catch (PDOException $e) {
        echo "Ошибка добавления данных: " . $e->getMessage();
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить должность</title>
    <link rel="stylesheet" href="style/settings.css">
    <link rel="stylesheet" href="style/create_discipline.css">
</head>
<body>
    <div class="container">
        <h2>Добавить должность</h2>
        <form method="POST">
            <div class="form-group">
                <label for="name">Название:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Описание:</label>
                <textarea id="description" name="description"></textarea>
            </div>

            <div class="button-group">
                <button type="submit">Создать</button>
                <button type="reset">Очистить</button>
                <a href="positions.php" class="cancel-button">Отменить</a>
            </div>
        </form>
    </div>
</body>
</html>