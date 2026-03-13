<?php
// backend/db/seed_settings.php
require_once __DIR__ . '/../config/database.php';

$settings = [
    // Branding
    ['branding', 'site_name_part1', 'IBCC'],
    ['branding', 'site_name_part2', 'Trip'],
    ['branding', 'site_icon_svg', '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 004 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'],
    
    // Contact
    ['contact', 'contact_phone', '+91 7878335572'],
    ['contact', 'contact_email', 'info@ibcctrip.com'],
    ['contact', 'contact_address', '123, Travel Hub, Connaught Place, New Delhi - 110001'],
    ['contact', 'whatsapp_no', '917878335572'],
    
    // Social
    ['social', 'facebook_url', 'https://facebook.com/ibcctrip'],
    ['social', 'instagram_url', 'https://instagram.com/ibcctrip'],
    ['social', 'twitter_url', 'https://twitter.com/ibcctrip'],
    
    // Style
    ['style', 'color_primary', '#0B3D91'],
    ['style', 'color_secondary', '#FF6B00']
];

$stmt = $pdo->prepare("INSERT INTO site_settings (category, s_key, s_value) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE s_value = VALUES(s_value)");

foreach ($settings as $s) {
    $stmt->execute($s);
}

echo "Site settings seeded successfully!";
