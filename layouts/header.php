<?php
// frontend/layouts/header.php
// ─────────────────────────────────────────────────────────────
// Global sticky navbar — include at the top of every page body
// Variables expected:
//   $activePage  string  — e.g. 'home', 'trips', 'blog', 'contact', 'about'
//   $transparent bool    — if true, starts transparent (for hero pages)
// ─────────────────────────────────────────────────────────────

if (!defined('FRONTEND_URL')) require_once __DIR__ . '/../components/config.php';

$activePage  = $activePage  ?? '';
$transparent = $transparent ?? false;

$navDef  = $transparent ? 'bg-transparent' : 'bg-primary shadow-lg';
$textClr = 'text-white';

function navCls(string $page, string $active): string {
    return $page === $active
        ? 'text-secondary font-semibold'
        : 'text-white/90 hover:text-secondary transition-colors font-medium';
}
?>

<!-- ====== NAVBAR ====== -->
<nav id="main-navbar"
     class="fixed top-0 left-0 right-0 z-[999] transition-all duration-300 <?= $navDef ?>"
     data-transparent="<?= $transparent ? '1' : '0' ?>">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 flex items-center justify-between h-16 md:h-20">

    <a href="<?= FRONTEND_URL ?>/" class="shrink-0">
      <?= renderLogo() ?>
    </a>

    <!-- Desktop Nav Links -->
    <ul class="hidden md:flex items-center gap-6 text-sm">
      <li><a href="<?= FRONTEND_URL ?>/"   class="<?= navCls('home', $activePage) ?>">Home</a></li>
      <li><a href="<?= FRONTEND_URL ?>/trips"   class="<?= navCls('trips', $activePage) ?>">Trips</a></li>

      <!-- Destinations Dropdown -->
      <li class="relative group">
        <button class="<?= navCls('destinations', $activePage) ?> flex items-center gap-1 cursor-pointer">
          Destinations
          <svg class="w-3.5 h-3.5 opacity-70 group-hover:rotate-180 transition-transform"
               fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <div class="absolute top-full left-0 mt-3 w-52 bg-white rounded-2xl shadow-2xl
                    border border-gray-100 p-3 opacity-0 invisible
                    group-hover:opacity-100 group-hover:visible transition-all duration-200">
          <a href="<?= FRONTEND_URL ?>/country" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-gray-700 hover:bg-blue-50 hover:text-primary text-sm transition-colors">
            🌍 Countries
          </a>
          <a href="<?= FRONTEND_URL ?>/state"   class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-gray-700 hover:bg-blue-50 hover:text-primary text-sm transition-colors">
            🗺 States
          </a>
          <a href="<?= FRONTEND_URL ?>/city"    class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-gray-700 hover:bg-blue-50 hover:text-primary text-sm transition-colors">
            🏙 Cities
          </a>
          <a href="<?= FRONTEND_URL ?>/place"   class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-gray-700 hover:bg-blue-50 hover:text-primary text-sm transition-colors">
            📍 Places
          </a>
        </div>
      </li>

      <li><a href="<?= FRONTEND_URL ?>/blog"    class="<?= navCls('blog', $activePage) ?>">Blog</a></li>
      <li><a href="<?= FRONTEND_URL ?>/about"   class="<?= navCls('about', $activePage) ?>">About</a></li>
      <li><a href="<?= FRONTEND_URL ?>/contact" class="<?= navCls('contact', $activePage) ?>">Contact</a></li>
    </ul>

    <!-- Right-side Auth Buttons -->
    <div class="hidden md:flex items-center gap-3">
      <!-- Shown when NOT logged in (JS toggles visibility) -->
      <a id="nav-login" href="<?= FRONTEND_URL ?>/login" style="display:none"
         class="text-white text-sm font-semibold hover:text-secondary transition-colors">
        Login
      </a>

      <!-- Shown when logged in -->
      <a id="nav-dashboard" href="<?= FRONTEND_URL ?>/dashboard" style="display:none"
         class="flex items-center gap-2 text-white text-sm font-semibold">
        <span class="w-8 h-8 rounded-full bg-secondary text-white flex items-center justify-center text-xs font-extrabold"
              id="nav-avatar">U</span>
        <span id="nav-user-name" class="hidden lg:inline"></span>
      </a>

      <a href="<?= FRONTEND_URL ?>/trips"
         class="bg-secondary text-white text-sm font-extrabold px-5 py-2.5 rounded-xl
                hover:bg-orange-600 transition-colors shadow-lg whitespace-nowrap">
        Explore Trips
      </a>
    </div>

    <!-- Mobile Menu Toggle -->
    <button id="mobile-toggle"
            class="md:hidden text-white p-2 rounded-lg hover:bg-white/10 transition-colors"
            onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>
  </div>

  <!-- Mobile Menu -->
  <div id="mobile-menu" class="hidden md:hidden bg-primary border-t border-white/10 px-4 pb-4">
    <a href="<?= FRONTEND_URL ?>/"   class="block text-white py-2.5 border-b border-white/10 text-sm">🏠 Home</a>
    <a href="<?= FRONTEND_URL ?>/trips"   class="block text-white py-2.5 border-b border-white/10 text-sm">✈️ Trips</a>
    <a href="<?= FRONTEND_URL ?>/country" class="block text-white py-2.5 border-b border-white/10 text-sm">🌍 Countries</a>
    <a href="<?= FRONTEND_URL ?>/state"   class="block text-white py-2.5 border-b border-white/10 text-sm">🗺 States</a>
    <a href="<?= FRONTEND_URL ?>/city"    class="block text-white py-2.5 border-b border-white/10 text-sm">🏙 Cities</a>
    <a href="<?= FRONTEND_URL ?>/blog"    class="block text-white py-2.5 border-b border-white/10 text-sm">📝 Blog</a>
    <a href="<?= FRONTEND_URL ?>/about"   class="block text-white py-2.5 border-b border-white/10 text-sm">🏢 About</a>
    <a href="<?= FRONTEND_URL ?>/contact" class="block text-white py-2.5 border-b border-white/10 text-sm">📞 Contact</a>
    <a id="mobile-login" href="<?= FRONTEND_URL ?>/login" style="display:none" class="block mt-3 bg-secondary text-white text-center py-3 rounded-xl font-extrabold text-sm">
      Login / Register
    </a>
    <div id="mobile-user" class="hidden mt-3 pt-3 border-t border-white/10">
      <div class="flex items-center gap-3 mb-4">
        <div id="mobile-avatar" class="w-10 h-10 rounded-full bg-secondary text-white flex items-center justify-center font-extrabold">U</div>
        <div class="text-white">
          <p id="mobile-user-name" class="font-bold text-sm"></p>
          <p class="text-xs text-white/60">Verified Explorer</p>
        </div>
      </div>
      <a id="mobile-dashboard-btn" href="<?= FRONTEND_URL ?>/dashboard" class="block text-white py-2 text-sm font-semibold">🏠 Dashboard</a>
    </div>
  </div>
