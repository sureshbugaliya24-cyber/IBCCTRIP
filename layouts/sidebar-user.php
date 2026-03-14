<?php
// frontend/layouts/sidebar-user.php
// ─────────────────────────────────────────────────────────────

if (!defined('FRONTEND_URL')) require_once __DIR__ . '/../components/config.php';

$activeTab = $activeTab ?? 'overview';

function sideCls(string $tab, string $active): string {
    return $tab === $active
        ? 'bg-primary text-white shadow-lg shadow-primary/20'
        : 'text-gray-500 hover:bg-gray-50 hover:text-primary transition-all';
}
?>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden lg:sticky lg:top-24">
  <!-- Desktop Header (Hidden on Mobile) -->
  <div class="hidden lg:block p-6 border-b border-gray-100 text-center">
    <div class="w-20 h-20 rounded-2xl bg-primary text-white flex items-center justify-center text-3xl font-extrabold mx-auto mb-3 shadow-lg" id="side-avatar">
      U
    </div>
    <h3 class="font-extrabold text-gray-900" id="side-name">User Name</h3>
    <p class="text-xs text-gray-400 mt-1" id="side-email">user@example.com</p>
  </div>
  
  <nav class="p-2 lg:p-3 flex lg:flex-col gap-2 overflow-x-auto no-scrollbar whitespace-nowrap lg:whitespace-normal">
    <button onclick="switchDashTab('overview')" 
            class="dash-sidebar-btn flex-none lg:w-full flex items-center gap-3 px-5 lg:px-4 py-3 rounded-xl text-sm font-bold transition-all <?= sideCls('overview', $activeTab) ?>">
      <span class="text-lg lg:text-base">📊</span> <span class="lg:inline">Overview</span>
    </button>
    <button onclick="switchDashTab('bookings')" 
            class="dash-sidebar-btn flex-none lg:w-full flex items-center gap-3 px-5 lg:px-4 py-3 rounded-xl text-sm font-bold transition-all <?= sideCls('bookings', $activeTab) ?>">
      <span class="text-lg lg:text-base">✈️</span> <span class="lg:inline">My Bookings</span>
    </button>
    <button onclick="switchDashTab('profile')" 
            class="dash-sidebar-btn flex-none lg:w-full flex items-center gap-3 px-5 lg:px-4 py-3 rounded-xl text-sm font-bold transition-all <?= sideCls('profile', $activeTab) ?>">
      <span class="text-lg lg:text-base">👤</span> <span class="lg:inline">My Profile</span>
    </button>
    
    <div class="lg:pt-2 lg:mt-2 lg:border-t border-gray-100 flex-none ml-auto lg:ml-0">
      <button onclick="Session.logout()" 
              class="flex items-center gap-3 px-5 lg:px-4 py-3 rounded-xl text-sm font-bold text-red-500 hover:bg-red-50 transition-all">
        <span class="text-lg lg:text-base">🚪</span> <span class="hidden lg:inline">Logout</span>
      </button>
    </div>
  </nav>
</div>

<style>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
