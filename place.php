<?php
// frontend/place.php — Specific Tourist Place Detail
// ─────────────────────────────────────────────────────────────

require_once __DIR__ . '/components/config.php';
require_once __DIR__ . '/components/helpers.php';
require_once __DIR__ . '/components/city-card.php'; // contains renderPlaceCard
require_once __DIR__ . '/components/trip-card.php';
require_once __DIR__ . '/components/breadcrumb.php';

$slug = qParam('slug');
if (!$slug && isset($_SERVER['PATH_INFO'])) {
    $slug = trim($_SERVER['PATH_INFO'], '/');
}

if (!$slug) {
    $places = apiGet('locations.php', ['action' => 'places']);
    $pageTitle = 'Must-Visit Places';
    $activePage = 'destinations';
    $transparent = true;
    require_once __DIR__ . '/layouts/head.php';
    require_once __DIR__ . '/layouts/header.php';
    ?>
    <div class="bg-primary py-20 px-4 text-center text-white">
      <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Must-Visit <span class="text-secondary">Places</span></h1>
      <p class="text-white/70 max-w-2xl mx-auto">Handpicked landmarks and hidden gems you simply cannot miss.</p>
    </div>
    <div class="max-w-7xl mx-auto px-4 py-20">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($places as $p): renderPlaceCard($p); endforeach; ?>
      </div>
    </div>
    <?php
    require_once __DIR__ . '/layouts/footer.php';
    echo '</body></html>';
    exit;
}

$resp = apiGetFull('locations.php', ['action' => 'place', 'slug' => $slug]);
if (empty($resp['success'])) redirect(FRONTEND_URL . '/place');

$place = $resp['data'] ?? [];
$trips = $place['trips'] ?? [];

$pageTitle = !empty($place['meta_title']) ? $place['meta_title'] : ($place['name'] ?? 'Place');
$pageDesc = $place['meta_description'] ?? '';
$pageKeywords = $place['meta_keywords'] ?? '';
$exactTitle = !empty($place['meta_title']);

$activePage = 'destinations';
$transparent = true;

require_once __DIR__ . '/layouts/head.php';
require_once __DIR__ . '/layouts/header.php';
?>

<div class="relative h-[45vh] min-h-[300px] overflow-hidden">
  <img src="<?= img_url($place['featured_image'] ?? null, 'destination') ?>" class="w-full h-full object-cover">
  <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent"></div>
  <div class="absolute inset-0 flex flex-col justify-end pb-12 px-4 max-w-7xl mx-auto left-0 right-0">
    <?php renderBreadcrumb([
      ['Home', FRONTEND_URL . '/'],
      ['Destinations', FRONTEND_URL . '/country'],
      [$place['country_name'] ?? 'Country', FRONTEND_URL . '/country/' . ($place['country_slug'] ?? '')],
      [$place['city_name'] ?? 'City', FRONTEND_URL . '/city/' . ($place['city_slug'] ?? '')],
      [$place['name'] ?? ''],
    ]); ?>
    <h1 class="text-4xl md:text-5xl font-black text-white mt-4"><?= e($place['name'] ?? '') ?></h1>
    <p class="text-white/80 text-lg mt-2 font-bold uppercase tracking-widest text-secondary"><?= e($place['city_name'] ?? '') ?>, <?= e($place['country_name'] ?? '') ?></p>
  </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-16">
  <div class="flex flex-col lg:flex-row gap-16">
    <div class="flex-1 min-w-0">
      <div class="bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-gray-100 mb-16">
        <h2 class="text-2xl font-extrabold text-gray-900 mb-6">About <?= e($place['name'] ?? '') ?></h2>
        <div class="quill-content text-gray-600 leading-relaxed text-sm md:text-base">
          <?= $place['description'] ?? '' ?>
        </div>
      </div>

      <?php if (count($trips) > 0): ?>
      <div>
        <h3 class="text-2xl font-extrabold text-gray-900 mb-8">Trips that Visit <?= e($place['name'] ?? '') ?></h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <?php foreach ($trips as $trip): renderTripCard($trip); endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <aside class="lg:w-80 shrink-0">
      <div class="bg-accent rounded-3xl p-8 sticky top-24 text-center">
        <div class="text-3xl mb-4">📍</div>
        <h3 class="font-extrabold text-gray-900 text-lg mb-2">Plan Your Visit</h3>
        <p class="text-gray-500 text-sm mb-6">Interested in visiting <?= e($place['name'] ?? '') ?>? Book a package that includes this destination.</p>
        <a href="<?= FRONTEND_URL ?>/trips?q=<?= urlencode($place['name'] ?? '') ?>" 
           class="block bg-primary text-white font-extrabold py-3.5 rounded-xl hover:bg-blue-800 transition-all shadow-lg shadow-primary/10">Browse Related Trips</a>
      </div>
    </aside>
  </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
<script src="<?= FRONTEND_URL ?>/js/app.js?v=<?= APP_VERSION ?>"></script>
</body>
</html>
