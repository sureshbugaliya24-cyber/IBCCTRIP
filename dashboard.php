<?php
// frontend/dashboard.php — User Dashboard
// ─────────────────────────────────────────────────────────────

require_once __DIR__ . '/components/config.php';
require_once __DIR__ . '/components/helpers.php';

$pageTitle = 'My Dashboard';
$activePage = 'dashboard';
$transparent = false;

require_once __DIR__ . '/layouts/head.php';
require_once __DIR__ . '/layouts/header.php';
?>
<style>
  @media print {
    @page { size: auto; margin: 0mm; }
    html, body { 
      height: 100% !important; 
      margin: 0 !important; 
      padding: 0 !important; 
      overflow: visible !important;
      -webkit-print-color-adjust: exact; 
      print-color-adjust: exact;
    }
    body * { visibility: hidden; }
    #invoice-modal, #invoice-modal * { visibility: visible; }
    #invoice-modal { 
      position: absolute !important; 
      left: 0 !important; 
      top: 0 !important; 
      width: 100% !important; 
      height: auto !important;
      background: white !important;
      display: block !important;
      padding: 0 !important;
      margin: 0 !important;
    }
    #invoice-content {
      box-shadow: none !important;
      border: none !important;
      width: 100% !important;
      max-width: none !important;
      max-height: none !important;
      overflow: visible !important;
      margin: 0 !important;
      padding: 2cm !important; /* Standard print margin */
    }
    button[onclick="closeInvoice()"], button[onclick="window.print()"], .backdrop-blur-sm { display: none !important; }
    .bg-black\/60 { background: white !important; }
  }
</style>

<div class="max-w-7xl mx-auto px-4 py-6 md:py-10">
  <div class="flex flex-col lg:flex-row gap-6 md:gap-8">
    
    <!-- Sidebar -->
    <aside class="lg:w-72 shrink-0">
      <?php require_once __DIR__ . '/layouts/sidebar-user.php'; ?>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 min-w-0">
      
      <!-- Loading State -->
      <div id="dash-loader" class="flex items-center justify-center py-40">
        <div class="spinner"></div>
      </div>

      <!-- Overview Tab -->
      <div id="tab-overview" class="dash-tab hidden space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Trips</p>
            <h3 class="text-3xl font-extrabold text-primary" id="stat-total">0</h3>
          </div>
          <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Upcoming</p>
            <h3 class="text-3xl font-extrabold text-secondary" id="stat-upcoming">0</h3>
          </div>
          <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Completed</p>
            <h3 class="text-3xl font-extrabold text-green-600" id="stat-completed">0</h3>
          </div>
        </div>

        <!-- Recent Booking Brief -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-extrabold text-gray-900">Recent Activity</h3>
            <button onclick="switchDashTab('bookings')" class="text-primary text-xs font-bold hover:underline">View All</button>
          </div>
          <div id="recent-bookings-list" class="divide-y divide-gray-100">
            <p class="p-10 text-center text-gray-400 text-sm">No recent bookings found.</p>
          </div>
        </div>
      </div>

      <!-- Bookings Tab -->
      <div id="tab-bookings" class="dash-tab hidden">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden overflow-x-auto">
          <div class="p-6 border-b border-gray-100">
            <h3 class="font-extrabold text-gray-900">My Booking History</h3>
          </div>
          <table class="w-full text-left tbl">
            <thead>
              <tr>
                <th>Booking Ref</th>
                <th>Trip Details</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="bookings-table-body">
              <!-- JS Injected -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Profile Tab -->
      <div id="tab-profile" class="dash-tab hidden">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden p-8">
          <h3 class="font-extrabold text-gray-900 text-xl mb-6">Profile Settings</h3>
          <form id="profile-form" onsubmit="updateProfile(event)" class="max-w-xl space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Full Name</label>
                <input type="text" id="prof-name" required class="w-full bg-gray-50 border border-gray-100 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-primary">
              </div>
              <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Phone Number</label>
                <input type="tel" id="prof-phone" required class="w-full bg-gray-50 border border-gray-100 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-primary">
              </div>
            </div>
            <div>
              <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Email Address (Cannot be changed)</label>
              <input type="email" id="prof-email" readOnly class="w-full bg-gray-100 border border-gray-200 rounded-xl py-3 px-4 text-sm text-gray-500 cursor-not-allowed">
            </div>
            <div>
              <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">New Password (Leave blank to keep current)</label>
              <input type="password" id="prof-password" placeholder="••••••••" class="w-full bg-gray-50 border border-gray-100 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-primary">
            </div>
            <div class="pt-4">
              <button type="submit" id="btn-update-prof" class="bg-primary text-white font-extrabold px-8 py-3.5 rounded-xl hover:bg-blue-800 transition-all shadow-lg shadow-primary/20">
                Save Changes
              </button>
            </div>
          </form>
        </div>
      </div>

    </main>
  </div>
