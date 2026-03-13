<?php
// backend/db/verify_structure.php
require_once __DIR__ . '/../config/database.php';

echo "Database connected.\n";

$tables = ['contact_messages', 'testimonials', 'site_stats', 'site_settings'];

foreach ($tables as $table) {
    echo "\nStructure for table: $table\n";
    try {
        $stmt = $pdo->query("DESCRIBE `$table`");
        while ($row = $stmt->fetch()) {
            echo " - {$row['Field']} ({$row['Type']})\n";
        }
    } catch (Exception $e) {
        echo " X ERROR: " . $e->getMessage() . "\n";
    }
}
