<?php
// frontend/trip.php — Dynamic Trip Detail Page
// ─────────────────────────────────────────────────────────────

require_once __DIR__ . '/components/config.php';
require_once __DIR__ . '/components/helpers.php';
require_once __DIR__ . '/components/breadcrumb.php';

$slug = qParam('slug');
if (!$slug && isset($_SERVER['PATH_INFO'])) {
    $slug = trim($_SERVER['PATH_INFO'], '/');
}
if (!$slug) { redirect(FRONTEND_URL . '/trips'); }

$tripResp = apiGetFull('trips.php', ['action' => 'detail', 'slug' => $slug]);
if (empty($tripResp['success'])) {
    http_response_code(404);
    $pageTitle  = 'Trip Not Found';
    $activePage = 'trips';
    require_once __DIR__ . '/layouts/head.php';
    require_once __DIR__ . '/layouts/header.php';
    echo '<div class="max-w-xl mx-auto text-center py-40">
            <div class="text-6xl mb-5">😕</div>
            <h1 class="text-2xl font-extrabold text-gray-900 mb-3">Trip Not Found</h1>
            <p class="text-gray-400 mb-6">This trip may have been removed or the link is wrong.</p>
            <a href="' . FRONTEND_URL . '/trips" class="bg-primary text-white font-bold px-6 py-3 rounded-xl">← Browse All Trips</a>
          </div>';
    require_once __DIR__ . '/layouts/footer.php';
    echo '<script src="' . FRONTEND_URL . '/js/app.js?v=<?= APP_VERSION ?>"></script></body></html>';
    exit;
}

$trip      = $tripResp['data'] ?? [];
$itinerary = $trip['itinerary'] ?? [];
$gallery       = $trip['gallery']       ?? [];
$videos        = $trip['videos']        ?? [];
$related       = $trip['related']       ?? [];
$related_blogs = $trip['related_blogs'] ?? [];

// Dynamic meta
$pageTitle   = $trip['title'] ?? 'Trip Detail';
$pageDesc    = $trip['meta_description'] ?: ($trip['short_description'] ?: 'Book ' . $pageTitle . ' with IBCC Trip.');
$ogImage     = $trip['cover_image'] ?? '';
$activePage  = 'trips';
$transparent = true;

require_once __DIR__ . '/layouts/head.php';
require_once __DIR__ . '/layouts/header.php';
?>

<!-- ===== HERO ===== -->
<div class="relative h-[70vh] min-h-[400px] max-h-[650px] overflow-hidden -mt-0">
  <img src="<?= img_url($trip['cover_image'] ?? null, 'trip') ?>"
       alt="<?= e($trip['title'] ?? '') ?>"
       class="w-full h-full object-cover">
  <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>

  <div class="absolute inset-0 flex flex-col justify-end pb-10 px-4 md:px-10 max-w-7xl mx-auto left-0 right-0">
    <!-- Breadcrumb -->
    <?php renderBreadcrumb([
      ['Home',  FRONTEND_URL . '/'],
      ['Trips', FRONTEND_URL . '/trips'],
      [$trip['country_name'] ?? 'Destinations', $trip['country_slug'] ? FRONTEND_URL . '/country/' . $trip['country_slug'] : ''],
      [$trip['title'] ?? '', ''],
    ]); ?>

    <!-- Badges -->
    <div class="flex flex-wrap gap-2 mt-3 mb-3">
      <span class="bg-secondary/90 backdrop-blur text-white text-xs font-bold px-3 py-1 rounded-full">
        <?= e($trip['trip_type'] ?? 'Tour') ?>
      </span>
      <?php if (!empty($trip['difficulty'])): ?>
      <span class="bg-white/20 backdrop-blur text-white text-xs font-bold px-3 py-1 rounded-full">
        <?= e($trip['difficulty']) ?>
      </span>
      <?php endif; ?>
      <?php if (!empty($trip['is_featured'])): ?>
      <span class="bg-yellow-400/90 text-yellow-900 text-xs font-bold px-3 py-1 rounded-full">⭐ Featured</span>
      <?php endif; ?>
    </div>

    <h1 class="text-3xl md:text-5xl font-extrabold text-white leading-tight mb-3">
      <?= e($trip['title'] ?? '') ?>
    </h1>

    <div class="flex flex-wrap gap-4 text-white/80 text-sm mt-2">
      <?php if (!empty($trip['duration_days'])): ?>
      <span class="flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <?= (int)$trip['duration_days'] ?> Days / <?= max(1, (int)$trip['duration_days'] - 1) ?> Nights
      </span>
      <?php endif; ?>
      <?php if (!empty($trip['country_name'])): ?>
      <span class="flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
        <?= e($trip['country_name']) ?>
      </span>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- ===== MAIN CONTENT ===== -->