</div>

<!-- Invoice Modal -->
<div id="invoice-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4" onclick="if(event.target===this) closeInvoice()">
  <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto relative" id="invoice-content">
    <!-- JS Injected -->
  </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
<script src="<?= FRONTEND_URL ?>/js/app.js?v=<?= APP_VERSION ?>"></script>
<script>
let currentUser = null;

document.addEventListener('DOMContentLoaded', async () => {
  currentUser = await Session.init();
  if (!currentUser) {
    window.location.href = '<?= FRONTEND_URL ?>/login?redirect=' + encodeURIComponent(window.location.href);
    return;
  }

  // Populate Sidebar
  document.getElementById('side-name').textContent = currentUser.name;
  document.getElementById('side-email').textContent = currentUser.email;
  document.getElementById('side-avatar').textContent = currentUser.name.charAt(0).toUpperCase();

  // Dashboard data loading
  await loadDashboardData();
  switchDashTab('overview');
});

async function loadDashboardData() {
  const loader = document.getElementById('dash-loader');
  loader.classList.remove('hidden');

  const response = await IBCC.bookings.myBookings();
  loader.classList.add('hidden');

  if (response?.success) {
    renderStats(response.data);
    renderBookings(response.data);
    
    // Fill profile
    document.getElementById('prof-name').value  = currentUser.name || '';
    document.getElementById('prof-phone').value = currentUser.phone || '';
    document.getElementById('prof-email').value = currentUser.email || '';
  }
}

function renderStats(bookings) {
  const total     = bookings.length;
  const upcoming  = bookings.filter(b => b.status === 'Scheduled' || b.status === 'In Progress').length;
  const completed = bookings.filter(b => b.status === 'Completed').length;

  document.getElementById('stat-total').textContent     = total;
  document.getElementById('stat-upcoming').textContent  = upcoming;
  document.getElementById('stat-completed').textContent = completed;

  const recentList = document.getElementById('recent-bookings-list');
  if (total === 0) {
    recentList.innerHTML = '<p class="p-10 text-center text-gray-400 text-sm">No bookings yet.</p>';
    return;
  }

  recentList.innerHTML = bookings.slice(0, 3).map(b => `
    <div onclick="viewInvoice(${b.id})" class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors cursor-pointer group">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center text-xl group-hover:bg-primary group-hover:text-white transition-colors">✈️</div>
        <div>
          <p class="font-bold text-gray-900 group-hover:text-primary transition-colors">${b.trip_title}</p>
          <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Ref: ${b.booking_ref}</p>
        </div>
      </div>
      <div class="text-right">
        <p class="font-extrabold text-primary">₹${Number(b.total_price).toLocaleString('en-IN')}</p>
        <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase ${getStatusCls(b.status)}">${b.status}</span>
      </div>
    </div>
  `).join('');
}

