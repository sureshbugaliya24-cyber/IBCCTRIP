<?php
require_once __DIR__ . '/backend/config/database.php';

try {
    // 1. Add trip_details column
    $pdo->exec("ALTER TABLE bookings ADD COLUMN trip_details JSON DEFAULT NULL AFTER base_price");
    echo "Added trip_details column to bookings.\n";

    // 2. Update Foreign Key constraint
    // First, find the constraint name (we saw it was bookings_ibfk_2)
    $pdo->exec("ALTER TABLE bookings DROP FOREIGN KEY bookings_ibfk_2");
    $pdo->exec("ALTER TABLE bookings MODIFY trip_id int(10) unsigned DEFAULT NULL"); // Ensure it's nullable
    $pdo->exec("ALTER TABLE bookings ADD CONSTRAINT bookings_trip_fk FOREIGN KEY (trip_id) REFERENCES trips (id) ON DELETE SET NULL");
    echo "Updated foreign key constraint to ON DELETE SET NULL.\n";

    echo "Migration successful!\n";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
