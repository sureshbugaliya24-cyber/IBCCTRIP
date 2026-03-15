<?php
// frontend/refund-policy.php — Refund Policy
require_once __DIR__ . '/components/config.php';
require_once __DIR__ . '/components/helpers.php';
require_once __DIR__ . '/components/breadcrumb.php';

$pageTitle = 'Refund Policy';
$activePage = 'refund';
require_once __DIR__ . '/layouts/head.php';
require_once __DIR__ . '/layouts/header.php';
?>

<div class="bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4">
        <?php renderBreadcrumb([
            ['Home', FRONTEND_URL . '/'],
            ['Refund Policy', '']
        ], 'light'); ?>
        
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 md:p-12 mt-8">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-8 uppercase tracking-tight">Refund & Cancellation</h1>
            
            <div class="prose max-w-none text-gray-600 space-y-6 leading-relaxed">
                <p>We understand that plans can change. Below is our standard refund and cancellation policy for bookings made through <strong><?= APP_NAME ?></strong>.</p>
                
                <h3 class="text-xl font-bold text-gray-900 uppercase tracking-wide">1. Cancellation by the Traveler</h3>
                <p>If you wish to cancel your booking, the following charges will apply based on when the cancellation request is received:</p>
                <ul class="list-disc ml-6 space-y-2">
                    <li><strong>30+ days before departure:</strong> 90% refund of the total trip cost.</li>
                    <li><strong>15–29 days before departure:</strong> 50% refund of the total trip cost.</li>
                    <li><strong>7–14 days before departure:</strong> 25% refund of the total trip cost.</li>
                    <li><strong>Less than 7 days before departure:</strong> No refund possible.</li>
                </ul>
                
                <h3 class="text-xl font-bold text-gray-900 uppercase tracking-wide">2. Cancellation by IBCC Trip</h3>
                <p>In the rare event that we must cancel a trip (e.g., due to extreme weather, safety concerns, or insufficient group size), you will be offered a full refund or an alternative trip of equal value. We are not responsible for any personal expenses incurred (e.g., non-refundable flights purchased separately).</p>
                
                <h3 class="text-xl font-bold text-gray-900 uppercase tracking-wide">3. Partial Refunds</h3>
                <p>No refunds will be provided for unused services during the trip (e.g., missed meals, unused activities, or early checkout from hotels) once the journey has commenced.</p>
                
                <h3 class="text-xl font-bold text-gray-900 uppercase tracking-wide">4. Process for Refunds</h3>
                <p>Refund requests must be submitted in writing via email to <strong><?= e(CONTACT_EMAIL) ?></strong>. Approved refunds will be processed within 7–10 working days back to the original payment source.</p>
                
                <h3 class="text-xl font-bold text-gray-900 uppercase tracking-wide">5. Postponement</h3>
                <p>If you wish to postpone your trip instead of cancelling, please contact us at least 21 days in advance. We will do our best to accommodate your new dates, subject to price changes and availability from our partners.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
</body></html>
