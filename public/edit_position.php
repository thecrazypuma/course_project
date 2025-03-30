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
    $stmt = $pdo->prepare("SELECT * FROM positions WHERE id = ?");
    $stmt->execute([$id]);
    $positions = $stmt->fetch();

    if (!$positions) {
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
        $stmt = $pdo->prepare("UPDATE positions SET name = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $description, $id]);

        header('Location: positions.php');
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
    <title>Редактирование должности</title>
    <link rel="stylesheet" href="style/edit.css">
    <style>
        textarea {
    height: 100px;
}
    </style>
</head>
<body>
    <div class="container">
        <h2>Редактирование должности</h2>
        <form method="POST">
            <div class="form-group">
                <label for="name">Название:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($positions['name']); ?>">
            </div>
            <div class="form-group">
                <label for="description">Описание:</label>
                <textarea id="description" name="description" value=""><?php echo htmlspecialchars($positions['description']); ?></textarea>
                <!-- <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($positions['description']); ?>"> -->
            </div>
            <div class="button-group">
                <button type="submit">Сохранить</button>
                <a href="/positions.php" class="cancel-button">Отменить</a>
            </div>
        </form>
    </div>
</body>
</html>