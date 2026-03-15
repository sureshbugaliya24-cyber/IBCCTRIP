<?php
// frontend/sitemap.php
// ─────────────────────────────────────────────────────────────

require_once __DIR__ . '/components/config.php';
require_once __DIR__ . '/components/helpers.php';
require_once __DIR__ . '/components/breadcrumb.php';

$pageTitle = 'Sitemap';
$pageDesc = 'Navigate easily through the IBCC Trip website with our comprehensive sitemap.';
$activePage = 'sitemap';
$transparent = true;

require_once __DIR__ . '/layouts/head.php';
require_once __DIR__ . '/layouts/header.php';
?>

<!-- Page Header -->
<div class="bg-gradient-to-r from-primary to-blue-800 text-white py-12">
    <div class="max-w-7xl mx-auto px-4">
        <?php renderBreadcrumb([
    ['Home', FRONTEND_URL . '/'],
    ['Sitemap', '']
]); ?>
        <h1 class="text-3xl md:text-5xl font-extrabold mt-4">Site<span class="text-secondary">map</span></h1>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">

        <!-- Main Pages -->
        <div>
            <div class="flex items-center gap-3 mb-4">
                <span
                    class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xl">🏠</span>
                <h2 class="text-xl font-bold text-gray-900">Main Pages</h2>
            </div>
            <ul class="space-y-3">
                <li><a href="<?= FRONTEND_URL?>/"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">Home</a></li>
                <li><a href="<?= FRONTEND_URL?>/trips"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">Explore Trips</a></li>
                <li><a href="<?= FRONTEND_URL?>/gallery"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">Photo Gallery</a></li>
                <li><a href="<?= FRONTEND_URL?>/blog"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">Travel Blog</a></li>
                <li><a href="<?= FRONTEND_URL?>/about"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">About Us</a></li>
                <li><a href="<?= FRONTEND_URL?>/contact"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">Contact Us</a></li>
            </ul>
        </div>

        <!-- Destinations Explore -->
        <div>
            <div class="flex items-center gap-3 mb-4">
                <span
                    class="w-10 h-10 rounded-full bg-secondary/10 text-secondary flex items-center justify-center text-xl">🌍</span>
                <h2 class="text-xl font-bold text-gray-900">Destinations</h2>
            </div>
            <ul class="space-y-3">
                <li><a href="<?= FRONTEND_URL?>/country"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">Browse by Country</a>
                </li>
                <li><a href="<?= FRONTEND_URL?>/state"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">Browse by State</a></li>
                <li><a href="<?= FRONTEND_URL?>/city"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">Browse by City</a></li>
                <li><a href="<?= FRONTEND_URL?>/place"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">Top Places to Visit</a>
                </li>
            </ul>
        </div>

        <!-- Themes -->
        <div>
            <div class="flex items-center gap-3 mb-4">
                <span
                    class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xl">✨</span>
                <h2 class="text-xl font-bold text-gray-900">Trip Themes</h2>
            </div>
            <ul class="space-y-3">
                <li><a href="<?= FRONTEND_URL?>/trips?type=Domestic"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">Domestic Tours</a></li>
                <li><a href="<?= FRONTEND_URL?>/trips?type=International"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">International Tours</a>
                </li>
                <li><a href="<?= FRONTEND_URL?>/trips?type=Luxury"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">Luxury Escapes</a></li>
                <li><a href="<?= FRONTEND_URL?>/trips?type=Adventure"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">Adventure Trips</a></li>
                <li><a href="<?= FRONTEND_URL?>/trips?type=Honeymoon"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">Honeymoon Packages</a>
                </li>
                <li><a href="<?= FRONTEND_URL?>/trips?type=Religious"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">Religious Tours</a></li>
            </ul>
        </div>

        <!-- Account -->
        <div>
            <div class="flex items-center gap-3 mb-4">
                <span
                    class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl">👤</span>
                <h2 class="text-xl font-bold text-gray-900">Account</h2>
            </div>
            <ul class="space-y-3">
                <li><a href="<?= FRONTEND_URL?>/login"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">Login Register</a></li>
                <li><a href="<?= FRONTEND_URL?>/dashboard"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">User Dashboard</a></li>
                <li><a href="<?= FRONTEND_URL?>/forgot-password"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">Forgot Password</a></li>
            </ul>
        </div>

        <!-- Legal & Policies -->
        <div>
            <div class="flex items-center gap-3 mb-4">
                <span
                    class="w-10 h-10 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center text-xl">⚖️</span>
                <h2 class="text-xl font-bold text-gray-900">Legal Policies</h2>
            </div>
            <ul class="space-y-3">
                <li><a href="<?= FRONTEND_URL?>/privacy"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">Privacy Policy</a></li>
                <li><a href="<?= FRONTEND_URL?>/terms"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">Terms Conditions</a></li>
                <li><a href="<?= FRONTEND_URL?>/refund-policy"
                        class="text-gray-600 hover:text-secondary hover:underline font-medium">Refund Policy</a></li>
            </ul>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>