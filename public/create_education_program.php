<?php
require_once "backend/auth.php";
if (!isAuthorized() || $_SESSION['access'] == 'user') {
    header('HTTP/1.1 403 Forbidden');
    exit();
}
require_once "backend/db.php";

try {
    $stmtEmployees = $pdo->query("SELECT id, fullName FROM employees");
    $employees = $stmtEmployees->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Ошибка получения списка сотрудников: " . $e->getMessage();
    exit();
}

try {
    $stmtDisciplines = $pdo->query("SELECT id, name FROM disciplines");
    $disciplines = $stmtDisciplines->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Ошибка получения списка дисциплин: " . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = trim($_POST['code']);
    $employeeID = $_POST['employeeID'];
    $name = trim($_POST['name']);

    try {
        $stmt = $pdo->prepare("INSERT INTO educationProgramms (code, employeeID, name) VALUES (?, ?, ?)");
        $stmt->execute([$code, $employeeID, $name]);

        header('Location: education_programs.php');
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
    <title>Добавить образовательную программу</title>
    <link rel="stylesheet" href="style/edit.css">
    <link rel="stylesheet" href="style/create_discipline.css">
    <style>
        input[type="text"],
        input[type="date"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
    height: 100px;
}
    </style>
</head>
<body>
    <div class="container">
        <h2>Добавить образовательную программу</h2>
        <form method="POST">
            <div class="form-group">
                <label for="code">Код программы:</label>
                <input type="text" id="code" name="code" required>
            </div>

            <div class="form-group">
                <label for="employeeID">Сотрудник:</label>
                <select id="employeeID" name="employeeID" required>
                    <option value="">Выберите сотрудника</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?php echo htmlspecialchars($employee['id']); ?>">
                            <?php echo htmlspecialchars($employee['fullName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="name">Название программы:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="button-group">
                <button type="submit">Создать</button>
                <button type="reset">Очистить</button>
                <a href="education_programs.php" class="cancel-button">Отменить</a>
            </div>
        </form>
    </div>
</body>
</html>