<div class="max-w-7xl mx-auto px-4 py-10">
  <div class="flex flex-col lg:flex-row gap-10">

    <!-- ===== LEFT: Trip Details ===== -->
    <div class="flex-1 min-w-0">

      <!-- Tabs -->
      <div class="flex border-b border-gray-200 mb-6 overflow-x-auto">
        <?php
        $tabs = ['overview' => 'Overview', 'itinerary' => 'Itinerary', 'inclusions' => 'Inclusions', 'gallery' => 'Gallery & Map', 'video' => 'Videos'];
        foreach ($tabs as $id => $label):
        ?>
        <button onclick="switchTab('<?= $id ?>')" id="tab-<?= $id ?>"
                class="tab-btn whitespace-nowrap px-5 py-3 text-sm font-semibold border-b-2 transition-colors
                       <?= $id === 'overview' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-800' ?>">
          <?= $label ?>
        </button>
        <?php endforeach; ?>
      </div>

      <!-- Overview -->
      <div id="panel-overview" class="tab-panel">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-5">
          <h2 class="text-xl font-extrabold text-gray-900 mb-4">About This Trip</h2>
          <div class="text-gray-600 leading-relaxed text-sm">
            <?= nl2br(e($trip['description'] ?? 'No description available.')) ?>
          </div>
        </div>
        <?php if (!empty($trip['highlights'])): ?>
        <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100">
          <h3 class="font-extrabold text-gray-900 mb-3">✨ Highlights</h3>
          <ul class="space-y-2">
            <?php 
            $hl = is_array($trip['highlights']) ? $trip['highlights'] : (is_string($trip['highlights']) ? explode("\n", $trip['highlights']) : []);
            foreach ($hl as $h): if (trim($h)): 
            ?>
            <li class="flex items-start gap-2 text-sm text-gray-700">
              <span class="text-secondary mt-0.5 shrink-0">✓</span>
              <?= e(trim($h)) ?>
            </li>
            <?php endif; endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <!-- Route Map Image -->
        <div class="mt-5 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-extrabold text-gray-900 mb-3">📍 Route Map</h3>
          <div class="rounded-xl overflow-hidden border border-gray-100 aspect-video bg-gray-50 flex items-center justify-center relative group cursor-pointer" onclick="document.getElementById('map-lightbox-img').src=this.querySelector('img').src; document.getElementById('map-lightbox').classList.remove('hidden'); document.getElementById('map-lightbox').style.display='flex';">
             <img src="<?= img_url($trip['map_image'] ?? null, 'trip') ?>" 
                  alt="Route Map for <?= e($trip['title']) ?>" 
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
             <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
          </div>
        </div>
      </div>

      <!-- Itinerary -->
      <div id="panel-itinerary" class="tab-panel hidden">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="p-5 border-b border-gray-100">
            <h2 class="text-xl font-extrabold text-gray-900">Day-by-Day Itinerary</h2>
          </div>
          <?php if (count($itinerary) > 0): ?>
          <div class="divide-y divide-gray-100">
            <?php foreach ($itinerary as $i => $day): ?>
            <div class="p-5 hover:bg-gray-50 transition-colors">
              <button onclick="toggleAccordion('day-<?= $i ?>')"
                      class="w-full flex items-center justify-between text-left">
                <div class="flex items-center gap-3">
                  <span class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center text-sm font-extrabold shrink-0">
                    <?= (int)($day['day_number'] ?? $i+1) ?>
                  </span>
                  <div>
                    <h3 class="font-bold text-gray-900"><?= e($day['title'] ?? 'Day ' . ($i+1)) ?></h3>
                    <?php if (!empty($day['location'])): ?>
                    <p class="text-xs text-gray-400"><?= e($day['location']) ?></p>
                    <?php endif; ?>
                  </div>
                </div>
                <svg class="w-5 h-5 text-gray-400 transition-transform" id="arrow-<?= $i ?>"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
              <div id="day-<?= $i ?>" class="hidden mt-3 ml-13 pl-11 text-sm text-gray-600 leading-relaxed">
                <?= nl2br(e($day['description'] ?? '')) ?>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php else: ?>
          <p class="text-gray-400 p-6 text-sm">Detailed itinerary will be shared after booking.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Inclusions / Exclusions -->
      <div id="panel-inclusions" class="tab-panel hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="bg-green-50 rounded-2xl p-6 border border-green-100">
            <h3 class="font-extrabold text-gray-900 mb-4 flex items-center gap-2">
              <span class="w-6 h-6 bg-green-500 rounded-full text-white flex items-center justify-center text-xs">✓</span>
              Inclusions
            </h3>
            <?php if (!empty($trip['inclusions'])): ?>
            <ul class="space-y-2">
              <?php 
              $incArr = is_array($trip['inclusions']) ? $trip['inclusions'] : (is_string($trip['inclusions']) ? explode("\n", $trip['inclusions']) : []);
              foreach ($incArr as $inc): if (trim($inc)): 
              ?>
              <li class="flex items-start gap-2 text-sm text-gray-700">
                <span class="text-green-500 mt-0.5 shrink-0">✓</span><?= e(trim($inc)) ?>
              </li>
              <?php endif; endforeach; ?>
            </ul>
            <?php else: ?>
            <p class="text-gray-400 text-sm">Inclusions available on request.</p>
            <?php endif; ?>
          </div>
          <div class="bg-red-50 rounded-2xl p-6 border border-red-100">
            <h3 class="font-extrabold text-gray-900 mb-4 flex items-center gap-2">
              <span class="w-6 h-6 bg-red-500 rounded-full text-white flex items-center justify-center text-xs">✗</span>
              Exclusions
            </h3>
            <?php if (!empty($trip['exclusions'])): ?>
            <ul class="space-y-2">
              <?php 
              $excArr = is_array($trip['exclusions']) ? $trip['exclusions'] : (is_string($trip['exclusions']) ? explode("\n", $trip['exclusions']) : []);
              foreach ($excArr as $exc): if (trim($exc)): 
              ?>
              <li class="flex items-start gap-2 text-sm text-gray-700">
                <span class="text-red-500 mt-0.5 shrink-0">✗</span><?= e(trim($exc)) ?>
              </li>
              <?php endif; endforeach; ?>
            </ul>
            <?php else: ?>
            <p class="text-gray-400 text-sm">Standard exclusions apply.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div id="panel-gallery" class="tab-panel hidden">
        <?php 
        $displayGallery = $gallery;
        if (empty($displayGallery)) {
            $placeholder = img_url(null, 'trip');
            $displayGallery = [
                ['image_url' => $placeholder],
                ['image_url' => $placeholder],
                ['image_url' => $placeholder]
            ];
        }
        ?>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
          <?php foreach ($displayGallery as $img): ?>
          <a href="<?= e($img['image_url'] ?? '') ?>"
             class="lightbox-img block overflow-hidden rounded-2xl aspect-[4/3] cursor-zoom-in group">
            <img src="<?= e($img['image_url'] ?? '') ?>"
                 alt="<?= e($img['caption'] ?? $trip['title'] ?? '') ?>"
                 loading="lazy"
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
          </a>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Videos -->
      <div id="panel-video" class="tab-panel hidden">
        <?php if (count($videos) > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <?php foreach ($videos as $vid): 
            $vurl = $vid['youtube_url'] ?? '';
            if (empty($vurl)) continue;
            
            // Handle YouTube Embed Conversion
            $embedUrl = $vurl;
            $isDirect = (strpos($vurl, '.mp4') !== false || strpos($vurl, '.webm') !== false || strpos($vurl, '.ogg') !== false);
            
            if (!$isDirect && (strpos($vurl, 'youtube.com') !== false || strpos($vurl, 'youtu.be') !== false)) {
                if (strpos($vurl, 'watch?v=') !== false) {
                    $parts = parse_url($vurl);
                    parse_str($parts['query'], $query);
                    $embedUrl = 'https://www.youtube.com/embed/' . ($query['v'] ?? '');
                } elseif (strpos($vurl, 'youtu.be/') !== false) {
                    $id = substr($vurl, strrpos($vurl, '/') + 1);
                    $embedUrl = 'https://www.youtube.com/embed/' . $id;
                }
            }
          ?>
          <div class="bg-black rounded-2xl overflow-hidden shadow-sm aspect-video flex items-center justify-center">
            <?php if ($isDirect): ?>
                <video src="<?= e($vurl) ?>" controls class="w-full h-full object-contain"></video>
            <?php else: ?>
                <iframe class="w-full h-full" src="<?= e($embedUrl) ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-gray-400 text-center py-10">No videos available for this trip yet.</p>
        <?php endif; ?>
      </div>

    </div><!-- /left -->

    <!-- ===== STICKY BOOKING SIDEBAR ===== -->
    <aside class="lg:w-80 shrink-0">
      <div class="sticky top-24 space-y-4">

        <!-- Pricing Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
          <div class="bg-gradient-to-r from-primary to-blue-700 p-5 text-white">
            <p class="text-white/70 text-xs uppercase tracking-widest mb-1">Starting From</p>
            <?php if (!empty($trip['discounted_price']) && $trip['discounted_price'] < $trip['base_price']): ?>
            <span class="line-through text-white/50 text-sm"><?= formatPrice($trip['base_price']) ?></span>
            <?php endif; ?>
            <div class="text-4xl font-extrabold">
              <?= formatPrice($trip['discounted_price'] ?: $trip['base_price'] ?? 0) ?>
            </div>
            <p class="text-white/70 text-xs mt-1">per person (All inclusive)</p>
          </div>

          <div class="p-5">
            <!-- Quick Facts -->
            <div class="space-y-3 mb-5">
              <?php
              $facts = [
                ['🕐', 'Duration',   ($trip['duration_days'] ?? 1) . ' Days / ' . max(1, ($trip['duration_days'] ?? 1) - 1) . ' Nights'],
                ['🌍', 'Destination',$trip['country_name'] ?? '—'],
                ['🎯', 'Type',        $trip['trip_type'] ?? '—'],
                ['🏔', 'Difficulty',  $trip['difficulty'] ?? 'Easy'],
                ['👫', 'Min Group',    ($trip['min_group_size'] ?? 1) . ' Person'],
              ];
              foreach ($facts as [$icon, $key, $val]): ?>
              <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                <span class="text-gray-500 text-sm"><?= $icon ?> <?= e($key) ?></span>
                <span class="font-semibold text-sm text-gray-900"><?= e((string)$val) ?></span>
              </div>
              <?php endforeach; ?>
            </div>

            <!-- Embedded Booking Form -->
            <form id="booking-form" onsubmit="submitBooking(event)" class="mt-6 border-t border-gray-100 pt-5 space-y-4">
              <h4 class="font-extrabold text-gray-900 text-base mb-1">Book Your Spot</h4>
              <div class="space-y-3">
                <div>
                  <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Start Date *</label>
                  <input id="b-start" type="date" required
                         min="<?= date('Y-m-d', strtotime('+3 days')) ?>"
                         class="w-full border border-gray-200 rounded-xl py-2 px-3 text-sm focus:outline-none focus:border-primary">
                </div>
                <div>
                  <label class="block text-xs font-bold text-gray-500 uppercase mb-1">No. of People *</label>
                  <input id="b-members" type="number" min="1" max="50" value="2" required
                         class="w-full border border-gray-200 rounded-xl py-2 px-3 text-sm focus:outline-none focus:border-primary"
                         oninput="updateTotal()">
                </div>
              </div>

              <!-- Contact Info -->
              <div class="space-y-3 pt-2">
                <div>
                  <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Full Name *</label>
                  <input id="b-name" type="text" required placeholder="Enter your full name"
                         class="w-full border border-gray-200 rounded-xl py-2 px-3 text-sm focus:outline-none focus:border-primary">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                  <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Email Address *</label>
                    <input id="b-email" type="email" required placeholder="Email"
                           class="w-full border border-gray-200 rounded-xl py-2 px-3 text-sm focus:outline-none focus:border-primary">
                  </div>
                  <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Phone Number *</label>
                    <input id="b-phone" type="tel" required placeholder="Phone"
                           class="w-full border border-gray-200 rounded-xl py-2 px-3 text-sm focus:outline-none focus:border-primary">
                  </div>
                </div>
              </div>

              <div class="bg-blue-50 rounded-xl p-3 border border-blue-100 mt-2">
                <div class="flex justify-between text-xs">
                  <span class="text-gray-600">Price × Travelers (<span id="member-count">2</span>)</span>
                  <span id="total-price" class="font-semibold text-primary"><?= formatPrice(($trip['discounted_price'] ?: $trip['base_price'] ?? 0) * 2) ?></span>
                </div>
              </div>


              <!-- Special Requests -->
              <div class="pt-2">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Special Requests</label>
                <textarea id="b-notes" rows="2" placeholder="Dietary, accessibility..."
                          class="w-full border border-gray-200 rounded-xl py-2 px-3 text-sm focus:outline-none focus:border-primary resize-none"></textarea>
              </div>

              <!-- Payment Method Selection -->
              <div id="payment-selection" class="space-y-3 pt-3">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Select Payment Method</label>
                <div class="grid grid-cols-2 gap-3" id="payment-options">
                  <!-- Will be populated by JS -->
                  <div class="col-span-2 py-4 text-center border-2 border-dashed border-gray-100 rounded-2xl text-gray-400 text-xs">
                    Detecting payment providers...
                  </div>
                </div>
              </div>

              <div id="b-error" class="hidden bg-red-50 text-red-700 rounded-xl px-3 py-2 text-xs mt-3"></div>

              <button type="submit" id="b-submit"
                      class="w-full bg-secondary text-white font-extrabold py-3.5 rounded-xl
                             hover:bg-orange-600 transition-colors text-sm shadow-lg">
                Confirm Booking 🎉
              </button>
            </form>

            <a href="https://wa.me/<?= WHATSAPP_NO ?>?text=Hi!+I'm+interested+in+<?= urlencode($trip['title'] ?? 'this trip') ?>"
               target="_blank"
               class="flex items-center justify-center gap-2 w-full border-2 border-green-500 text-green-700
                      font-bold py-3 mt-3 rounded-xl hover:bg-green-50 transition-colors text-sm">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967..."/></svg>
              Chat on WhatsApp
            </a>
          </div>
        </div>

        <!-- Related Trips -->
        <?php if (count($related) > 0): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
          <h3 class="font-extrabold text-gray-900 mb-4">You Might Also Like</h3>
          <div class="space-y-3">
            <?php foreach (array_slice($related, 0, 3) as $r): ?>
            <a href="<?= FRONTEND_URL ?>/trip/<?= urlencode($r['slug'] ?? '') ?>"
               class="flex gap-3 hover:bg-gray-50 rounded-xl p-2 transition-colors">
              <img src="<?= e($r['cover_image'] ?? '') ?>"
                   class="w-14 h-14 rounded-lg object-cover shrink-0" loading="lazy">
              <div>
                <p class="font-semibold text-gray-900 text-sm line-clamp-2"><?= e($r['title'] ?? '') ?></p>
                <p class="text-primary font-bold text-sm"><?= formatPrice($r['discounted_price'] ?: $r['base_price'] ?? 0) ?></p>
              </div>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Related Blogs -->
        <?php if (count($related_blogs) > 0): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mt-4">
          <h3 class="font-extrabold text-gray-900 mb-4">Travel Stories</h3>
          <div class="space-y-3">
            <?php foreach (array_slice($related_blogs, 0, 3) as $rb): ?>
            <a href="<?= FRONTEND_URL ?>/blog/<?= urlencode($rb['slug'] ?? '') ?>"
               class="flex gap-3 hover:bg-gray-50 rounded-xl p-2 transition-colors">
               <div class="w-14 h-14 rounded-lg bg-gray-100 overflow-hidden shrink-0">
                  <img src="<?= e($rb['featured_image'] ?? '') ?>" class="w-full h-full object-cover" loading="lazy">
               </div>
               <div>
                 <p class="font-semibold text-gray-900 text-sm line-clamp-2"><?= e($rb['title'] ?? '') ?></p>
                 <p class="text-xs text-gray-400 mt-0.5"><?= date('M j, Y', strtotime($rb['created_at'])) ?></p>
               </div>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

      </div>
    </aside><!-- /sidebar -->

  </div>
</div>

<!-- ===== BOOKING MODAL ===== -->


<!-- Lightbox Modal -->
<div id="lightbox" class="fixed inset-0 bg-black/95 z-50 hidden items-center justify-center" onclick="closeLightbox()">
  <button class="absolute top-5 right-5 text-white text-4xl font-light">&times;</button>
  <img id="lightbox-img" src="" alt="" class="max-w-[90vw] max-h-[90vh] object-contain rounded-xl shadow-2xl">
</div>

<!-- Lightbox Modal for Map -->
<div id="map-lightbox" class="fixed inset-0 bg-black/95 z-[60] hidden items-center justify-center p-4" onclick="if(event.target===this) this.classList.add('hidden');">
  <button class="absolute top-5 right-5 text-white text-4xl font-light hover:text-red-500" onclick="document.getElementById('map-lightbox').classList.add('hidden'); document.getElementById('map-lightbox').style.display='';">&times;</button>
  <img id="map-lightbox-img" src="" alt="Map View" class="w-full max-w-5xl max-h-[85vh] object-contain rounded-2xl shadow-2xl bg-white/5">
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
<script src="<?= FRONTEND_URL ?>/js/app.js?v=<?= APP_VERSION ?>"></script>
<script>
const TRIP_SLUG = '<?= e($slug) ?>';
const TRIP_ID   = <?= (int)($trip['id'] ?? 0) ?>;
const PRICE_PP  = <?= (float)($trip['discounted_price'] ?: $trip['base_price'] ?? 0) ?>;

// Tabs
function switchTab(id) {
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
  document.querySelectorAll('.tab-btn').forEach(b => {
    b.className = b.className.replace(/border-primary text-primary/,'').trim() + ' border-transparent text-gray-500';
  });
  document.getElementById('panel-' + id).classList.remove('hidden');
  const btn = document.getElementById('tab-' + id);
  btn.className = btn.className.replace(/border-transparent text-gray-500/,'').trim() + ' border-primary text-primary';
}

// Accordion
function toggleAccordion(id) {
  const el = document.getElementById(id);
  const i  = id.replace('day-','');
  el.classList.toggle('hidden');
  const arr = document.getElementById('arrow-' + i);
  if (arr) arr.style.transform = el.classList.contains('hidden') ? '' : 'rotate(180deg)';
}



function updateTotal() {
  const members = parseInt(document.getElementById('b-members').value) || 1;
  document.getElementById('member-count').textContent = members;
  document.getElementById('total-price').textContent  = '₹' + (PRICE_PP * members).toLocaleString('en-IN');
}

async function submitBooking(e) {
  e.preventDefault();
  const user = await Session.init();
  if (!user) { 
    window.location.href = '<?= FRONTEND_URL ?>/login?redirect=' + encodeURIComponent(window.location.href); 
    return; 
  }

  const selectedMethod = document.querySelector('input[name="payment_method"]:checked')?.value;
  if (!selectedMethod) {
    Utils.toast('Please select a payment method', 'error');
    return;
  }

  const btn   = document.getElementById('b-submit');
  const errEl = document.getElementById('b-error');
  errEl.classList.add('hidden');
  const originalBtnText = btn.textContent;
  btn.textContent = 'Processing...'; btn.disabled = true;

  try {
    const bookingData = {
      trip_id:     TRIP_ID,
      start_date:  document.getElementById('b-start').value,
      num_members: parseInt(document.getElementById('b-members').value),
      full_name:   document.getElementById('b-name').value,
      email:      document.getElementById('b-email').value,
      phone:      document.getElementById('b-phone').value,
      special_notes: document.getElementById('b-notes').value,
      payment_method: selectedMethod
    };

    const r = await IBCC.bookings.create(bookingData);

    if (!r?.success) {
      throw new Error(r?.message || 'Booking failed');
    }

    const data = r.data;

    if (selectedMethod === 'Razorpay' && data.razorpay_order_id) {
        // Razorpay Checkout
        const options = {
            key: data.razorpay_key,
            amount: data.total_price * 100, // in paise
            currency: 'INR',
            name: 'IBCC Trip',
            description: 'Booking for ' + data.trip_title,
            order_id: data.razorpay_order_id,
            handler: async function (response) {
                btn.textContent = 'Verifying...';
                const v = await IBCC.bookings.verifyPayment({
                    razorpay_payment_id: response.razorpay_payment_id,
                    razorpay_order_id: response.razorpay_order_id,
                    razorpay_signature: response.razorpay_signature
                });
                
                if (v?.success) {
                    Utils.toast('🎉 Payment successful! Booking confirmed.');
                    setTimeout(() => window.location.href = '<?= FRONTEND_URL ?>/dashboard', 2000);
                } else {
                    Utils.toast(v?.message || 'Payment verification failed', 'error');
                    btn.textContent = originalBtnText; btn.disabled = false;
                }
            },
            prefill: {
                name: bookingData.full_name,
                email: bookingData.email,
                contact: bookingData.phone
            },
            theme: { color: '<?= COLOR_PRIMARY ?>' },
            modal: {
                ondismiss: function() {
                    btn.textContent = originalBtnText; btn.disabled = false;
                    Utils.toast('Payment cancelled', 'info');
                }
            }
        };
        const rzp = new Razorpay(options);
        rzp.open();
    } else {
        // COD or other non-online flows
        Utils.toast('🎉 Booking received! Please pay on arrival.');
        setTimeout(() => window.location.href = '<?= FRONTEND_URL ?>/dashboard.php', 2000);
    }

  } catch (err) {
    btn.textContent = originalBtnText; btn.disabled = false;
    errEl.textContent = err.message || 'Booking failed. Please try again.';
    errEl.classList.remove('hidden');
  }
}

// Lightbox
document.querySelectorAll('.lightbox-img').forEach(el => {
  el.addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('lightbox-img').src = this.href;
    const lb = document.getElementById('lightbox');
    lb.classList.remove('hidden'); lb.style.display = 'flex';
  });
});
function closeLightbox() {
  const lb = document.getElementById('lightbox');
  lb.classList.add('hidden'); lb.style.display = '';
}

