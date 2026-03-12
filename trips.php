<?php
// frontend/trips.php — Trips Listing Page
// ─────────────────────────────────────────────────────────────

require_once __DIR__ . '/components/config.php';
require_once __DIR__ . '/components/helpers.php';
require_once __DIR__ . '/components/trip-card.php';
require_once __DIR__ . '/components/pagination.php';

// Read GET filters
$page       = max(1, (int)($_GET['page'] ?? 1));
$q          = qParam('q');
$countryId  = qParam('country_id');
$stateId    = qParam('state_id');
$type       = qParam('type');
$minPrice   = qParam('min_price');
$maxPrice   = qParam('max_price');
$duration   = qParam('duration');
$sortBy     = qParam('sort', 'featured');

// Fetch filters data
$countries = apiGet('locations.php', ['action' => 'countries']);

// Build API params
$params = ['page' => $page, 'per_page' => 12, 'sort' => $sortBy];
if ($q)         $params['q']          = $q;
if ($countryId) $params['country_id'] = $countryId;
if ($stateId)   $params['state_id']   = $stateId;
if ($type)      $params['type']       = $type;
if ($minPrice)  $params['min_price']  = $minPrice;
if ($maxPrice)  $params['max_price']  = $maxPrice;
if ($duration)  $params['duration']   = $duration;

$response  = apiGetFull('trips.php', $params);
$trips     = $response['data'] ?? [];
$totalTrips= $response['pagination']['total'] ?? 0;
$totalPages= $response['pagination']['last_page'] ?? 1;

// Build base URL for pagination (preserve filters)
$filterParams = http_build_query(array_filter(compact('q','countryId','stateId','type','minPrice','maxPrice','duration','sortBy'), fn($v) => $v !== ''));
$paginationUrl = 'trips' . ($filterParams ? '?' . $filterParams : '');

// Page meta
$pageTitle  = $q ? '"' . $q . '" — Trip Search Results' : 'Explore All Trip Packages';
$pageDesc   = 'Browse ' . ($totalTrips > 0 ? $totalTrips . '+ ' : '') . 'curated travel packages. Filter by destination, budget, type, and duration. Book online instantly.';
$activePage = 'trips';

require_once __DIR__ . '/layouts/head.php';
require_once __DIR__ . '/layouts/header.php';
?>

