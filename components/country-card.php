<?php
// frontend/components/country-card.php
// Reusable Country Card — renderCountryCard($country)

if (!defined('FRONTEND_URL')) require_once __DIR__ . '/config.php';
if (!function_exists('e')) require_once __DIR__ . '/helpers.php';

function renderCountryCard(array $c): void {
    $slug  = e($c['slug'] ?? '');
    $name  = e($c['name'] ?? 'Country');
    $flag  = e($c['flag_icon'] ?? '🌍');
    $img   = img_url($c['featured_image'] ?? null, 'destination');
    $count = (int)($c['trip_count'] ?? 0);
    $url   = FRONTEND_URL . '/country/' . urlencode($c['slug'] ?? '');
    ?>
<a href="<?= $url ?>"
   class="group relative block overflow-hidden rounded-2xl shadow-sm hover:shadow-2xl
          transition-all duration-300 border border-gray-100 aspect-[3/4]">
  <!-- BG Image -->
  <img src="<?= $img ?>" alt="<?= $name ?>" loading="lazy"
       class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
  <!-- Gradient -->
  <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
  <!-- Flag top-right -->
  <span class="absolute top-3 right-3 text-2xl"><?= $flag ?></span>
  <!-- Name + count -->
  <div class="absolute bottom-4 left-4 right-4">
    <h3 class="text-white font-extrabold text-xl leading-tight"><?= $name ?></h3>
    <p class="text-white/70 text-xs mt-1"><?= $count ?> Trip<?= $count !== 1 ? 's' : '' ?> Available</p>
  </div>
  <!-- Hover overlay -->
  <div class="absolute inset-0 bg-primary/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
</a>
<?php
}
if (isset($country) && is_array($country)) renderCountryCard($country);
