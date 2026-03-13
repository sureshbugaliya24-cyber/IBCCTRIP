<?php
// verify_data.php
require_once 'backend/config/database.php';
$stats = $pdo->query("SELECT * FROM site_stats")->fetchAll(PDO::FETCH_ASSOC);
$testimonials = $pdo->query("SELECT * FROM testimonials")->fetchAll(PDO::FETCH_ASSOC);
echo "STATS COUNT: " . count($stats) . "\n";
print_r($stats);
echo "\nTESTIMONIALS COUNT: " . count($testimonials) . "\n";
print_r($testimonials);
