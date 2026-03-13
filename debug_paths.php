<?php
define('DEBUG_PATHS', true);
require_once __DIR__ . '/backend/config/config.php';
require_once 'backend/config/database.php';

echo "BASE_URL: " . BASE_URL . "\n";
echo "FRONTEND_URL: " . FRONTEND_URL . "\n";

$stmt = $pdo->query("SELECT id, title, cover_image, map_image FROM trips LIMIT 5");
$trips = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($trips as $trip) {
    echo "Trip: " . $trip['title'] . "\n";
    echo "  Cover: " . $trip['cover_image'] . "\n";
    echo "  Map: " . $trip['map_image'] . "\n";
}

$stmt = $pdo->query("SELECT id, image_url FROM trip_gallery LIMIT 5");
$gallery = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($gallery as $g) {
    echo "Gallery Image: " . $g['image_url'] . "\n";
}
