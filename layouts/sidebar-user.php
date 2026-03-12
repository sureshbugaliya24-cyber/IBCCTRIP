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

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
  <div class="p-6 border-b border-gray-100 text-center">
    <div class="w-20 h-20 rounded-2xl bg-primary text-white flex items-center justify-center text-3xl font-extrabold mx-auto mb-3 shadow-lg" id="side-avatar">
      U
    </div>
    <h3 class="font-extrabold text-gray-900" id="side-name">User Name</h3>
    <p class="text-xs text-gray-400 mt-1" id="side-email">user@example.com</p>
  </div>
  
  <nav class="p-3 space-y-1">
    <button onclick="switchDashTab('overview')" 
            class="dash-sidebar-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold <?= sideCls('overview', $activeTab) ?>">
      📊 Overview
    </button>
    <button onclick="switchDashTab('bookings')" 
            class="dash-sidebar-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold <?= sideCls('bookings', $activeTab) ?>">
      ✈️ My Bookings
    </button>
    <button onclick="switchDashTab('profile')" 
            class="dash-sidebar-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold <?= sideCls('profile', $activeTab) ?>">
      👤 My Profile
    </button>
    <div class="pt-2 mt-2 border-t border-gray-100">
      <button onclick="Session.logout()" 
              class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-red-500 hover:bg-red-50 transition-all">
        🚪 Logout
      </button>
    </div>
  </nav>
</div>
