<?php
require_once "backend/auth.php";
if (!isAuthorized() || $_SESSION['access'] == 'user') {
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
    $stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->execute([$id]);
    $employee = $stmt->fetch();

    if (!$employee) {
        echo "Сотрудник с указанным ID не найден.";
        exit();
    }

    $stmtEmployeeDisciplines = $pdo->prepare("SELECT disciplineID FROM employeeDiscipline WHERE employeeID = ?");
    $stmtEmployeeDisciplines->execute([$id]);
    $employeeDisciplineIDs = $stmtEmployeeDisciplines->fetchAll(PDO::FETCH_COLUMN, 0);

} catch (PDOException $e) {
    echo "Ошибка получения данных: " . $e->getMessage();
    exit();
}

try {
    $stmtPositions = $pdo->query("SELECT id, name FROM positions");
    $positions = $stmtPositions->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Ошибка получения данных о должностях: " . $e->getMessage();
    exit();
}

try {
    $stmtDisciplines = $pdo->query("SELECT id, name FROM disciplines");
    $disciplines = $stmtDisciplines->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Ошибка получения данных о дисциплинах: " . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = trim($_POST['fullName']);
    $dateOfBirth = $_POST['dateOfBirth'];
    $email = trim($_POST['email']);
    $phoneNumber = trim($_POST['phoneNumber']);
    $positionID = $_POST['positionID'];
    $academicDegree = $_POST['academicDegree'];
    $disciplineIDs = $_POST['disciplineIDs'] ?? [];

    try {
        $stmt = $pdo->prepare("UPDATE employees SET fullName = ?, dateOfBirth = ?, email = ?, phoneNumber = ?, positionID = ?, academicDegree = ? WHERE id = ?");
        $stmt->execute([$fullName, $dateOfBirth, $email, $phoneNumber, $positionID, $academicDegree, $id]);

        $stmtDeleteDisciplines = $pdo->prepare("DELETE FROM employeeDiscipline WHERE employeeID = ?");
        $stmtDeleteDisciplines->execute([$id]);

        if (!empty($disciplineIDs)) {
            $stmtInsertDisciplines = $pdo->prepare("INSERT INTO employeeDiscipline (employeeID, disciplineID) VALUES (?, ?)");
            foreach ($disciplineIDs as $disciplineID) {
                $stmtInsertDisciplines->execute([$id, $disciplineID]);
            }
        }

        header('Location: index.php');
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
    <style>
        .multiple-select {
          width: 100%;
          padding: 8px;
          border: 1px solid #ddd;
          border-radius: 4px;
          box-sizing: border-box;
          height: 150px;
          overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Редактирование сотрудника</h2>
        <form method="POST">
        <div class="form-group">
                <label for="fullName">ФИО:</label>
                <input type="text" id="fullName" name="fullName" value="<?php echo htmlspecialchars($employee['fullName'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="dateOfBirth">Дата рождения:</label>
                <input type="date" id="dateOfBirth" name="dateOfBirth" value="<?php echo htmlspecialchars($employee['dateOfBirth'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($employee['email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="phoneNumber">Номер телефона:</label>
                <input type="text" id="phoneNumber" name="phoneNumber" value="<?php echo htmlspecialchars($employee['phoneNumber'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="positionID">Должность:</label>
                <select id="positionID" name="positionID" required>
                    <option value="">Выберите должность</option>
                    <?php foreach ($positions as $position): ?>
                        <option value="<?php echo htmlspecialchars($position['id']); ?>" <?php echo ($employee['positionID'] == $position['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($position['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="disciplineIDs">Дисциплины:</label>
                <select id="disciplineIDs" name="disciplineIDs[]" multiple class="multiple-select">
                    <?php foreach ($disciplines as $discipline): ?>
                        <option value="<?php echo htmlspecialchars($discipline['id']); ?>"
                            <?php echo in_array($discipline['id'], $employeeDisciplineIDs) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($discipline['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="academicDegree">Ученая степень:</label>
                <select id="academicDegree" name="academicDegree" required>
                    <option value="Без ученой степени" <?php echo ($employee['academicDegree'] == 'Без ученой степени') ? 'selected' : ''; ?>>Без ученой степени</option>
                    <option value="Кандидат наук" <?php echo ($employee['academicDegree'] == 'Кандидат наук') ? 'selected' : ''; ?>>Кандидат наук</option>
                    <option value="Доктор наук" <?php echo ($employee['academicDegree'] == 'Доктор наук') ? 'selected' : ''; ?>>Доктор наук</option>
                </select>
            </div>

            <div class="button-group">
                <button type="submit">Сохранить</button>
                <a href="index.php" class="cancel-button">Отменить</a>
            </div>
        </form>
    </div>
</body>
</html>