<?php
require_once "backend/auth.php";
if (!isAuthorized() || $_SESSION['access'] == 'user') {
    header('HTTP/1.1 403 Forbidden');
    exit();
}
require_once "backend/db.php";


$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$search = str_replace('*', '%', $search);

$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'id';
$order = isset($_GET['order']) ? trim($_GET['order']) : 'asc';

$allowedColumns = ['id', 'name', 'description'];
if (!in_array($sort, $allowedColumns)) {
    $sort = 'id';
}

$allowedOrders = ['asc', 'desc'];
if (!in_array($order, $allowedOrders)) {
    $order = 'asc';
}

try {
    $sql = "SELECT * FROM positions";
    if (!empty($search)) {
        $sql .= " WHERE name LIKE :search1 
                OR description LIKE :search2";
    }

    $sql .= " ORDER BY $sort $order";

    $stmt = $pdo->prepare($sql);
    
    if (!empty($search)) {
        $searchValue = '%' . $search . '%';
        $stmt->bindValue(':search1', $searchValue, PDO::PARAM_STR);
        $stmt->bindValue(':search2', $searchValue, PDO::PARAM_STR);
    }
    $stmt->execute();
    $positions = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Ошибка получения данных: " . $e->getMessage();
    exit();
}

$column_names = [
    'id' => 'ID',
    'name' => 'Название',
    'description' => 'Описание',
];
?>