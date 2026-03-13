<?php
// backend/api/testimonials.php — Public Testimonials & Stats API
// ============================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/ResponseHelper.php';
require_once __DIR__ . '/../middleware/CorsMiddleware.php';

CorsMiddleware::handle();
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: public, max-age=300'); // Cache for 5 minutes

$action = $_GET['action'] ?? 'all';

try {
    if ($action === 'stats') {
        $stmt = $pdo->query("SELECT stat_key, stat_value, stat_label, icon FROM site_stats ORDER BY id ASC");
        ResponseHelper::success($stmt->fetchAll());
    } else {
        // Default: fetch published testimonials
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $stmt = $pdo->prepare("SELECT name, role, rating, comment, image FROM testimonials WHERE status = 'Published' ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        ResponseHelper::success($stmt->fetchAll());
    }
} catch (PDOException $e) {
    ResponseHelper::error(
        APP_ENV === 'production' ? 'Server error' : $e->getMessage(), 500
    );
}
