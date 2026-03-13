<?php
// backend/db/migrate_payments.php
require_once __DIR__ . '/../config/database.php';

try {
    $pdo->exec("ALTER TABLE payments 
        ADD COLUMN IF NOT EXISTS razorpay_order_id VARCHAR(100) DEFAULT NULL AFTER invoice_number,
        ADD COLUMN IF NOT EXISTS razorpay_payment_id VARCHAR(100) DEFAULT NULL AFTER razorpay_order_id,
        ADD COLUMN IF NOT EXISTS razorpay_signature VARCHAR(255) DEFAULT NULL AFTER razorpay_payment_id");
    
    echo "Payments table migrated successfully!\n";
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
