<?php
require_once "backend/auth.php";
if (!isAuthorized() || $_SESSION['access'] == 'user') {
    header('HTTP/1.1 403 Forbidden');
    exit();
}
require_once "backend/db.php";

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
    // $disciplineID = $_POST['disciplineID'] !== "" ? $_POST['disciplineID'] : null;
    $academicDegree = $_POST['academicDegree'];
    $disciplineIDs = $_POST['disciplineIDs'] ?? [];

    try {

        $stmt = $pdo->prepare("INSERT INTO employees (fullName, dateOfBirth, email, phoneNumber, positionID, academicDegree) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$fullName, $dateOfBirth, $email, $phoneNumber, $positionID, $academicDegree]);
        $employeeID = $pdo->lastInsertId();
        var_dump($employeeID);
        if (!empty($disciplineIDs)) {
            $stmtDiscipline = $pdo->prepare("INSERT INTO employeeDiscipline (employeeID, disciplineID) VALUES (?, ?)");
            foreach ($disciplineIDs as $disciplineID) {
                $stmtDiscipline->execute([$employeeID, $disciplineID]);
            }
        }

        header('Location: index.php');
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
    <title>Добавить сотрудника</title>
    <link rel="stylesheet" href="style/create_employee.css">
</head>
<body>
    <div class="container">
        <h2>Добавить сотрудника</h2>
        <form method="POST">
        <div class="form-group">
                <label for="fullName">ФИО:</label>
                <input type="text" id="fullName" name="fullName" required>
            </div>
            <div class="form-group">
                <label for="dateOfBirth">Дата рождения:</label>
                <input type="date" id="dateOfBirth" name="dateOfBirth">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="phoneNumber">Номер телефона:</label>
                <input type="text" id="phoneNumber" name="phoneNumber">
            </div>
            <div class="form-group">
                <label for="positionID">Должность:</label>
                <select id="positionID" name="positionID" required>
                    <option value="">Выберите должность</option>
                    <?php foreach ($positions as $position): ?>
                        <option value="<?php echo htmlspecialchars($position['id']); ?>">
                            <?php echo htmlspecialchars($position['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="disciplineIDs">Дисциплины:</label>
                <select id="disciplineIDs" name="disciplineIDs[]" multiple class="multiple-select">
                    <?php foreach ($disciplines as $discipline): ?>
                        <option value="<?php echo htmlspecialchars($discipline['id']); ?>"">
                            <?php echo htmlspecialchars($discipline['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="academicDegree">Ученая степень:</label>
                <select id="academicDegree" name="academicDegree" required>
                    <option value="Без ученой степени">Без ученой степени</option>
                    <option value="Кандидат наук">Кандидат наук</option>
                    <option value="Доктор наук">Доктор наук</option>
                </select>
            </div>

            <div class="button-group">
                <button type="submit">Создать</button>
                <button type="reset">Очистить</button>
                <a href="index.php" class="cancel-button">Отменить</a>
            </div>
        </form>
    </div>
</body>
</html>