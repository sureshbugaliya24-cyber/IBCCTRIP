<?php
// backend/api/gallery.php — Public Gallery API
// ============================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/ResponseHelper.php';
require_once __DIR__ . '/../middleware/CorsMiddleware.php';

CorsMiddleware::handle();
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: public, max-age=600'); // Cache for 10 minutes

$action = $_GET['action'] ?? 'list';

try {
    switch ($action) {
        case 'list':
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;
            $stmt = $pdo->prepare("SELECT id, image_url, category, caption, created_at FROM gallery ORDER BY created_at DESC LIMIT ?");
            $stmt->execute([$limit]);
            ResponseHelper::success($stmt->fetchAll());
            break;

        default:
            ResponseHelper::error('Invalid action', 400);
    }
} catch (PDOException $e) {
    ResponseHelper::error(
        APP_ENV === 'production' ? 'Server error' : $e->getMessage(), 500
    );
}
