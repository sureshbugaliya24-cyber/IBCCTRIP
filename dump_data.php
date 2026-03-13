<?php
// dump_data.php
require_once 'backend/config/database.php';
function dump($table) {
    global $pdo;
    echo "--- $table CONTENT ---\n";
    try {
        $q = $pdo->query("SELECT * FROM $table");
        $results = $q->fetchAll(PDO::FETCH_ASSOC);
        echo "COUNT: " . count($results) . "\n";
        print_r($results);
    } catch (Exception $e) {
        echo "ERROR on $table: " . $e->getMessage() . "\n";
    }
}
dump('site_stats');
dump('testimonials');
