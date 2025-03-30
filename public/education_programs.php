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

$allowedColumns = ['id', 'code', 'employeeName', 'name'];
if (!in_array($sort, $allowedColumns)) {
    $sort = 'id';
}

$allowedOrders = ['asc', 'desc'];
if (!in_array($order, $allowedOrders)) {
    $order = 'asc';
}

try {
  $sql = "SELECT ep.id, ep.code, e.fullName AS employeeName, ep.name
          FROM educationProgramms ep
          LEFT JOIN employees e ON ep.employeeID = e.id";

  if (!empty($search)) {
      $sql .= " WHERE ep.code LIKE :search1
              OR e.fullName LIKE :search2
              OR ep.name LIKE :search3";
  }

    $sql .= " ORDER BY $sort $order";


    $stmt = $pdo->prepare($sql);

    if (!empty($search)) {
        $searchValue = "%" . $search . "%";
        $stmt->bindValue(':search1', $searchValue, PDO::PARAM_STR);
        $stmt->bindValue(':search2', $searchValue, PDO::PARAM_STR);
        $stmt->bindValue(':search3', $searchValue, PDO::PARAM_STR);
    }

    $stmt->execute();
    $educationPrograms = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Ошибка получения данных: " . $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Учебные программы</title>
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/table.css">
</head>
<body>
  <div class="container">

      <div class="button-container">
          <a href="create_education_program.php" class="create-button">Добавить программу</a>
          <a href="index.php">Назад</a>
      </div>

      <div class="search-form">
          <form action="" method="GET">
              <input type="text" name="search" class="search-input" placeholder="Поиск..." value="<?php echo htmlspecialchars($search); ?>">
              <button type="submit" class="search-button">Поиск</button>
          </form>
      </div>

      <h2>Образовательные программы</h2>

      <table class="employee-table">
          <thead>
              <tr>
                <?php if (!empty($educationPrograms)): ?>
                  <?php
                  $columns = [
                      'id' => 'ID',
                      'code' => 'Код программы',
                      'employeeName' => 'Сотрудник',
                      'name' => 'Название образовательной программы',
                    ];
                    foreach ($columns as $column => $label): ?>
                      <th>
                        <a href="?sort=<?php echo $column; ?>&order=<?php echo ($sort == $column && $order == 'asc') ? 'desc' : 'asc'; ?>">
                          <?php echo htmlspecialchars($label); ?>
                            <?php
                              if ($sort == $column) {
                                  echo ($order == 'asc') ? ' ▲' : ' ▼';
                              }
                          ?>
                        </a>
                      </th>
                  <?php endforeach; ?>
                <?php endif; ?>

                  <th>Действия</th>
              </tr>
          </thead>
          <tbody>
          <?php if (!empty($educationPrograms)): ?>
              <?php foreach ($educationPrograms as $program): ?>
                  <tr>
                      <td><?php echo htmlspecialchars($program['id']); ?></td>
                      <td><?php echo htmlspecialchars($program['code']); ?></td>
                      <td><?php echo htmlspecialchars($program['employeeName']); ?></td>
                      <td><?php echo htmlspecialchars($program['name']); ?></td>
                      <td>
                          <a href="edit_education_program.php?id=<?php echo $program['id']; ?>" class="edit-button">Редактировать</a>
                          <button class="delete-button" onclick="deleteProgram(<?php echo $program['id']; ?>)">Удалить</button>
                      </td>
                  </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="100%">Нет данных об учебных программах.</td></tr>
            <?php endif; ?>
          </tbody>
      </table>
  </div>
  <script src="javascript/index.js"></script>
</body>
</html>