function renderBookings(bookings) {
  const tbody = document.getElementById('bookings-table-body');
  if (bookings.length === 0) {
    tbody.innerHTML = '<tr><td colspan="5" class="text-center py-20 text-gray-400">No bookings found.</td></tr>';
    return;
  }

  tbody.innerHTML = bookings.map(b => `
    <tr>
      <td class="font-bold">#${b.booking_ref}</td>
      <td>
        <div class="font-bold text-gray-900">${b.trip_title}</div>
        <div class="text-xs text-gray-400">${Utils.formatDate(b.start_date)} • ${b.num_members} Person</div>
      </td>
      <td class="font-extrabold text-primary">₹${Number(b.total_price).toLocaleString('en-IN')}</td>
      <td><span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase ${getStatusCls(b.status)}">${b.status}</span></td>
      <td>
        <div class="flex items-center gap-2">
          <button onclick="viewInvoice(${b.id})" class="text-primary hover:underline font-bold text-xs">Invoice</button>
        </div>
      </td>
    </tr>
  `).join('');
}

function getStatusCls(status) {
  const map = {
    'Pending':     'bg-yellow-100 text-yellow-700',
    'Scheduled':   'bg-blue-100 text-blue-700',
    'In Progress': 'bg-orange-100 text-orange-700',
    'Completed':   'bg-green-100 text-green-700',
    'Cancelled':   'bg-red-100 text-red-700'
  };
  return map[status] || 'bg-gray-100 text-gray-500';
}

function switchDashTab(tabId) {
  document.querySelectorAll('.dash-tab').forEach(t => t.classList.add('hidden'));
  document.getElementById('tab-' + tabId).classList.remove('hidden');

  document.querySelectorAll('.dash-sidebar-btn').forEach(b => {
    if (b.textContent.toLowerCase().includes(tabId)) {
      b.className = b.className.replace('text-gray-500 hover:bg-gray-50 hover:text-primary', 'bg-primary text-white shadow-lg shadow-primary/20');
    } else {
      b.className = b.className.replace('bg-primary text-white shadow-lg shadow-primary/20', 'text-gray-500 hover:bg-gray-50 hover:text-primary');
    }
  });
}