</nav>

<!-- Spacer for non-transparent header (hero pages handle this themselves) -->
<?php if (!$transparent): ?>
<div class="h-16 md:h-20"></div>
<?php endif; ?>

<script src="<?= FRONTEND_URL ?>/js/app.js?v=<?= APP_VERSION ?>"></script>
<script>
// Global Session Sync & Scroll Effects
(function() {
  const nav       = document.getElementById('main-navbar');
  const isTrans   = nav.dataset.transparent === '1';

  // Auth Sync
  async function syncAuth() {
    const user = await Session.init();
    const loginBtn = document.getElementById('nav-login');
    const dashBtn  = document.getElementById('nav-dashboard');
    const mLogin   = document.getElementById('mobile-login');
    const mUser    = document.getElementById('mobile-user');

    if (user && user.logged_in) {
        // Desktop
        const avatar   = document.getElementById('nav-avatar');
        const name     = document.getElementById('nav-user-name');
        
        if(loginBtn) loginBtn.style.display = 'none';
        if(dashBtn) dashBtn.style.display = 'flex';
        if(avatar) avatar.textContent = user.name[0].toUpperCase();
        if(name) name.textContent = user.name.split(' ')[0];

        // Mobile
        const mName  = document.getElementById('mobile-user-name');
        const mAvatar= document.getElementById('mobile-avatar');
        
        if(mLogin) mLogin.style.display = 'none';
        if(mUser) mUser.classList.remove('hidden');
        if(mName) mName.textContent = user.name;
        if(mAvatar) mAvatar.textContent = user.name[0].toUpperCase();
    } else {
        // NOT logged in
        if(loginBtn) loginBtn.style.display = 'inline-block';
        if(dashBtn) dashBtn.style.display = 'none';
        
        if(mLogin) mLogin.style.display = 'block';
        if(mUser) mUser.classList.add('hidden');
    }
  }

  // Scroll Handler
  function handleScroll() {
    if (!isTrans) return;
    if (window.scrollY > 80) {
      nav.classList.remove('bg-transparent');
      nav.classList.add('bg-primary', 'shadow-lg');
    } else {
      nav.classList.add('bg-transparent');
      nav.classList.remove('bg-primary', 'shadow-lg');
    }
  }

  syncAuth();
  if (isTrans) {
    window.addEventListener('scroll', handleScroll, { passive: true });
  }
}());
</script>
