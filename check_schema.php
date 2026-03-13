<?php
// check_schema.php
require_once 'backend/config/database.php';
echo "--- SITE_STATS ---\n";
$q = $pdo->query("DESCRIBE site_stats");
print_r($q->fetchAll(PDO::FETCH_ASSOC));
echo "\n--- TESTIMONIALS ---\n";
$q = $pdo->query("DESCRIBE testimonials");
print_r($q->fetchAll(PDO::FETCH_ASSOC));
