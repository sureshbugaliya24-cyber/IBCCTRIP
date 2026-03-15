<?php
// frontend/state.php — State Listing & Detail
// ─────────────────────────────────────────────────────────────

require_once __DIR__ . '/components/config.php';
require_once __DIR__ . '/components/helpers.php';
require_once __DIR__ . '/components/city-card.php'; // contains renderStateCard, renderCityCard
require_once __DIR__ . '/components/trip-card.php';
require_once __DIR__ . '/components/breadcrumb.php';

$slug = qParam('slug');
if (!$slug && isset($_SERVER['PATH_INFO'])) {
    $slug = trim($_SERVER['PATH_INFO'], '/');
}

// List all states if no slug
if (!$slug) {
    $states = apiGet('locations.php', ['action' => 'states']);
    $pageTitle = 'Explore States & Regions';
    $activePage = 'destinations';
    $transparent = true;
    require_once __DIR__ . '/layouts/head.php';
    require_once __DIR__ . '/layouts/header.php';
    ?>
    <div class="bg-primary py-20 px-4 text-center text-white">
      <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Discover <span class="text-secondary">Regions</span></h1>
      <p class="text-white/70 max-w-2xl mx-auto">Explore the diverse landscapes and cultures of every state we cover.</p>
    </div>
    <div class="max-w-7xl mx-auto px-4 py-20">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($states as $s): renderStateCard($s); endforeach; ?>
      </div>
    </div>
    <?php
    require_once __DIR__ . '/layouts/footer.php';
    echo '</body></html>';
    exit;
}

// Single State Detail
$resp = apiGetFull('locations.php', ['action' => 'state', 'slug' => $slug]);
if (empty($resp['success'])) redirect(FRONTEND_URL . '/state');

$state = $resp['data'] ?? [];
$cities = $state['cities'] ?? [];
$trips  = $state['trips']  ?? [];

$pageTitle = !empty($state['meta_title']) ? $state['meta_title'] : ($state['name'] ?? 'State');
$pageDesc = $state['meta_description'] ?? '';
$pageKeywords = $state['meta_keywords'] ?? '';
$exactTitle = !empty($state['meta_title']);

$activePage = 'destinations';
$transparent = true;

require_once __DIR__ . '/layouts/head.php';
require_once __DIR__ . '/layouts/header.php';
?>

<div class="relative h-[55vh] min-h-[350px] overflow-hidden">
  <img src="<?= img_url($state['featured_image'] ?? null, 'destination') ?>" class="w-full h-full object-cover">
  <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent"></div>
  <div class="absolute inset-0 flex flex-col justify-end pb-12 px-4 max-w-7xl mx-auto left-0 right-0">
    <?php renderBreadcrumb([
      ['Home', FRONTEND_URL . '/'],
      ['Destinations', FRONTEND_URL . '/country'],
      [$state['country_name'] ?? 'Destinations', FRONTEND_URL . '/country/' . ($state['country_slug'] ?? '')],
      [$state['name'] ?? ''],
    ]); ?>
    <h1 class="text-4xl md:text-6xl font-black text-white mt-4"><?= e($state['name'] ?? '') ?></h1>
    <p class="text-white/80 text-lg mt-2 font-bold uppercase tracking-widest text-secondary"><?= e($state['country_name'] ?? '') ?></p>
  </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-16">
  <div class="flex flex-col lg:flex-row gap-16">
    <div class="flex-1 min-w-0">
      <div class="text-gray-600 leading-relaxed mb-16 max-w-3xl">
        <h2 class="text-2xl font-extrabold text-gray-900 mb-6">Explore <?= e($state['name'] ?? '') ?></h2>
        <div class="quill-content">
            <?= $state['description'] ?? '' ?>
        </div>
      </div>

      <?php if (count($cities) > 0): ?>
      <div class="mb-20">
        <h2 class="text-2xl font-extrabold text-gray-900 mb-8">Major Cities in <?= e($state['name'] ?? '') ?></h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
          <?php foreach ($cities as $city): renderCityCard($city); endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if (count($trips) > 0): ?>
      <div>
        <h2 class="text-2xl font-extrabold text-gray-900 mb-8">Top Packages for <?= e($state['name'] ?? '') ?></h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <?php foreach ($trips as $trip): renderTripCard($trip); endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <aside class="lg:w-80 shrink-0">
      <div class="bg-accent rounded-3xl p-8 sticky top-24">
        <h3 class="font-extrabold text-gray-900 text-lg mb-4">Fast Facts</h3>
        <ul class="space-y-4 text-sm">
          <li class="flex justify-between border-b pb-2"><span class="text-gray-400">Country</span><span class="font-bold"><?= e($state['country_name'] ?? '') ?></span></li>
          <li class="flex justify-between border-b pb-2"><span class="text-gray-400">Available Cities</span><span class="font-bold"><?= count($cities) ?></span></li>
          <li class="flex justify-between border-b pb-2"><span class="text-gray-400">Total Trips</span><span class="font-bold"><?= count($trips) ?></span></li>
        </ul>
        <a href="<?= FRONTEND_URL ?>/contact" class="mt-8 block bg-secondary text-white text-center font-extrabold py-3.5 rounded-xl">Get Custom Quote</a>
      </div>
    </aside>
  </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
<script src="<?= FRONTEND_URL ?>/js/app.js?v=<?= APP_VERSION ?>"></script>
</body>
</html>
