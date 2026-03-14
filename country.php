<?php
// frontend/country.php — Country Listing & Detail
// ─────────────────────────────────────────────────────────────

require_once __DIR__ . '/components/config.php';
require_once __DIR__ . '/components/helpers.php';
require_once __DIR__ . '/components/country-card.php';
require_once __DIR__ . '/components/city-card.php';
require_once __DIR__ . '/components/trip-card.php';
require_once __DIR__ . '/components/blog-card.php';
require_once __DIR__ . '/components/breadcrumb.php';

$slug = qParam('slug');
if (!$slug && isset($_SERVER['PATH_INFO'])) {
  $slug = trim($_SERVER['PATH_INFO'], '/');
}

// If no slug, show all countries
if (!$slug) {
  $countries = apiGet('locations.php', ['action' => 'countries']);
  $pageTitle = 'Explore Destinations';
  $activePage = 'destinations';
  $transparent = true;
  require_once __DIR__ . '/layouts/head.php';
  require_once __DIR__ . '/layouts/header.php';
?>
<div class="bg-primary py-20 px-4 text-center text-white">
  <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Explore <span class="text-secondary">Destinations</span></h1>
  <p class="text-white/70 max-w-2xl mx-auto">Discover spectacular countries across the globe with IBCC Trip.</p>
</div>
<div class="max-w-7xl mx-auto px-4 py-20">
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <?php foreach ($countries as $c):
    renderCountryCard($c);
  endforeach; ?>
  </div>
</div>
<?php
  require_once __DIR__ . '/layouts/footer.php';
  echo '</body></html>';
  exit;
}

// Single Country Detail
$resp = apiGetFull('locations.php', ['action' => 'country', 'slug' => $slug]);
if (empty($resp['success'])) {
  redirect(FRONTEND_URL . '/country');
}

$country = $resp['data'] ?? [];
$states = $country['states'] ?? [];
$trips = $country['trips'] ?? [];
$blogs = $country['blogs'] ?? [];

$pageTitle = $country['name'] ?? 'Country';
$activePage = 'destinations';
$transparent = true;

require_once __DIR__ . '/layouts/head.php';
require_once __DIR__ . '/layouts/header.php';
?>

<!-- Hero -->
<div class="relative h-[60vh] min-h-[400px] overflow-hidden">
  <img src="<?= e($country['featured_image'] ?? '')?>" class="w-full h-full object-cover">
  <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
  <div class="absolute inset-0 flex flex-col justify-end pb-12 px-4 max-w-7xl mx-auto left-0 right-0">
    <?php renderBreadcrumb([
  ['Home', FRONTEND_URL . '/'],
  ['Destinations', FRONTEND_URL . '/country'],
  [$country['name'] ?? ''],
]); ?>
    <h1 class="text-4xl md:text-6xl font-black text-white mt-4 flex items-center gap-4">
      <?= e($country['name'] ?? '')?>
      <span class="text-4xl">
        <?= e($country['flag_icon'] ?? '')?>
      </span>
    </h1>
  </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-16">
  <div class="flex flex-col lg:flex-row gap-16">

    <!-- Main Info -->
    <div class="flex-1 min-w-0">
      <div class="prose max-w-none text-gray-600 leading-relaxed mb-16">
        <h2 class="text-2xl font-extrabold text-gray-900 mb-6">About
          <?= e($country['name'] ?? '')?>
        </h2>
        <?= nl2br(e($country['description'] ?? ''))?>
      </div>

      <!-- States / Regions -->
      <?php if (count($states) > 0): ?>
      <div class="mb-20">
        <div class="flex items-center justify-between mb-8">
          <h2 class="text-2xl font-extrabold text-gray-900">Popular States / Regions</h2>
          <a href="<?= FRONTEND_URL?>/state" class="text-primary font-bold text-sm hover:underline">View All</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
          <?php foreach ($states as $state):
    renderStateCard($state);
  endforeach; ?>
        </div>
      </div>
      <?php
endif; ?>

      <!-- Related Trips -->
      <?php if (count($trips) > 0): ?>
      <div class="mb-20">
        <h2 class="text-2xl font-extrabold text-gray-900 mb-8">Handpicked Packages for
          <?= e($country['name'] ?? '')?>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <?php foreach ($trips as $trip):
    renderTripCard($trip);
  endforeach; ?>
        </div>
        <div class="mt-8 text-center">
          <a href="<?= FRONTEND_URL?>/trips?country_id=<?= $country['id']?>"
            class="inline-block bg-primary text-white font-extrabold px-8 py-3.5 rounded-xl hover:bg-blue-800 transition-all">
            Browse All
            <?= e($country['name'] ?? '')?> Trips
          </a>
        </div>
      </div>
      <?php
endif; ?>
    </div>

    <!-- Sidebar -->
    <aside class="lg:w-80 shrink-0">
      <!-- Latest Blogs -->
      <?php if (count($blogs) > 0): ?>
      <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-8">
        <h3 class="font-extrabold text-gray-900 mb-5">Travel Guides</h3>
        <div class="space-y-6">
          <?php foreach ($blogs as $blog):
    renderBlogCard($blog, 'normal');
  endforeach; ?>
        </div>
      </div>
      <?php
endif; ?>

      <!-- CTA -->
      <div class="bg-secondary rounded-3xl p-8 text-white text-center shadow-2xl shadow-secondary/10">
        <div class="text-4xl mb-4">🌟</div>
        <h3 class="font-extrabold text-xl mb-2">Need a Custom Plan?</h3>
        <p class="text-white/80 text-sm mb-6">Let our experts design the perfect itinerary for your trip to
          <?= e($country['name'] ?? '')?>.
        </p>
        <a href="<?= FRONTEND_URL?>/contact"
          class="block bg-white text-secondary font-extrabold py-3.5 rounded-xl hover:bg-gray-50 transition-all">Get
          Free Quote</a>
      </div>
    </aside>

  </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
<script src="<?= FRONTEND_URL?>/js/app.js?v=<?= APP_VERSION?>"></script>
</body>

</html>