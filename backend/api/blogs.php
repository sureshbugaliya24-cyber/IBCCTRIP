<?php
// backend/api/blogs.php — Blog API
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

        case 'list':
            $where  = ["b.status = 'Published'"];
            $params = [];
            $joins  = [];

            if (!empty($_GET['category'])) {
                $joins[] = 'LEFT JOIN blog_categories bc2 ON b.category_id = bc2.id';
                $where[] = 'bc2.slug = ?'; 
                $params[] = $_GET['category'];
            }
            if (!empty($_GET['category_id'])) {
                $where[] = 'b.category_id = ?'; $params[] = (int)$_GET['category_id'];
            }
            if (!empty($_GET['country_id'])) {
                $where[] = 'b.country_id = ?'; $params[] = (int)$_GET['country_id'];
            }
            if (!empty($_GET['q'])) {
                $where[] = '(b.title LIKE ? OR b.excerpt LIKE ?)';
                $params[] = '%'.$_GET['q'].'%';
                $params[] = '%'.$_GET['q'].'%';
            }
            
            $w = implode(' AND ', $where);
            $j = implode(' ', $joins);

            $countStmt = $pdo->prepare("SELECT COUNT(*) FROM blogs b $j WHERE $w");
            $countStmt->execute($params);
            $total = (int) $countStmt->fetchColumn();

            $stmt = $pdo->prepare(
                "SELECT b.id, b.title, b.slug, b.excerpt, b.featured_image, b.author, b.tags, b.created_at,
                        bc.name AS category_name, bc.slug AS category_slug,
                        c.name AS country_name
                 FROM blogs b
                 LEFT JOIN blog_categories bc ON b.category_id = bc.id
                 LEFT JOIN countries c         ON b.country_id = c.id
                 $j
                 WHERE $w
                 ORDER BY b.created_at DESC
                 LIMIT ? OFFSET ?"
            );
            $stmt->execute(array_merge($params, [$limit, $offset]));
            ResponseHelper::paginated($stmt->fetchAll(), $total, $page, $limit);
            break;

        case 'detail':
            $slug = trim($_GET['slug'] ?? '');
            if (!$slug) ResponseHelper::error('Slug required');

            $stmt = $pdo->prepare(
                "SELECT b.*, bc.name AS category_name, bc.slug AS category_slug,
                        c.name AS country_name, c.slug AS country_slug
                 FROM blogs b
                 LEFT JOIN blog_categories bc ON b.category_id = bc.id
                 LEFT JOIN countries c         ON b.country_id = c.id
                 WHERE b.slug = ? AND b.status = 'Published'"
            );
            $stmt->execute([$slug]);
            $blog = $stmt->fetch();
            if (!$blog) ResponseHelper::error('Blog not found', 404);

            $pdo->prepare("UPDATE blogs SET views = views + 1 WHERE slug = ?")->execute([$slug]);

            // Related
            $rStmt = $pdo->prepare(
                "SELECT id, title, slug, featured_image, created_at FROM blogs
                 WHERE status = 'Published' AND id != ? AND (category_id = ? OR country_id = ?)
                 LIMIT 3"
            );
            $rStmt->execute([$blog['id'], $blog['category_id'], $blog['country_id']]);
            $blog['related'] = $rStmt->fetchAll();

            ResponseHelper::success($blog);
            break;

        case 'categories':
            $stmt = $pdo->query(
                "SELECT c.*, 
                        (SELECT COUNT(*) FROM blogs b WHERE b.category_id = c.id AND b.status = 'Published') AS post_count 
                 FROM blog_categories c 
                 ORDER BY c.sort_order, c.name"
            );
            ResponseHelper::success($stmt->fetchAll());
            break;

        case 'recent':
            $stmt = $pdo->query(
                "SELECT id, title, slug, featured_image, excerpt, created_at FROM blogs
                 WHERE status = 'Published' ORDER BY created_at DESC LIMIT 5"
            );
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