document.addEventListener('DOMContentLoaded', async () => {
  const user = await Session.init();
  if (user) {
    document.getElementById('nav-login').style.display = 'none';
    document.getElementById('nav-dashboard').style.display = 'flex';
    
    // Pre-fill booking form
    const nameEl  = document.getElementById('b-name');
    const emailEl = document.getElementById('b-email');
    const phoneEl = document.getElementById('b-phone');
    if (nameEl && user.name)  nameEl.value  = user.name;
    if (emailEl && user.email) emailEl.value = user.email;
    
    // If phone is missing from session, fetch full profile
    if (!user.phone) {
        const fullUser = await IBCC.auth.me();
        if (fullUser?.success && phoneEl) {
            phoneEl.value = fullUser.data.phone || '';
        }
    } else if (phoneEl) {
        phoneEl.value = user.phone;
    }
  }

  // Setup Payments UI (Always show available methods, even if not logged in yet)
  const payOptions = document.getElementById('payment-options');
  const submitBtn  = document.getElementById('b-submit');
  const userData   = await Session.init(); // Reuse session init (cached in object)
  const activePayments = userData?.active_payments || [];
  
  if (activePayments.length === 0) {
      if (payOptions) {
          payOptions.innerHTML = `
              <div class="col-span-2 p-4 bg-red-50 text-red-600 rounded-2xl border border-red-100 text-[10px] font-bold uppercase tracking-tight text-center">
                  ⚠️ Online booking is temporarily disabled. Please contact us to book.
              </div>`;
      }
      if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
          submitBtn.textContent = 'Booking Unavailable';
      }
  } else if (payOptions) {
      payOptions.innerHTML = activePayments.map((p, idx) => `
          <label class="cursor-pointer group">
              <input type="radio" name="payment_method" value="${p}" class="hidden peer" ${idx === 0 ? 'checked' : ''}>
              <div class="p-4 border-2 border-gray-100 rounded-2xl flex flex-col items-center gap-2 group-hover:border-primary/20 peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                  <span class="text-xl">${p === 'Razorpay' ? '💳' : '💵'}</span>
                  <span class="text-[10px] font-black uppercase tracking-widest text-gray-500 peer-checked:text-primary">${p === 'Razorpay' ? 'Pay Online' : 'Cash (COD)'}</span>
              </div>
          </label>
      `).join('');
  }
});
</script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</body>
</html>
