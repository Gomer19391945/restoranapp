<?php
// Обязательно подключаем БД до вывода любого HTML
require_once 'config/db.php';

$errors = [];
$name   = '';
$email  = '';
$phone  = '';
$address  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Получаем данные
    $name             = trim($_POST['name'] ?? '');
    $email            = trim($_POST['email'] ?? '');
    $phone            = trim($_POST['phone'] ?? '');
    $address            = trim($_POST['address'] ?? '');
    $password         = trim($_POST['password'] ?? '');
    $password_confirm = trim($_POST['password_confirm'] ?? '');

    // 2. Валидация данных
    if (empty($name)) {
        $errors[] = 'Введите ваше имя.';
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Введите корректный email.';
    }

    // Валидация телефона (опционально, но если ввели - проверяем)
    if (!empty($phone) && !preg_match('/^\+?[0-9]{10,15}$/', $phone)) {
        $errors[] = 'Введите корректный номер телефона (от 10 до 15 цифр).';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Пароль должен содержать минимум 6 символов.';
    }

    if ($password !== $password_confirm) {
        $errors[] = 'Пароли не совпадают.';
    }

    // 3. Проверка уникальности email
    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Пользователь с таким email уже зарегистрирован.';
        }
    }

    // 4. Сохранение в базу
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Роль 'user' и дата присвоятся автоматически благодаря настройкам БД
        $stmt = $pdo->prepare(
            'INSERT INTO users (name, email, phone, address, password) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$name, $email, $phone, address, $password_hash]);

        // 5. Перенаправление при успехе
        header('Location: login.php?registered=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Регистрация в F&H</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<main class="main">
  <div class="container">
    <div class="auth-card">
      <h1 class="auth-card__title">Регистрация F&H</h1>
      <p class="auth-card__subtitle">Заказывайте лучшую еду в пару кликов!</p>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
          <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form class="form" method="POST" action="">
        
        <div class="form-group">
          <label class="form-label" for="name">Ваше Имя</label>
          <input class="form-input" type="text" id="name" name="name"
            value="<?= htmlspecialchars($name) ?>"
            placeholder="Например, Антон" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="email">Email</label>
          <input class="form-input" type="email" id="email" name="email"
            value="<?= htmlspecialchars($email) ?>"
            placeholder="ваш@email.com" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="phone">Телефон</label>
          <input class="form-input" type="tel" id="phone" name="phone"
            value="<?= htmlspecialchars($phone) ?>"
            placeholder="+7 (700) 000-00-00">
        </div>

        <div class="form-group">
          <label class="form-label" for="address">адрес</label>
          <input class="form-input" type="address" id="address" name="address"
            value="<?= htmlspecialchars($address) ?>"
            placeholder="г.Сарань ул.Победы 4/2">
        </div>

        <div class="form-group">
          <label class="form-label" for="password">Пароль</label>
          <input class="form-input" type="password" id="password" name="password"
            placeholder="Минимум 6 символов" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="password_confirm">Повторите пароль</label>
          <input class="form-input" type="password" id="password_confirm"
            name="password_confirm" placeholder="Повторите пароль" required>
        </div>

        <button class="btn btn-primary" type="submit">
          Создать аккаунт
        </button>

        <p class="auth-card__footer">
          Уже есть аккаунт? <a href="login.php">Войти</a>
        </p>

      </form>
    </div>
  </div>
</main>

</body>
</html>