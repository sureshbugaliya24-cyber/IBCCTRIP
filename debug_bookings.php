<?php
require_once __DIR__ . '/backend/config/database.php';

$stmt = $pdo->query("SELECT id, trip_id, trip_details, booking_ref FROM bookings ORDER BY id DESC LIMIT 5");
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Last 5 Bookings:\n";
foreach ($bookings as $b) {
    echo "ID: {$b['id']}, Ref: {$b['booking_ref']}, TripID: " . ($b['trip_id'] ?? 'NULL') . ", Details: " . ($b['trip_details'] ? 'Set' : 'Empty') . "\n";
    if ($b['trip_details']) {
        print_r(json_decode($b['trip_details'], true));
    }
    echo "-------------------\n";
}
