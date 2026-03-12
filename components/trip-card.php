<?php
// frontend/components/trip-card.php
// ─────────────────────────────────────────────────────────────
// Reusable Trip Card Component
// ─────────────────────────────────────────────────────────────

if (!defined('FRONTEND_URL')) require_once __DIR__ . '/config.php';
if (!function_exists('formatPrice')) require_once __DIR__ . '/helpers.php';

function renderTripCard(array $trip) {
    $slug       = e($trip['slug'] ?? '');
    $title      = e($trip['title'] ?? 'Trip');
    $cover      = e($trip['cover_image'] ?? 'https://images.unsplash.com/photo-1488085061387-422e29b40080?w=600');
    $country    = e($trip['country_name'] ?? '');
    $duration   = (int)($trip['duration_days'] ?? 1);
    $type       = e($trip['trip_type'] ?? 'Tour');
    $difficulty = e($trip['difficulty'] ?? '');
    $price      = (float)($trip['discounted_price'] ?: $trip['base_price'] ?? 0);
    $origPrice  = $trip['discounted_price'] ? (float)($trip['base_price'] ?? 0) : 0;
    $isSale     = $trip['discounted_price'] > 0;
    $isFeatured = !empty($trip['is_featured']);

    $detailUrl  = FRONTEND_URL . '/trip.php/' . urlencode($trip['slug'] ?? '');
    ?>
    <article class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 group border border-gray-100 flex flex-col">
      <a href="<?= $detailUrl ?>" class="relative block overflow-hidden h-52 shrink-0">
        <img src="<?= $cover ?>" alt="<?= $title ?>" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
        <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
          <?php if ($isSale): ?>
            <span class="bg-red-500 text-white text-xs font-extrabold px-2.5 py-1 rounded-full">SALE</span>
          <?php endif; ?>
          <?php if ($isFeatured): ?>
            <span class="bg-secondary text-white text-xs font-extrabold px-2.5 py-1 rounded-full">⭐ Featured</span>
          <?php endif; ?>
        </div>
        <div class="absolute bottom-3 left-3 right-3 flex items-end justify-between">
          <div class="flex gap-1.5 flex-wrap">
            <span class="bg-white/90 backdrop-blur text-xs font-bold px-2.5 py-1 rounded-full text-blue-900"><?= $duration ?> Days</span>
            <span class="bg-white/90 backdrop-blur text-xs font-bold px-2.5 py-1 rounded-full text-gray-700"><?= $type ?></span>
          </div>
          <?php if ($difficulty): ?>
            <span class="bg-black/40 backdrop-blur text-white text-xs px-2 py-1 rounded-full"><?= $difficulty ?></span>
          <?php endif; ?>
        </div>
      </a>
      <div class="p-4 flex flex-col flex-1">
        <?php if ($country): ?>
          <p class="text-xs text-secondary font-semibold uppercase tracking-wider mb-1"><?= $country ?></p>
        <?php endif; ?>
        <a href="<?= $detailUrl ?>">
          <h3 class="font-extrabold text-gray-900 text-base mb-2 line-clamp-2 group-hover:text-primary transition-colors leading-snug"><?= $title ?></h3>
        </a>
        <div class="flex items-center justify-between mt-auto pt-3 border-t border-gray-100">
          <div>
            <?php if ($origPrice > 0): ?>
              <span class="text-gray-400 line-through text-xs mr-1"><?= formatPrice($origPrice) ?></span>
            <?php endif; ?>
            <span class="text-primary font-extrabold text-xl"><?= formatPrice($price) ?></span>
            <span class="text-gray-400 text-xs">/person</span>
          </div>
          <a href="<?= $detailUrl ?>" class="bg-secondary hover:bg-orange-600 text-white text-xs font-extrabold px-4 py-2 rounded-xl transition-colors shadow-sm whitespace-nowrap">Book Now</a>
        </div>
      </div>
    </article>
    <?php
}

if (isset($trip) && is_array($trip)) {
    renderTripCard($trip);
}
?>
