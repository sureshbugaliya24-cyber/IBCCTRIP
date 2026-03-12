<?php
require_once __DIR__ . '/backend/config/config.php';
require_once __DIR__ . '/backend/config/database.php';
$tables = ['trips','blogs','countries','states','cities','places','users','trip_itinerary','trip_gallery'];
header('Content-Type: text/plain');
foreach($tables as $t) {
    echo "=== $t ===\n";
    try {
        $s = $pdo->query("DESCRIBE `$t`");
        foreach($s as $r) echo $r['Field'].' ('.$r['Type'].")\n";
    } catch(Exception $e) {
        echo 'Error: '.$e->getMessage()."\n";
    }
    echo "\n";
}
