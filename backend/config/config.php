<?php
// backend/config/config.php
// ============================================================
// IBCC Trip — Application Configuration
// ============================================================

define('APP_NAME',    'IBCC Trip');
define('APP_VERSION', '2.0.0');
define('APP_ENV',     'development'); // 'production' on live server

// Base URL — adjust for your server setup
define('BASE_URL',    'http://localhost/trip/ibcctrip');
define('FRONTEND_URL', BASE_URL);
define('ADMIN_URL',    BASE_URL . '/admin');

// Upload paths
define('UPLOAD_DIR',       __DIR__ . '/../../backend/uploads/');
define('UPLOAD_URL',       BASE_URL . '/backend/uploads/');
define('UPLOAD_MAX_SIZE',  5 * 1024 * 1024); // 5 MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);

// Session
define('SESSION_NAME',     'ibcctrip_sess');
define('SESSION_LIFETIME', 86400); // 24 hours

// Security
define('CSRF_TOKEN_NAME', 'ibcc_csrf');

// Pagination
define('ITEMS_PER_PAGE', 12);

// API settings
define('API_BASE', BASE_URL . '/backend/api');
