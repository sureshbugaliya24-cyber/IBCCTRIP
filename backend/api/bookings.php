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

function createRazorpayOrder(float $amount, string $receipt): ?string
{
    if (!defined('PAYMENT_RAZORPAY_KEY') || !defined('PAYMENT_RAZORPAY_SECRET')) return null;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, PAYMENT_RAZORPAY_KEY . ':' . PAYMENT_RAZORPAY_SECRET);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'amount'   => (int)($amount * 100), // In paise
        'currency' => 'INR',
        'receipt'  => $receipt,
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $res = curl_exec($ch);
    $data = json_decode($res, true);
    curl_close($ch);
    
    return $data['id'] ?? null;
}

function verifyRazorpaySignature(string $orderId, string $paymentId, string $signature): bool
{
    if (!defined('PAYMENT_RAZORPAY_SECRET')) return false;
    $expected = hash_hmac('sha256', $orderId . '|' . $paymentId, PAYMENT_RAZORPAY_SECRET);
    return hash_equals($expected, $signature);
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
            $fullName  = $clean(   $input['full_name']  ?? $_SESSION['user_name'] ?? '');
            $email     = filter_var(trim($input['email'] ?? $_SESSION['user_email'] ?? ''), FILTER_VALIDATE_EMAIL);
            
            // For phone, we might need a DB lookup if not in session, but let's try input first
            $phone     = $clean(   $input['phone']      ?? '');
            if (!$phone && !empty($_SESSION['user_id'])) {
                $uStmt = $pdo->prepare("SELECT phone FROM users WHERE id = ?");
                $uStmt->execute([$_SESSION['user_id']]);
                $phone = $uStmt->fetchColumn() ?: '';
            }

            $notes     = $clean(   $input['special_requests'] ?? $input['special_notes'] ?? '');

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
            $tripStmt = $pdo->prepare("SELECT * FROM trips WHERE id = ? AND is_active = 1");
            $tripStmt->execute([$tripId]);
            $trip = $tripStmt->fetch();
            if (!$trip) ResponseHelper::error('Trip not found or inactive', 404);

            // Create snapshot of trip details
            $tripSnapshot = [
                'title'            => $trip['title'],
                'slug'             => $trip['slug'],
                'cover_image'      => $trip['cover_image'],
                'duration_days'    => $trip['duration_days'],
                'base_price'       => $trip['base_price'],
                'discounted_price' => $trip['discounted_price'],
                'trip_type'        => $trip['trip_type'],
                'difficulty'       => $trip['difficulty']
            ];
            $tripSnapshotJson = json_encode($tripSnapshot);

            $pricePerPerson = $trip['discounted_price'] ?? $trip['base_price'];
            $totalPrice     = round($pricePerPerson * $numMembers, 2);
            $endDate        = date('Y-m-d', strtotime($startDate . " +{$numDays} days"));

            $bookingRef  = generateBookingRef($pdo);
            $invoiceNum  = generateInvoiceNumber($pdo);

            $userId = AuthMiddleware::optionalAuth()['id'] ?? null;
            session_write_close(); // Prevent session locking for other requests

            try {
                $pdo->beginTransaction();

                // 1. Insert booking
                $bStmt = $pdo->prepare(
                    "INSERT INTO bookings (booking_ref, user_id, trip_id, start_date, end_date, duration_days, num_members,
                                           full_name, email, phone, special_notes, status, total_price, currency_code, base_price, trip_details)
                     VALUES (?,?,?,?,?,?,?,?,?,?,?,'Pending',?,'INR',?,?)"
                );
                $bStmt->execute([
                    $bookingRef, $userId, $tripId, $startDate, $endDate, $numDays, $numMembers,
                    $fullName, $email, $phone, $notes, $totalPrice, $totalPrice, $tripSnapshotJson
                ]);
                $bookingId = (int) $pdo->lastInsertId();

                $methodName = $clean($input['payment_method'] ?? 'COD');
                
                // Validate if method is enabled
                if ($methodName === 'Razorpay' && (!defined('PAYMENT_RAZORPAY_ENABLED') || PAYMENT_RAZORPAY_ENABLED != '1')) {
                    ResponseHelper::error('Razorpay is currently unavailable');
                }
                if ($methodName === 'COD' && (!defined('PAYMENT_COD_ENABLED') || PAYMENT_COD_ENABLED != '1')) {
                    ResponseHelper::error('COD is currently unavailable');
                }

                $rzpOrderId = null;
                if ($methodName === 'Razorpay') {
                    $rzpOrderId = createRazorpayOrder($totalPrice, $bookingRef);
                    if (!$rzpOrderId) ResponseHelper::error('Failed to initialize Razorpay payment');
                }

                // 2. Insert payment record
                $pStmt = $pdo->prepare(
                    "INSERT INTO payments (booking_id, invoice_number, amount, currency_code, status, payment_method, razorpay_order_id)
                     VALUES (?,?,?,?,'Pending',?,?)"
                );
                $pStmt->execute([$bookingId, $invoiceNum, $totalPrice, 'INR', $methodName, $rzpOrderId]);

                $pdo->commit();

                ResponseHelper::success([
                    'booking_id'     => $bookingId,
                    'booking_ref'    => $bookingRef,
                    'invoice_number' => $invoiceNum,
                    'total_price'    => $totalPrice,
                    'trip_title'     => $trip['title'],
                    'start_date'     => $startDate,
                    'end_date'       => $endDate,
                    'num_members'    => $numMembers,
                    'payment_method' => $methodName,
                    'razorpay_order_id' => $rzpOrderId,
                    'razorpay_key'      => PAYMENT_RAZORPAY_KEY ?? null
                ], 'Booking created successfully!');

            } catch (Exception $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                
                // If it's a unique constraint violation (SQLSTATE 23000), it's likely a collision
                if ($e instanceof PDOException && $e->getCode() == '23000') {
                    ResponseHelper::error('A temporary system error occurred. Please try again in a moment.', 409);
                } else {
                    throw $e; // Re-throw for outer handler
                }
            }
            break;

        case 'verify-payment':
            $rzpPaymentId = $clean($input['razorpay_payment_id'] ?? '');
            $rzpOrderId   = $clean($input['razorpay_order_id'] ?? '');
            $rzpSignature = $clean($input['razorpay_signature'] ?? '');
            
            if (!$rzpPaymentId || !$rzpOrderId || !$rzpSignature) {
                ResponseHelper::error('Missing payment verification data');
            }
            
            if (!verifyRazorpaySignature($rzpOrderId, $rzpPaymentId, $rzpSignature)) {
                ResponseHelper::error('Invalid payment signature');
            }
            
            // Signature is valid, update booking and payment
            try {
                $pdo->beginTransaction();
                
                // Find payment record by order id
                $pStmt = $pdo->prepare("SELECT id, booking_id FROM payments WHERE razorpay_order_id = ?");
                $pStmt->execute([$rzpOrderId]);
                $payment = $pStmt->fetch();
                
                if (!$payment) {
                    $pdo->rollBack();
                    ResponseHelper::error('Payment record not found for this order');
                }
                
                // Update payment
                $upStmt = $pdo->prepare("UPDATE payments SET status = 'Paid', razorpay_payment_id = ?, razorpay_signature = ? WHERE id = ?");
                $upStmt->execute([$rzpPaymentId, $rzpSignature, $payment['id']]);
                
                // Update booking
                $ubStmt = $pdo->prepare("UPDATE bookings SET status = 'Confirmed' WHERE id = ?");
                $ubStmt->execute([$payment['booking_id']]);
                
                $pdo->commit();
                ResponseHelper::success([], 'Payment verified and booking confirmed!');
                
            } catch (Exception $e) {
                if ($pdo->inTransaction()) $pdo->rollBack();
                ResponseHelper::error($e->getMessage());
            }
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
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Handle fallbacks for deleted trips
            foreach ($results as &$row) {
                if ($row['trip_id'] === null) {
                    $snap = !empty($row['trip_details']) ? json_decode($row['trip_details'], true) : [];
                    $row['trip_title']  = $snap['title'] ?? 'Trip (Deleted)';
                    $row['trip_slug']   = $snap['slug'] ?? '';
                    $row['cover_image'] = $snap['cover_image'] ?? '';
                }
            }

            ResponseHelper::success($results);
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
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$booking) ResponseHelper::error('Booking not found', 404);

            // Handle fallbacks for deleted trips
            if ($booking['trip_id'] === null) {
                $snap = !empty($booking['trip_details']) ? json_decode($booking['trip_details'], true) : [];
                $booking['trip_title']  = $snap['title'] ?? 'Trip (Deleted)';
                $booking['trip_slug']   = $snap['slug'] ?? '';
                $booking['cover_image'] = $snap['cover_image'] ?? '';
                $booking['trip_description'] = $snap['description'] ?? 'This trip has been removed from our listings.';
            }

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
