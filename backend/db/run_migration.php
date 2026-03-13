<?php
// backend/db/run_migration.php
require_once __DIR__ . '/../config/database.php';

$file = __DIR__ . '/migration_v3.sql';
if (!file_exists($file)) die("File not found: $file\n");

$sql = file_get_contents($file);

try {
    // Basic multi-query execution
    // Note: PDO doesn't always support multiple queries in exec() depending on driver/settings
    // We'll split by ; and execute one by one
    $queries = explode(';', $sql);
    foreach ($queries as $query) {
        $q = trim($query);
        if ($q) {
            $pdo->exec($q);
        }
    }
    echo "Migration Success\n";
} catch (Exception $e) {
    echo "Migration Error: " . $e->getMessage() . "\n";
}