async function viewInvoice(id) {
  const modal = document.getElementById('invoice-modal');
  const cont  = document.getElementById('invoice-content');
  cont.innerHTML = '<div class="p-10 text-center"><div class="spinner mx-auto mb-4"></div>Fetching invoice...</div>';
  modal.classList.remove('hidden');
  modal.style.display = 'flex';

  const r = await IBCC.bookings.invoice(id);
  if (r?.success) {
    const b = r.data;
    cont.innerHTML = `
      <div class="p-8 md:p-12 relative">
        <button onclick="closeInvoice()" class="absolute top-6 right-6 text-gray-400 hover:text-gray-900">
           <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div class="flex flex-col md:flex-row justify-between mb-12 gap-8">
           <div>
              <div class="flex items-center gap-2 mb-4">
                 <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center">
                    <div class="w-6 h-6 text-white">${window.APP_CONFIG.BRAND.ICON_SVG}</div>
                 </div>
                 <span class="font-extrabold text-2xl">${window.APP_CONFIG.BRAND.NAME_PART1} <span class="text-secondary">${window.APP_CONFIG.BRAND.NAME_PART2}</span></span>
              </div>
              <p class="text-sm text-gray-500 leading-relaxed uppercase tracking-widest font-bold">Official Invoice</p>
           </div>
           <div class="text-left md:text-right">
              <h2 class="text-3xl font-black text-gray-900 mb-1">#${b.booking_ref}-INVoice</h2>
              <p class="text-gray-400 text-sm">Issue Date: ${Utils.formatDate(b.created_at)}</p>
           </div>
        </div>

        <div class="grid grid-cols-2 gap-10 mb-12">
            <div>
               <h4 class="text-xs font-bold text-gray-400 uppercase mb-3 px-1">Billed To</h4>
               <p class="font-extrabold text-gray-900 text-lg">${b.full_name}</p>
               <p class="text-sm text-gray-400 mt-1">${b.email}</p>
               <p class="text-sm text-gray-400">${b.phone}</p>
            </div>
            <div class="text-right">
               <h4 class="text-xs font-bold text-gray-400 uppercase mb-3 px-1">Booking Info</h4>
               <p class="font-bold text-gray-900">Ref: ${b.booking_ref}</p>
               <p class="text-sm text-gray-400 mt-1">Status: <span class="text-green-600 font-bold">${b.payment_status}</span></p>
            </div>
        </div>

        <div class="bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 mb-8">
            <table class="w-full text-left">
               <thead class="bg-gray-100">
                  <tr class="text-[10px] uppercase font-bold text-gray-500">
                     <th class="px-6 py-3">Trip Description</th>
                     <th class="px-6 py-3">Rate</th>
                     <th class="px-6 py-3 text-center">Person</th>
                     <th class="px-6 py-3 text-right">Total</th>
                  </tr>
               </thead>
               <tbody>
                  <tr class="border-b border-gray-200/50">
                     <td class="px-6 py-4">
                        <p class="font-bold text-gray-900">${b.trip_title}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Start Date: ${Utils.formatDate(b.start_date)}</p>
                     </td>
                     <td class="px-6 py-4 text-sm text-gray-600">₹${(b.total_price/b.num_members).toLocaleString('en-IN')}</td>
                     <td class="px-6 py-4 text-center text-sm">${b.num_members}</td>
                     <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">₹${Number(b.total_price).toLocaleString('en-IN')}</td>
                  </tr>
               </tbody>
            </table>
        </div>

        <div class="flex flex-col items-end gap-2 mb-10">
            <div class="w-full md:w-64 flex justify-between text-sm text-gray-500"><span class="px-1">Subtotal</span><span>₹${Number(b.total_price).toLocaleString('en-IN')}</span></div>
            <div class="w-full md:w-64 flex justify-between text-sm text-gray-500 border-b border-gray-100 pb-2"><span class="px-1">Taxes (0%)</span><span>₹0</span></div>
            <div class="w-full md:w-64 flex justify-between text-xl font-black text-primary pt-2"><span class="px-1">Total Paid</span><span>₹${Number(b.total_price).toLocaleString('en-IN')}</span></div>
        </div>

        <div class="flex items-center justify-end pt-8 border-t border-gray-100 border-dashed">
            <button onclick="window.print()" class="bg-dark text-white text-xs font-extrabold px-6 py-2.5 rounded-xl hover:bg-black transition-colors">Print PDF</button>
        </div>
      </div>
    `;
  } else {
    cont.innerHTML = '<div class="p-10 text-center text-red-500">Failed to load invoice.</div>';
  }
}

function closeInvoice() {
  const modal = document.getElementById('invoice-modal');
  modal.classList.add('hidden');
  modal.style.display = '';
}

async function updateProfile(e) {
  e.preventDefault();
  const btn = document.getElementById('btn-update-prof');
  btn.disabled = true; btn.textContent = 'Saving...';
  
  const data = {
    name:     document.getElementById('prof-name').value,
    phone:    document.getElementById('prof-phone').value,
    password: document.getElementById('prof-password').value || undefined
  };

  const r = await IBCC.auth.updateProfile(data);
  btn.disabled = false; btn.textContent = 'Save Changes';
  
  if (r?.success) {
    Utils.toast('✅ Profile updated successfully!');
    setTimeout(() => window.location.reload(), 1000);
  } else {
    Utils.toast(r?.message || 'Update failed', 'error');
  }
}

async function cancelBooking(ref) {
   if (!confirm('Are you sure you want to cancel this booking? This action is irreversible.')) return;
   const r = await IBCC.bookings.cancel(ref);
   if (r?.success) {
     Utils.toast('✅ Booking cancelled.');
     loadDashboardData();
   } else {
     Utils.toast(r?.message || 'Failed to cancel', 'error');
   }
}
</script>
</body>
</html>
