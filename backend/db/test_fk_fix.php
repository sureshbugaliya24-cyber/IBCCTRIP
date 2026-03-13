<?php
// backend/db/test_fk_fix.php
require_once __DIR__ . '/../config/database.php';

$toNull = fn($v) => ($v === '' || $v === 'null' || $v === null) ? null : $v;

$test_city_id = ""; // Empty string from form
$sanitized = $toNull($test_city_id);

echo "Testing toNull helper...\n";
echo "Input: [" . $test_city_id . "]\n";
echo "Sanitized: " . ($sanitized === null ? "NULL" : "VALUE") . "\n";

if ($sanitized === null) {
    echo "SUCCESS: Empty string converted to NULL correctly.\n";
} else {
    echo "FAILURE: Empty string NOT converted to NULL.\n";
    exit(1);
}

// Optional: Try a dummy insertion if you want to be 100% sure against the DB
try {
    echo "\nTesting actual INSERT with NULL city_id...\n";
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("INSERT INTO trips (title, slug, city_id) VALUES (?,?,?)");
    $stmt->execute(['FK Test Trip', 'fk-test-' . time(), null]);
    echo "SUCCESS: DB accepted NULL for city_id.\n";
    $pdo->rollBack(); // Don't actually keep it
} catch (Exception $e) {
    echo "FAILURE: DB rejected NULL for city_id: " . $e->getMessage() . "\n";
    $pdo->rollBack();
}
