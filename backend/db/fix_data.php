<?php
// backend/db/fix_data.php — Populate missing data
// ============================================================

require_once __DIR__ . '/../config/database.php';

try {
    // 1. Populate Site Stats if empty
    $count = $pdo->query("SELECT COUNT(*) FROM site_stats")->fetchColumn();
    if ($count == 0) {
        $pdo->exec("INSERT INTO `site_stats` (stat_key, stat_value, stat_label, icon) VALUES
            ('happy_travelers', '15,000+', 'Happy Travelers', '😊'),
            ('curated_tours', '500+', 'Curated Tours', '🌍'),
            ('destinations', '50+', 'Destinations', '📍'),
            ('experience', '12+', 'Years Experience', '⭐')");
        echo "Site stats populated.\n";
    }

    // 2. Populate Testimonials if empty
    $countT = $pdo->query("SELECT COUNT(*) FROM testimonials")->fetchColumn();
    if ($countT == 0) {
        $pdo->exec("INSERT INTO `testimonials` (name, role, rating, comment, status, image) VALUES
            ('Rajesh Sharma', 'Explorer from Delhi', 5, 'Booked Rajasthan trip. Absolutely flawless experience — hotel, transport, guides, everything was top-notch! IBCC Trip never disappoints.', 'Published', ''),
            ('Priya Mehta', 'Traveler from Mumbai', 5, 'Our Bali honeymoon was a dream come true. The itinerary was perfect, with just the right balance of adventure and relaxation.', 'Published', ''),
            ('Amit Patel', 'Explorer from Gujarat', 5, 'Third trip with IBCC Trip. Dubai package was amazing value. Will keep coming back — best travel agency in India!', 'Published', '')");
        echo "Testimonials populated.\n";
    }

    echo "Data fix complete.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
