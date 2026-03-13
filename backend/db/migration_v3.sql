-- Migration: Create Testimonials and Contact Messages tables

CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20),
  `subject` VARCHAR(150),
  `message` TEXT NOT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `role` VARCHAR(100) DEFAULT 'Traveler',
  `rating` TINYINT DEFAULT 5,
  `comment` TEXT NOT NULL,
  `image` VARCHAR(255),
  `status` ENUM('Draft', 'Published') DEFAULT 'Published',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Optional: Initial stats table if needed for "Happy Travelers"
CREATE TABLE IF NOT EXISTS `site_stats` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `stat_key` VARCHAR(50) UNIQUE NOT NULL,
  `stat_value` VARCHAR(50) NOT NULL,
  `stat_label` VARCHAR(100) NOT NULL,
  `icon` VARCHAR(50),
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `site_stats` (stat_key, stat_value, stat_label, icon) VALUES
('happy_travelers', '15,000+', 'Happy Travelers', '😊'),
('curated_tours', '500+', 'Curated Tours', '🌍'),
('destinations', '50+', 'Destinations', '📍'),
('experience', '12+', 'Years Experience', '⭐');
