<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="container">
        <h2>Авторизация</h2>
        <form action="backend/login.php" method="POST">
            <div class="form-group">
                <label for="login_username">Логин:</label>
                <input type="text" id="login_username" name="login_username" required>
            </div>
            <div class="form-group">
                <label for="login_password">Пароль:</label>
                <input type="password" id="login_password" name="login_password" required>
            </div>
            <button type="submit">Войти</button>
            <p>Еще нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
        </form>
        <div id="login_message"></div>
    </div>
    <script>
    const loginMessage = document.getElementById('login_message');

    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');
    const success = urlParams.get('success');

    if (message) {
      loginMessage.textContent = message;
      loginMessage.classList.add(success == "1" ? 'success' : 'error')
    }
    </script>
</body>
</html>