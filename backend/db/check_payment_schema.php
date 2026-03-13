<?php
// backend/db/check_payment_schema.php
require_once __DIR__ . '/../config/database.php';

echo "Table: payments\n";
$q = $pdo->query("DESCRIBE payments");
while($r = $q->fetch()) echo " - {$r['Field']} | {$r['Type']}\n";

echo "\nTable: bookings\n";
$q = $pdo->query("DESCRIBE bookings");
while($r = $q->fetch()) echo " - {$r['Field']} | {$r['Type']}\n";
