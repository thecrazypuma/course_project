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
