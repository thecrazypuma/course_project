<?php require_once "backend/positions.php"; ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Должности</title>
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/table.css">
</head>

<body>
    <div class="container">
        <!-- ПАНЕЛЬ КНОПОК -->
        <div class="button-container">
            <button class="menu-button" onclick="document.location='create_position.php'">Добавить должность</button>
            <button class="menu-button" onclick="document.location='index.php'">Выйти</button>
        </div>

        <!-- СТРОКА ПОИСКА ПО ТАБЛИЦЕ-->
        <div class="search-form">
            <form action="positions.php" method="GET">
                <input type="text" name="search" class="search-input" placeholder="Поиск..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="search-button">Поиск</button>
            </form>
        </div>

        <!-- ТАБЛИЦА ДИСЦИПЛИН-->
        <h2>Список должностей</h2>
        <table class="employee-table">
            <thead>
                <tr>
                    <?php if ($positions): ?>
                        <?php
                         $columns = array_keys($positions[0]);
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
                    <?php if ($positions): ?>
                        <?php foreach ($positions as $position): ?>
                            <tr>
                                <?php foreach ($position as $value): ?>
                                    <td><?php echo htmlspecialchars($value); ?></td>
                                <?php endforeach; ?>
                                <?php if (isset($_SESSION['access']) && ($_SESSION['access'] == 'superadmin' || $_SESSION['access'] == 'admin' || $_SESSION['access'] == 'employee')): ?>
                                <td>
                                    <a href="/edit_position.php?id=<?php echo $position['id']; ?>" class="edit-button">Редактировать</a>
                                    <button class="delete-button" onclick="deletePositions(<?php echo $position['id']; ?>)">Удалить</button>
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
