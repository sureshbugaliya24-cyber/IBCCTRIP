<?php
// frontend/components/helpers.php
// ─────────────────────────────────────────────────────────────
// Shared PHP helper functions used across all pages
// ─────────────────────────────────────────────────────────────

if (!defined('API_URL')) {
    require_once __DIR__ . '/config.php';
}

/**
 * Make a GET request to the backend REST API and return decoded JSON.
 * Returns the 'data' field or an empty array on failure.
 */
function apiGet(string $endpoint, array $params = []): array {
    $url = API_URL . '/' . ltrim($endpoint, '/');
    if ($params) $url .= '?' . http_build_query($params);
    $ctx = stream_context_create(['http' => [
        'method'  => 'GET',
        'header'  => "Accept: application/json\r\n",
        'timeout' => 8,
    ]]);
    $raw = @file_get_contents($url, false, $ctx);
    if (!$raw) return [];
    $json = json_decode($raw, true);
    return $json['data'] ?? [];
}

/**
 * Same as apiGet but returns the full response (for pagination meta).
 */
function apiGetFull(string $endpoint, array $params = []): array {
    $url = API_URL . '/' . ltrim($endpoint, '/');
    if ($params) $url .= '?' . http_build_query($params);
    $ctx = stream_context_create(['http' => [
        'method'  => 'GET',
        'header'  => "Accept: application/json\r\n",
        'timeout' => 8,
    ]]);
    $raw = @file_get_contents($url, false, $ctx);
    if (!$raw) return [];
    return json_decode($raw, true) ?? [];
}

/**
 * Format a number as Indian Rupee (₹1,23,456)
 */
function formatPrice(float $amount): string {
    return '₹' . number_format($amount, 0);
}

/**
 * Format a date string to "15 Mar 2026"
 */
function formatDate(string $dateStr): string {
    if (!$dateStr) return '—';
    return date('d M Y', strtotime($dateStr));
}

/**
 * Sanitize output for HTML
 */
function e(string $val): string {
    return htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
}

/**
 * Return a status badge HTML
 */
function statusBadge(string $status): string {
    $colors = [
        'Pending'     => 'bg-yellow-100 text-yellow-800',
        'Scheduled'   => 'bg-blue-100 text-blue-800',
        'In Progress' => 'bg-orange-100 text-orange-800',
        'Completed'   => 'bg-green-100 text-green-800',
        'Cancelled'   => 'bg-red-100 text-red-800',
        'Published'   => 'bg-green-100 text-green-700',
        'Draft'       => 'bg-gray-100 text-gray-600',
    ];
    $cls = $colors[$status] ?? 'bg-gray-100 text-gray-600';
    return '<span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold ' . $cls . '">' . e($status) . '</span>';
}

/**
 * Check if user is logged in (server-side session bridge)
 * The frontend uses JS sessions; this is a lightweight check via cookie.
 */
function getSessionUser(): array {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_name(FE_SESSION_NAME);
        session_start();
    }
    return $_SESSION['user'] ?? [];
}

/**
 * Redirect helper
 */
function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

/**
 * Get a query param (GET) sanitized
 */
function qParam(string $key, mixed $default = ''): string {
    return htmlspecialchars($_GET[$key] ?? $default, ENT_QUOTES, 'UTF-8');
}

/**
 * Active page class helper for navbar
 */
function activeClass(string $page, string $current): string {
    return $page === $current ? 'text-secondary' : 'hover:text-secondary transition-colors';
}
