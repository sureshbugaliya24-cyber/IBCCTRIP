<?php
// frontend/privacy.php — Privacy Policy
require_once __DIR__ . '/components/config.php';
require_once __DIR__ . '/components/helpers.php';
require_once __DIR__ . '/components/breadcrumb.php';

$pageTitle = 'Privacy Policy';
$activePage = 'privacy';
require_once __DIR__ . '/layouts/head.php';
require_once __DIR__ . '/layouts/header.php';
?>

<div class="bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4">
        <?php renderBreadcrumb([
            ['Home', FRONTEND_URL . '/'],
            ['Privacy Policy', '']
        ]); ?>
        
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 md:p-12 mt-8">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-8 uppercase tracking-tight">Privacy Policy</h1>
            
            <div class="prose max-w-none text-gray-600 space-y-6 leading-relaxed">
                <p>Welcome to <strong><?= APP_NAME ?></strong>. Your privacy is critically important to us.</p>
                
                <h3 class="text-xl font-bold text-gray-900 uppercase tracking-wide">1. Information We Collect</h3>
                <p>We collect information you provide directly to us when you book a trip, create an account, or contact our support team. This may include your name, email address, phone number, and payment details.</p>
                
                <h3 class="text-xl font-bold text-gray-900 uppercase tracking-wide">2. How We Use Your Information</h3>
                <p>We use your information to process bookings, send confirmations, and provide personalized travel advice. We also use it to improve our services and send promotional updates if you've opted in.</p>
                
                <h3 class="text-xl font-bold text-gray-900 uppercase tracking-wide">3. Data Security</h3>
                <p>We implement industry-standard security measures to protect your personal data. Your payment information is processed through secure, encrypted gateways (like Razorpay) and is never stored on our servers.</p>
                
                <h3 class="text-xl font-bold text-gray-900 uppercase tracking-wide">4. Third-Party Services</h3>
                <p>We may share your information with travel partners (hotels, guides) necessary to fulfill your itinerary. We do not sell your data to third-party advertisers.</p>
                
                <h3 class="text-xl font-bold text-gray-900 uppercase tracking-wide">5. Your Rights</h3>
                <p>You have the right to access, update, or delete your personal information at any time through your dashboard or by contacting us.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
</body></html>
