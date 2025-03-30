<?php require_once "backend/index.php"; ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список сотрудников</title>
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/table.css">
</head>

<body>
    <!-- ПАНЕЛЬ УПРАВЛЕНИЯ АДМИНА   -->
    <div id="menuSidebar" class="sidebar">
        <a href="disciplines.php">Дисциплины</a>
        <a href="positions.php" >Должности</a>
        <a href="education_programs.php">Образовательные программы</a>
        <?php if ($_SESSION['access'] == 'admin'): ?>
            <a href="settings_users.php">Пользователи</a>
        <?php endif; ?>
    </div>

    <div class="container">
        <!-- ПАНЕЛЬ КНОПОК -->
        <div class="button-container">
            <?php if ($_SESSION['access'] != 'user'): ?>
                <button class="menu-button" onclick="toggleNav()">Администрирование</button>
                <!-- <button class="menu-button" onclick="showConfirmation()">Очистить базу данных</button> -->
            <?php endif; ?>
            <?php if (isset($_SESSION['access']) && ($_SESSION['access'] == 'superadmin' || $_SESSION['access'] == 'admin' || $_SESSION['access'] == 'employee')): ?>
                <button class="menu-button" onclick="document.location='/create_employee.php'">Добавить сотрудника</button>
                <button class="menu-button" onclick="clearEmployees()">Очистить таблицу сотрудников</button>
            <?php endif; ?>
            <button class="menu-button" onclick="document.location='settings.php'">Настройки</button>
            <button class="menu-button" onclick="document.location='backend/logout.php'">Выйти</button>
        </div>

        <!-- СТРОКА ПОИСКА ПО ТАБЛИЦЕ-->
        <div class="search-form">
            <form action="index.php" method="GET">
                <input type="text" name="search" class="search-input" placeholder="Поиск..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="search-button">Поиск</button>
            </form>
        </div>

        <!-- ТАБЛИЦА ШТАТНОГО СОСТАВА КАФЕДРЫ-->
        <h2>Список сотрудников</h2>
        <table class="employee-table">
            <thead>
                <tr>
                    <?php if ($employees): ?>
                        <?php
                         foreach ($column_names as $column => $label): ?>
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
                <?php if ($employees): ?>
                    <?php foreach ($employees as $employee): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($employee['id'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($employee['fullName'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($employee['dateOfBirth'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($employee['email'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($employee['phoneNumber'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($employee['positionName'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($employee['disciplineName'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($employee['academicDegree'] ?? ''); ?></td>
                            <!-- <td><?php echo htmlspecialchars($employee['programInfo'] ?? ''); ?></td> -->
                            <td>
                                <div class="program-info-container">
                                    <span class="short-text">
                                        <?php
                                        $programInfo = htmlspecialchars($employee['programInfo'] ?? '');
                                        $maxLength = 100;

                                        if (mb_strlen($programInfo) > $maxLength) {
                                            echo mb_substr($programInfo, 0, $maxLength) . '...';
                                        } else {
                                            echo $programInfo;
                                        }
                                        ?>
                                    </span>
                                    <?php if (mb_strlen($programInfo) > $maxLength): ?>
                                        <span class="full-text" style="display: none;"><?php echo $programInfo; ?></span>
                                        <a href="#" class="show-more">показать все</a>
                                        <a href="#" class="show-less" style="display: none;">скрыть</a>
                                    <?php endif; ?>
                                </div>
                            </td>


                            <?php if (isset($_SESSION['access']) && ($_SESSION['access'] == 'admin' || $_SESSION['access'] == 'employee')): ?>
                                <td >
                                    <button class="edit-button" onclick="document.location='edit_employee.php?id=<?php echo $employee['id']; ?>'">Редактировать</button>

                                    <button class="delete-button" onclick="deleteEmployee(<?php echo $employee['id']; ?>)">Удалить</button>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="100%">Нет данных о сотрудниках.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!--  Модальное окно подтверждения  -->
    <div id="confirmationModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
        <div style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px;">
            <p>Вы уверены, что хотите ОЧИСТИТЬ ВСЕ ТАБЛИЦЫ в базе данных?  Это действие НЕОБРАТИМО!</p>
             <p style="color:red; font-weight: bold;">ЕЩЕ РАЗ: ЭТО УДАЛИТ ВСЕ ДАННЫЕ!</p>
            <form action="backend/clear_database.php" method="POST">
                <input type="hidden" name="confirm_clear" value="yes">
                <button type="button" onclick="hideConfirmation()" style="background-color: #ccc; color: black; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-right: 10px;">Отмена</button>
                <button type="submit" style="background-color: #f44336; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Очистить БД</button>
            </form>
        </div>
    </div>

    <script src="javascript/index.js"></script>
</body>
</html>