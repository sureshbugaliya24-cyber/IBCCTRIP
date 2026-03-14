<?php
// backend/config/settings_init.php (Called from database.php)

$GLOBAL_SETTINGS = [];
if (!isset($pdo)) return; 
try {
    $stmt = $pdo->query("SELECT s_key, s_value FROM site_settings");
    while ($row = $stmt->fetch()) {
        $GLOBAL_SETTINGS[$row['s_key']] = $row['s_value'];
    }
} catch (Exception $e) {
    // Fail silently if table doesn't exist yet
}

function get_site_setting($key, $default = '') {
    global $GLOBAL_SETTINGS;
    return $GLOBAL_SETTINGS[strtolower($key)] ?? $default;
}

// Map settings to constants if not already defined
$mappings = [
    'CONTACT_PHONE'   => 'contact_phone',
    'CONTACT_EMAIL'   => 'contact_email',
    'CONTACT_ADDRESS' => 'contact_address',
    'FACEBOOK_URL'    => 'facebook_url',
    'INSTAGRAM_URL'   => 'instagram_url',
    'WHATSAPP_NO'     => 'whatsapp_no',
    'SITE_NAME_PART1' => 'site_name_part1',
    'SITE_NAME_PART2' => 'site_name_part2',
    'COLOR_PRIMARY'   => 'color_primary',
    'COLOR_SECONDARY' => 'color_secondary',
    'SITE_ICON_SVG'   => 'site_icon_svg',
    'COMPANY_NAME'    => 'company_name',
    'COMPANY_GST'     => 'company_gst',
    
    // Payments
    'PAYMENT_RAZORPAY_ENABLED' => 'payment_razorpay_enabled',
    'PAYMENT_RAZORPAY_KEY'     => 'payment_razorpay_key',
    'PAYMENT_RAZORPAY_SECRET'  => 'payment_razorpay_secret',
    'PAYMENT_COD_ENABLED'      => 'payment_cod_enabled',
    
    // Social
    'TWITTER_URL'  => 'twitter_url',
    'YOUTUBE_URL'  => 'youtube_url',
    
    // Placeholders
    'PLACEHOLDER_GENERAL' => 'placeholder_general',
    'PLACEHOLDER_TRIP'    => 'placeholder_trip',
    'PLACEHOLDER_BLOG'    => 'placeholder_blog',
    'PLACEHOLDER_CITY'    => 'placeholder_city'
];

foreach ($mappings as $const => $key) {
    if (isset($GLOBAL_SETTINGS[$key])) {
        if (!defined($const)) define($const, $GLOBAL_SETTINGS[$key]);
    }
}

if (!defined('APP_NAME')) {
    define('APP_NAME', (defined('SITE_NAME_PART1') ? SITE_NAME_PART1 . ' ' . SITE_NAME_PART2 : 'IBCC Trip'));
}

if (!defined('COLOR_PRIMARY')) define('COLOR_PRIMARY', '#1E1B7A');
if (!defined('COLOR_SECONDARY')) define('COLOR_SECONDARY', '#f97316');

