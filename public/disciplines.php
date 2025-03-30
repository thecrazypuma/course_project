<?php require_once "backend/disciplines.php"; ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Дисциплины</title>
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/table.css">
</head>

<body>
    <div class="container">
        <!-- ПАНЕЛЬ КНОПОК -->
        <div class="button-container">
            <button class="menu-button" onclick="document.location='create_discipline.php'">Добавить дисциплину</button>
            <button class="menu-button" onclick="document.location='index.php'">Выйти</button>
        </div>

        <!-- СТРОКА ПОИСКА ПО ТАБЛИЦЕ-->
        <div class="search-form">
            <form action="disciplines.php" method="GET">
                <input type="text" name="search" class="search-input" placeholder="Поиск..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="search-button">Поиск</button>
            </form>
        </div>

        <!-- ТАБЛИЦА ДИСЦИПЛИН-->
        <h2>Список дисциплин</h2>
        <table class="employee-table">
            <thead>
                <tr>
                    <?php if ($disciplines): ?>
                        <?php
                         $columns = array_keys($disciplines[0]);
                         foreach ($columns as $column): ?>
                            <th>
                                <a href="?sort=<?php echo $column; ?>&order=<?php echo ($sort == $column && $order == 'asc') ? 'desc' : 'asc'; ?>">
                                    <?php echo htmlspecialchars(isset($column_names[$column]) ? $column_names[$column] : $column); ?>
                                    <?php
                                        if ($sort == $column) {
                                            echo ($order == 'asc') ? ' ▲' : ' ▼';
                                        }
                                    ?>
                                </a>
                            </th>
                        <?php endforeach; ?>
                        <?php if (isset($_SESSION['access']) && ($_SESSION['access'] == 'superadmin' || $_SESSION['access'] == 'admin' || $_SESSION['access'] == 'employee')): ?>
                            <th>Действия</th>
                        <?php endif; ?>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                    <?php if ($disciplines): ?>
                        <?php foreach ($disciplines as $discipline): ?>
                            <tr>
                                <?php foreach ($discipline as $value): ?>
                                    <td><?php echo htmlspecialchars($value); ?></td>
                                <?php endforeach; ?>
                                <?php if (isset($_SESSION['access']) && ($_SESSION['access'] == 'superadmin' || $_SESSION['access'] == 'admin' || $_SESSION['access'] == 'employee')): ?>
                                <td>
                                    <a href="edit_discipline.php?id=<?php echo $discipline['id']; ?>" class="edit-button">Редактировать</a>
                                    <button class="delete-button" onclick="deleteDisciplines(<?php echo $discipline['id']; ?>)">Удалить</button>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="100%">Нет данных.</td></tr>
                    <?php endif; ?>
                </tbody>
        </table>
    </div>
    <script src="javascript/index.js"></script>
</body>
</html>
