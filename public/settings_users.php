<?php require_once "backend/settings_users.php"; ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список пользователей</title>
    <link rel="stylesheet" href="style/index.css">
    <!-- <link rel="stylesheet" href="style/settings_users.css"> -->
    <link rel="stylesheet" href="style/table.css">
</head>

<body>
    <?php if (isset($_SESSION['access']) && ($_SESSION['access'] == 'superadmin' || $_SESSION['access'] == 'admin')): ?>
        <div class="container">
            <div class="button-container">
                <a href="index.php">Выйти</a>
            </div>

            <h2>Список пользователей</h2>
            <table class="employee-table">
                <thead>
                    <tr>
                        <?php if ($employees): ?>
                            <?php
                            $columns = array_keys($employees[0]);
                            foreach ($columns as $column): ?>
                                <th><?php echo htmlspecialchars(isset($column_names[$column]) ? $column_names[$column] : $column); ?></th>
                            <?php endforeach; ?>
                            <th>Действия</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($employees): ?>
                        <?php foreach ($employees as $employee): ?>
                            <tr>
                                <?php foreach ($employee as $value): ?>
                                    <td><?php echo htmlspecialchars($value); ?></td>
                                <?php endforeach; ?>
                                <td>
                                    <a href="/edit_user.php?id=<?php echo $employee['id']; ?>" class="edit-button">Редактировать</a>
                                    <button class="delete-button" onclick="deleteUser(<?php echo $employee['id']; ?>)">Удалить</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="100%">Нет данных.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>
    <script>
        function deleteUser(id) {
            if (confirm('Вы уверены, что хотите удалить эту учетную запись?')) {
                window.location.href = '/backend/delete_user.php?id=' + id;
            }
        }
    </script>

</body>
</html>