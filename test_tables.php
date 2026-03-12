<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require __DIR__ . '/backend/config/database.php';

try {
    $pdo->query('SELECT 1 FROM blog_categories LIMIT 1');
    echo "blog_categories exists.\n";
} catch(Exception $e) {
    echo "blog_categories missing: " . $e->getMessage() . "\n";
}

try {
    $pdo->query('SELECT 1 FROM trips LIMIT 1');
    echo "trips exists.\n";
} catch(Exception $e) {
    echo "trips missing: " . $e->getMessage() . "\n";
}
