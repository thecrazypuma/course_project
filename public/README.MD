<!-- Должности -->
CREATE TABLE positions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT
);

<!-- Дисциплины -->
CREATE TABLE disciplines(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT
);

<!-- Пользователи сайта -->
CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(32) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(64) NOT NULL,
    access SET('user','employee','admin') NOT NULL DEFAULT 'user'
);

<!-- Сотрудники -->
CREATE TABLE employees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fullName VARCHAR(255) NOT NULL,
    dateOfBirth DATE,
    email VARCHAR(255) UNIQUE,
    phoneNumber VARCHAR(20),
    positionID INT,
    academicDegree SET('Без ученой степени','Кандидат наук','Доктор наук') NOT NULL,
    FOREIGN KEY (positionID) REFERENCES positions(ID) ON DELETE CASCADE
);

<!-- Образовательные программы -->
CREATE TABLE educationProgramms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(16) NOT NULL,
    employeeID INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    FOREIGN KEY (employeeID) REFERENCES employees(id) ON DELETE CASCADE
);

<!-- Сотрудники-дисциплины -->
CREATE TABLE educationProgramms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employeeID INT,
    disciplineID INT,
    FOREIGN KEY (employeeID) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (disciplineID) REFERENCES disciplines(id) ON DELETE CASCADE
);


web/
├── create_discipline.php           => страница добавления дисциплины

├── create_education_program.php    => страница добавления образовательной программы
├── create_employee.php             => страница добавления сотрудника
├── create_position.php             => страница добавления должности
├── disciplines.php                 => страница со списком дисциплин и их управлением
├── edit_discipline.php             => страница редактирования дисциплины
├── edit_education_program.php      => страница редактирования
├── edit_employee.php               => страница редактирования сотрудника
├── edit_position.php               => страница редактирования должности
├── edit_user.php                   => страница редактирования пользователей
├── education_programs.php          => страница со списком образовательных программ
├── index.php                       => главная страница, на ней располагается таблица сотрудников
├── login.php                       => страница авторизации
├── positions.php                   => страница со списком должностей и их управлением
├── register.php                    => страница регистрации
├── settings_users.php              => страница со списком пользователей и их управлением (для администратора)
├── settings.php                    => страница с настройками пользователя
├── backend/                        => папка для php скриптов
│   ├── auth.php                    => скрипт для авторизации
│   ├── clear_employee.php          => скрипт очистки таблицы сотрудников
│   ├── db.php                      => скрипт для подключения к базе данных
│   ├── delete_discipline.php       => скрипт удаления дисциплины
│   ├── delete_employee.php         => скрипт удаления сотрудника
│   ├── delete_position.php         => скрипт удаления должности
│   ├── delete_program.php          => скрипт удаления учебной программы
│   ├── delete_user.php             => скрипт удаления пользователя
│   ├── disciplines.php             => backend часть страницы disciplines.php
│   ├── index.php                   => backend часть страницы
│   ├── login.php                   => скрипт страницы авторизации
│   ├── logout.php                  => скрипт деавторизации
│   ├── positions.php               => backend часть страницы positions.php
│   ├── register.php                => скрипт регистрации
│   └── settings_users.php          => backend часть страницы settings_users.php
├── style/                          => папка с css стилями
│   ├── create_discipline.css       => стили для страниц создания должности, дисциплины
│   ├── create_employee.css         => стили для страницы создания сотрудника
│   ├── edit.css                    => стили для страниц редактирования
│   ├── index.css                   => стили для главной страницы
│   ├── settings.css                => стили для страницы настроек
│   ├── style.css                   => стили для страниц регистрации и авторизации
│   └── table.css                   => стили для таблиц
├── javascript/                     => папка для js скриптов
│   └── index.js                    => Файл, в котором хранятся все скрипты
