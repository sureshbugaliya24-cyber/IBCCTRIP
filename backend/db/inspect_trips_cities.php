<?php
// backend/db/inspect_trips_cities.php
require_once __DIR__ . '/../config/database.php';

try {
    echo "--- TRIPS SCHEMA ---\n";
    $q = $pdo->query("SHOW CREATE TABLE trips");
    $r = $q->fetch();
    echo $r['Create Table'] . "\n\n";

    echo "--- CITIES TABLE SAMPLE ---\n";
    $q = $pdo->query("SELECT id, name FROM cities LIMIT 5");
    while($r = $q->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: {$r['id']}, Name: {$r['name']}\n";
    }

    echo "\n--- CITIES COUNT ---\n";
    $q = $pdo->query("SELECT COUNT(*) as total FROM cities");
    $r = $q->fetch();
    echo "Total cities in DB: {$r['total']}\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
