<?php
// backend/db/add_payment_settings.php
require_once __DIR__ . '/../config/database.php';

$settings = [
    ['payment', 'payment_razorpay_enabled', '0'],
    ['payment', 'payment_razorpay_key', ''],
    ['payment', 'payment_razorpay_secret', ''],
    ['payment', 'payment_cod_enabled', '1']
];

$stmt = $pdo->prepare("INSERT IGNORE INTO site_settings (category, s_key, s_value) VALUES (?, ?, ?)");

foreach ($settings as $s) {
    if ($stmt->execute($s)) {
        echo "Added setting: {$s[1]}\n";
    }
}

echo "Payment settings added successfully (if they didn't already exist)!";
