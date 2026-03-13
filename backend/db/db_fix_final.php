<?php
// backend/db/db_fix_final.php
require_once __DIR__ . '/../config/database.php';

echo "Database connected.\n";

try {
    // 1. Fix contact_messages
    echo "Fixing contact_messages...\n";
    $pdo->exec("ALTER TABLE `contact_messages` ADD COLUMN IF NOT EXISTS `status` ENUM('New', 'Trying to Contact', 'Talked', 'Replied') DEFAULT 'New'");
    $pdo->exec("ALTER TABLE `contact_messages` ADD COLUMN IF NOT EXISTS `admin_notes` TEXT");
    echo "contact_messages updated.\n";

    // 2. Fix site_settings if missing
    echo "Checking site_settings...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS site_settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category VARCHAR(50) NOT NULL,
        s_key VARCHAR(50) UNIQUE NOT NULL,
        s_value TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "site_settings verified.\n";

    // 3. Fix site_stats
    echo "Checking site_stats...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS site_stats (
      id INT AUTO_INCREMENT PRIMARY KEY,
      stat_key VARCHAR(50) UNIQUE NOT NULL,
      stat_value VARCHAR(50) NOT NULL,
      stat_label VARCHAR(100) NOT NULL,
      icon VARCHAR(50),
      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "site_stats verified.\n";

    echo "\nAll database fixes applied successfully!\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
