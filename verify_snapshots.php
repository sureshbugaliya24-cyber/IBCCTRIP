<?php
require_once __DIR__ . '/backend/config/config.php';
require_once __DIR__ . '/backend/config/database.php';

try {
    // 1. Create a test trip
    $pdo->exec("INSERT INTO trips (title, slug, base_price, duration_days, is_active) VALUES ('Snapshot Test Trip', 'snapshot-test', 5000, 3, 1)");
    $tripId = $pdo->lastInsertId();
    echo "Created test trip ID: $tripId\n";

    // 2. Create a booking (Simulate what bookings.php does)
    $bookingRef = 'TEST-REF-' . time();
    $snapshot = json_encode([
        'title' => 'Snapshot Test Trip',
        'slug' => 'snapshot-test',
        'cover_image' => 'test-image.jpg',
        'duration_days' => 3,
        'base_price' => 5000
    ]);
    
    $pdo->prepare("INSERT INTO bookings (booking_ref, trip_id, full_name, email, phone, total_price, trip_details, status) VALUES (?,?,?,?,?,?,?, 'Pending')")
        ->execute([$bookingRef, $tripId, 'Test User', 'test@example.com', '1234567890', 5000, $snapshot]);
    $bookingId = $pdo->lastInsertId();
    echo "Created booking ID: $bookingId with snapshot.\n";

    // 3. Delete the trip
    $pdo->prepare("DELETE FROM trips WHERE id = ?")->execute([$tripId]);
    echo "Deleted trip ID: $tripId.\n";

    // 4. Verify booking still exists and has NULL trip_id but has snapshot
    $stmt = $pdo->prepare("SELECT trip_id, trip_details FROM bookings WHERE id = ?");
    $stmt->execute([$bookingId]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($booking && $booking['trip_id'] === null && !empty($booking['trip_details'])) {
        echo "SUCCESS: Trip ID is NULL and snapshot exists.\n";
        $data = json_decode($booking['trip_details'], true);
        echo "Snapshot Title: " . $data['title'] . "\n";
    } else {
        echo "FAILURE: Booking state is incorrect.\n";
        print_r($booking);
    }

    // Cleanup
    $pdo->prepare("DELETE FROM bookings WHERE id = ?")->execute([$bookingId]);
    echo "Cleaned up test booking.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
