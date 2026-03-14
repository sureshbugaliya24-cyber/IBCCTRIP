<?php
// components/config.php
// Load Backend Config & Database (Includes settings_init.php)
require_once __DIR__ . '/../backend/config/database.php';

require_once __DIR__ . '/../backend/config/env.php';

if (!defined('APP_NAME')) {
    define('APP_NAME', (defined('SITE_NAME_PART1') ? SITE_NAME_PART1 . ' ' . SITE_NAME_PART2 : 'IBCC Trip'));
}
if (!defined('APP_TAGLINE')) define('APP_TAGLINE', 'Your Journey, Our Passion');
if (!defined('APP_VERSION')) define('APP_VERSION', '2.0');

if (defined('IS_LIVE') && IS_LIVE == 1) {
    if (!defined('BASE_URL')) define('BASE_URL', 'https://ibcctrip.com');
} else {
    // Detect base URL automatically for local
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    $projectRootPath = str_replace('\\', '/', realpath(dirname(__DIR__)));
    $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])) : '';

    $rootPath = '';
    if ($docRoot && stripos($projectRootPath, $docRoot) === 0 && strlen($projectRootPath) > strlen($docRoot)) {
        $rootPath = '/' . trim(substr($projectRootPath, strlen($docRoot)), '/');
    } else {
        if (preg_match('/htdocs\/(.+)$/i', $projectRootPath, $matches)) {
            $rootPath = '/' . $matches[1];
        } else {
            $rootPath = '/' . basename($projectRootPath);
        }
    }
    $rootPath = '/' . ltrim(str_replace('\\', '/', $rootPath), '/');
    if (!defined('BASE_URL')) define('BASE_URL', $scheme . '://' . $host . rtrim($rootPath, '/'));
}
if (!defined('FRONTEND_URL')) define('FRONTEND_URL', BASE_URL);
if (!defined('ADMIN_URL'))    define('ADMIN_URL',    BASE_URL . '/admin');
if (!defined('API_URL'))      define('API_URL',      BASE_URL . '/backend/api');

// Session
if (!defined('FE_SESSION_NAME')) define('FE_SESSION_NAME', 'ibcctrip_sess');

// Global Settings Sync
try {
    $stmtSettings = $pdo->query("SELECT s_key, s_value FROM site_settings");
    $settingsData = $stmtSettings->fetchAll(PDO::FETCH_KEY_PAIR);
    foreach ($settingsData as $s_key => $s_val) {
        $constName = strtoupper($s_key);
        if (!defined($constName)) define($constName, $s_val);
    }
} catch (Exception $e) {}
