<?php
// backend/middleware/AuthMiddleware.php
// ============================================================
// Authentication & Authorization Guards
// ============================================================

require_once __DIR__ . '/../helpers/ResponseHelper.php';

class AuthMiddleware
{
    public static function requireAuth(): array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            ResponseHelper::error('Authentication required. Please login.', 401);
        }

        return [
            'id'   => $_SESSION['user_id'],
            'role' => $_SESSION['user_role'] ?? 'customer',
            'name' => $_SESSION['user_name'] ?? '',
        ];
    }

    public static function requireAdmin(): array
    {
        $user = self::requireAuth();

        if ($user['role'] !== 'admin') {
            ResponseHelper::error('Admin access required.', 403);
        }

        return $user;
    }

    public static function optionalAuth(): ?array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            return null;
        }

        return [
            'id'   => $_SESSION['user_id'],
            'role' => $_SESSION['user_role'] ?? 'customer',
            'name' => $_SESSION['user_name'] ?? '',
        ];
    }
}
