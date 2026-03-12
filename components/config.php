<?php
// frontend/components/config.php
// ─────────────────────────────────────────────────────────────
// Central configuration for the PHP frontend layer
// ─────────────────────────────────────────────────────────────

define('APP_NAME', 'IBCC Trip');
define('APP_TAGLINE', 'Your Journey, Our Passion');
define('APP_VERSION', '2.0');

// Detect base URL automatically (works on XAMPP and production)
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Get the physical path of this file and move up to the project root
$currentFilePath = str_replace('\\', '/', __DIR__); // .../components
$projectPath = dirname($currentFilePath); // .../ibcctrip

// Get document root
$docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);

// Calculate the relative URL path
$root = str_replace($docRoot, '', $projectPath);
$root = '/' . ltrim($root, '/');

define('BASE_URL', $scheme . '://' . $host . $root);
define('FRONTEND_URL', BASE_URL);
define('ADMIN_URL', BASE_URL . '/admin');
define('API_URL', BASE_URL . '/backend/api');

// WhatsApp number (no + or spaces)
define('WHATSAPP_NO', '917878335572');
define('CONTACT_EMAIL', 'info@ibcctrip.com');
define('CONTACT_PHONE', '+91 7878335572');
define('CONTACT_ADDRESS', '123, Travel Hub, Connaught Place, New Delhi - 110001');

// Company socials
define('FACEBOOK_URL', '#');
define('INSTAGRAM_URL', '#');
define('TWITTER_URL', '#');

// Session
define('FE_SESSION_NAME', 'ibcctrip_sess');
