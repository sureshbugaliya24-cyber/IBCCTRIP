<?php
/**
 * migrate_paths.php
 * One-time script to update hardcoded image URLs in the database.
 */

require_once __DIR__ . '/backend/config/config.php';
require_once __DIR__ . '/backend/config/database.php';

echo "FRONTEND_URL detected as: " . FRONTEND_URL . "\n";

$targets = [
    'http://localhost/trip/ibcctrip/' => FRONTEND_URL . '/',
    'http://localhost/uploads/'       => FRONTEND_URL . '/uploads/'
];

$tables = [
    'trips'         => ['cover_image', 'map_image'],
    'trip_gallery'  => ['image_url'],
    'blogs'         => ['featured_image'],
    'countries'     => ['featured_image'],
    'states'        => ['featured_image'],
    'cities'        => ['featured_image'],
    'places'        => ['featured_image'],
    'trip_videos'   => ['thumbnail']
];

foreach ($tables as $table => $columns) {
    foreach ($columns as $column) {
        foreach ($targets as $oldPath => $newPath) {
            $sql = "UPDATE $table SET $column = REPLACE($column, ?, ?) WHERE $column LIKE ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$oldPath, $newPath, "$oldPath%"]);
            if ($stmt->rowCount() > 0) {
                echo "Updated $table.$column: " . $stmt->rowCount() . " rows affected (Replaced $oldPath with $newPath).\n";
            }
        }
    }
}

echo "Migration complete!\n";
