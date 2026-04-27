<?php
// config/db.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'restoranapp'); // Имя твоей базы данных для F&H
define('DB_USER', 'root');
define('DB_PASS', '');            // Пароль в OpenServer обычно пустой
define('DB_CHARSET', 'utf8mb4');

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Ошибка подключения к базе данных F&H: ' . $e->getMessage());
}
?>