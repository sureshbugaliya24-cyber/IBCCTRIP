<?php
// backend/db/force_fix.php
require_once __DIR__ . '/../config/database.php';

try {
    // Drop and recreate site_stats
    $pdo->exec("DROP TABLE IF EXISTS `site_stats` CASCADE");
    $pdo->exec("CREATE TABLE `site_stats` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `stat_key` VARCHAR(50) UNIQUE NOT NULL,
      `stat_value` VARCHAR(50) NOT NULL,
      `stat_label` VARCHAR(100) NOT NULL,
      `icon` VARCHAR(50),
      `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("INSERT INTO `site_stats` (stat_key, stat_value, stat_label, icon) VALUES
        ('happy_travelers', '15,000+', 'Happy Travelers', '😊'),
        ('curated_tours', '500+', 'Curated Tours', '🌍'),
        ('destinations', '50+', 'Destinations', '📍'),
        ('experience', '12+', 'Years Experience', '⭐')");

    // Drop and recreate testimonials
    $pdo->exec("DROP TABLE IF EXISTS `testimonials` CASCADE");
    $pdo->exec("CREATE TABLE `testimonials` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `name` VARCHAR(100) NOT NULL,
      `role` VARCHAR(100) DEFAULT 'Traveler',
      `rating` TINYINT DEFAULT 5,
      `comment` TEXT NOT NULL,
      `image` VARCHAR(255),
      `status` ENUM('Draft', 'Published') DEFAULT 'Published',
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("INSERT INTO `testimonials` (name, role, rating, comment, status, image) VALUES
        ('Rajesh Sharma', 'Explorer from Delhi', 5, 'Booked Rajasthan trip. Absolutely flawless experience — hotel, transport, guides, everything was top-notch!', 'Published', ''),
        ('Priya Mehta', 'Traveler from Mumbai', 5, 'Our Bali honeymoon was a dream come true. The itinerary was perfect, with just the right balance.', 'Published', ''),
        ('Amit Patel', 'Explorer from Gujarat', 5, 'Third trip with IBCC Trip. Dubai package was amazing value. Will keep coming back!', 'Published', '')");

    echo "Force fix complete: Tables recreated and populated.\n";

} catch (Exception $e) {
    echo "Force fix error: " . $e->getMessage() . "\n";
}
