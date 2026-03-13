<?php
// backend/api/locations.php — Location Hierarchy API
// ============================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/ResponseHelper.php';
require_once __DIR__ . '/../middleware/CorsMiddleware.php';

CorsMiddleware::handle();
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: public, max-age=600'); // Cache for 10 minutes (static data)

$action = $_GET['action'] ?? 'countries';

try {
    switch ($action) {

        case 'countries':
            $stmt = $pdo->query(
                "SELECT c.*, COUNT(DISTINCT t.id) AS trip_count
                 FROM countries c
                 LEFT JOIN trips t ON t.country_id = c.id AND t.is_active = 1
                 GROUP BY c.id
                 ORDER BY c.sort_order ASC, c.name ASC"
            );
            ResponseHelper::success($stmt->fetchAll());
            break;

        case 'country':
            $slug = trim($_GET['slug'] ?? '');
            if (!$slug) ResponseHelper::error('Slug required');

            $stmt = $pdo->prepare("SELECT * FROM countries WHERE slug = ?");
            $stmt->execute([$slug]);
            $country = $stmt->fetch();
            if (!$country) ResponseHelper::error('Country not found', 404);

            // States
            $sStmt = $pdo->prepare("SELECT * FROM states WHERE country_id = ? ORDER BY sort_order, name");
            $sStmt->execute([$country['id']]);
            $country['states'] = $sStmt->fetchAll();

            // Trips
            $tStmt = $pdo->prepare(
                "SELECT id, title, slug, cover_image, base_price, discounted_price, duration_days
                 FROM trips WHERE country_id = ? AND is_active = 1 LIMIT 6"
            );
            $tStmt->execute([$country['id']]);
            $country['trips'] = $tStmt->fetchAll();

            // Blogs
            $bStmt = $pdo->prepare(
                "SELECT id, title, slug, featured_image, excerpt, created_at
                 FROM blogs WHERE country_id = ? AND status = 'Published' LIMIT 4"
            );
            $bStmt->execute([$country['id']]);
            $country['blogs'] = $bStmt->fetchAll();

            ResponseHelper::success($country);
            break;

        case 'states':
            $where  = [];
            $params = [];
            if (!empty($_GET['country_id'])) { $where[] = 'country_id = ?'; $params[] = (int)$_GET['country_id']; }
            $w = $where ? 'WHERE ' . implode(' AND ', $where) : '';
            $stmt = $pdo->prepare("SELECT s.*, c.name AS country_name FROM states s LEFT JOIN countries c ON s.country_id = c.id $w ORDER BY s.sort_order, s.name");
            $stmt->execute($params);
            ResponseHelper::success($stmt->fetchAll());
            break;

        case 'state':
            $slug = trim($_GET['slug'] ?? '');
            if (!$slug) ResponseHelper::error('Slug required');

            $stmt = $pdo->prepare(
                "SELECT s.*, c.name AS country_name, c.slug AS country_slug
                 FROM states s LEFT JOIN countries c ON s.country_id = c.id WHERE s.slug = ?"
            );
            $stmt->execute([$slug]);
            $state = $stmt->fetch();
            if (!$state) ResponseHelper::error('State not found', 404);

            $cStmt = $pdo->prepare("SELECT * FROM cities WHERE state_id = ? ORDER BY sort_order, name");
            $cStmt->execute([$state['id']]);
            $state['cities'] = $cStmt->fetchAll();

            $tStmt = $pdo->prepare("SELECT id, title, slug, cover_image, base_price, discounted_price, duration_days FROM trips WHERE state_id = ? AND is_active = 1 LIMIT 6");
            $tStmt->execute([$state['id']]);
            $state['trips'] = $tStmt->fetchAll();

            $bStmt = $pdo->prepare("SELECT id, title, slug, featured_image, excerpt FROM blogs WHERE state_id = ? AND status = 'Published' LIMIT 4");
            $bStmt->execute([$state['id']]);
            $state['blogs'] = $bStmt->fetchAll();

            ResponseHelper::success($state);
            break;

        case 'cities':
            $where  = [];
            $params = [];
            if (!empty($_GET['state_id'])) { $where[] = 'state_id = ?'; $params[] = (int)$_GET['state_id']; }
            $w = $where ? 'WHERE ' . implode(' AND ', $where) : '';
            $stmt = $pdo->prepare("SELECT ci.*, s.name AS state_name FROM cities ci LEFT JOIN states s ON ci.state_id = s.id $w ORDER BY ci.sort_order, ci.name");
            $stmt->execute($params);
            ResponseHelper::success($stmt->fetchAll());
            break;

        case 'city':
            $slug = trim($_GET['slug'] ?? '');
            if (!$slug) ResponseHelper::error('Slug required');

            $stmt = $pdo->prepare(
                "SELECT ci.*, s.name AS state_name, s.slug AS state_slug, c.name AS country_name, c.slug AS country_slug
                 FROM cities ci
                 LEFT JOIN states s    ON ci.state_id = s.id
                 LEFT JOIN countries c ON s.country_id = c.id
                 WHERE ci.slug = ?"
            );
            $stmt->execute([$slug]);
            $city = $stmt->fetch();
            if (!$city) ResponseHelper::error('City not found', 404);

            $pStmt = $pdo->prepare("SELECT * FROM places WHERE city_id = ? ORDER BY sort_order, name");
            $pStmt->execute([$city['id']]);
            $city['places'] = $pStmt->fetchAll();

            $tStmt = $pdo->prepare("SELECT id, title, slug, cover_image, base_price, discounted_price, duration_days FROM trips WHERE city_id = ? AND is_active = 1 LIMIT 6");
            $tStmt->execute([$city['id']]);
            $city['trips'] = $tStmt->fetchAll();

            // Related Trips (Same State, Different City)
            $rtStmt = $pdo->prepare(
                "SELECT id, title, slug, cover_image, base_price, discounted_price, duration_days 
                 FROM trips WHERE state_id = ? AND city_id != ? AND is_active = 1 LIMIT 4"
            );
            $rtStmt->execute([$city['state_id'], $city['id']]);
            $city['related_trips'] = $rtStmt->fetchAll();

            $bStmt = $pdo->prepare("SELECT id, title, slug, featured_image, excerpt FROM blogs WHERE city_id = ? AND status = 'Published' LIMIT 4");
            $bStmt->execute([$city['id']]);
            $city['blogs'] = $bStmt->fetchAll();

            ResponseHelper::success($city);
            break;

        case 'places':
            $where  = [];
            $params = [];
            if (!empty($_GET['city_id'])) { $where[] = 'city_id = ?'; $params[] = (int)$_GET['city_id']; }
            $w = $where ? 'WHERE ' . implode(' AND ', $where) : '';
            $stmt = $pdo->prepare("SELECT p.*, ci.name AS city_name FROM places p LEFT JOIN cities ci ON p.city_id = ci.id $w ORDER BY p.sort_order, p.name");
            $stmt->execute($params);
            ResponseHelper::success($stmt->fetchAll());
            break;

        case 'place':
            $slug = trim($_GET['slug'] ?? '');
            if (!$slug) ResponseHelper::error('Slug required');

            $stmt = $pdo->prepare(
                "SELECT p.*, ci.name AS city_name, ci.slug AS city_slug,
                        s.name AS state_name, s.slug AS state_slug,
                        c.name AS country_name, c.slug AS country_slug
                 FROM places p
                 LEFT JOIN cities ci   ON p.city_id = ci.id
                 LEFT JOIN states s    ON ci.state_id = s.id
                 LEFT JOIN countries c ON s.country_id = c.id
                 WHERE p.slug = ?"
            );
            $stmt->execute([$slug]);
            $place = $stmt->fetch();
            if (!$place) ResponseHelper::error('Place not found', 404);

            ResponseHelper::success($place);
            break;

        default:
            ResponseHelper::error('Invalid action', 400);
    }
} catch (PDOException $e) {
    ResponseHelper::error(
        APP_ENV === 'production' ? 'Server error' : $e->getMessage(), 500
    );
}
