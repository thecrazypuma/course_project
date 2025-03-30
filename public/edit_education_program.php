<?php
require_once "backend/auth.php";
if (!isAuthorized() || $_SESSION['access'] == 'user') {
    header('HTTP/1.1 403 Forbidden');
    exit();
}
require_once "backend/db.php";

$id = isset($_GET['id']) ? trim($_GET['id']) : null;

if (!$id) {
    echo "Не указан ID программы.";
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT * FROM educationProgramms WHERE id = ?");
    $stmt->execute([$id]);
    $program = $stmt->fetch();

    if (!$program) {
        echo "Программа с указанным ID не найдена.";
        exit();
    }
} catch (PDOException $e) {
    echo "Ошибка получения данных: " . $e->getMessage();
    exit();
}

try {
    $stmtEmployees = $pdo->query("SELECT id, fullName FROM employees");
    $employees = $stmtEmployees->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Ошибка получения списка сотрудников: " . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = trim($_POST['code']);
    $employeeID = $_POST['employeeID'];
    $name = trim($_POST['name']);

    try {
        $stmt = $pdo->prepare("UPDATE educationProgramms SET code = ?, employeeID = ?, name = ? WHERE id = ?");
        $stmt->execute([$code, $employeeID, $name, $id]);

        header('Location: education_programs.php');
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
    <title>Редактирование образовательной программы</title>
    <link rel="stylesheet" href="style/edit.css">
</head>
<body>
    <div class="container">
        <h2>Редактирование образовательной программы</h2>
        <form method="POST">
            <div class="form-group">
                <label for="code">Код программы:</label>
                <input type="text" id="code" name="code" value="<?php echo htmlspecialchars($program['code'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="employeeID">Сотрудник:</label>
                <select id="employeeID" name="employeeID" required>
                    <option value="">Выберите сотрудника</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?php echo htmlspecialchars($employee['id']); ?>" <?php echo ($program['employeeID'] == $employee['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($employee['fullName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="name">Название образовательной программы:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($program['name'] ?? ''); ?>" required>
            </div>

            <div class="button-group">
                <button type="submit">Сохранить</button>
                <a href="education_programs.php" class="cancel-button">Отменить</a>
            </div>
        </form>
    </div>
</body>
</html>