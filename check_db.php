<?php
require_once __DIR__ . '/backend/config/config.php';
require_once __DIR__ . '/backend/config/database.php';

$stmt = $pdo->query("SELECT id, full_name, phone FROM users WHERE email = 'rajesh@example.com' OR full_name LIKE '%Rajesh%'");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($users);

$stmt2 = $pdo->query("SELECT id, user_id, phone FROM bookings WHERE user_id = 2 OR email = 'rajesh@example.com'");
$bookings = $stmt2->fetchAll(PDO::FETCH_ASSOC);
print_r($bookings);

$stmt3 = $pdo->query("SELECT id, booking_id, name, type FROM booking_members");
$members = $stmt3->fetchAll(PDO::FETCH_ASSOC);
print_r($members);
