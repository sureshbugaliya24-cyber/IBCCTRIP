<?php
// backend/core/Router.php
class Router
{
    public static function route($pdo, $url)
    {
        $url = rtrim($url, '/');
        $segments = explode('/', $url);

        $page = $segments[0] ?: 'home';

        // Admin Routes
        if ($page == 'admin') {
            $adminPage = $segments[1] ?? 'dashboard';
            $file = __DIR__ . "/../admin/{$adminPage}.php";
            if (file_exists($file)) {
                require $file;
            }
            else {
                echo "Admin 404";
            }
            return;
        }

        // API Routes
        if ($page == 'api') {
            header('Content-Type: application/json');
            $apiRoute = $segments[1] ?? 'index';
            $file = __DIR__ . "/../api/{$apiRoute}.php";
            if (file_exists($file)) {
                require $file;
            }
            else {
                http_response_code(404);
                echo json_encode(['error' => 'API endpoint not found']);
            }
            return;
        }

        // --- PUBLIC VIEWS (Frontend) ---

        $childView = __DIR__ . "/../../frontend/views/{$page}.php";
        if (!file_exists($childView)) {
            $childView = __DIR__ . "/../../frontend/views/404.php";
        }

        // --- DYNAMIC SEO LOGIC ---
        $lang = I18n::getLang();
        $seo_title_col = "seo_title_" . $lang;
        $meta_desc_col = "meta_desc_" . $lang;
        $keywords_col = "keywords_" . $lang;
        $title_col = "title_" . $lang;

        // Define base protocol and domain dynamically or fallback
        $base_url = BASE_URL;

        // Build Canonical URL WITHOUT URL params like ?lang=hi
        $canonical_url = $base_url . ($url ? '/' . $url : '');

        if (isset($_GET['slug'])) {
            $canonical_url .= '?slug=' . $_GET['slug'];
        }

        $seo = [
            'title' => I18n::get('nav_' . $page) . ' - IBCC Trip',
            'meta_desc' => 'Premium travel agency providing the best trips.',
            'keywords' => 'travel, trips, agency, holidays',
            'canonical_url' => $canonical_url
        ];

        if ($page === 'home') {
            $seo['title'] = "IBCC Trip - Premium Travel Agency";
        }

        // Pre-fetch dynamic slugs if passed
        $slug = $_GET['slug'] ?? '';
        if ($slug) {
            if ($page === 'trip-details') {
                $stmt = $pdo->prepare("SELECT {$title_col} as title, {$seo_title_col} as seo_title, {$meta_desc_col} as meta_desc, {$keywords_col} as keywords FROM trips WHERE slug = ?");
                $stmt->execute([$slug]);
                $data = $stmt->fetch();
                if ($data) {
                    $seo['title'] = ($data['seo_title'] ?: $data['title']) . " - IBCC Trip";
                    if ($data['meta_desc'])
                        $seo['meta_desc'] = $data['meta_desc'];
                    if ($data['keywords'])
                        $seo['keywords'] = $data['keywords'];
                }
            }
            elseif ($page === 'blog-details') {
                $stmt = $pdo->prepare("SELECT {$title_col} as title, {$seo_title_col} as seo_title, {$meta_desc_col} as meta_desc, {$keywords_col} as keywords FROM blogs WHERE slug = ?");
                $stmt->execute([$slug]);
                $data = $stmt->fetch();
                if ($data) {
                    $seo['title'] = ($data['seo_title'] ?: $data['title']) . " - IBCC Trip";
                    if ($data['meta_desc'])
                        $seo['meta_desc'] = $data['meta_desc'];
                    if ($data['keywords'])
                        $seo['keywords'] = $data['keywords'];
                }
            }
        // Add similar logic for destinations (states, cities) if they become dynamic SEO pages.
        }

        require __DIR__ . "/../../frontend/views/layout.php";
    }
}
?>
