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

$navDef  = $transparent ? 'bg-transparent' : 'bg-white shadow-md border-b border-gray-100';
$textClr = $transparent ? 'text-white' : 'text-gray-900';

function navCls(string $page, string $active): string {
    $isTrans = $GLOBALS['transparent'] ?? false;
    if ($page === $active) return 'nav-link-item active text-secondary font-bold';
    return 'nav-link-item ' . ($isTrans ? 'text-white/90' : 'text-gray-600') . ' hover:text-secondary transition-colors font-medium';
}
?>

<!-- ====== NAVBAR ====== -->
<nav id="main-navbar"
     class="fixed top-0 left-0 right-0 z-[999] transition-all duration-300 <?= $navDef ?>"
     data-transparent="<?= $transparent ? '1' : '0' ?>">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 flex items-center justify-between h-16 md:h-20">

    <a href="<?= FRONTEND_URL ?>/" class="shrink-0 flex items-center">
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
          <a href="<?= FRONTEND_URL ?>/gallery" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-gray-700 hover:bg-blue-50 hover:text-primary text-sm transition-colors border-t border-gray-50 mt-1">
            📸 Photo Gallery
          </a>
        </div>
      </li>

      <li><a href="<?= FRONTEND_URL ?>/blog"    class="<?= navCls('blog', $activePage) ?>">Blog</a></li>
      <li><a href="<?= FRONTEND_URL ?>/about"   class="<?= navCls('about', $activePage) ?>">About</a></li>
      <li><a href="<?= FRONTEND_URL ?>/contact" class="<?= navCls('contact', $activePage) ?>">Contact</a></li>
    </ul>

    <div class="hidden md:flex items-center gap-3">
      <!-- Shown when NOT logged in (JS toggles visibility) -->
      <a id="nav-login" href="<?= FRONTEND_URL ?>/login" style="display:none"
         class="auth-link <?= $transparent ? 'text-white' : 'text-gray-900' ?> text-sm font-semibold hover:text-secondary transition-colors">
        Login
      </a>

      <!-- Shown when logged in -->
      <a id="nav-dashboard" href="<?= FRONTEND_URL ?>/dashboard" style="display:none"
         class="auth-link flex items-center gap-2 <?= $transparent ? 'text-white' : 'text-gray-900' ?> text-sm font-semibold">
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
            class="md:hidden <?= $transparent ? 'text-white' : 'text-gray-900' ?> p-2 rounded-lg hover:bg-gray-100 transition-colors"
            onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>
  </div>

  <!-- Mobile Menu -->
  <div id="mobile-menu" class="hidden md:hidden bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-xl overflow-hidden animate-fadeIn">
    <div class="p-4 flex flex-col gap-1">
      <a href="<?= FRONTEND_URL ?>/"   class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-700 font-bold transition-all">🏠 Home</a>
      <a href="<?= FRONTEND_URL ?>/trips"   class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-700 font-bold transition-all">✈️ Trips</a>
      <a href="<?= FRONTEND_URL ?>/gallery" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-700 font-bold transition-all">📸 Gallery</a>
      <a href="<?= FRONTEND_URL ?>/blog"    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-700 font-bold transition-all">📝 Blog</a>
      <a href="<?= FRONTEND_URL ?>/about"   class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-700 font-bold transition-all">🏢 About</a>
      <a href="<?= FRONTEND_URL ?>/contact" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-700 font-bold transition-all">📞 Contact</a>
      
      <div id="mobile-auth-section" class="mt-4 pt-4 border-t border-gray-100">
        <a id="mobile-login" href="<?= FRONTEND_URL ?>/login" style="display:none" 
           class="block w-full bg-secondary text-white text-center py-3.5 rounded-xl font-extrabold shadow-lg shadow-secondary/20 hover:scale-[1.02] active:scale-95 transition-all">
          Login / Register
        </a>
        <div id="mobile-user" class="hidden">
          <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-2xl mb-4">
            <div id="mobile-avatar" class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-black text-xl shadow-md border-2 border-white">U</div>
            <div>
              <p id="mobile-user-name" class="font-extrabold text-gray-900 leading-tight"></p>
              <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Verified Explorer</p>
            </div>
          </div>
          <a id="mobile-dashboard-btn" href="<?= FRONTEND_URL ?>/dashboard" 
             class="flex items-center justify-center gap-2 w-full bg-primary text-white py-3.5 rounded-xl font-extrabold shadow-lg shadow-primary/20 hover:bg-blue-800 transition-all">
            🏠 Dashboard
          </a>
        </div>
      </div>
    </div>
  </div>
</nav>

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
    if (window.scrollY > 50) {
      nav.classList.add('scrolled', 'shadow-xl');
      if (isTrans) {
          nav.classList.remove('bg-transparent');
          nav.classList.add('bg-white');
      }
    } else {
      nav.classList.remove('scrolled', 'shadow-xl');
      if (isTrans) {
          nav.classList.add('bg-transparent');
          nav.classList.remove('bg-white');
      }
    }
  }

  syncAuth();
  window.addEventListener('scroll', handleScroll, { passive: true });
  handleScroll(); // Initial check
}());
</script>

<style>
  #main-navbar.scrolled { background-color: white !important; height: 70px !important; }
  #main-navbar.scrolled .logo-text { color: #111827 !important; }
  #main-navbar.scrolled .nav-link-item { color: #4B5563 !important; }
  #main-navbar.scrolled .nav-link-item:hover,
  #main-navbar.scrolled .nav-link-item.active { color: <?= COLOR_SECONDARY ?> !important; }
  #main-navbar.scrolled .auth-link { color: #111827 !important; }
  #main-navbar.scrolled .auth-link:hover { color: <?= COLOR_SECONDARY ?> !important; }
  
  /* Ensure logo icon is visible if header is primary */
  #main-navbar.bg-primary .logo-icon { background-color: white !important; }
  #main-navbar.bg-primary .logo-icon svg { fill: <?= COLOR_PRIMARY ?> !important; stroke: <?= COLOR_PRIMARY ?> !important; }
  #main-navbar.bg-primary .logo-text { color: white !important; }
</style>
