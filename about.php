<?php
// frontend/about.php — About Us Page
// ─────────────────────────────────────────────────────────────

require_once __DIR__ . '/components/config.php';
require_once __DIR__ . '/components/helpers.php';

$pageTitle = 'About Us';
$pageDesc  = 'Learn more about IBCC Trip, India\'s leading premium travel agency since 2010.';
$activePage = 'about';

require_once __DIR__ . '/layouts/head.php';
require_once __DIR__ . '/layouts/header.php';
?>

<div class="bg-gradient-to-r from-primary to-blue-800 py-24 text-center text-white">
  <div class="max-w-3xl mx-auto px-4">
    <h1 class="text-4xl md:text-6xl font-extrabold mb-6">Our Journey</h1>
    <p class="text-white/80 text-lg md:text-xl leading-relaxed">
      Crafting extraordinary travel experiences since 2010. 
      Awarded India's most trusted premium travel agency.
    </p>
  </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-20">
  <div class="flex flex-col lg:flex-row items-center gap-16 mb-24">
    <div class="lg:w-1/2">
      <div class="relative">
        <img src="https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=800&q=80" 
             class="rounded-3xl shadow-2xl relative z-10" alt="About IBCC Trip">
        <div class="absolute -top-6 -left-6 w-32 h-32 bg-secondary rounded-3xl -z-0 opacity-20"></div>
        <div class="absolute -bottom-6 -right-6 w-48 h-48 bg-primary rounded-3xl -z-0 opacity-10"></div>
      </div>
    </div>
    <div class="lg:w-1/2">
      <p class="text-secondary font-bold uppercase tracking-widest text-sm mb-3">Who we are</p>
      <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-6Leading-tight">We turn travel dreams into <span class="text-primary">unforgettable realities.</span></h2>
      <div class="space-y-4 text-gray-500 text-sm md:text-base leading-relaxed">
        <p>IBCC Trip started with a simple vision: to make high-end, premium travel accessible and hassle-free for Indian travelers. Today, with a company scale of over ₹500 Crores, we stand as a beacon of trust and quality in the travel industry.</p>
        <p>We don't just sell tour packages; we curate memories. Our team of expert travel designers works around the clock to ensure every itinerary is balanced, unique, and filled with local experiences that you won't find on a standard brochure.</p>
        <p>Whether it's a luxury honeymoon in Bali, a spiritual journey through Rajasthan, or an adrenaline-pumping trek in Nepal, we handle everything from flights and hotels to local transport and 24/7 on-ground support.</p>
      </div>
      <div class="grid grid-cols-2 gap-8 mt-10">
        <div>
          <h4 class="text-4xl font-black text-primary mb-1">15K+</h4>
          <p class="text-gray-400 text-xs font-bold uppercase">Happy Travelers</p>
        </div>
        <div>
          <h4 class="text-4xl font-black text-secondary mb-1">500+</h4>
          <p class="text-gray-400 text-xs font-bold uppercase">Curated Tours</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Core Values -->
  <div class="bg-gray-50 rounded-[3rem] p-10 md:p-16 mb-24">
    <div class="text-center mb-12">
      <h2 class="text-3xl font-extrabold text-gray-900">Our Core <span class="text-primary">Values</span></h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
      <div class="text-center">
        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-3xl mx-auto mb-5 shadow-sm">💎</div>
        <h3 class="font-extrabold text-gray-900 text-xl mb-3">Uncompromising Quality</h3>
        <p class="text-gray-500 text-sm leading-relaxed">We partner only with the best hotels and service providers to ensure your comfort and safety.</p>
      </div>
      <div class="text-center">
        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-3xl mx-auto mb-5 shadow-sm">🤝</div>
        <h3 class="font-extrabold text-gray-900 text-xl mb-3">Trust & Transparency</h3>
        <p class="text-gray-500 text-sm leading-relaxed">No hidden costs. No surprise charges. What you see is what you get, every single time.</p>
      </div>
      <div class="text-center">
        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-3xl mx-auto mb-5 shadow-sm">🌍</div>
        <h3 class="font-extrabold text-gray-900 text-xl mb-3">Global Expertise</h3>
        <p class="text-gray-500 text-sm leading-relaxed">Our local experts in 50+ countries ensure you get an authentic experience wherever you go.</p>
      </div>
    </div>
  </div>

  <!-- CTA -->
  <div class="bg-primary rounded-3xl p-12 text-center text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-secondary/20 to-transparent pointer-events-none"></div>
    <div class="relative z-10">
      <h2 class="text-3xl font-extrabold mb-4">Start Your Story With Us</h2>
      <p class="text-white/70 mb-8 max-w-xl mx-auto">Join thousands of satisfied travelers who discovered the world with IBCC Trip.</p>
      <a href="<?= FRONTEND_URL ?>/trips.php" 
         class="inline-block bg-secondary text-white font-extrabold px-10 py-4 rounded-2xl hover:bg-orange-600 transition-all shadow-xl shadow-secondary/20">
        Explore Packages
      </a>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
<script src="<?= FRONTEND_URL ?>/js/app.js?v=<?= APP_VERSION ?>"></script>
</body>
</html>
