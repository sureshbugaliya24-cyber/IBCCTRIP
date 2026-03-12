<?php
// frontend/index.php — Homepage
// ─────────────────────────────────────────────────────────────

require_once __DIR__ . '/components/config.php';
require_once __DIR__ . '/components/helpers.php';
require_once __DIR__ . '/components/trip-card.php';
require_once __DIR__ . '/components/blog-card.php';
require_once __DIR__ . '/components/country-card.php';

// Fetch data server-side
$featuredTrips = apiGet('trips.php', ['action' => 'featured', 'limit' => 6]);
$countries     = apiGet('locations.php', ['action' => 'countries', 'limit' => 8]);
$latestBlogs   = apiGet('blogs.php', ['action' => 'recent', 'limit' => 3]);

// Page meta
$pageTitle   = 'Premium Travel Packages in India & Abroad';
$pageDesc    = 'IBCC Trip – India\'s most trusted travel agency. Book curated domestic & international tour packages. Rajasthan, Dubai, Thailand, Bali & 50+ destinations.';
$pageKeywords= 'travel agency india, tour packages, ibcc trip, rajasthan tour, dubai packages, thailand honeymoon, international travel';
$transparent  = true;
$activePage   = 'home';

require_once __DIR__ . '/layouts/head.php';
require_once __DIR__ . '/layouts/header.php';
?>

<!-- ===== HERO SECTION ===== -->
<section class="relative min-h-screen flex items-center overflow-hidden">
  <!-- Background video/image -->
  <div class="absolute inset-0 z-0">
    <img src="https://images.unsplash.com/photo-1506929562872-bb421503ef21?w=1600&q=85"
         alt="IBCC Trip Hero" class="w-full h-full object-cover">
    <div class="hero-overlay absolute inset-0"></div>
  </div>

  <!-- Animated particles (CSS-only) -->
  <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
    <div style="position:absolute;width:300px;height:300px;background:radial-gradient(circle,rgba(255,107,0,.3) 0%,transparent 70%);top:10%;right:15%;animation:fadeIn 3s ease infinite alternate;"></div>
    <div style="position:absolute;width:200px;height:200px;background:radial-gradient(circle,rgba(11,61,145,.4) 0%,transparent 70%);bottom:20%;left:10%;animation:fadeIn 4s ease 1s infinite alternate;"></div>
  </div>

  <div class="relative z-10 max-w-7xl mx-auto px-4 pt-20 pb-32 text-white">
    <div class="max-w-3xl fade-up">
      <div class="inline-flex items-center gap-2 glass rounded-full px-4 py-2 text-sm mb-6">
        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
        <span>⭐ Rated #1 Travel Agency in India 2025</span>
      </div>
      <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold leading-tight mb-6">
        Discover the World<br>
        <span class="text-secondary">Your Way</span>
      </h1>
      <p class="text-white/80 text-lg md:text-xl mb-10 leading-relaxed">
        15,000+ travelers trust IBCC Trip for handcrafted domestic & international tours.
        From ₹9,999 per person. No hidden charges.
      </p>

      <!-- Search Bar -->
      <div class="bg-white rounded-2xl p-2 shadow-2xl flex flex-col sm:flex-row gap-2">
        <input type="text" id="hero-search"
               placeholder="Search destinations, trips, countries..."
               class="flex-1 px-5 py-3.5 text-gray-900 text-sm focus:outline-none rounded-xl bg-transparent">
        <a id="hero-search-btn" href="<?= FRONTEND_URL ?>/trips"
           class="bg-secondary text-white font-extrabold px-7 py-3.5 rounded-xl text-sm
                  hover:bg-orange-600 transition-colors whitespace-nowrap text-center">
          Search Trips →
        </a>
      </div>

      <!-- Live suggestions dropdown -->
      <div id="hero-suggestions"
           class="hidden mt-2 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden text-gray-800 text-sm max-h-60 overflow-y-auto"></div>

      <!-- Quick Filters -->
      <div class="flex flex-wrap gap-2 mt-5">
        <?php
        $quickTypes = ['Domestic','International','Adventure','Luxury','Honeymoon'];
        foreach ($quickTypes as $qt):
        ?>
        <a href="<?= FRONTEND_URL ?>/trips?type=<?= urlencode($qt) ?>"
           class="glass text-white text-xs font-semibold px-4 py-2 rounded-full
                  hover:bg-secondary/70 transition-colors">
          <?= e($qt) ?>
        </a>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Stats Floating Card -->
    <div class="absolute bottom-8 left-4 right-4 md:left-auto md:right-12 md:bottom-16">
      <div class="glass rounded-2xl p-5 grid grid-cols-3 gap-0 max-w-sm mx-auto md:mx-0 text-center">
        <div class="border-r border-white/20">
          <p class="font-extrabold text-2xl text-secondary">15K+</p>
          <p class="text-white/70 text-xs mt-1">Travelers</p>
        </div>
        <div class="border-r border-white/20">
          <p class="font-extrabold text-2xl text-secondary">500+</p>
          <p class="text-white/70 text-xs mt-1">Packages</p>
        </div>
        <div>
          <p class="font-extrabold text-2xl text-secondary">50+</p>
          <p class="text-white/70 text-xs mt-1">Countries</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== FEATURED TRIPS ===== -->
