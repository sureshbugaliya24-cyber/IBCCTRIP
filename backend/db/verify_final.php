<?php
// backend/db/verify_final.php
require_once __DIR__ . '/../config/database.php';

function checkTable($pdo, $name) {
    echo "--- $name ---\n";
    try {
        $q = $pdo->query("DESCRIBE `$name`")->fetchAll();
        foreach($q as $f) echo "{$f['Field']} | {$f['Type']}\n";
    } catch(Exception $e) { echo "ERROR: " . $e->getMessage() . "\n"; }
}

checkTable($pdo, 'contact_messages');
checkTable($pdo, 'site_settings');
checkTable($pdo, 'site_stats');
