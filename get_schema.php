<?php
require_once __DIR__ . '/backend/config/database.php';

$tables = ['trips', 'blogs', 'countries', 'states', 'cities', 'places'];
foreach($tables as $t) {
    echo "TABLE: $t\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM $t");
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
    echo "\n";
}
