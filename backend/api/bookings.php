<?php
// backend/api/bookings.php — Booking API
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

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$input  = [];
if ($method === 'POST') {
    $raw   = file_get_contents('php://input');
    $input = json_decode($raw, true) ?: $_POST;
}

$clean = fn($v) => htmlspecialchars(strip_tags(trim($v ?? '')), ENT_QUOTES, 'UTF-8');

function generateBookingRef(PDO $pdo): string
{
    do {
        $ref = 'IBCC-' . date('Y') . '-' . str_pad(random_int(1, 99999), 5, '0', STR_PAD_LEFT);
        $chk = $pdo->prepare("SELECT id FROM bookings WHERE booking_ref = ?");
        $chk->execute([$ref]);
    } while ($chk->fetch());
    return $ref;
}

function generateInvoiceNumber(PDO $pdo): string
{
    do {
        $inv = 'INV-' . date('Y') . '-' . str_pad(random_int(1, 99999), 4, '0', STR_PAD_LEFT);
        $chk = $pdo->prepare("SELECT id FROM payments WHERE invoice_number = ?");
        $chk->execute([$inv]);
    } while ($chk->fetch());
    return $inv;
}

try {
    switch ($action) {

        // POST ?action=create
        case 'create':
            if ($method !== 'POST') ResponseHelper::error('Method not allowed', 405);

            $tripId    = (int)    ($input['trip_id']    ?? 0);
            $startDate = $clean(   $input['start_date'] ?? '');
            $numDays   = (int)    ($input['num_days']   ?? 1);
            $numMembers= max(1,(int)($input['num_members'] ?? 1));
            $fullName  = $clean(   $input['full_name']  ?? '');
            $email     = filter_var(trim($input['email'] ?? ''), FILTER_VALIDATE_EMAIL);
            $phone     = $clean(   $input['phone']      ?? '');
            $notes     = $clean(   $input['special_notes'] ?? '');

            // Validation
            if (!$tripId)    ResponseHelper::error('Trip is required');
            if (!$startDate) ResponseHelper::error('Start date is required');
            if (!$fullName)  ResponseHelper::error('Full name is required');
            if (!$email)     ResponseHelper::error('Valid email is required');
            if (!$phone)     ResponseHelper::error('Phone number is required');

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate)) {
                ResponseHelper::error('Invalid date format. Use YYYY-MM-DD');
            }

            // Fetch trip price
            $tripStmt = $pdo->prepare("SELECT id, base_price, discounted_price, duration_days, title FROM trips WHERE id = ? AND is_active = 1");
            $tripStmt->execute([$tripId]);
            $trip = $tripStmt->fetch();
            if (!$trip) ResponseHelper::error('Trip not found or inactive', 404);

            $pricePerPerson = $trip['discounted_price'] ?? $trip['base_price'];
            $totalPrice     = round($pricePerPerson * $numMembers, 2);
            $endDate        = date('Y-m-d', strtotime($startDate . " +{$numDays} days"));

            $bookingRef  = generateBookingRef($pdo);
            $invoiceNum  = generateInvoiceNumber($pdo);

            $userId = AuthMiddleware::optionalAuth()['id'] ?? null;

            // Insert booking
            $bStmt = $pdo->prepare(
                "INSERT INTO bookings (booking_ref, user_id, trip_id, start_date, end_date, duration_days, num_members,
                                       full_name, email, phone, special_notes, status, total_price, currency_code, base_price)
                 VALUES (?,?,?,?,?,?,?,?,?,?,?,'Pending',?,'INR',?)"
            );
            $bStmt->execute([
                $bookingRef, $userId, $tripId, $startDate, $endDate, $numDays, $numMembers,
                $fullName, $email, $phone, $notes, $totalPrice, $totalPrice
            ]);
            $bookingId = (int) $pdo->lastInsertId();

            // Insert payment
            $pStmt = $pdo->prepare(
                "INSERT INTO payments (booking_id, invoice_number, amount, currency_code, status)
                 VALUES (?,?,?,'INR','Pending')"
            );
            $pStmt->execute([$bookingId, $invoiceNum, $totalPrice]);

            ResponseHelper::success([
                'booking_id'     => $bookingId,
                'booking_ref'    => $bookingRef,
                'invoice_number' => $invoiceNum,
                'total_price'    => $totalPrice,
                'trip_title'     => $trip['title'],
                'start_date'     => $startDate,
                'end_date'       => $endDate,
                'num_members'    => $numMembers,
            ], 'Booking created successfully!');
            break;

        // GET ?action=my-bookings
        case 'my-bookings':
            $user = AuthMiddleware::requireAuth();

            $stmt = $pdo->prepare(
                "SELECT b.*, t.title AS trip_title, t.cover_image, t.slug AS trip_slug,
                        p.invoice_number, p.status AS payment_status
                 FROM bookings b
                 LEFT JOIN trips t    ON b.trip_id = t.id
                 LEFT JOIN payments p ON b.id = p.booking_id
                 WHERE b.user_id = ?
                 ORDER BY b.created_at DESC"
            );
            $stmt->execute([$user['id']]);
            ResponseHelper::success($stmt->fetchAll());
            break;

        // GET ?action=invoice&id=
        case 'invoice':
            $user      = AuthMiddleware::requireAuth();
            $bookingId = (int)($_GET['id'] ?? 0);

            $stmt = $pdo->prepare(
                "SELECT b.*, t.title AS trip_title, t.slug AS trip_slug, t.cover_image,
                        t.description AS trip_description,
                        p.invoice_number, p.amount, p.status AS payment_status, p.created_at AS invoice_date
                 FROM bookings b
                 LEFT JOIN trips t    ON b.trip_id = t.id
                 LEFT JOIN payments p ON b.id = p.booking_id
                 WHERE b.id = ? AND (b.user_id = ? OR ? = 'admin')"
            );
            $stmt->execute([$bookingId, $user['id'], $user['role']]);
            $booking = $stmt->fetch();
            if (!$booking) ResponseHelper::error('Booking not found', 404);

            // Company info
            $booking['company'] = [
                'name'    => 'IBCC Trip',
                'address' => '123, Travel Hub, Connaught Place, New Delhi - 110001',
                'phone'   => '+91 98765 43210',
                'email'   => 'info@ibcctrip.com',
                'gst'     => 'GST07AAADI1234A1Z5',
            ];

            ResponseHelper::success($booking);
            break;

        // POST ?action=cancel&id=
        case 'cancel':
            $user      = AuthMiddleware::requireAuth();
            $bookingId = (int)($_GET['id'] ?? ($input['booking_id'] ?? 0));

            $stmt = $pdo->prepare("SELECT id, status FROM bookings WHERE id = ? AND user_id = ?");
            $stmt->execute([$bookingId, $user['id']]);
            $booking = $stmt->fetch();
            if (!$booking) ResponseHelper::error('Booking not found', 404);

            if (in_array($booking['status'], ['Completed', 'Cancelled'])) {
                ResponseHelper::error('Booking cannot be cancelled in its current status');
            }

            $pdo->prepare("UPDATE bookings SET status = 'Cancelled' WHERE id = ?")->execute([$bookingId]);
            ResponseHelper::success([], 'Booking cancelled');
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
