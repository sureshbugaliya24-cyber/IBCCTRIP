<?php
// frontend/city.php — City Listing & Detail
// ─────────────────────────────────────────────────────────────

require_once __DIR__ . '/components/config.php';
require_once __DIR__ . '/components/helpers.php';
require_once __DIR__ . '/components/city-card.php'; // contains renderCityCard, renderPlaceCard
require_once __DIR__ . '/components/trip-card.php';
require_once __DIR__ . '/components/breadcrumb.php';

$slug = qParam('slug');
if (!$slug && isset($_SERVER['PATH_INFO'])) {
    $slug = trim($_SERVER['PATH_INFO'], '/');
}

if (!$slug) {
    $cities = apiGet('locations.php', ['action' => 'cities']);
    $pageTitle = 'Explore Cities';
    $activePage = 'destinations';
    require_once __DIR__ . '/layouts/head.php';
    require_once __DIR__ . '/layouts/header.php';
    ?>
    <div class="bg-primary py-20 px-4 text-center text-white">
      <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Discover <span class="text-secondary">Cities</span></h1>
      <p class="text-white/70 max-w-2xl mx-auto">From bustling metropolises to historic towns, find your next urban adventure.</p>
    </div>
    <div class="max-w-7xl mx-auto px-4 py-20">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($cities as $c): renderCityCard($c); endforeach; ?>
      </div>
    </div>
    <?php
    require_once __DIR__ . '/layouts/footer.php';
    echo '</body></html>';
    exit;
}

$resp = apiGetFull('locations.php', ['action' => 'city', 'slug' => $slug]);
if (empty($resp['success'])) redirect(FRONTEND_URL . '/city.php');

$city   = $resp['data'] ?? [];
$places = $city['places'] ?? [];
$trips  = $city['trips']  ?? [];

$pageTitle = $city['name'] ?? 'City';
$activePage = 'destinations';

require_once __DIR__ . '/layouts/head.php';
require_once __DIR__ . '/layouts/header.php';
?>

<div class="relative h-[50vh] min-h-[300px] overflow-hidden">
  <img src="<?= e($city['featured_image'] ?? '') ?>" class="w-full h-full object-cover">
  <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent"></div>
  <div class="absolute inset-0 flex flex-col justify-end pb-12 px-4 max-w-7xl mx-auto left-0 right-0">
    <?php renderBreadcrumb([
      ['Home', FRONTEND_URL . '/index.php'],
      ['Destinations', FRONTEND_URL . '/country.php'],
      [$city['country_name'] ?? 'Country', FRONTEND_URL . '/country.php/' . ($city['country_slug'] ?? '')],
      [$city['state_name'] ?? 'State', FRONTEND_URL . '/state.php/' . ($city['state_slug'] ?? '')],
      [$city['name'] ?? ''],
    ]); ?>
    <h1 class="text-4xl md:text-6xl font-black text-white mt-4"><?= e($city['name'] ?? '') ?></h1>
    <p class="text-white/80 text-lg mt-2 font-bold uppercase tracking-widest text-secondary"><?= e($city['state_name'] ?? '') ?>, <?= e($city['country_name'] ?? '') ?></p>
  </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-16">
  <div class="flex flex-col lg:flex-row gap-16">
    <div class="flex-1 min-w-0">
      <div class="text-gray-600 leading-relaxed mb-16 max-w-3xl">
        <h2 class="text-2xl font-extrabold text-gray-900 mb-6">Discover <?= e($city['name'] ?? '') ?></h2>
        <?= nl2br(e($city['description'] ?? '')) ?>
      </div>

      <?php if (count($places) > 0): ?>
      <div class="mb-20">
        <h2 class="text-2xl font-extrabold text-gray-900 mb-8">Points of Interest in <?= e($city['name'] ?? '') ?></h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <?php foreach ($places as $place): renderPlaceCard($place); endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if (count($trips) > 0): ?>
      <div>
        <h2 class="text-2xl font-extrabold text-gray-900 mb-8">Trips Visiting <?= e($city['name'] ?? '') ?></h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <?php foreach ($trips as $trip): renderTripCard($trip); endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <aside class="lg:w-80 shrink-0">
      <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 sticky top-24">
        <h3 class="font-extrabold text-gray-900 text-lg mb-6">Explore More</h3>
        <div class="space-y-4">
          <?php if (!empty($city['country_slug'])): ?>
          <a href="<?= FRONTEND_URL ?>/country.php/<?= e($city['country_slug']) ?>" 
             class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl hover:bg-primary hover:text-white transition-all group">
            <span class="font-bold text-sm">More in <?= e($city['country_name'] ?? 'Country') ?></span>
            <span class="group-hover:translate-x-1 transition-transform">→</span>
          </a>
          <?php endif; ?>
          <?php if (!empty($city['state_slug'])): ?>
          <a href="<?= FRONTEND_URL ?>/state.php/<?= e($city['state_slug']) ?>" 
             class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl hover:bg-primary hover:text-white transition-all group">
            <span class="font-bold text-sm">More in <?= e($city['state_name'] ?? 'State') ?></span>
            <span class="group-hover:translate-x-1 transition-transform">→</span>
          </a>
          <?php endif; ?>
        </div>
      </div>
    </aside>
  </div>

  <!-- Related Trips Section -->
  <?php if (!empty($city['related_trips'])): ?>
  <div class="mt-24 border-t border-gray-100 pt-16">
    <div class="flex items-center justify-between mb-10">
      <div>
        <h2 class="text-3xl font-black text-gray-900">Discover More <span class="text-primary">Trips</span></h2>
        <p class="text-gray-400 mt-2">More adventures from the same region</p>
      </div>
      <a href="<?= FRONTEND_URL ?>/trips.php" class="text-primary font-bold hover:underline">View All →</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($city['related_trips'] as $rt): renderTripCard($rt); endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
<script src="<?= FRONTEND_URL ?>/js/app.js?v=<?= APP_VERSION ?>"></script>
</body>
</html>
