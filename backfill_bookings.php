<?php
require_once __DIR__ . '/backend/config/database.php';

try {
    $stmt = $pdo->query("SELECT b.id, b.trip_id, t.title, t.slug, t.cover_image, t.duration_days, t.base_price, t.discounted_price, t.trip_type, t.difficulty 
                         FROM bookings b 
                         JOIN trips t ON b.trip_id = t.id 
                         WHERE b.trip_details IS NULL OR b.trip_details = ''");
    $toUpdate = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Found " . count($toUpdate) . " bookings to backfill.\n";

    $upStmt = $pdo->prepare("UPDATE bookings SET trip_details = ? WHERE id = ?");

    foreach ($toUpdate as $row) {
        $snapshot = [
            'title'            => $row['title'],
            'slug'             => $row['slug'],
            'cover_image'      => $row['cover_image'],
            'duration_days'    => $row['duration_days'],
            'base_price'       => $row['base_price'],
            'discounted_price' => $row['discounted_price'],
            'trip_type'        => $row['trip_type'],
            'difficulty'       => $row['difficulty']
        ];
        $upStmt->execute([json_encode($snapshot), $row['id']]);
        echo "Backfilled booking ID: {$row['id']}\n";
    }

    echo "Backfill complete!\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