<!-- Page Hero Banner -->
<div class="bg-gradient-to-r from-primary to-blue-800 py-10 px-4 text-white">
  <div class="max-w-7xl mx-auto">
    <h1 class="text-2xl md:text-4xl font-extrabold mb-1">
      <?= $q ? 'Results for: <em class="text-secondary not-italic">"' . e($q) . '"</em>' : 'Explore All <span class="text-secondary">Trip Packages</span>' ?>
    </h1>
    <p class="text-white/70 text-sm">
      <?= $totalTrips ?> trips found
      <?= $q ? ' matching your search' : '' ?>
      <?= $countryId ? ' in selected country' : '' ?>
    </p>
  </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-10">
  <div class="flex flex-col lg:flex-row gap-8">

    <!-- ===== FILTER SIDEBAR ===== -->
    <aside class="lg:w-64 shrink-0">
      <form method="GET" action="trips" id="filter-form"
            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">

        <div class="flex items-center justify-between p-5 border-b border-gray-100">
          <h3 class="font-extrabold text-gray-900">Filters</h3>
          <a href="trips" class="text-red-500 text-xs font-semibold hover:underline">Clear All</a>
        </div>

        <div class="p-5 space-y-6">
          <!-- Search -->
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Keyword</label>
            <input name="q" type="text" value="<?= e($q) ?>" placeholder="Search trips..."
                   class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-primary">
          </div>

          <!-- Country -->
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Country</label>
            <select name="country_id" id="f-country"
                    class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-primary"
                    onchange="loadStates(this.value)">
              <option value="">All Countries</option>
              <?php foreach ($countries as $c): ?>
              <option value="<?= e($c['id']) ?>" <?= $countryId == $c['id'] ? 'selected' : '' ?>>
                <?= e($c['flag_icon'] . ' ' . $c['name']) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- State (dynamic, loaded via JS) -->
          <div id="state-filter" class="<?= $countryId ? '' : 'hidden' ?>">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">State / Region</label>
            <select name="state_id" id="f-state"
                    class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-primary">
              <option value="">All States</option>
            </select>
          </div>

          <!-- Trip Type -->
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Trip Type</label>
            <div class="space-y-1.5">
              <?php foreach (['Domestic','International','Adventure','Luxury','Honeymoon','Religious'] as $t): ?>
              <label class="flex items-center gap-2.5 cursor-pointer">
                <input type="radio" name="type" value="<?= e($t) ?>"
                       <?= $type === $t ? 'checked' : '' ?>
                       class="accent-primary">
                <span class="text-sm text-gray-700"><?= e($t) ?></span>
              </label>
              <?php endforeach; ?>
              <label class="flex items-center gap-2.5 cursor-pointer">
                <input type="radio" name="type" value="" <?= !$type ? 'checked' : '' ?> class="accent-primary">
                <span class="text-sm text-gray-700">All Types</span>
              </label>
            </div>
          </div>

          <!-- Budget Slider -->
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Budget (₹)</label>
            <div class="flex gap-2">
              <input name="min_price" type="number" placeholder="Min" value="<?= e($minPrice) ?>"
                     class="w-1/2 border border-gray-200 rounded-xl py-2 px-2.5 text-xs focus:outline-none focus:border-primary">
              <input name="max_price" type="number" placeholder="Max" value="<?= e($maxPrice) ?>"
                     class="w-1/2 border border-gray-200 rounded-xl py-2 px-2.5 text-xs focus:outline-none focus:border-primary">
            </div>
          </div>

          <!-- Duration -->
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Duration</label>
            <select name="duration"
                    class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-primary">
              <option value="">Any</option>
              <?php foreach ([['1-3','1-3 Days'],['4-7','4-7 Days'],['8-14','8-14 Days'],['15+','15+ Days']] as [$v,$l]): ?>
              <option value="<?= $v ?>" <?= $duration === $v ? 'selected' : '' ?>><?= $l ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <button type="submit"
                  class="w-full bg-primary text-white font-extrabold py-3 rounded-xl
                         hover:bg-blue-800 transition-colors text-sm shadow-lg">
            Apply Filters
          </button>
        </div>
      </form>
    </aside>

    <!-- ===== TRIPS GRID ===== -->
    <div class="flex-1 min-w-0">
      <!-- Sort bar -->
      <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
        <p class="text-sm text-gray-500">
          Showing <strong><?= count($trips) ?></strong> of <strong><?= $totalTrips ?></strong> trips
          <?php if ($page > 1): ?>(Page <?= $page ?>)<?php endif; ?>
        </p>
        <div class="flex items-center gap-2 text-sm">
          <span class="text-gray-500 font-medium">Sort:</span>
          <?php foreach ([['featured','Featured'],['price_asc','Price ↑'],['price_desc','Price ↓'],['duration','Duration'],['newest','Newest']] as [$val,$label]): ?>
          <a href="?<?= http_build_query(array_merge(array_filter(compact('q','countryId','stateId','type','minPrice','maxPrice','duration')), ['sort' => $val])) ?>"
             class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors
                    <?= $sortBy === $val ? 'bg-primary text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' ?>">
            <?= $label ?>
          </a>
          <?php endforeach; ?>
        </div>
      </div>

      <?php if (count($trips) > 0): ?>
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php foreach ($trips as $trip): renderTripCard($trip); endforeach; ?>
      </div>

      <!-- Pagination -->
      <?php renderPagination($page, $totalPages, $paginationUrl); ?>

      <?php else: ?>
      <div class="bg-white rounded-2xl p-16 text-center border border-gray-100 shadow-sm">
        <div class="text-5xl mb-4">🔍</div>
        <h3 class="text-xl font-extrabold text-gray-900 mb-2">No Trips Found</h3>
        <p class="text-gray-400 mb-5">Try adjusting your filters or search for something else.</p>
        <a href="trips" class="bg-secondary text-white font-bold px-6 py-3 rounded-xl text-sm">Clear Filters</a>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
<script src="<?= FRONTEND_URL ?>/js/app.js?v=<?= APP_VERSION ?>"></script>
<script>
// Session sync
document.addEventListener('DOMContentLoaded', async () => {
  const user = await Session.init();
  if (user) {
    document.getElementById('nav-login').style.display = 'none';
    document.getElementById('nav-dashboard').style.display = 'flex';
    document.getElementById('nav-logout').style.display = 'block';
    document.getElementById('nav-avatar').textContent = user.name[0];
  }
});

// Dynamic state loading
async function loadStates(countryId) {
  const stateEl  = document.getElementById('f-state');
  const stateDiv = document.getElementById('state-filter');
  if (!countryId) { stateDiv.classList.add('hidden'); return; }
  stateDiv.classList.remove('hidden');
  const r = await IBCC.locations.states(countryId);
  stateEl.innerHTML = '<option value="">All States</option>' +
    (r?.data || []).map(s => `<option value="${s.id}">${s.name}</option>`).join('');
}
</script>
</body>
</html>
