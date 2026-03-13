<?php
// backend/db/audit_schema.php
require_once __DIR__ . '/../config/database.php';

function auditTable($pdo, $table) {
    echo "\n--- AUDIT: $table ---\n";
    try {
        // Columns
        $q = $pdo->query("DESCRIBE `$table`")->fetchAll();
        foreach($q as $f) {
            echo " [COL] {$f['Field']} | {$f['Type']} | KEY:{$f['Key']}\n";
        }
        
        // Indexes
        echo " [INDEXES]\n";
        $q = $pdo->query("SHOW INDEX FROM `$table`")->fetchAll();
        foreach($q as $i) {
            echo "  - {$i['Key_name']} | COLUMN: {$i['Column_name']} | UNIQUE: ".($i['Non_unique'] ? 'No' : 'Yes')."\n";
        }

        // Engine
        $q = $pdo->query("SHOW TABLE STATUS WHERE Name = '$table'")->fetch();
        echo " [ENGINE] {$q['Engine']}\n";

    } catch (Exception $e) {
        echo " ERROR: " . $e->getMessage() . "\n";
    }
}

auditTable($pdo, 'bookings');
auditTable($pdo, 'payments');
auditTable($pdo, 'trips');
auditTable($pdo, 'users');
