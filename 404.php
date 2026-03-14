<?php
// frontend/404.php — Custom 404 Page
require_once __DIR__ . '/components/config.php';
require_once __DIR__ . '/components/helpers.php';

http_response_code(404);
$pageTitle = 'Page Not Found';
require_once __DIR__ . '/layouts/head.php';
require_once __DIR__ . '/layouts/header.php';
?>

<div class="min-h-[70vh] flex items-center justify-center px-4 bg-gray-50">
    <div class="max-w-xl w-full text-center space-y-8">
        <div class="relative">
            <h1 class="text-[12rem] font-black text-primary/5 leading-none">404</h1>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-8xl">🏜️</span>
            </div>
        </div>
        
        <div class="space-y-4">
            <h2 class="text-4xl font-black text-gray-900 uppercase tracking-tight">You seem lost...</h2>
            <p class="text-gray-500 text-lg">The destination you're looking for doesn't exist or has been moved to a new horizon.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
            <a href="<?= FRONTEND_URL ?>/" class="bg-primary text-white font-black px-10 py-5 rounded-3xl shadow-xl hover:scale-105 active:scale-95 transition-all text-xs uppercase tracking-widest">Back to Home</a>
            <a href="<?= FRONTEND_URL ?>/trips" class="bg-white text-gray-900 font-black px-10 py-5 rounded-3xl border border-gray-200 hover:bg-gray-50 transition-all text-xs uppercase tracking-widest">Explore Trips</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
</body></html>
