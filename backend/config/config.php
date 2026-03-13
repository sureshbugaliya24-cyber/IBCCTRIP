<?php
// backend/config/config.php
// DB Constants defined below (around line 14-50)
// We will include settings_init.php after defining them to allow DB settings to override env.php defaults.

// Branding and Version moved after settings injection
if (!defined('APP_VERSION')) define('APP_VERSION', '2.0.0');
if (!defined('APP_TAGLINE')) define('APP_TAGLINE', 'Premium Travel Experiences');

if (defined('IS_LIVE') && IS_LIVE == 1) {
    if (!defined('APP_ENV')) define('APP_ENV', 'production');
    if (!defined('BASE_URL')) define('BASE_URL', 'https://ibcctrip.com');
    
    // Live DB (Update these when deploying)
    if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
    if (!defined('DB_PORT')) define('DB_PORT', '3306');
    if (!defined('DB_NAME')) define('DB_NAME', 'ibcctrip'); 
    if (!defined('DB_USER')) define('DB_USER', 'root');
    if (!defined('DB_PASS')) define('DB_PASS', '');
} else {
    if (!defined('APP_ENV')) define('APP_ENV', 'development');
    
    // Base URL detection logic...
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $projectRootPath = str_replace('\\', '/', realpath(dirname(dirname(__DIR__))));
    $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])) : '';
    $rootPath = '';
    if ($docRoot && stripos($projectRootPath, $docRoot) === 0 && strlen($projectRootPath) > strlen($docRoot)) { $rootPath = '/' . trim(substr($projectRootPath, strlen($docRoot)), '/'); }
    else { if (preg_match('/htdocs\/(.+)$/i', $projectRootPath, $matches)) { $rootPath = '/' . $matches[1]; } else { $rootPath = '/' . basename($projectRootPath); } }
    $rootPath = '/' . ltrim(str_replace('\\', '/', $rootPath), '/');
    
    if (!defined('BASE_URL')) define('BASE_URL', $scheme . '://' . $host . rtrim($rootPath, '/'));

    // Local DB
    if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
    if (!defined('DB_PORT')) define('DB_PORT', '3306');
    if (!defined('DB_NAME')) define('DB_NAME', 'ibcctrip');
    if (!defined('DB_USER')) define('DB_USER', 'root');
    if (!defined('DB_PASS')) define('DB_PASS', '');
}

// settings_init.php and env.php will be included via database.php to ensure $pdo is available.
if (!defined('FRONTEND_URL')) define('FRONTEND_URL', BASE_URL);
if (!defined('ADMIN_URL'))    define('ADMIN_URL',    BASE_URL . '/admin');

// Upload paths
if (!defined('UPLOAD_DIR'))       define('UPLOAD_DIR',       __DIR__ . '/../../backend/uploads/');
if (!defined('UPLOAD_URL'))       define('UPLOAD_URL',       BASE_URL . '/backend/uploads/');
if (!defined('UPLOAD_MAX_SIZE'))  define('UPLOAD_MAX_SIZE',  5 * 1024 * 1024); // 5 MB
if (!defined('ALLOWED_IMAGE_TYPES')) define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);

// Session & Security
if (!defined('SESSION_NAME'))     define('SESSION_NAME',     'ibcctrip_sess');
if (!defined('SESSION_LIFETIME')) define('SESSION_LIFETIME', 86400); // 24 hours

// Set secure session cookie parameters
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Lax');
if (defined('IS_LIVE') && IS_LIVE == 1) {
    ini_set('session.cookie_secure', 1);
}

// Security
if (!defined('CSRF_TOKEN_NAME')) define('CSRF_TOKEN_NAME', 'ibcc_csrf');

// Pagination
if (!defined('ITEMS_PER_PAGE')) define('ITEMS_PER_PAGE', 12);

// API settings
if (!defined('API_BASE')) define('API_BASE', BASE_URL . '/backend/api');
