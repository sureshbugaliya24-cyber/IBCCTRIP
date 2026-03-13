<?php
// backend/db/verify_data.php
require_once __DIR__ . '/../config/database.php';

echo "Database connected.\n";

$t = $pdo->query("SELECT COUNT(*) FROM testimonials WHERE status='Published'")->fetchColumn();
echo "Published Testimonials: $t\n";

$s = $pdo->query("SELECT COUNT(*) FROM site_stats")->fetchColumn();
echo "Site Stats: $s\n";

$q = $pdo->query("SELECT * FROM testimonials LIMIT 3")->fetchAll();
var_dump($q);
