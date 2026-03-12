<?php
// backend/api/contact.php — Contact Form API
// ============================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/ResponseHelper.php';
require_once __DIR__ . '/../middleware/CorsMiddleware.php';

CorsMiddleware::handle();
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ResponseHelper::error('Method not allowed', 405);
}

$raw   = file_get_contents('php://input');
$input = json_decode($raw, true) ?: $_POST;
$clean = fn($v) => htmlspecialchars(strip_tags(trim($v ?? '')), ENT_QUOTES, 'UTF-8');

$name    = $clean($input['name']    ?? '');
$email   = filter_var(trim($input['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$phone   = $clean($input['phone']   ?? '');
$subject = $clean($input['subject'] ?? 'General Inquiry');
$message = $clean($input['message'] ?? '');

if (!$name)    ResponseHelper::error('Name is required');
if (!$email)   ResponseHelper::error('Valid email is required');
if (!$message) ResponseHelper::error('Message is required');

try {
    $stmt = $pdo->prepare(
        "INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?,?,?,?,?)"
    );
    $stmt->execute([$name, $email, $phone, $subject, $message]);

    ResponseHelper::success([], 'Thank you! We will get back to you within 24 hours.');
} catch (PDOException $e) {
    ResponseHelper::error(
        APP_ENV === 'production' ? 'Server error' : $e->getMessage(), 500
    );
}
