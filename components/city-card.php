<?php
// frontend/components/city-card.php
// Reusable City Card — renderCityCard($city)

if (!defined('FRONTEND_URL')) require_once __DIR__ . '/config.php';
if (!function_exists('e')) require_once __DIR__ . '/helpers.php';

function renderCityCard(array $c): void {
    $name      = e($c['name'] ?? 'City');
    $slug      = e($c['slug'] ?? '');
    $stateName = e($c['state_name'] ?? '');
    $img       = img_url($c['featured_image'] ?? null, 'destination');
    $desc      = e(strip_tags($c['description'] ?? ''));
    $url       = FRONTEND_URL . '/city/' . urlencode($c['slug'] ?? '');
    ?>
<a href="<?= $url ?>"
   class="group block bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl
          transition-all duration-300 border border-gray-100">
  <div class="relative overflow-hidden h-40">
    <img src="<?= $img ?>" alt="<?= $name ?>" loading="lazy"
         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
    <div class="absolute bottom-3 left-3">
      <h3 class="text-white font-extrabold text-lg"><?= $name ?></h3>
      <?php if ($stateName): ?>
      <p class="text-white/70 text-xs"><?= $stateName ?></p>
      <?php endif; ?>
    </div>
  </div>
  <?php if ($desc): ?>
  <div class="p-3">
    <p class="text-gray-500 text-xs line-clamp-2"><?= $desc ?></p>
  </div>
  <?php endif; ?>
</a>
<?php
}
function renderStateCard(array $s): void {
    $name        = e($s['name'] ?? 'State');
    $countryName = e($s['country_name'] ?? '');
    $img         = img_url($s['featured_image'] ?? null, 'destination');
    $desc        = e(strip_tags($s['description'] ?? ''));
    $url         = FRONTEND_URL . '/state/' . urlencode($s['slug'] ?? '');
    ?>
<a href="<?= $url ?>"
   class="group block bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl
          transition-all duration-300 border border-gray-100">
  <div class="relative overflow-hidden h-40">
    <img src="<?= $img ?>" alt="<?= $name ?>" loading="lazy"
         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
    <div class="absolute bottom-3 left-3">
      <h3 class="text-white font-extrabold text-lg"><?= $name ?></h3>
      <?php if ($countryName): ?>
      <p class="text-white/70 text-xs"><?= $countryName ?></p>
      <?php endif; ?>
    </div>
  </div>
  <?php if ($desc): ?>
  <div class="p-3"><p class="text-gray-500 text-xs line-clamp-2"><?= $desc ?></p></div>
  <?php endif; ?>
</a>
<?php
}
function renderPlaceCard(array $p): void {
    $name     = e($p['name'] ?? 'Place');
    $cityName = e($p['city_name'] ?? '');
    $img      = img_url($p['featured_image'] ?? null, 'destination');
    $desc     = e(strip_tags($p['description'] ?? ''));
    $url      = FRONTEND_URL . '/place/' . urlencode($p['slug'] ?? '');
    ?>
<a href="<?= $url ?>"
   class="group block bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl
          transition-all duration-300 border border-gray-100">
  <div class="relative overflow-hidden h-40">
    <img src="<?= $img ?>" alt="<?= $name ?>" loading="lazy"
         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
    <div class="absolute bottom-3 left-3">
      <h3 class="text-white font-extrabold text-base"><?= $name ?></h3>
      <?php if ($cityName): ?>
      <p class="text-white/70 text-xs"><?= $cityName ?></p>
      <?php endif; ?>
    </div>
    <div class="absolute top-3 left-3 bg-white/90 backdrop-blur text-xs font-bold px-2 py-1 rounded-full text-gray-700">📍 Place</div>
  </div>
  <?php if ($desc): ?>
  <div class="p-3"><p class="text-gray-500 text-xs line-clamp-2"><?= $desc ?></p></div>
  <?php endif; ?>
</a>
<?php
}
if (isset($city)  && is_array($city))  renderCityCard($city);
if (isset($state) && is_array($state)) renderStateCard($state);
if (isset($place) && is_array($place)) renderPlaceCard($place);
