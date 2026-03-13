<?php
// backend/api/trips.php  — Full Trip API
// ============================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/ResponseHelper.php';
require_once __DIR__ . '/../middleware/CorsMiddleware.php';

CorsMiddleware::handle();
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: public, max-age=300'); // Cache for 5 minutes

$action = $_GET['action'] ?? 'list';
$page   = max(1, (int)($_GET['page'] ?? 1));
$limit  = ITEMS_PER_PAGE;
$offset = ($page - 1) * $limit;

try {
    switch ($action) {

        // GET /api/trips.php?action=featured
        case 'featured':
            $stmt = $pdo->query(
                "SELECT t.id, t.title, t.slug, t.description, t.base_price, t.discounted_price,
                        t.duration_days, t.cover_image, t.difficulty, t.trip_type, t.max_members,
                        c.name AS country_name, c.slug AS country_slug,
                        ci.name AS city_name
                 FROM trips t
                 LEFT JOIN countries c ON t.country_id = c.id
                 LEFT JOIN cities ci   ON t.city_id = ci.id
                 WHERE t.is_featured = 1 AND t.is_active = 1
                 ORDER BY t.sort_order ASC, t.id DESC
                 LIMIT 6"
            );
            ResponseHelper::success($stmt->fetchAll());
            break;

        // GET /api/trips.php?action=list&page=&country_id=&state_id=&city_id=&min_price=&max_price=&duration=&type=
        case 'list':
            $where  = ['t.is_active = 1'];
            $params = [];

            if (!empty($_GET['country_id'])) {
                $where[] = 't.country_id = ?'; $params[] = (int)$_GET['country_id'];
            }
            if (!empty($_GET['state_id'])) {
                $where[] = 't.state_id = ?'; $params[] = (int)$_GET['state_id'];
            }
            if (!empty($_GET['city_id'])) {
                $where[] = 't.city_id = ?'; $params[] = (int)$_GET['city_id'];
            }
            if (!empty($_GET['min_price'])) {
                $where[] = 't.base_price >= ?'; $params[] = (float)$_GET['min_price'];
            }
            if (!empty($_GET['max_price'])) {
                $where[] = 't.base_price <= ?'; $params[] = (float)$_GET['max_price'];
            }
            if (!empty($_GET['duration'])) {
                $where[] = 't.duration_days = ?'; $params[] = (int)$_GET['duration'];
            }
            if (!empty($_GET['type'])) {
                $where[] = 't.trip_type = ?'; $params[] = $_GET['type'];
            }
            if (!empty($_GET['q'])) {
                $where[] = '(t.title LIKE ? OR t.description LIKE ?)';
                $params[] = '%' . $_GET['q'] . '%';
                $params[] = '%' . $_GET['q'] . '%';
            }

            $whereStr = implode(' AND ', $where);

            $countStmt = $pdo->prepare("SELECT COUNT(*) FROM trips t WHERE $whereStr");
            $countStmt->execute($params);
            $total = (int) $countStmt->fetchColumn();

            $dataParams = array_merge($params, [$limit, $offset]);
            $stmt = $pdo->prepare(
                "SELECT t.id, t.title, t.slug, t.description, t.base_price, t.discounted_price,
                        t.duration_days, t.cover_image, t.difficulty, t.trip_type, t.max_members,
                        c.name AS country_name, c.slug AS country_slug,
                        s.name AS state_name,
                        ci.name AS city_name
                 FROM trips t
                 LEFT JOIN countries c ON t.country_id = c.id
                 LEFT JOIN states s    ON t.state_id = s.id
                 LEFT JOIN cities ci   ON t.city_id = ci.id
                 WHERE $whereStr
                 ORDER BY t.sort_order ASC, t.id DESC
                 LIMIT ? OFFSET ?"
            );
            $stmt->execute($dataParams);

            ResponseHelper::paginated($stmt->fetchAll(), $total, $page, $limit);
            break;

        // GET /api/trips.php?action=detail&slug=jaipur-heritage-tour
        case 'detail':
            $slug = trim($_GET['slug'] ?? '');
            if (empty($slug)) {
                ResponseHelper::error('Slug is required', 400);
            }

            $stmt = $pdo->prepare(
                "SELECT t.*, c.name AS country_name, c.slug AS country_slug,
                        s.name AS state_name, s.slug AS state_slug,
                        ci.name AS city_name, ci.slug AS city_slug,
                        p.name AS place_name, p.slug AS place_slug
                 FROM trips t
                 LEFT JOIN countries c ON t.country_id = c.id
                 LEFT JOIN states s    ON t.state_id = s.id
                 LEFT JOIN cities ci   ON t.city_id = ci.id
                 LEFT JOIN places p    ON t.place_id = p.id
                 WHERE t.slug = ? AND t.is_active = 1"
            );
            $stmt->execute([$slug]);
            $trip = $stmt->fetch();

            if (!$trip) {
                ResponseHelper::error('Trip not found', 404);
            }

            // Increment view count
            $pdo->prepare("UPDATE trips SET views = views + 1 WHERE slug = ?")->execute([$slug]);

            // Itinerary
            $iStmt = $pdo->prepare(
                "SELECT day_number, title, description, meals, accommodation
                 FROM trip_itinerary WHERE trip_id = ? ORDER BY day_number ASC"
            );
            $iStmt->execute([$trip['id']]);
            $trip['itinerary'] = $iStmt->fetchAll();

            // Gallery
            $gStmt = $pdo->prepare(
                "SELECT image_url, alt_text FROM trip_gallery WHERE trip_id = ? ORDER BY sort_order ASC"
            );
            $gStmt->execute([$trip['id']]);
            $trip['gallery'] = $gStmt->fetchAll();

            // Videos
            $vStmt = $pdo->prepare(
                "SELECT title, youtube_url, thumbnail FROM trip_videos WHERE trip_id = ?"
            );
            $vStmt->execute([$trip['id']]);
            $trip['videos'] = $vStmt->fetchAll();

            // Related Trips (same country, excluding current)
            $relTripsStmt = $pdo->prepare(
                "SELECT id, title, slug, cover_image, base_price, discounted_price 
                 FROM trips 
                 WHERE country_id = ? AND id != ? AND is_active = 1 
                 ORDER BY id DESC LIMIT 3"
            );
            $relTripsStmt->execute([$trip['country_id'], $trip['id']]);
            $trip['related'] = $relTripsStmt->fetchAll();

            // Related Blogs (same country)
            $relBlogsStmt = $pdo->prepare(
                "SELECT id, title, slug, featured_image, created_at 
                 FROM blogs 
                 WHERE country_id = ? AND status = 'Published' 
                 ORDER BY created_at DESC LIMIT 3"
            );
            $relBlogsStmt->execute([$trip['country_id']]);
            $trip['related_blogs'] = $relBlogsStmt->fetchAll();

            // Decode JSON fields
            $trip['highlights'] = json_decode($trip['highlights'] ?? '[]', true);
            $trip['inclusions'] = json_decode($trip['inclusions'] ?? '[]', true);
            $trip['exclusions'] = json_decode($trip['exclusions'] ?? '[]', true);

            ResponseHelper::success($trip);
            break;

        // GET /api/trips.php?action=search&q=jaipur
        case 'search':
            $q = trim($_GET['q'] ?? '');
            if (strlen($q) < 2) {
                ResponseHelper::success([]);
            }
            $stmt = $pdo->prepare(
                "SELECT t.id, t.title, t.slug, t.cover_image, t.base_price, t.duration_days,
                        c.name AS country_name
                 FROM trips t
                 LEFT JOIN countries c ON t.country_id = c.id
                 WHERE t.is_active = 1 AND (t.title LIKE ? OR t.description LIKE ? OR c.name LIKE ?)
                 LIMIT 8"
            );
            $like = "%$q%";
            $stmt->execute([$like, $like, $like]);
            ResponseHelper::success($stmt->fetchAll());
            break;

        default:
            ResponseHelper::error('Invalid action', 400);
    }
} catch (PDOException $e) {
    ResponseHelper::error(
        APP_ENV === 'production' ? 'Server error' : $e->getMessage(),
        500
    );
}
