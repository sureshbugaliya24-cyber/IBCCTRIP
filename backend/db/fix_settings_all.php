<?php
// backend/db/fix_settings_all.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Direct connection to avoid any config inclusion loops
$dsn = 'mysql:host=localhost;dbname=ibcctrip;charset=utf8mb4';
try {
    $pdo = new PDO($dsn, 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    // Create Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS site_settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category VARCHAR(50) NOT NULL,
        s_key VARCHAR(50) UNIQUE NOT NULL,
        s_value TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "Table verified/created.\n";
    
    // Seed Data
    $settings = [
        ['branding', 'site_name_part1', 'IBCC'],
        ['branding', 'site_name_part2', 'Trip'],
        ['branding', 'site_icon_svg', '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 004 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'],
        ['contact', 'contact_phone', '+91 7878335572'],
        ['contact', 'contact_email', 'info@ibcctrip.com'],
        ['contact', 'contact_address', '123, Travel Hub, Connaught Place, New Delhi - 110001'],
        ['contact', 'whatsapp_no', '917878335572'],
        ['social', 'facebook_url', 'https://facebook.com/ibcctrip'],
        ['social', 'instagram_url', 'https://instagram.com/ibcctrip'],
        ['social', 'twitter_url', 'https://twitter.com/ibcctrip'],
        ['style', 'color_primary', '#0B3D91'],
        ['style', 'color_secondary', '#FF6B00']
    ];
    
    $stmt = $pdo->prepare("INSERT INTO site_settings (category, s_key, s_value) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE s_value = VALUES(s_value)");
    foreach ($settings as $s) {
        $stmt->execute($s);
    }
    echo "Data seeded successfully.\n";
    
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
