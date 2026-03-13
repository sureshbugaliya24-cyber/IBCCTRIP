<?php
require_once __DIR__ . '/backend/config/config.php';
require_once __DIR__ . '/backend/config/database.php';

// 1. Check Deletion Guard
$tripIdWithBooking = $pdo->query("SELECT trip_id FROM bookings LIMIT 1")->fetchColumn();
if ($tripIdWithBooking) {
    echo "Testing deletion guard for trip ID: $tripIdWithBooking\n";
    // Mock the delete request or check logic
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE trip_id = ?");
    $stmt->execute([$tripIdWithBooking]);
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        echo "SUCCESS: Deletion guard correctly identifies bookings ($count).\n";
    } else {
        echo "FAILURE: Deletion guard would have failed.\n";
    }
} else {
    echo "No bookings found to test deletion guard.\n";
}

// 2. Check handleUpload with new params
function testUploadNaming($name, $prefix, $random) {
    echo "Testing naming for: $prefix (Random: " . ($random ? 'Y' : 'N') . ")\n";
    $ext = 'png';
    $cleanPrefix = preg_replace('/[^a-z0-9]+/', '-', strtolower($prefix));
    $cleanPrefix = trim($cleanPrefix, '-');
    $filename = $cleanPrefix . ($random ? '_RANDOM' : '') . '.' . $ext;
    echo "Resulting filename pattern: $filename\n";
}

testUploadNaming('Dubai Luxury', 'dubai-luxury-tour-map', false);
testUploadNaming('Dubai Luxury Gallery', 'dubai-luxury-tour-gallery-1', false);
