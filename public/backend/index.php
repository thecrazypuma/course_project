<?php
require_once "backend/auth.php";
if (!isAuthorized()) {
    header('Location: login.php');
    exit();
}
require_once "backend/db.php";

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search = str_replace('*', '%', $search);

$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'id';
$order = isset($_GET['order']) ? trim($_GET['order']) : 'asc';

$allowedColumns = ['id', 'fullName', 'dateOfBirth', 'email', 'phoneNumber', 'positionName', 'disciplineName', 'p.name', 'd.name', 'academicDegree', 'programInfo'];
if (!in_array($sort, $allowedColumns)) {
    $sort = 'id';
}

$allowedOrders = ['asc', 'desc'];
if (!in_array($order, $allowedOrders)) {
    $order = 'asc';
}

$stmt = $pdo->prepare("SET SESSION group_concat_max_len = 4096");
$stmt->execute();

try {
    $sql = "SELECT e.id, e.fullName, e.dateOfBirth, e.email, e.phoneNumber, p.name AS positionName,
                   GROUP_CONCAT(DISTINCT d.name SEPARATOR '; ') AS disciplineName,
                   e.academicDegree,
                   GROUP_CONCAT(DISTINCT CONCAT(ep.code, ' - ', ep.name) SEPARATOR '; ') AS programInfo
            FROM employees e
            LEFT JOIN positions p ON e.positionID = p.id
            LEFT JOIN employeeDiscipline ed ON e.id = ed.employeeID
            LEFT JOIN disciplines d ON ed.disciplineID = d.id
            LEFT JOIN educationProgramms ep ON e.id = ep.employeeID";

    if (!empty($search)) {
        $sql .= " WHERE e.fullName LIKE :search1
                  OR e.dateOfBirth LIKE :search2
                  OR e.email LIKE :search3
                  OR e.phoneNumber LIKE :search4
                  OR p.name LIKE :search5
                  OR d.name LIKE :search6
                  OR e.academicDegree LIKE :search7
                  OR ep.code LIKE :search8
                  OR ep.name LIKE :search9";
    }

     $sql .= " GROUP BY e.id, e.fullName, e.dateOfBirth, e.email, e.phoneNumber, p.name, e.academicDegree";
    $sql .= " ORDER BY $sort $order";
    $stmt = $pdo->prepare($sql);
    
    if (!empty($search)) {
        $searchValue = '%' . $search . '%';
        $stmt->bindValue(':search1', $searchValue, PDO::PARAM_STR);
        $stmt->bindValue(':search2', $searchValue, PDO::PARAM_STR);
        $stmt->bindValue(':search3', $searchValue, PDO::PARAM_STR);
        $stmt->bindValue(':search4', $searchValue, PDO::PARAM_STR);
        $stmt->bindValue(':search5', $searchValue, PDO::PARAM_STR);
        $stmt->bindValue(':search6', $searchValue, PDO::PARAM_STR);
        $stmt->bindValue(':search7', $searchValue, PDO::PARAM_STR);
        $stmt->bindValue(':search8', $searchValue, PDO::PARAM_STR);
        $stmt->bindValue(':search9', $searchValue, PDO::PARAM_STR);
    }
    $stmt->execute();
    $employees = $stmt->fetchAll();

    // Отладки
    // echo "<pre>";
    // var_dump($employees);
    // echo "</pre>";
    // exit();

} catch (PDOException $e) {
    echo "Ошибка получения данных: " . $e->getMessage();
    exit();
}
?>

<?php

$column_names = [
    'id' => 'ID',
    'fullName' => 'ФИО',
    'dateOfBirth' => 'Дата рождения',
    'email' => 'Email',
    'phoneNumber' => 'Телефон',
    'positionName' => 'Должность',
    'disciplineName' => 'Дисциплина',
    'academicDegree' => 'Ученая степень',
    'programInfo' => 'Образовательные программы'
];
?>