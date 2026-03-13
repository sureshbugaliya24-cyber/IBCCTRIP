<?php
require_once __DIR__ . '/backend/config/database.php';
$stmt = $pdo->query("DESCRIBE bookings");
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo "{$row['Field']} - {$row['Type']}\n";
}
echo "\n--- Foreign Keys ---\n";
$stmt = $pdo->query("SHOW CREATE TABLE bookings");
echo $stmt->fetch(PDO::FETCH_ASSOC)['Create Table'];
