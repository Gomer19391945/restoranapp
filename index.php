<?php
// 1. Подключение к базе данных
require_once 'config/db.php';

// 2. Получение данных (например, 6 последних добавленных блюд)
try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC LIMIT 6");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Ошибка загрузки данных: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F&H - Food & Health | Агрегатор доставки</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="header">
    <div class="container">
        <nav class="nav">
            <a href="index.php" class="logo">F&H</a>
            <ul class="nav-list">
                <li><a href="catalog.php">Рестораны</a></li>
                <li><a href="register.php">Регистрация</a></li>
                <li><a href="login.php" class="btn-login">Войти</a></li>
            </ul>
        </nav>
    </div>
</header>

<section class="hero">
    <div class="container">
        <h1>Здоровая еда из лучших ресторанов Казахстана</h1>
        <p>Выбирайте блюда с расчетом КБЖУ и заказывайте доставку в пару кликов.</p>
        <form action="search.php" method="GET" class="search-form">
            <input type="text" name="query" placeholder="Поиск блюда или ресторана...">
            <button type="submit">Найти</button>
        </form>
    </div>
</section>

<main class="main">
    <div class="container">
        <h2 class="section-title">Популярные блюда</h2>
        
        <div class="product-grid">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="img/<?= $product['image'] ?? 'default_food.jpg' ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-card__img">
                        <div class="product-card__content">
                            <h3 class="product-card__title"><?= htmlspecialchars($product['name']) ?></h3>
                            <p class="product-card__info">КБЖУ: <?= $product['calories'] ?? '0' ?> ккал</p>
                            <div class="product-card__footer">
                                <span class="product-card__price"><?= number_format($product['price'], 0, '', ' ') ?> ₸</span>
                                <a href="product.php?id=<?= $product['id'] ?>" class="btn-add">Заказать</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>К сожалению, меню пока пусто. Зайдите позже!</p>
            <?php endif; ?>
        </div>
    </div>
</main>

<footer class="footer">
    <div class="container">
        <p>&copy; 2026 F&H (Food & Health). Мажит Ансар ПВТ-9-24</p>
    </div>
</footer>

</body>
</html>