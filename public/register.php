<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="container">
        <h2>Регистрация</h2>
        <form action="backend/register.php" method="POST">
             <div class="form-group">
                <label for="username">Логин:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required>
            </div>
             <div class="form-group">
                <label for="password_repeat">Повторите пароль:</label>
                <input type="password" id="password_repeat" name="password_repeat" required>
            </div>
            <button type="submit">Зарегистрироваться</button>
            <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
        </form>
        <div id="register_message"></div>
    </div>
    <script>
    const registerMessage = document.getElementById('register_message');

    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');
    const success = urlParams.get('success');

    if (message) {
      registerMessage.textContent = message;
      registerMessage.classList.add(success == "1" ? 'success' : 'error')
    }
    </script>
</body>
</html>