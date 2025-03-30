<?php
require_once "backend/auth.php";
if (!isAuthorized() || $_SESSION['access'] != 'admin') {
    header('HTTP/1.1 403 Forbidden');
    exit();
}
require_once "backend/db.php";

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    $sql = "SELECT id, username, email, access FROM users";

    $stmt = $pdo->prepare($sql);

    if (!empty($search)) {
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    }

    $stmt->execute();
    $employees = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Ошибка получения данных: " . $e->getMessage();
    exit();
}
?>

<?php
$column_names = [
    'id' => 'ID Пользователя',
    'username' => 'Логин',
    'email' => 'Электронная почта',
    'access' => 'Уровень доступа',
];
?>