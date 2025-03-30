<?php
require_once "backend/auth.php";
if (!isAuthorized() || $_SESSION['access'] != 'admin') {
    header('HTTP/1.1 403 Forbidden');
    exit();
}
require_once "backend/db.php";

$id = isset($_GET['id']) ? trim($_GET['id']) : null;

if (!$id) {
    echo "Не указан ID сотрудника.";
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $employee = $stmt->fetch();

    if (!$employee) {
        echo "Пользователь с указанным ID не найден.";
        exit();
    }
} catch (PDOException $e) {
    echo "Ошибка получения данных: " . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $access = trim($_POST['access']);

    try {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, access = ? WHERE id = ?");
        $stmt->execute([$username, $email, $access, $id]);

        header('Location: settings_users.php');
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
    <title>Редактирование сотрудника</title>
    <link rel="stylesheet" href="style/edit.css">
</head>
<body>
    <div class="container">
        <h2>Редактирование пользователя</h2>
        <form method="POST">
            <div class="form-group">
                <label for="username">Логин:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($employee['username']); ?>">
            </div>
            <div class="form-group">
                <label for="email">Электронная почта:</label>
                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>">
            </div>
            <div class="form-group">
                <label for="access">Уровень доступа:</label>
                <select id="access" name="access">
                    <option value="admin" <?php if ($employee['access'] == 'admin') echo 'selected'; ?>>Администратор</option>
                    <option value="employee" <?php if ($employee['access'] == 'employee') echo 'selected'; ?>>Сотрудник</option>
                    <option value="user" <?php if ($employee['access'] == 'user') echo 'selected'; ?>>Пользователь</option>
                </select>
            </div>
            <div class="button-group">
                <button type="submit">Сохранить</button>
                <a href="settings_users.php" class="cancel-button">Отменить</a>
            </div>
        </form>
    </div>
</body>
</html>