<section class="py-20 max-w-7xl mx-auto px-4">
  <div class="flex items-center justify-between mb-10">
    <div>
      <p class="text-secondary font-semibold text-sm uppercase tracking-widest mb-2">Curated For You</p>
      <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">
        Featured <span class="text-primary">Trip Packages</span>
      </h2>
    </div>
    <a href="<?= FRONTEND_URL ?>/trips"
       class="hidden md:flex items-center gap-1 text-primary font-bold hover:text-secondary transition-colors">
      View All Trips
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </a>
  </div>

  <?php if (count($featuredTrips) > 0): ?>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($featuredTrips as $trip): renderTripCard($trip); endforeach; ?>
  </div>
  <?php else: ?>
  <div class="text-center py-20 text-gray-400">No featured trips available yet.</div>
  <?php endif; ?>

  <div class="text-center mt-8 md:hidden">
    <a href="<?= FRONTEND_URL ?>/trips" class="inline-flex items-center gap-2 text-primary font-bold text-sm hover:text-secondary transition-colors">
      View All Packages →
    </a>
  </div>
</section>

<!-- ===== DESTINATIONS / COUNTRIES ===== -->
<section class="bg-primary py-20">
  <div class="max-w-7xl mx-auto px-4">
    <div class="text-center mb-10">
      <p class="text-secondary font-semibold text-sm uppercase tracking-widest mb-2">Explore The Globe</p>
      <h2 class="text-3xl md:text-4xl font-extrabold text-white">
        Top <span class="text-secondary">Destinations</span> We Cover
      </h2>
    </div>

    <?php if (count($countries) > 0): ?>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
      <?php foreach ($countries as $c): renderCountryCard($c); endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="text-center mt-8">
      <a href="<?= FRONTEND_URL ?>/country"
         class="inline-flex items-center gap-2 bg-secondary text-white font-extrabold
                px-8 py-4 rounded-2xl hover:bg-orange-600 transition-colors shadow-xl">
        View All Destinations
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>
  </div>
</section>

<!-- ===== HOW IT WORKS ===== -->
<section class="py-20 bg-accent">
  <div class="max-w-7xl mx-auto px-4">
    <div class="text-center mb-12">
      <p class="text-secondary font-semibold text-sm uppercase tracking-widest mb-2">Simple Process</p>
      <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">Book in <span class="text-primary">3 Easy Steps</span></h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <?php
      $steps = [
        ['🔍', 'Discover Your Trip', 'Browse 500+ curated packages. Filter by destination, budget, duration, and type.'],
        ['📋', 'Customize & Book',   'Fill a simple form, pick your dates and group size. No hidden charges.'],
        ['✈️', 'Travel & Explore',   'Receive your e-ticket, itinerary, and 24/7 travel support on your phone.'],
      ];
      foreach ($steps as $i => $s): ?>
      <div class="relative text-center">
        <div class="w-20 h-20 rounded-2xl bg-white shadow-lg flex items-center justify-center text-4xl mx-auto mb-5 border border-gray-100">
          <?= $s[0] ?>
        </div>
        <div class="absolute top-7 left-1/2 md:left-3/4 hidden md:block
                    <?= $i === 2 ? 'opacity-0' : '' ?>">
          <div class="w-16 h-0.5 bg-gray-200 ml-4 relative">
            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-secondary"></div>
          </div>
        </div>
        <div class="w-8 h-8 rounded-full bg-primary text-white text-sm font-extrabold flex items-center justify-center mx-auto -mt-4 mb-3 relative z-10">
          <?= $i + 1 ?>
        </div>
        <h3 class="font-extrabold text-gray-900 text-xl mb-2"><?= e($s[1]) ?></h3>
        <p class="text-gray-500 text-sm leading-relaxed"><?= e($s[2]) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== TESTIMONIALS ===== -->
