<?php
// backend/api/admin.php — Admin CRUD API
// ============================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/ResponseHelper.php';
require_once __DIR__ . '/../middleware/CorsMiddleware.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

CorsMiddleware::handle();
header('Content-Type: application/json; charset=UTF-8');

session_name(SESSION_NAME);
session_start();

// ALL admin endpoints require admin role
$admin = AuthMiddleware::requireAdmin();

$method   = $_SERVER['REQUEST_METHOD'];
$resource = $_GET['resource'] ?? '';
$action   = $_GET['action']   ?? 'list';
$id       = (int)($_GET['id'] ?? 0);

$input = [];
if (in_array($method, ['POST', 'PUT'])) {
    $raw   = file_get_contents('php://input');
    $input = json_decode($raw, true) ?: $_POST;
}

$clean = fn($v) => htmlspecialchars(strip_tags(trim($v ?? '')), ENT_QUOTES, 'UTF-8');

function handleUpload($fileArray, $uploadDir = '../../uploads/', $prefix = 'img') {
    if (!isset($fileArray) || $fileArray['error'] !== UPLOAD_ERR_OK) return null;
    $ext = strtolower(pathinfo($fileArray['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) return null;
    
    // SEO Friendly naming
    $cleanPrefix = preg_replace('/[^a-z0-9]+/', '-', strtolower($prefix));
    $cleanPrefix = trim($cleanPrefix, '-');
    if (!$cleanPrefix) $cleanPrefix = 'img';
    
    $filename = $cleanPrefix . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $targetPath = __DIR__ . '/' . $uploadDir . $filename;
    
    // Ensure dir exists
    $dirPath = dirname($targetPath);
    if (!is_dir($dirPath)) mkdir($dirPath, 0777, true);
    
    if (move_uploaded_file($fileArray['tmp_name'], $targetPath)) {
        return FRONTEND_URL . '/uploads/' . $filename; // Store public URL
    }
    return null;
}

try {
    switch ($resource) {

        // ---- DASHBOARD STATS ----
        case 'stats':
            $stats = [];
            $stats['total_bookings']  = (int) $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
            $stats['total_customers'] = (int) $pdo->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn();
            $stats['total_trips']     = (int) $pdo->query("SELECT COUNT(*) FROM trips WHERE is_active=1")->fetchColumn();
            $stats['total_revenue']   = (float)$pdo->query("SELECT COALESCE(SUM(amount),0) FROM payments WHERE status='Paid'")->fetchColumn();

            $stats['pending_bookings']   = (int) $pdo->query("SELECT COUNT(*) FROM bookings WHERE status='Pending'")->fetchColumn();
            $stats['upcoming_bookings']  = (int) $pdo->query("SELECT COUNT(*) FROM bookings WHERE status='Scheduled' AND start_date >= CURDATE()")->fetchColumn();
            $stats['active_bookings']    = (int) $pdo->query("SELECT COUNT(*) FROM bookings WHERE status='In Progress'")->fetchColumn();
            $stats['completed_bookings'] = (int) $pdo->query("SELECT COUNT(*) FROM bookings WHERE status='Completed'")->fetchColumn();
            $stats['unread_messages']    = (int) $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read=0")->fetchColumn();

            // Revenue per month (last 6 months)
            $revStmt = $pdo->query(
                "SELECT DATE_FORMAT(created_at,'%Y-%m') AS month, COALESCE(SUM(amount),0) AS revenue
                 FROM payments WHERE status='Paid' AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                 GROUP BY month ORDER BY month ASC"
            );
            $stats['monthly_revenue'] = $revStmt->fetchAll();

            // Top trips
            $topStmt = $pdo->query(
                "SELECT t.title, t.slug, COUNT(b.id) AS booking_count
                 FROM trips t LEFT JOIN bookings b ON t.id = b.trip_id
                 GROUP BY t.id ORDER BY booking_count DESC LIMIT 5"
            );
            $stats['top_trips'] = $topStmt->fetchAll();

            // Recent bookings
            $recStmt = $pdo->query(
                "SELECT b.booking_ref, b.full_name, b.email, b.status, b.total_price, b.created_at,
                        t.title AS trip_title
                 FROM bookings b LEFT JOIN trips t ON b.trip_id = t.id
                 ORDER BY b.created_at DESC LIMIT 10"
            );
            $stats['recent_bookings'] = $recStmt->fetchAll();

            ResponseHelper::success($stats);
            break;

        // ---- TRIPS ----
        case 'trips':
            if ($action === 'list') {
                $page   = max(1, (int)($_GET['page'] ?? 1));
                $limit  = 20;
                $offset = ($page - 1) * $limit;
                $q      = trim($_GET['q'] ?? '');
                $where  = '1=1';
                $params = [];
                if ($q) { $where = 't.title LIKE ?'; $params[] = "%$q%"; }

                $total = (int) $pdo->prepare("SELECT COUNT(*) FROM trips t WHERE $where")->execute($params) ? 0 : 0;
                $cStmt = $pdo->prepare("SELECT COUNT(*) FROM trips t WHERE $where");
                $cStmt->execute($params);
                $total = (int) $cStmt->fetchColumn();

                $stmt = $pdo->prepare(
                    "SELECT t.*, c.name AS country_name
                     FROM trips t LEFT JOIN countries c ON t.country_id = c.id
                     WHERE $where ORDER BY t.id DESC LIMIT ? OFFSET ?"
                );
                $stmt->execute(array_merge($params, [$limit, $offset]));
                ResponseHelper::paginated($stmt->fetchAll(), $total, $page, $limit);
            }
            elseif ($action === 'detail' && $id) {
                $stmt = $pdo->prepare("SELECT * FROM trips WHERE id = ?");
                $stmt->execute([$id]);
                $trip = $stmt->fetch();
                if (!$trip) ResponseHelper::error('Not found', 404);

                $iStmt = $pdo->prepare("SELECT * FROM trip_itinerary WHERE trip_id = ? ORDER BY day_number");
                $iStmt->execute([$id]);
                $trip['itinerary'] = $iStmt->fetchAll();

                $gStmt = $pdo->prepare("SELECT * FROM trip_gallery WHERE trip_id = ? ORDER BY sort_order");
                $gStmt->execute([$id]);
                $trip['gallery'] = $gStmt->fetchAll();

                $vStmt = $pdo->prepare("SELECT * FROM trip_videos WHERE trip_id = ?");
                $vStmt->execute([$id]);
                $trip['videos'] = $vStmt->fetchAll();

                ResponseHelper::success($trip);
            }
            elseif ($action === 'create') {
                // Validate & insert trip
                $title    = $clean($input['title'] ?? '');
                $slug     = $clean($input['slug']  ?? '');
                $metaTitle = $clean($input['meta_title'] ?? $title);
                if (!$title || !$slug) ResponseHelper::error('Title and slug required');

                $coverImage = handleUpload($_FILES['cover_image'] ?? null, '../../uploads/', $metaTitle);

                $stmt = $pdo->prepare(
                    "INSERT INTO trips (title,slug,description,highlights,inclusions,exclusions,
                                       country_id,state_id,city_id,place_id,base_price,discounted_price,
                                       duration_days,max_members,difficulty,trip_type,is_featured,is_active,
                                       meta_title,meta_description,cover_image)
                     VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
                );
                $stmt->execute([
                    $title,
                    $slug,
                    $input['description'] ?? '',
                    $input['highlights']  ?? '[]',
                    $input['inclusions']  ?? '[]',
                    $input['exclusions']  ?? '[]',
                    $input['country_id']  ?? null,
                    $input['state_id']    ?? null,
                    $input['city_id']     ?? null,
                    $input['place_id']    ?? null,
                    (float)($input['base_price'] ?? 0),
                    !empty($input['discounted_price']) ? (float)$input['discounted_price'] : null,
                    (int)($input['duration_days'] ?? 1),
                    (int)($input['max_members']   ?? 20),
                    $input['difficulty']  ?? 'Easy',
                    $input['trip_type']   ?? 'Domestic',
                    (int)($input['is_featured'] ?? 0),
                    1,
                    $clean($input['meta_title'] ?? ''),
                    $clean($input['meta_description'] ?? ''),
                    $coverImage
                ]);
                ResponseHelper::success(['id' => (int)$pdo->lastInsertId()], 'Trip created');
            }
            elseif ($action === 'update' && $id) {
                $fields = ['title','slug','description','highlights','inclusions','exclusions',
                           'country_id','state_id','city_id','place_id','base_price','discounted_price',
                           'duration_days','max_members','difficulty','trip_type','is_featured','is_active',
                           'meta_title','meta_description','meta_keywords'];
                $sets   = [];
                $vals   = [];
                foreach ($fields as $f) {
                    if (array_key_exists($f, $input)) { $sets[] = "`$f` = ?"; $vals[] = $input[$f]; }
                }
                
                $metaTitle = $clean($input['meta_title'] ?? '');
                
                $coverImage = handleUpload($_FILES['cover_image'] ?? null, '../../uploads/', $metaTitle ?: 'trip-cover');
                if ($coverImage) { $sets[] = "`cover_image` = ?"; $vals[] = $coverImage; }
                $mapImage = handleUpload($_FILES['map_image'] ?? null, '../../uploads/', ($metaTitle ?: 'trip') . '-map');
                if ($mapImage) { $sets[] = "`map_image` = ?"; $vals[] = $mapImage; }

                if (empty($sets)) ResponseHelper::error('Nothing to update');
                $vals[] = $id;
                $pdo->prepare("UPDATE trips SET " . implode(',', $sets) . " WHERE id = ?")->execute($vals);

                // Save itinerary if provided
                if (!empty($input['itinerary'])) {
                    $itn = is_string($input['itinerary']) ? json_decode($input['itinerary'], true) : $input['itinerary'];
                    if (is_array($itn)) {
                        $pdo->prepare("DELETE FROM trip_itinerary WHERE trip_id=?")->execute([$id]);
                        $iStmt = $pdo->prepare("INSERT INTO trip_itinerary (trip_id,day_number,title,description,meals,accommodation) VALUES (?,?,?,?,?,?)");
                        foreach ($itn as $day) {
                            $iStmt->execute([$id, (int)($day['day_number']??1), $day['title']??'', $day['description']??'', $day['meals']??'', $day['accommodation']??'']);
                        }
                    }
                }

                // Save gallery images if uploaded
                if (!empty($_FILES['gallery_images'])) {
                    $gStmt = $pdo->prepare("INSERT INTO trip_gallery (trip_id, image_url, alt_text, sort_order) VALUES (?,?,?,?)");
                    $files = $_FILES['gallery_images'];
                    $count = is_array($files['name']) ? count($files['name']) : 1;
                    $galleryPrefix = ($metaTitle ?: 'trip') . '-gallery';
                    for ($i = 0; $i < $count; $i++) {
                        $singleFile = is_array($files['name']) ? ['name'=>$files['name'][$i],'type'=>$files['type'][$i],'tmp_name'=>$files['tmp_name'][$i],'error'=>$files['error'][$i],'size'=>$files['size'][$i]] : $files;
                        $imgUrl = handleUpload($singleFile, '../../uploads/', $galleryPrefix . '-' . ($i + 1));
                        if ($imgUrl) {
                            $gStmt->execute([$id, $imgUrl, '', $i]);
                        }
                    }
                }
                
                // Save videos if provided
                if (isset($input['videos'])) {
                    $pdo->prepare("DELETE FROM trip_videos WHERE trip_id = ?")->execute([$id]);
                    $vids = [];
                    if (is_string($input['videos']) && (strpos($input['videos'], '[') === 0)) {
                        $vids = json_decode($input['videos'], true) ?: [];
                    } elseif (is_array($input['videos'])) {
                        $vids = $input['videos'];
                    } else {
                        $vids = explode("\n", $input['videos']);
                    }
                    
                    $vStmt = $pdo->prepare("INSERT INTO trip_videos (trip_id, youtube_url) VALUES (?,?)");
                    foreach ($vids as $vurl) {
                        $vurl = trim($vurl);
                        if ($vurl) $vStmt->execute([$id, $vurl]);
                    }
                }

                ResponseHelper::success([], 'Trip updated');
            }
            elseif ($action === 'delete' && $id) {
                $pdo->prepare("DELETE FROM trips WHERE id = ?")->execute([$id]);
                ResponseHelper::success([], 'Trip deleted');
            }
            break;

        // ---- BOOKINGS ----
        case 'bookings':
            if ($action === 'list') {
                $page   = max(1, (int)($_GET['page'] ?? 1));
                $limit  = 20;
                $offset = ($page - 1) * $limit;
                $status = $_GET['status'] ?? '';
                $where  = $status ? "b.status = ?" : '1=1';
                $params = $status ? [$status] : [];

                $cStmt = $pdo->prepare("SELECT COUNT(*) FROM bookings b WHERE $where");
                $cStmt->execute($params);
                $total = (int) $cStmt->fetchColumn();

                $stmt = $pdo->prepare(
                    "SELECT b.*, t.title AS trip_title, p.invoice_number, p.status AS payment_status
                     FROM bookings b
                     LEFT JOIN trips t ON b.trip_id = t.id
                     LEFT JOIN payments p ON b.id = p.booking_id
                     WHERE $where ORDER BY b.created_at DESC LIMIT ? OFFSET ?"
                );
                $stmt->execute(array_merge($params, [$limit, $offset]));
                ResponseHelper::paginated($stmt->fetchAll(), $total, $page, $limit);
            }
            elseif ($action === 'update-status' && $id) {
                $status = $clean($input['status'] ?? '');
                $allowed = ['Pending','Scheduled','In Progress','Completed','Cancelled'];
                if (!in_array($status, $allowed)) ResponseHelper::error('Invalid status');
                $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?")->execute([$status, $id]);
                ResponseHelper::success([], 'Status updated');
            }
            break;

        // ---- CUSTOMERS ----
        case 'customers':
            $page   = max(1, (int)($_GET['page'] ?? 1));
            $limit  = 20;
            $offset = ($page - 1) * $limit;

            $cStmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role='customer'");
            $total = (int) $cStmt->fetchColumn();

            $stmt = $pdo->prepare(
                "SELECT u.id, u.full_name, u.email, u.phone, u.created_at,
                        COUNT(b.id) AS booking_count,
                        COALESCE(SUM(b.total_price),0) AS total_spent
                 FROM users u
                 LEFT JOIN bookings b ON u.id = b.user_id
                 WHERE u.role = 'customer'
                 GROUP BY u.id ORDER BY u.created_at DESC
                 LIMIT ? OFFSET ?"
            );
            $stmt->execute([$limit, $offset]);
            ResponseHelper::paginated($stmt->fetchAll(), $total, $page, $limit);
            break;

        // ---- LOCATIONS ----
        case 'countries':
            if ($action === 'list') {
                $stmt = $pdo->query("SELECT * FROM countries ORDER BY sort_order, name");
                ResponseHelper::success($stmt->fetchAll());
            } elseif ($action === 'create' || ($action === 'update' && $id)) {
                $fields = ['name','slug','code','description','is_featured','flag_icon','meta_title','meta_description','meta_keywords'];
                $sets = []; $vals = [];
                foreach($fields as $f) {
                    if (array_key_exists($f, $input)) { $sets[] = "`$f` = ?"; $vals[] = $input[$f]; }
                }
                $metaTitle = $clean($input['meta_title'] ?? $input['name'] ?? 'dest');
                $img = handleUpload($_FILES['featured_image'] ?? null, '../../uploads/', $metaTitle);
                if ($img) { $sets[] = "`featured_image` = ?"; $vals[] = $img; }

                if ($action === 'create') {
                    $pdo->prepare("INSERT INTO countries (".implode(',', array_map(fn($f)=>str_replace('`','',$f), $sets)).") VALUES (".implode(',', array_fill(0, count($sets), '?')).")")->execute($vals);
                    ResponseHelper::success(['id' => (int)$pdo->lastInsertId()], 'Created');
                } else {
                    $vals[] = $id;
                    $pdo->prepare("UPDATE countries SET ".implode(',', $sets)." WHERE id = ?")->execute($vals);
                    ResponseHelper::success([], 'Updated');
                }
            } elseif ($action === 'delete' && $id) {
                $pdo->prepare("DELETE FROM countries WHERE id=?")->execute([$id]);
                ResponseHelper::success([], 'Deleted');
            }
            break;

        case 'states':
            if ($action === 'list') {
                $stmt = $pdo->query("SELECT s.*, c.name AS country_name FROM states s LEFT JOIN countries c ON s.country_id = c.id ORDER BY s.sort_order, s.name");
                ResponseHelper::success($stmt->fetchAll());
            } elseif ($action === 'create' || ($action === 'update' && $id)) {
                $fields = ['country_id','name','slug','description','is_featured','meta_title','meta_description','meta_keywords'];
                $sets = []; $vals = [];
                foreach($fields as $f) {
                    if (array_key_exists($f, $input)) { $sets[] = "`$f` = ?"; $vals[] = $input[$f]; }
                }
                $metaTitle = $clean($input['meta_title'] ?? $input['name'] ?? 'dest');
                $img = handleUpload($_FILES['featured_image'] ?? null, '../../uploads/', $metaTitle);
                if ($img) { $sets[] = "`featured_image` = ?"; $vals[] = $img; }

                if ($action === 'create') {
                    $pdo->prepare("INSERT INTO states (".implode(',', array_map(fn($f)=>str_replace('`','',$f), $sets)).") VALUES (".implode(',', array_fill(0, count($sets), '?')).")")->execute($vals);
                    ResponseHelper::success(['id' => (int)$pdo->lastInsertId()], 'Created');
                } else {
                    $vals[] = $id;
                    $pdo->prepare("UPDATE states SET ".implode(',', $sets)." WHERE id = ?")->execute($vals);
                    ResponseHelper::success([], 'Updated');
                }
            } elseif ($action === 'delete' && $id) {
                $pdo->prepare("DELETE FROM states WHERE id=?")->execute([$id]);
                ResponseHelper::success([], 'Deleted');
            }
            break;

        case 'cities':
            if ($action === 'list') {
                $stmt = $pdo->query("SELECT ci.*, s.name AS state_name FROM cities ci LEFT JOIN states s ON ci.state_id = s.id ORDER BY ci.sort_order, ci.name");
                ResponseHelper::success($stmt->fetchAll());
            } elseif ($action === 'create' || ($action === 'update' && $id)) {
                $fields = ['state_id','name','slug','description','is_featured','meta_title','meta_description','meta_keywords'];
                $sets = []; $vals = [];
                foreach($fields as $f) {
                    if (array_key_exists($f, $input)) { $sets[] = "`$f` = ?"; $vals[] = $input[$f]; }
                }
                $metaTitle = $clean($input['meta_title'] ?? $input['name'] ?? 'dest');
                $img = handleUpload($_FILES['featured_image'] ?? null, '../../uploads/', $metaTitle);
                if ($img) { $sets[] = "`featured_image` = ?"; $vals[] = $img; }

                if ($action === 'create') {
                    $pdo->prepare("INSERT INTO cities (".implode(',', array_map(fn($f)=>str_replace('`','',$f), $sets)).") VALUES (".implode(',', array_fill(0, count($sets), '?')).")")->execute($vals);
                    ResponseHelper::success(['id' => (int)$pdo->lastInsertId()], 'Created');
                } else {
                    $vals[] = $id;
                    $pdo->prepare("UPDATE cities SET ".implode(',', $sets)." WHERE id = ?")->execute($vals);
                    ResponseHelper::success([], 'Updated');
                }
            } elseif ($action === 'delete' && $id) {
                $pdo->prepare("DELETE FROM cities WHERE id=?")->execute([$id]);
                ResponseHelper::success([], 'Deleted');
            }
            break;

        case 'places':
            if ($action === 'list') {
                $stmt = $pdo->query("SELECT p.*, ci.name AS city_name FROM places p LEFT JOIN cities ci ON p.city_id = ci.id ORDER BY p.sort_order, p.name");
                ResponseHelper::success($stmt->fetchAll());
            } elseif ($action === 'create' || ($action === 'update' && $id)) {
                $fields = ['city_id','name','slug','description','is_featured','meta_title','meta_description','meta_keywords'];
                $sets = []; $vals = [];
                foreach($fields as $f) {
                    if (array_key_exists($f, $input)) { $sets[] = "`$f` = ?"; $vals[] = $input[$f]; }
                }
                $img = handleUpload($_FILES['featured_image'] ?? null);
                if ($img) { $sets[] = "`featured_image` = ?"; $vals[] = $img; }

                if ($action === 'create') {
                    $pdo->prepare("INSERT INTO places (".implode(',', array_map(fn($f)=>str_replace('`','',$f), $sets)).") VALUES (".implode(',', array_fill(0, count($sets), '?')).")")->execute($vals);
                    ResponseHelper::success(['id' => (int)$pdo->lastInsertId()], 'Created');
                } else {
                    $vals[] = $id;
                    $pdo->prepare("UPDATE places SET ".implode(',', $sets)." WHERE id = ?")->execute($vals);
                    ResponseHelper::success([], 'Updated');
                }
            } elseif ($action === 'delete' && $id) {
                $pdo->prepare("DELETE FROM places WHERE id=?")->execute([$id]);
                ResponseHelper::success([], 'Deleted');
            }
            break;

        // ---- BLOGS ----
        case 'blogs':
            $page   = max(1, (int)($_GET['page'] ?? 1));
            $limit  = 20;
            $offset = ($page - 1) * $limit;

            if ($action === 'list') {
                $cStmt = $pdo->query("SELECT COUNT(*) FROM blogs");
                $total = (int) $cStmt->fetchColumn();
                $stmt  = $pdo->prepare(
                    "SELECT b.*, bc.name AS category_name
                     FROM blogs b LEFT JOIN blog_categories bc ON b.category_id = bc.id
                     ORDER BY b.created_at DESC LIMIT ? OFFSET ?"
                );
                $stmt->execute([$limit, $offset]);
                ResponseHelper::paginated($stmt->fetchAll(), $total, $page, $limit);
            } elseif ($action === 'create' || ($action === 'update' && $id)) {
                $fields = ['category_id','country_id','state_id','city_id','title','slug','excerpt','content','author','tags','status','meta_title','meta_description','meta_keywords'];
                $sets = []; $vals = [];
                foreach ($fields as $f) {
                    if (array_key_exists($f, $input)) { $sets[] = "`$f` = ?"; $vals[] = $input[$f] === '' ? null : $input[$f]; }
                }
                $metaTitle = $clean($input['meta_title'] ?? $input['title'] ?? 'blog');
                $featImg = handleUpload($_FILES['featured_image'] ?? null, '../../uploads/', $metaTitle);
                if ($featImg) { $sets[] = "`featured_image` = ?"; $vals[] = $featImg; }

                if ($action === 'create') {
                    if (empty($sets)) ResponseHelper::error('Missing data');
                    $pdo->prepare("INSERT INTO blogs (".implode(',', array_map(fn($f)=>str_replace('`','',$f), $sets)).") VALUES (".implode(',', array_fill(0, count($sets), '?')).")")->execute($vals);
                    ResponseHelper::success(['id' => (int)$pdo->lastInsertId()], 'Blog created');
                } else {
                    if (!empty($sets)) {
                        $vals[] = $id;
                        $pdo->prepare("UPDATE blogs SET " . implode(',', $sets) . " WHERE id=?")->execute($vals);
                    }
                    ResponseHelper::success([], 'Blog updated');
                }
            } elseif ($action === 'delete' && $id) {
                $pdo->prepare("DELETE FROM blogs WHERE id=?")->execute([$id]);
                ResponseHelper::success([], 'Deleted');
            }
            break;

        // ---- TRIP GALLERY ----
        case 'trip_gallery':
            if ($action === 'delete' && $id) {
                // Get file path to delete physically
                $stmt = $pdo->prepare("SELECT image_url FROM trip_gallery WHERE id = ?");
                $stmt->execute([$id]);
                $img = $stmt->fetchColumn();
                if ($img) {
                    $filename = basename($img);
                    $path = __DIR__ . '/../../uploads/' . $filename;
                    if (file_exists($path)) unlink($path);
                }
                $pdo->prepare("DELETE FROM trip_gallery WHERE id=?")->execute([$id]);
                ResponseHelper::success([], 'Gallery image removed');
            }
            break;

        // ---- USERS / ADMINS ----
        case 'users':
            if ($action === 'list') {
                $stmt = $pdo->query("SELECT id, full_name, email, phone, role, is_active, created_at FROM users ORDER BY created_at DESC");
                ResponseHelper::success($stmt->fetchAll());
            } elseif ($action === 'create') {
                $pw = $input['password'] ?? '';
                if (!$pw) ResponseHelper::error('Password required');
                $hash = password_hash($pw, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (full_name,email,phone,password,role,is_active) VALUES (?,?,?,?,?,?)");
                $stmt->execute([$clean($input['full_name']??''), $clean($input['email']??''), $clean($input['phone']??''), $hash, $input['role']??'customer', (int)($input['is_active']??1)]);
                ResponseHelper::success(['id'=>(int)$pdo->lastInsertId()], 'User created');
            } elseif ($action === 'update' && $id) {
                $sets=[]; $vals=[];
                foreach(['full_name','email','phone','role','is_active'] as $f) {
                    if(array_key_exists($f,$input)){$sets[]="`$f`=?"; $vals[]=$f==='role'?$input[$f]:($f==='is_active'?(int)$input[$f]:$clean($input[$f]));}
                }
                if(!empty($input['password'])){$sets[]='`password`=?'; $vals[]=password_hash($input['password'], PASSWORD_DEFAULT);}
                if(!empty($sets)){$vals[]=$id; $pdo->prepare("UPDATE users SET ".implode(',', $sets)." WHERE id=?")->execute($vals);}
                ResponseHelper::success([], 'User updated');
            } elseif ($action === 'delete' && $id) {
                $pdo->prepare("DELETE FROM users WHERE id=? AND role != 'admin'")->execute([$id]);
                ResponseHelper::success([], 'Deleted');
            }
            break;

        // ---- CONTACT MESSAGES ----
        case 'messages':
            $page   = max(1, (int)($_GET['page'] ?? 1));
            $limit  = 20;
            $offset = ($page - 1) * $limit;

            if ($action === 'list') {
                $cStmt = $pdo->query("SELECT COUNT(*) FROM contact_messages");
                $total = (int) $cStmt->fetchColumn();
                $stmt  = $pdo->prepare("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT ? OFFSET ?");
                $stmt->execute([$limit, $offset]);
                ResponseHelper::paginated($stmt->fetchAll(), $total, $page, $limit);
            } elseif ($action === 'mark-read' && $id) {
                $pdo->prepare("UPDATE contact_messages SET is_read=1 WHERE id=?")->execute([$id]);
                ResponseHelper::success([], 'Marked as read');
            }
            break;

        default:
            ResponseHelper::error('Invalid resource', 400);
    }
} catch (PDOException $e) {
    ResponseHelper::error(
        APP_ENV === 'production' ? 'Server error' : $e->getMessage(), 500
    );
}
