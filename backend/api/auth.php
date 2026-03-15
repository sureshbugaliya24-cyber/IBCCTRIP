<?php
// backend/api/auth.php — Authentication API
// ============================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/ResponseHelper.php';
require_once __DIR__ . '/../middleware/CorsMiddleware.php';

CorsMiddleware::handle();
header('Content-Type: application/json; charset=UTF-8');

session_name(SESSION_NAME);
session_start();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// Read request body
$input = [];
if ($method === 'POST') {
    $raw = file_get_contents('php://input');
    $input = json_decode($raw, true) ?: $_POST;
}

// Sanitize helper (local)
$clean = fn($v) => htmlspecialchars(strip_tags(trim($v ?? '')), ENT_QUOTES, 'UTF-8');

try {
    switch ($action) {

        // POST ?action=register
        case 'register':
            if ($method !== 'POST') ResponseHelper::error('Method not allowed', 405);

            $name  = $clean($input['full_name'] ?? '');
            $email = filter_var(trim($input['email'] ?? ''), FILTER_VALIDATE_EMAIL);
            $pass  = $input['password'] ?? '';
            $phone = $clean($input['phone'] ?? '');

            if (!$name)  ResponseHelper::error('Full name is required');
            if (!$email) ResponseHelper::error('Valid email is required');
            if (strlen($pass) < 6) ResponseHelper::error('Password must be at least 6 characters');

            // Check duplicate
            $chk = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $chk->execute([$email]);
            if ($chk->fetch()) ResponseHelper::error('Email already registered', 409);

            $hash = password_hash($pass, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare(
                "INSERT INTO users (full_name, email, phone, password, role) VALUES (?,?,?,?,'customer')"
            );
            $stmt->execute([$name, $email, $phone, $hash]);
            $userId = (int) $pdo->lastInsertId();

            $_SESSION['user_id']   = $userId;
            $_SESSION['user_role'] = 'customer';
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;

            ResponseHelper::success(['user_id' => $userId, 'name' => $name, 'email' => $email, 'role' => 'customer'], 'Registration successful');
            break;

        // POST ?action=login
        case 'login':
            if ($method !== 'POST') ResponseHelper::error('Method not allowed', 405);

            $email = filter_var(trim($input['email'] ?? ''), FILTER_VALIDATE_EMAIL);
            $pass  = $input['password'] ?? '';

            if (!$email || !$pass) ResponseHelper::error('Email and password are required');

            $stmt = $pdo->prepare("SELECT id, full_name, email, phone, password, role, is_active FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($pass, $user['password'])) {
                ResponseHelper::error('Invalid email or password', 401);
            }

            if (!$user['is_active']) {
                ResponseHelper::error('Account is deactivated. Please contact support.', 403);
            }

                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_phone'] = $user['phone'] ?? '';

                ResponseHelper::success([
                    'user_id' => $user['id'],
                    'name'    => $user['full_name'],
                    'email'   => $user['email'],
                    'phone'   => $user['phone'] ?? '',
                    'role'    => $user['role'],
                ], 'Login successful');
            break;

        // GET ?action=logout
        case 'logout':
            $_SESSION = [];
            session_destroy();
            ResponseHelper::success([], 'Logged out successfully');
            break;

        // GET ?action=session
        case 'session':
            $active_payments = [];
            if (defined('PAYMENT_RAZORPAY_ENABLED') && PAYMENT_RAZORPAY_ENABLED == '1' && defined('PAYMENT_RAZORPAY_KEY') && !empty(PAYMENT_RAZORPAY_KEY)) {
                $active_payments[] = 'Razorpay';
            }
            if (defined('PAYMENT_COD_ENABLED') && PAYMENT_COD_ENABLED == '1') {
                $active_payments[] = 'COD';
            }

            if (!empty($_SESSION['user_id'])) {
                // Ensure session contains fresh DB data
                $stmtAuth = $pdo->prepare("SELECT full_name, email, phone, role FROM users WHERE id = ?");
                $stmtAuth->execute([$_SESSION['user_id']]);
                if ($fresh = $stmtAuth->fetch(PDO::FETCH_ASSOC)) {
                    $_SESSION['user_name']  = $fresh['full_name'];
                    $_SESSION['user_email'] = $fresh['email'];
                    $_SESSION['user_phone'] = $fresh['phone'] ?? '';
                    $_SESSION['user_role']  = $fresh['role'];
                }

                ResponseHelper::success([
                        'logged_in' => true,
                        'user_id'   => $_SESSION['user_id'],
                        'name'      => $_SESSION['user_name'],
                        'email'     => $_SESSION['user_email'] ?? '',
                        'phone'     => $_SESSION['user_phone'] ?? '',
                        'role'      => $_SESSION['user_role'],
                        'active_payments' => $active_payments
                    ]);
            } else {
                ResponseHelper::success([
                    'logged_in' => false,
                    'active_payments' => $active_payments
                ]);
            }
            break;

        // PUT ?action=profile  (requires auth)
        case 'profile':
            if (empty($_SESSION['user_id'])) ResponseHelper::error('Authentication required', 401);
            if ($method !== 'POST' && $method !== 'PUT') ResponseHelper::error('Method not allowed', 405);

            // Support both 'name' and 'full_name' from frontend
            $name  = $clean($input['full_name'] ?? $input['name'] ?? '');
            $phone = $clean($input['phone'] ?? '');

            if (!$name) ResponseHelper::error('Full name is required');

            $data = ['full_name' => $name, 'phone' => $phone];

            if (!empty($input['password'])) {
                if (strlen($input['password']) < 6) ResponseHelper::error('Password must be at least 6 characters');
                $data['password'] = password_hash($input['password'], PASSWORD_BCRYPT);
            }

            $sets = implode(', ', array_map(fn($c) => "`$c` = :$c", array_keys($data)));
            $stmt = $pdo->prepare("UPDATE users SET $sets WHERE id = :__id");
            $data['__id'] = $_SESSION['user_id'];
            $stmt->execute($data);

            $_SESSION['user_name']  = $name;
            $_SESSION['user_phone'] = $phone;

            ResponseHelper::success(['name' => $name], 'Profile updated');
            break;

        // GET ?action=me  — full profile data
        case 'me':
            if (empty($_SESSION['user_id'])) ResponseHelper::error('Authentication required', 401);

            $stmt = $pdo->prepare("SELECT id, full_name, email, phone, role, created_at FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
            if (!$user) ResponseHelper::error('User not found', 404);

            ResponseHelper::success($user);
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