<section class="py-20">
  <div class="max-w-7xl mx-auto px-4">
    <div class="text-center mb-10">
      <p class="text-secondary font-semibold text-sm uppercase tracking-widest mb-2">Real Stories</p>
      <h2 class="text-3xl font-extrabold text-gray-900">What Our <span class="text-primary">Travelers Say</span></h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php
      $testimonials = [
        ['Rajesh Sharma', 'Delhi', '⭐⭐⭐⭐⭐', 'Booked Rajasthan trip. Absolutely flawless experience — hotel, transport, guides, everything was top-notch! IBCC Trip never disappoints.', 'RS'],
        ['Priya Mehta',   'Mumbai', '⭐⭐⭐⭐⭐', 'Our Bali honeymoon was a dream come true. The itinerary was perfect, with just the right balance of adventure and relaxation.', 'PM'],
        ['Amit Patel',    'Gujarat', '⭐⭐⭐⭐⭐', 'Third trip with IBCC Trip. Dubai package was amazing value. Will keep coming back — best travel agency in India!', 'AP'],
      ];
      foreach ($testimonials as $t): ?>
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl transition-shadow">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-extrabold text-lg shrink-0">
            <?= $t[4] ?>
          </div>
          <div>
            <p class="font-extrabold text-gray-900"><?= e($t[0]) ?></p>
            <p class="text-gray-400 text-xs"><?= e($t[1]) ?></p>
          </div>
          <div class="ml-auto text-sm"><?= $t[2] ?></div>
        </div>
        <p class="text-gray-600 text-sm leading-relaxed italic">"<?= e($t[3]) ?>"</p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== LATEST BLOGS ===== -->
<?php if (count($latestBlogs) > 0): ?>
<section class="py-20 bg-accent">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex items-center justify-between mb-10">
      <div>
        <p class="text-secondary font-semibold text-sm uppercase tracking-widest mb-2">Travel Inspiration</p>
        <h2 class="text-3xl font-extrabold text-gray-900">Latest from Our <span class="text-primary">Blog</span></h2>
      </div>
      <a href="<?= FRONTEND_URL ?>/blog" class="hidden md:flex items-center gap-1 text-primary font-bold hover:text-secondary transition-colors text-sm">
        Read All Articles
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php foreach ($latestBlogs as $blog): renderBlogCard($blog, 'normal'); endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===== CTA SECTION ===== -->
<section class="py-20 bg-gradient-to-r from-primary to-blue-800 text-white text-center">
  <div class="max-w-3xl mx-auto px-4">
    <div class="text-5xl mb-5">✈️</div>
    <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Ready for Your Next Adventure?</h2>
    <p class="text-white/80 text-lg mb-10">
      Get a customized quote within 24 hours. No commitment required.
    </p>
    <div class="flex justify-center flex-wrap gap-4">
      <a href="<?= FRONTEND_URL ?>/trips"
         class="bg-secondary text-white font-extrabold px-8 py-4 rounded-2xl
                hover:bg-orange-600 transition-colors shadow-xl">
        Explore Packages
      </a>
      <a href="<?= FRONTEND_URL ?>/contact"
         class="border-2 border-white text-white font-extrabold px-8 py-4 rounded-2xl
                hover:bg-white/10 transition-colors">
        Get Free Quote
      </a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>

<!-- App JS => session + nav update + search -->
<script src="<?= FRONTEND_URL ?>/js/app.js?v=<?= APP_VERSION ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', async function() {
  // Update nav auth state
  const user = await Session.init();
  if (user) {
    document.getElementById('nav-login').style.display = 'none';
    document.getElementById('nav-dashboard').style.display = 'flex';
    document.getElementById('nav-logout').style.display = 'block';
    document.getElementById('nav-avatar').textContent = user.name[0];
    const nn = document.getElementById('nav-user-name');
    if (nn) nn.textContent = user.name.split(' ')[0];
  }

  // Hero live search
  const searchInput = document.getElementById('hero-search');
  const btn         = document.getElementById('hero-search-btn');
  const suggestions = document.getElementById('hero-suggestions');
  let debounce;

  searchInput.addEventListener('input', function() {
    clearTimeout(debounce);
    const q = this.value.trim();
    if (q.length < 2) { suggestions.classList.add('hidden'); return; }
    debounce = setTimeout(async () => {
      const r = await IBCC.trips.search(q);
      if (!r?.data?.length) { suggestions.classList.add('hidden'); return; }
      suggestions.innerHTML = r.data.map(t => `
        <a href="<?= FRONTEND_URL ?>/trip.php/${encodeURIComponent(t.slug)}"
           class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 transition-colors border-b border-gray-50 last:border-0">
          <img src="${t.cover_image||''}" class="w-10 h-10 rounded-lg object-cover shrink-0">
          <div>
            <p class="font-semibold text-gray-900 text-sm">${t.title}</p>
            <p class="text-xs text-gray-400">${t.duration_days} Days • ${t.trip_type}</p>
          </div>
          <span class="ml-auto font-bold text-primary text-sm">₹${Number(t.discounted_price||t.base_price).toLocaleString('en-IN')}</span>
        </a>`).join('');
      suggestions.classList.remove('hidden');
    }, 350);
  });

  document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target)) suggestions.classList.add('hidden');
  });

  btn.addEventListener('click', function(e) {
    const q = searchInput.value.trim();
    if (q) { e.preventDefault(); window.location.href = '<?= FRONTEND_URL ?>/trips?q=' + encodeURIComponent(q); }
  });
});
</script>
</body>
</html>
