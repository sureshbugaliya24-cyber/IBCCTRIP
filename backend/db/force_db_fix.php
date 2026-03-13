<?php
// backend/db/force_db_fix.php
require_once __DIR__ . '/../config/database.php';

try {
    echo "Starting Force Fix...\n";
    
    // 1. Manually check if columns exist first
    $stmt = $pdo->query("DESCRIBE `contact_messages`");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('status', $columns)) {
        echo "Adding 'status' column...\n";
        $pdo->exec("ALTER TABLE `contact_messages` ADD `status` ENUM('New', 'Trying to Contact', 'Talked', 'Replied') DEFAULT 'New'");
    } else {
        echo "'status' column already exists.\n";
    }
    
    if (!in_array('admin_notes', $columns)) {
        echo "Adding 'admin_notes' column...\n";
        $pdo->exec("ALTER TABLE `contact_messages` ADD `admin_notes` TEXT");
    } else {
        echo "'admin_notes' column already exists.\n";
    }
    
    echo "Verification:\n";
    $stmt = $pdo->query("DESCRIBE `contact_messages`");
    while($row = $stmt->fetch()) {
        echo " - Column: {$row['Field']}\n";
    }
    
} catch (Exception $e) {
    echo "FATAL ERROR: " . $e->getMessage() . "\n";
}
