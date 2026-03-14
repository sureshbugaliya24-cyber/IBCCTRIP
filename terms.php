<?php
// frontend/terms.php — Terms & Conditions
require_once __DIR__ . '/components/config.php';
require_once __DIR__ . '/components/helpers.php';
require_once __DIR__ . '/components/breadcrumb.php';

$pageTitle = 'Terms & Conditions';
$activePage = 'terms';
require_once __DIR__ . '/layouts/head.php';
require_once __DIR__ . '/layouts/header.php';
?>

<div class="bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4">
        <?php renderBreadcrumb([
            ['Home', FRONTEND_URL . '/'],
            ['Terms & Conditions', '']
        ]); ?>
        
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 md:p-12 mt-8">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-8 uppercase tracking-tight">Terms & Conditions</h1>
            
            <div class="prose max-w-none text-gray-600 space-y-6 leading-relaxed">
                <p>By using <strong><?= APP_NAME ?></strong>, you agree to the following terms and conditions. Please read them carefully before making a booking.</p>
                
                <h3 class="text-xl font-bold text-gray-900 uppercase tracking-wide">1. Booking & Payments</h3>
                <p>All bookings are subject to availability. A booking is only confirmed once the full payment or the agreed deposit is received. We accept various payment methods including credit cards, debit cards, and UPI via our secure payment gateway.</p>
                
                <h3 class="text-xl font-bold text-gray-900 uppercase tracking-wide">2. Travel Documents</h3>
                <p>It is the responsibility of the traveler to ensure they have valid passports, visas, and health certificates required for their journey. <?= APP_NAME ?> is not liable for any issues arising from missing or invalid documentation.</p>
                
                <h3 class="text-xl font-bold text-gray-900 uppercase tracking-wide">3. Liability</h3>
                <p>We act as an intermediary between you and the service providers (airlines, hotels, local guides). While we only work with trusted partners, we are not responsible for delays, accidents, or losses caused by third-party providers.</p>
                
                <h3 class="text-xl font-bold text-gray-900 uppercase tracking-wide">4. Conduct</h3>
                <p>Travelers are expected to follow local laws and respect the culture of the destinations they visit. Any illegal activity or significant disruption to other travelers may lead to immediate cancellation of the trip without refund.</p>
                
                <h3 class="text-xl font-bold text-gray-900 uppercase tracking-wide">5. Governing Law</h3>
                <p>These terms are governed by the laws of India. Any disputes will be subject to the exclusive jurisdiction of the courts in Jaipur, Rajasthan.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
</body></html>
