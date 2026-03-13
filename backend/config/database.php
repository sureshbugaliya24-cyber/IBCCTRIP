<?php
// backend/config/database.php
require_once __DIR__ . '/config.php';

if (!defined('DB_CHARSET')) define('DB_CHARSET', 'utf8mb4');

try {
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
    );
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_FOUND_ROWS   => true,
    ]);
    require_once __DIR__ . '/settings_init.php';
} catch (PDOException $e) {
    if (APP_ENV === 'production') {
        http_response_code(500);
        die(json_encode(['error' => 'Database connection failed']));
    }
    die('DB Error: ' . $e->getMessage());
}
