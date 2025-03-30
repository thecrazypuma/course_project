<?php
require_once "backend/auth.php";
if (!isAuthorized() || $_SESSION['access'] == 'user') {
    header('HTTP/1.1 403 Forbidden');
    exit();
}
require_once "backend/db.php";

$id = isset($_GET['id']) ? trim($_GET['id']) : null;

if (!$id) {
    echo "Не указан ID.";
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT * FROM disciplines WHERE id = ?");
    $stmt->execute([$id]);
    $disciplines = $stmt->fetch();

    if (!$disciplines) {
        echo "ID не найден.";
        exit();
    }
} catch (PDOException $e) {
    echo "Ошибка получения данных: " . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    try {
        $stmt = $pdo->prepare("UPDATE disciplines SET name = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $description, $id]);

        header('Location: disciplines.php');
        exit();
    } catch (PDOException $e) {
        echo "Ошибка обновления данных: " . $e->getMessage();
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование дисциплины</title>
    <link rel="stylesheet" href="style/edit.css">
    <style>
        
    </style>
</head>
<body>
    <div class="container">
        <h2>Редактирование дисциплины</h2>
        <form method="POST">
            <div class="form-group">
                <label for="name">Название:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($disciplines['name']); ?>">
            </div>
            <div class="form-group">
                <label for="description">Описание:</label>
                <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($disciplines['description']); ?>">
            </div>
            <div class="button-group">
                <button type="submit">Сохранить</button>
                <a href="disciplines.php" class="cancel-button">Отменить</a>
            </div>
        </form>
    </div>
</body>
</html>