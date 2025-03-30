<?php
require_once "backend/auth.php";
if (!isAuthorized()) {
    header('Location: login.php');
    exit();
}
require_once "backend/db.php";

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT email, password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user) {
        echo "Пользователь не найден.";
        exit();
    }

    $current_email = htmlspecialchars($user['email']);

} catch (PDOException $e) {
    echo "Ошибка получения данных: " . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_email = trim($_POST['new_email']);
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $new_password_repeat = $_POST['new_password_repeat'];
    $message = "";

    try {
        if (!empty($new_email) && $new_email != $current_email) {
            $stmt = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
            $stmt->execute([$new_email, $user_id]);
            $current_email = htmlspecialchars($new_email);
            $message .= "Email успешно обновлен.<br>";
        }

        if (!empty($new_password)) {
            if (empty($old_password)) {
                $message .= "Пожалуйста, введите старый пароль.<br>";
            } elseif (!password_verify($old_password, $user['password'])) {
                $message .= "Старый пароль введен неверно.<br>";
            } elseif ($new_password != $new_password_repeat) {
                $message .= "Новый пароль и подтверждение не совпадают.<br>";
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashed_password, $user_id]);
                $message .= "Пароль успешно обновлен.<br>";
            }
        }

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
    <title>Настройки</title>
    <link rel="stylesheet" href="style/settings.css">
</head>
<body>
    <div class="container">
        <h2>Настройки</h2>
        <form method="POST">
            <div class="form-group">
                <label for="new_email">Новый email:</label>
                <input type="email" id="new_email" name="new_email" value="<?php echo $current_email; ?>">
            </div>
            <div class="form-group">
                <label for="old_password">Старый пароль:</label>
                <input type="password" id="old_password" name="old_password">
            </div>
            <div class="form-group">
                <label for="new_password">Новый пароль:</label>
                <input type="password" id="new_password" name="new_password">
            </div>
             <div class="form-group">
                <label for="new_password_repeat">Повторите новый пароль:</label>
                <input type="password" id="new_password_repeat" name="new_password_repeat">
            </div>

            <div class="button-group">
                <button type="submit">Сохранить</button>
                <a href="index.php" class="cancel-button">Назад</a>
            </div>
            <?php if(!empty($message)): ?>
               <div class="message">
                  <?php echo $message; ?>
               </div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>