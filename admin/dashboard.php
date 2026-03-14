<?php
// admin/dashboard.php — Unified Admin Dashboard
// ─────────────────────────────────────────────────────────────

require_once __DIR__ . '/../components/config.php';
require_once __DIR__ . '/../components/helpers.php';
require_once __DIR__ . '/../components/logo.php';

// Server-side Authentication Guard
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ' . FRONTEND_URL . '/login.php');
    exit();
}

$siteFullName = (defined('SITE_NAME_PART1') ? SITE_NAME_PART1 : 'IBCC') . ' ' . (defined('SITE_NAME_PART2') ? SITE_NAME_PART2 : 'Trip');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | <?= e($siteFullName) ?></title>
  
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml;base64,<?= base64_encode(SITE_ICON_SVG) ?>">

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { 
            primary: '<?= COLOR_PRIMARY ?>', 
            secondary: '<?= COLOR_SECONDARY ?>', 
            dark: '<?= COLOR_DARK ?>' 
          },
          fontFamily: { sans: ['Inter', 'sans-serif'] }
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- Quill Editor Setup -->
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
  
  <link rel="stylesheet" href="<?= FRONTEND_URL ?>/css/style.css?v=<?= APP_VERSION ?>">

  <style>
    body { font-family: 'Inter', sans-serif; background: #F3F4F6; }
    .sidebar-link.active { background: <?= COLOR_PRIMARY ?>; color: white; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
    .card { background: white; border-radius: 1.5rem; border: 1px solid #F3F4F6; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .tbl th { background: #F9FAFB; padding: 12px 16px; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #6B7280; text-align: left; }
    .tbl td { padding: 14px 16px; font-size: 13px; border-bottom: 1px solid #F3F4F6; }
    .spinner { width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid <?= COLOR_PRIMARY ?>; border-radius: 50%; animation: spin 1s linear infinite; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
  </style>
  <!-- Global App Config -->
  <script>
    window.APP_CONFIG = {
      BASE_URL: '<?= BASE_URL ?>',
      ADMIN_URL: '<?= ADMIN_URL ?>',
      API_URL: '<?= API_URL ?>',
      VERSION: '<?= APP_VERSION ?>',
      BRAND: {
        NAME_PART1: '<?= SITE_NAME_PART1 ?>',
        NAME_PART2: '<?= SITE_NAME_PART2 ?>',
        FULL_NAME:  '<?= $siteFullName ?>',
        ICON_SVG:   `<?= SITE_ICON_SVG ?>`
      }
    };
  </script>
  <script src="<?= ADMIN_URL ?>/cms-forms.js?v=<?= APP_VERSION ?>"></script>
</head>
<body class="flex min-h-screen">

  <!-- Sidebar -->
  <aside class="w-64 bg-white border-r border-gray-100 flex flex-col sticky top-0 h-screen shrink-0 z-20">
    <div class="p-6 border-b border-gray-50">
        <a href="<?= ADMIN_URL ?>" class="block">
          <?php 
            // In Admin we might want a slightly different logo look or just the full one
            // Let's use darker text for the admin sidebar
            echo renderLogo('full', ''); 
          ?>
        </a>
        <style>
          /* Override logo text color for white sidebar */
          .logo-text { color: #111827 !important; }
        </style>
    </div>
    
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
      <button onclick="switchTab('overview')" id="nav-overview" class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-gray-500 hover:bg-gray-50 active">
        <span>📊</span> Overview
      </button>
      <button onclick="switchTab('bookings')" id="nav-bookings" class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-gray-500 hover:bg-gray-50">
        <span>✈️</span> Bookings
      </button>
      <button onclick="switchTab('trips')" id="nav-trips" class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-gray-500 hover:bg-gray-50">
        <span>🏝️</span> Trips
      </button>
      <button onclick="switchTab('destinations')" id="nav-destinations" class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-gray-500 hover:bg-gray-50">
        <span>🌍</span> Destinations
      </button>
      <button onclick="switchTab('gallery')" id="nav-gallery" class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-gray-500 hover:bg-gray-50">
        <span>🖼️</span> Gallery
      </button>
      <button onclick="switchTab('blogs')" id="nav-blogs" class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-gray-500 hover:bg-gray-50">
        <span>📝</span> Blogs
      </button>
      <button onclick="switchTab('testimonials')" id="nav-testimonials" class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-gray-500 hover:bg-gray-50">
        <span>⭐</span> Testimonials
      </button>
      <button onclick="switchTab('site_stats')" id="nav-site_stats" class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-gray-500 hover:bg-gray-50">
        <span>📈</span> Site Stats
      </button>
      <button onclick="switchTab('site_settings')" id="nav-site_settings" class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-gray-500 hover:bg-gray-50">
        <span>⚙️</span> Site Settings
      </button>
      <button onclick="switchTab('customers')" id="nav-customers" class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-gray-500 hover:bg-gray-50">
        <span>👥</span> Customers
      </button>
      <button onclick="switchTab('users')" id="nav-users" class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-gray-500 hover:bg-gray-50">
        <span>🔑</span> Users & Admins
      </button>
      <button onclick="switchTab('messages')" id="nav-messages" class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-gray-500 hover:bg-gray-50 relative">
        <span>✉️</span> Messages
        <span id="unread-count" class="absolute right-4 bg-red-500 text-white text-[10px] w-5 h-5 rounded-full hidden items-center justify-center font-bold">0</span>
      </button>
    </nav>
    
    <div class="p-4 border-t border-gray-50">
      <a href="<?= FRONTEND_URL ?>/" target="_blank" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-400 hover:text-primary transition-colors">
        <span>🌐</span> View Website
      </a>
      <button onclick="logout()" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-400 hover:text-red-600 font-bold transition-colors">
        <span>🚪</span> Logout
      </button>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 min-w-0 flex flex-col">
    <!-- Topbar -->
    <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-8 sticky top-0 z-10">
      <h2 id="header-title" class="text-lg font-black text-gray-900 uppercase tracking-tight">Overview</h2>
      <div class="flex items-center gap-4">
        <div class="text-right">
          <p id="admin-name" class="text-sm font-bold text-gray-900">Admin User</p>
          <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Master Admin</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center font-bold shadow-lg shadow-primary/20">A</div>
      </div>
    </header>

    <!-- Page Content Area -->
    <div id="page-content" class="p-8 flex-1">
       <!-- Content will be injected via JS -->
       <div class="flex flex-col items-center justify-center h-full text-gray-400">
         <div class="spinner mb-4"></div>
         <p class="text-sm font-medium">Initialising secure session...</p>
       </div>
    </div>
  </main>

  <!-- Modals Placeholder -->
  <div id="modal-root"></div>

  <!-- App Logic Wrapper => admin.js logic will be here for now -->
  <script src="<?= FRONTEND_URL ?>/js/app.js"></script>
  <script src="<?= FRONTEND_URL ?>/admin/cms-forms.js"></script>
  <script>
    // Minimalistic dashboard state handler (converted from dashboard.html logic)
    const API_URL = window.APP_CONFIG.API_URL;
    let currentTab = 'overview';
    const API = IBCC;

    async function init() {
      const user = await Session.init();
      if (!user || user.role !== 'admin') {
        window.location.href = '<?= FRONTEND_URL ?>/login';
        return;
      }
      document.getElementById('admin-name').textContent = user.name;
      await preloadReferences();
      switchTab('overview');
      updateUnreadCount();
    }

    async function switchTab(tabId) {
      currentTab = tabId;
      document.querySelectorAll('.sidebar-link').forEach(btn => {
        btn.classList.remove('active');
        if (btn.id === 'nav-' + tabId) btn.classList.add('active');
      });
      document.getElementById('header-title').textContent = tabId;
      
      const content = document.getElementById('page-content');
      content.innerHTML = '<div class="flex justify-center py-20"><div class="spinner"></div></div>';
      
      if (tabId === 'overview') { const r = await API.admin.getDashboardStats(); renderOverview(r.data); }
      else if (tabId === 'bookings') loadBookings();
      else if (tabId === 'trips') loadTrips();
      else if (tabId === 'destinations') loadDestinations();
      else if (tabId === 'blogs') loadBlogs();
      else if (tabId === 'testimonials') loadTestimonials();
      else if (tabId === 'site_stats') loadSiteStats();
      else if (tabId === 'site_settings') loadSiteSettings();
      else if (tabId === 'customers') loadCustomers();
      else if (tabId === 'users') loadUsers();
      else if (tabId === 'messages') loadMessages();
      else if (tabId === 'gallery') loadGallery();
    }

    function renderOverview(data) {
      document.getElementById('page-content').innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
           ${renderStatCard('Total Revenue', '₹' + Number(data.total_revenue || 0).toLocaleString(), 'text-green-600', '💰')}
           ${renderStatCard('Active Trips', data.total_trips || 0, 'text-primary', '🏝️')}
           ${renderStatCard('Total Bookings', data.total_bookings || 0, 'text-secondary', '✈️')}
           ${renderStatCard('Customers', data.total_customers || 0, 'text-indigo-600', '👥')}
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <div class="card p-6">
            <h3 class="font-extrabold text-gray-900 mb-4 px-1">Recent Bookings</h3>
            <table class="w-full tbl">
              <thead><tr><th>User</th><th>Trip</th><th>Status</th></tr></thead>
              <tbody>${(data.recent_bookings || []).map(b => `<tr class="cursor-pointer hover:bg-gray-50 transition-colors" onclick="viewBooking(${b.id})"><td>${b.full_name || b.customer_name}</td><td>${b.trip_title}</td><td><span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase bg-blue-50 text-blue-600">${b.status}</span></td></tr>`).join('') || '<tr><td colspan="3" class="text-center py-4 text-gray-400">No recent bookings</td></tr>'}</tbody>
            </table>
          </div>
          <div class="card p-6">
            <h3 class="font-extrabold text-gray-900 mb-4 px-1">Top Trips</h3>
            <table class="w-full tbl">
              <thead><tr><th>Trip</th><th>Bookings</th></tr></thead>
              <tbody>${(data.top_trips || []).map(t => `<tr><td class="font-bold text-gray-900">${t.title}</td><td>${t.booking_count}</td></tr>`).join('') || '<tr><td colspan="2" class="text-center py-4 text-gray-400">No data available</td></tr>'}</tbody>
            </table>
          </div>
        </div>
      `;
    }

    function renderStatCard(label, val, clr, icon) {
      return `
        <div class="card p-6 flex items-center justify-between">
          <div><p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">${label}</p><h3 class="text-2xl font-black ${clr}">${val}</h3></div>
          <div class="text-3xl opacity-20">${icon}</div>
        </div>
      `;
    }

    const CRUD = {
        show(title, content) {
            window.activeQuill = null; // reset reference
            document.getElementById('modal-root').innerHTML = `
            <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl w-full max-w-3xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="font-extrabold text-lg uppercase text-gray-800 tracking-wide">${title}</h3>
                        <button onclick="CRUD.close()" class="text-2xl text-gray-400 hover:text-red-500 leading-none">&times;</button>
                    </div>
                    <div class="p-6 overflow-y-auto flex-1">${content}</div>
                </div>
            </div>`;
        },
        initQuill(selector, contentHTML) {
            window.activeQuill = new Quill(selector, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'clean']
                    ]
                }
            });
            if(contentHTML) window.activeQuill.clipboard.dangerouslyPasteHTML(0, contentHTML);
        },
        // --- DYNAMIC LIST BUILDER ---
        renderDynamicList(containerId, initialDataStr) {
            let data = [];
            try { 
                if (typeof initialDataStr === 'string' && (initialDataStr.startsWith('[') || initialDataStr.startsWith('{'))) {
                    data = JSON.parse(initialDataStr); 
                } else if (typeof initialDataStr === 'string' && initialDataStr) {
                    data = initialDataStr.split(/\n/).map(s => s.trim()).filter(s => s);
                } else {
                    data = initialDataStr || [];
                }
            } catch(e){ data = []; }
            if(!Array.isArray(data)) data = [];

            const container = document.getElementById(containerId);
            if (!container) return;
            container.innerHTML = '';
            
            const listWrapper = document.createElement('div');
            listWrapper.className = 'space-y-2 mb-3 dynamic-list-items';
            container.appendChild(listWrapper);

            const addBtn = document.createElement('button');
            addBtn.type = 'button';
            addBtn.className = 'flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider text-secondary hover:text-primary transition-colors';
            addBtn.innerHTML = `<span>+ Add Item</span>`;
            addBtn.onclick = () => CRUD.addDynamicListItem(listWrapper, '');
            container.appendChild(addBtn);

            data.forEach(item => CRUD.addDynamicListItem(listWrapper, item.text || item));
            if(data.length === 0) CRUD.addDynamicListItem(listWrapper, '');
        },
        addDynamicListItem(wrapper, value) {
            const row = document.createElement('div');
            row.className = 'flex items-center gap-2 group';
            row.innerHTML = `
                <input type="text" value="${value.replace(/"/g, '&quot;')}" class="flex-1 border-gray-200 border rounded-xl p-2.5 text-sm dynamic-item-input focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" placeholder="Enter value...">
                <button type="button" onclick="this.parentElement.remove()" class="text-gray-300 hover:text-red-500 font-bold px-1 transition-colors text-lg">&times;</button>
            `;
            wrapper.appendChild(row);
        },
        getDynamicListData(containerId) {
            const container = document.getElementById(containerId);
            if (!container) return '[]';
            const inputs = container.querySelectorAll('.dynamic-item-input');
            const arr = [];
            inputs.forEach(inp => { if(inp.value.trim() !== '') arr.push(inp.value.trim()); });
            return JSON.stringify(arr);
        },
        close() { 
            document.getElementById('modal-root').innerHTML = ''; 
            window.activeQuill = null;
        }
    };

    // --- BOOKINGS ---
    async function loadBookings() {
        const r = await API.admin.getBookings();
        renderBookings(r.data || []);
    }
    function renderBookings(list) {
        document.getElementById('page-content').innerHTML = `
        <div class="card overflow-hidden">
          <table class="w-full tbl text-left">
            <thead class="bg-gray-50 border-b border-gray-100"><tr><th class="p-4">Ref</th><th class="p-4">Customer</th><th class="p-4">Trip</th><th class="p-4">Amount</th><th class="p-4">B. Status</th><th class="p-4">P. Status</th><th class="p-4">Action</th></tr></thead>
            <tbody>${list.map(b => `<tr>
              <td class="p-4 font-bold text-primary cursor-pointer hover:underline" onclick="viewBooking(${b.id})">#${b.booking_ref||b.id}</td>
              <td class="p-4">${b.customer_name||b.full_name}</td>
              <td class="p-4 line-clamp-1">${b.trip_title}</td>
              <td class="p-4 font-bold text-green-600">₹${Number(b.total_price||b.total_amount||0).toLocaleString()}</td>
              <td class="p-4">${Utils.statusBadge(b.status)}</td>
              <td class="p-4">${Utils.statusBadge(b.payment_status || 'Pending')}</td>
              <td class="p-4">
                <div class="flex items-center gap-2">
                    <select onchange="updateBookingStatus(${b.id}, this.value)" class="text-[10px] border rounded-lg p-1 outline-none focus:ring-2 focus:ring-primary/20 bg-white">
                      <option value="">Status...</option>
                      <option value="Pending">Pending</option>
                      <option value="Scheduled">Scheduled</option>
                      <option value="Completed">Completed</option>
                      <option value="Cancelled">Cancelled</option>
                    </select>
                    <button onclick="deleteEntity('bookings', ${b.id})" class="text-red-500 hover:text-red-700 font-bold p-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
              </td>
            </tr>`).join('') || '<tr><td colspan="7" class="text-center text-gray-400 py-10">No bookings found</td></tr>'}</tbody>
          </table>
        </div>`;
    }
    window.updateBookingStatus = async (id, status) => {
        if(!status) return;
        if(!confirm('Update status to ' + status + '?')) return;
        await API.admin.updateBooking(id, status);
        Utils.toast('Booking updated');
        loadBookings();
    };

    window.viewBooking = async (id) => {
        const r = await IBCC.bookings.invoice(id);
        if(!r.success) return Utils.toast('Failed to load details', 'error');
        const b = r.data;
        
        const detailsHtml = `
            <div class="space-y-6">
                <div class="flex justify-between items-start border-b pb-4">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900">INVOICE</h2>
                        <p class="text-gray-400 text-sm">#${b.invoice_number || b.booking_ref}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">${b.company.name}</p>
                        <p class="text-xs text-gray-400">${b.company.email}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <p class="text-[10px] font-black uppercase text-gray-400 mb-1">Customer</p>
                        <p class="font-bold text-gray-900">${b.full_name}</p>
                        <p class="text-sm text-gray-500">${b.email}</p>
                        <p class="text-sm text-gray-500">${b.phone || ''}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black uppercase text-gray-400 mb-1">Booking Date</p>
                        <p class="text-sm font-bold">${Utils.formatDate(b.created_at)}</p>
                        <p class="text-[10px] font-black uppercase text-gray-400 mt-3 mb-1">Travel Date</p>
                        <p class="text-sm font-bold text-primary">${Utils.formatDate(b.start_date)}</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                    <p class="text-[10px] font-black uppercase text-gray-400 mb-2">Trip Summary</p>
                    <div class="flex items-center gap-4">
                        <img src="${b.cover_image}" class="w-16 h-12 rounded-lg object-cover border shadow-sm">
                        <div>
                            <p class="font-bold text-gray-900">${b.trip_title}</p>
                            <p class="text-xs text-gray-500">${b.num_members} Person(s)</p>
                        </div>
                        <div class="ml-auto text-right">
                            <p class="font-black text-green-600 text-lg">₹${Number(b.total_price).toLocaleString()}</p>
                            <p class="text-[10px] text-gray-400 uppercase font-bold">${b.payment_status || 'Pending'}</p>
                        </div>
                    </div>
                </div>

                ${b.special_requests ? `
                <div>
                     <p class="text-[10px] font-black uppercase text-gray-400 mb-1">Special Requests</p>
                     <p class="text-sm text-gray-600 italic bg-yellow-50 p-3 rounded-xl">"${b.special_requests}"</p>
                </div>
                ` : ''}

                <div class="flex flex-wrap gap-3 pt-4">
                    <div class="flex-1 flex items-center gap-2">
                        <select onchange="updatePaymentStatus(${b.id}, this.value)" class="flex-1 border-2 border-primary/20 rounded-2xl py-3 px-4 text-sm font-bold text-primary focus:border-primary outline-none">
                            <option value="">Update Payment Status...</option>
                            <option value="Pending" ${b.payment_status==='Pending'?'selected':''}>Pending</option>
                            <option value="Paid" ${b.payment_status==='Paid'?'selected':''}>Paid</option>
                            <option value="Failed" ${b.payment_status==='Failed'?'selected':''}>Failed</option>
                            <option value="Refunded" ${b.payment_status==='Refunded'?'selected':''}>Refunded</option>
                        </select>
                    </div>
                    <button onclick="window.print()" class="bg-gray-100 text-gray-700 font-bold px-6 py-3 rounded-2xl hover:bg-gray-200 transition-colors">Print</button>
                    <button onclick="CRUD.close()" class="bg-primary text-white font-bold px-8 py-3 rounded-2xl hover:bg-blue-800 transition-colors shadow-lg">Done</button>
                </div>
            </div>
        `;
        CRUD.show('Booking Details', detailsHtml);
    };

    window.updatePaymentStatus = async (id, status) => {
        if(!status) return;
        if(!confirm('Update payment status to ' + status + '?')) return;
        const r = await fetch(API_URL + '/admin.php?resource=bookings&action=update-payment-status&id=' + id, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ status: status }),
            credentials: 'include'
        }).then(res => res.json());
        
        if(r.success) { 
            Utils.toast('Payment status updated'); 
            // Refresh current view if possible or reload bookings
            if (currentTab === 'bookings') loadBookings();
            else switchTab('overview');
            CRUD.close();
        } else {
            Utils.toast(r.message || 'Update failed', 'error');
        }
    };

    // --- GLOBAL REFS for DROPDOWNS ---
    let globals = { countries:[], states:[], cities:[], categories:[] };
    async function preloadReferences() {
        try {
            const [c, s, ci, cat] = await Promise.all([
                API.admin.getDestinations('countries'),
                API.admin.getDestinations('states'),
                API.admin.getDestinations('cities'),
                API.blogs.categories()
            ]);
            globals.countries = c?.data || [];
            globals.states = s?.data || [];
            globals.cities = ci?.data || [];
            globals.categories = cat?.data || [];
            // Also load places for trip forms
            const pl = await API.admin.getDestinations('places');
            globals.places = pl?.data || [];
        } catch(e) { console.error("Error preloading", e); }
    }

    const buildOptions = (list, selectedId, idKey='id', labelKey='name') => {
        return `<option value="">-- Select --</option>` + list.map(x => `<option value="${x[idKey]}" ${x[idKey] == selectedId ? 'selected' : ''}>${x[labelKey]}</option>`).join('');
    };

    // --- TRIPS ---
    let currentTrips = [];
    async function loadTrips() {
        const r = await API.admin.getTrips();
        currentTrips = r.data || [];
        renderTrips(currentTrips);
    }
    function renderTrips(list) {
        document.getElementById('page-content').innerHTML = `
        <div class="mb-5 flex justify-end"><button onclick="editTrip()" class="bg-primary hover:bg-blue-800 transition-colors text-white text-xs font-black uppercase tracking-wider px-6 py-2.5 rounded-2xl shadow-lg hover:shadow-xl">+ Add New Trip</button></div>
        <div class="card overflow-hidden">
          <table class="w-full tbl text-left">
            <thead class="bg-gray-50 border-b border-gray-100"><tr><th class="p-4">Image</th><th class="p-4">Trip Name</th><th class="p-4">Price</th><th class="p-4">Duration</th><th class="p-4">Action</th></tr></thead>
            <tbody>${list.map(t => `<tr>
              <td class="p-4">
                <div class="w-12 h-12 rounded-xl overflow-hidden border shadow-sm">
                    <img src="${t.cover_image||''}" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/100?text=Trip'">
                </div>
              </td>
              <td class="p-4 font-extrabold text-gray-900">${t.title}</td>
              <td class="p-4 font-bold text-green-600">₹${Number(t.discounted_price||t.base_price||0).toLocaleString()}</td>
              <td class="p-4 text-xs font-bold text-gray-500 uppercase tracking-tighter">${t.duration_days} Days</td>
              <td class="p-4">
                <div class="flex gap-2 text-xs font-bold">
                    <button onclick="editTrip(${t.id})" class="text-primary hover:underline">Edit</button> |
                    <button onclick="deleteEntity('trips', ${t.id})" class="text-red-500 hover:underline">Delete</button>
                </div>
              </td>
            </tr>`).join('') || '<tr><td colspan="5" class="text-center text-gray-400 py-10">No trips found</td></tr>'}</tbody>
          </table>
        </div>`;
    }
    window.editTrip = async (id = null) => {
        let t = {};
        if (id) {
            const r = await API.admin.getTripDetail(id);
            t = r.data || {};
        }
        CRUD.show(id ? 'Edit Trip' : 'Add New Trip', renderTripForm(t));
        setTimeout(() => {
            CRUD.initQuill('#t-desc-editor', t.description || '');
            CRUD.renderDynamicList('t-high', t.highlights);
            CRUD.renderDynamicList('t-incl', t.inclusions);
            CRUD.renderDynamicList('t-excl', t.exclusions);
            const videoUrls = t.videos ? t.videos.map(v => v.youtube_url).join('\n') : '';
            CRUD.renderDynamicList('t-vids', videoUrls);
        }, 80);
    };
    // saveTrip is in cms-forms.js (admin/cms-forms.js)

    // --- BLOGS ---
    let currentBlogs = [];
    async function loadBlogs() {
        const r = await API.admin.getBlogs();
        currentBlogs = r.data || [];
        renderBlogs(currentBlogs);
    }
    function renderBlogs(list) {
        document.getElementById('page-content').innerHTML = `
        <div class="mb-5 flex justify-end"><button onclick="editBlog()" class="bg-primary hover:bg-blue-800 transition-colors text-white text-xs font-black uppercase tracking-wider px-6 py-2.5 rounded-2xl shadow-lg hover:shadow-xl">+ Add New Blog</button></div>
        <div class="card overflow-hidden">
          <table class="w-full tbl text-left">
            <thead class="bg-gray-50 border-b border-gray-100"><tr><th class="p-4">Image</th><th class="p-4">Title</th><th class="p-4">Status</th><th class="p-4">Author</th><th class="p-4">Action</th></tr></thead>
            <tbody>${list.map(b => `<tr>
              <td class="p-4">
                <div class="w-14 h-10 rounded-xl overflow-hidden border shadow-sm">
                    <img src="${b.featured_image||''}" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/100?text=Blog'">
                </div>
              </td>
              <td class="p-4 font-extrabold text-gray-900">${b.title}</td>
              <td class="p-4">
                <span class="text-[10px] px-2 py-0.5 rounded-full font-black uppercase tracking-tighter ${b.status==='Published'?'bg-green-100 text-green-700':'bg-yellow-100 text-yellow-700'}">${b.status||'Draft'}</span>
              </td>
              <td class="p-4 text-gray-500 text-sm font-medium">${b.author}</td>
              <td class="p-4">
                <div class="flex gap-2 text-xs font-bold">
                    <button onclick="editBlog(${b.id})" class="text-primary hover:underline">Edit</button> |
                    <button onclick="deleteEntity('blogs', ${b.id})" class="text-red-500 hover:underline">Delete</button>
                </div>
              </td>
            </tr>`).join('') || '<tr><td colspan="5" class="text-center text-gray-400 py-10">No blogs found</td></tr>'}</tbody>
          </table>
        </div>`;
    }
    window.editBlog = (id = null) => {
        const b = id ? currentBlogs.find(x => x.id === id) : {};
        CRUD.show(id ? 'Edit Blog' : 'Add New Blog', renderBlogForm(b));
        setTimeout(() => CRUD.initQuill('#b-content-editor', b.content || ''), 50);
    };
    // saveBlog is now in cms-forms.js

    // --- DESTINATIONS ---
    let currentDests = [];
    let currentDestType = 'countries';
    async function loadDestinations(type = 'countries') {
        currentDestType = type;
        const r = await API.admin.getDestinations(type);
        currentDests = r.data || [];
        renderDestinations(currentDests, type);
    }
    function renderDestinations(list, type) {
        document.getElementById('page-content').innerHTML = `
        <div class="flex flex-wrap gap-2 mb-5">
            ${['countries','states','cities','places'].map(t => 
                `<button onclick="loadDestinations('${t}')" class="px-5 py-2.5 rounded-2xl text-xs font-black uppercase tracking-wider transition-all shadow-sm ${type===t?'bg-primary text-white scale-105':'bg-white text-gray-500 hover:bg-gray-50'} capitalize">${t}</button>`
            ).join('')}
            <div class="ml-auto flex gap-2"><button onclick="editDest()" class="bg-secondary text-white text-xs font-black uppercase tracking-wider px-6 py-2.5 rounded-2xl shadow-lg hover:shadow-xl transition-all">+ Add ${type.slice(0,-1)}</button></div>
        </div>
        <div class="card overflow-hidden">
          <table class="w-full tbl text-left">
            <thead class="bg-gray-50 border-b border-gray-100"><tr><th class="p-4">Image</th><th class="p-4">Name</th><th class="p-4">Slug</th><th class="p-4">Featured</th><th class="p-4">Action</th></tr></thead>
            <tbody>${list.map(d => `<tr>
              <td class="p-4">
                <div class="w-12 h-12 rounded-xl overflow-hidden border bg-gray-100 shadow-sm">
                    <img src="${d.featured_image||''}" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/100?text=No+Img'; this.classList.add('opacity-30')">
                </div>
              </td>
              <td class="p-4 font-extrabold text-gray-900">${d.name}</td>
              <td class="p-4 text-xs font-mono text-gray-400">/${d.slug}</td>
              <td class="p-4">${d.is_featured?'<span class="text-[10px] px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-full font-black uppercase tracking-tighter">★ Featured</span>':'<span class="text-[10px] text-gray-300 font-bold uppercase">No</span>'}</td>
              <td class="p-4">
                <div class="flex gap-2 text-xs font-bold">
                    <button onclick="editDest(${d.id})" class="text-primary hover:underline">Edit</button> |
                    <button onclick="deleteEntity('${type}', ${d.id})" class="text-red-500 hover:underline">Delete</button>
                </div>
              </td>
            </tr>`).join('') || `<tr><td colspan="5" class="text-center text-gray-400 py-10">No ${type} found</td></tr>`}</tbody>
          </table>
        </div>`;
    }
    window.editDest = (id = null) => {
        const d = id ? currentDests.find(x => x.id === id) : {};
        CRUD.show(id ? 'Edit ' + currentDestType : 'Add ' + currentDestType, renderDestForm(d, currentDestType));
        setTimeout(() => CRUD.initQuill('#d-desc-editor', d.description || ''), 50);
    };
    // saveDest is now in cms-forms.js

    // --- CUSTOMERS ---
    async function loadCustomers() {
        const r = await API.admin.getCustomers();
        renderCustomers(r.data || []);
    }
    function renderCustomers(list) {
        document.getElementById('page-content').innerHTML = `
        <div class="card overflow-hidden">
          <table class="w-full tbl text-left">
            <thead class="bg-gray-50 border-b border-gray-100"><tr><th class="p-4">Customer Name</th><th class="p-4">Contact Info</th><th class="p-4">Stats</th><th class="p-4">Total Spent</th></tr></thead>
            <tbody>${list.map(c => `<tr>
              <td class="p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-black">${c.full_name[0].toUpperCase()}</div>
                    <div>
                        <p class="font-extrabold text-gray-900">${c.full_name}</p>
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-tighter">Member</p>
                    </div>
                </div>
              </td>
              <td class="p-4">
                <p class="text-sm font-medium text-gray-700">${c.email}</p>
                <p class="text-xs text-gray-400 font-mono">${c.phone||'-'}</p>
              </td>
              <td class="p-4 text-xs font-bold text-gray-500 uppercase tracking-tighter">${c.booking_count||0} Bookings</td>
              <td class="p-4 font-bold text-green-600 text-lg">₹${Number(c.total_spent||0).toLocaleString()}</td>
            </tr>`).join('') || '<tr><td colspan="4" class="text-center text-gray-400 py-10">No customers found</td></tr>'}</tbody>
          </table>
        </div>`;
    }

    // --- TESTIMONIALS ---
    let currentTestimonials = [];
    async function loadTestimonials() {
        const r = await API.admin.getTestimonials();
        currentTestimonials = r.data || [];
        renderTestimonials(currentTestimonials);
    }
    function renderTestimonials(list) {
        document.getElementById('page-content').innerHTML = `
        <div class="mb-5 flex justify-end">
            <button onclick="editTestimonial()" class="bg-primary text-white text-xs font-black uppercase tracking-wider px-6 py-2.5 rounded-2xl shadow-lg">+ Add Testimonial</button>
        </div>
        <div class="card overflow-hidden">
          <table class="w-full tbl text-left">
            <thead><tr><th>User</th><th>Rating</th><th>Comment</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>${list.map(t => `<tr>
              <td>
                <div class="flex items-center gap-3">
                  <img src="${t.image||''}" class="w-10 h-10 rounded-full object-cover bg-gray-100" onerror="this.src='https://placehold.co/100?text=${t.name[0]}'">
                  <div><p class="font-bold text-gray-900">${t.name}</p><p class="text-[10px] text-gray-400 font-bold uppercase">${t.role}</p></div>
                </div>
              </td>
              <td class="text-orange-400 font-bold">${'⭐'.repeat(t.rating)}</td>
              <td class="text-sm text-gray-500 max-w-md line-clamp-2">${t.comment}</td>
              <td><span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase ${t.status==='Published'?'bg-green-100 text-green-700':'bg-gray-100 text-gray-500'}">${t.status}</span></td>
              <td>
                <div class="flex gap-2 text-xs font-bold">
                  <button onclick="editTestimonial(${t.id})" class="text-primary hover:underline">Edit</button> |
                  <button onclick="deleteEntity('testimonials', ${t.id})" class="text-red-500 hover:underline">Delete</button>
                </div>
              </td>
            </tr>`).join('') || '<tr><td colspan="5" class="text-center text-gray-400 py-10">No testimonials found</td></tr>'}</tbody>
          </table>
        </div>`;
    }
    window.editTestimonial = (id = null) => {
        const t = id ? currentTestimonials.find(x => x.id === id) : {};
        CRUD.show(id ? 'Edit Testimonial' : 'Add Testimonial', renderTestimonialForm(t));
    };

    // --- SITE STATS ---
    async function loadSiteStats() {
        const r = await API.admin.getSiteStats();
        renderSiteStats(r.data || []);
    }
    function renderSiteStats(list) {
        document.getElementById('page-content').innerHTML = `
        <div class="card overflow-hidden">
          <table class="w-full tbl text-left">
            <thead class="bg-gray-50 border-b border-gray-100"><tr><th class="p-4">Icon</th><th class="p-4">Label</th><th class="p-4">Value</th><th class="p-4">Action</th></tr></thead>
            <tbody>${list.map(s => `<tr>
              <td class="p-4 text-2xl">${s.icon}</td>
              <td class="p-4 font-extrabold text-gray-900">${s.stat_label}</td>
              <td class="p-4 font-black text-secondary text-lg">${s.stat_value}</td>
              <td class="p-4"><button onclick="editSiteStat(${s.id}, '${s.stat_label}', '${s.stat_value}')" class="text-primary font-black text-xs hover:underline uppercase tracking-widest">Edit</button></td>
            </tr>`).join('')}</tbody>
          </table>
        </div>`;
    }
    window.editSiteStat = (id, label, val) => {
        CRUD.show('Edit Site Stat', `
            <form onsubmit="saveSiteStat(event, ${id})" class="space-y-4">
                <div><label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1 mb-2 block">Label</label><input type="text" id="s-label" value="${label}" required class="w-full border rounded-2xl p-4 text-sm focus:border-primary focus:outline-none"></div>
                <div><label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1 mb-2 block">Value (e.g. 15K+)</label><input type="text" id="s-value" value="${val}" required class="w-full border rounded-2xl p-4 text-sm focus:border-primary focus:outline-none"></div>
                <button type="submit" class="w-full bg-primary text-white font-black py-4 rounded-2xl shadow-xl hover:shadow-2xl transition-all uppercase tracking-widest">Save Changes</button>
            </form>
        `);
    };
    window.saveSiteStat = async (e, id) => {
        e.preventDefault();
        const btn = e.target.querySelector('button');
        btn.disabled = true; btn.textContent = 'Saving...';
        const data = { stat_label: document.getElementById('s-label').value, stat_value: document.getElementById('s-value').value };
        const r = await API.admin.updateSiteStat(id, data);
        btn.textContent = 'Save Changes'; // Revert button text
        btn.disabled = false; // Re-enable button
        
        if (r.success) {
            Utils.toast('Stat updated successfully');
            CRUD.close();
            loadSiteStats();
        } else {
            Utils.toast(r.message || 'Failed to update stat', 'error');
        }
    };

    // --- SITE SETTINGS ---
    let currentSettings = [];
    async function loadSiteSettings() {
        const r = await API.admin.getSiteSettings();
        currentSettings = r.data || [];
        renderSiteSettings(currentSettings);
    }
    function renderSiteSettings(list) {
        const esc = (s) => s ? s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;') : '';
        
        // Group by category
        const groups = {};
        list.forEach(s => {
            const cat = s.category || 'general';
            if (!groups[cat]) groups[cat] = [];
            groups[cat].push(s);
        });

        const categories = Object.keys(groups);

        document.getElementById('page-content').innerHTML = `
        <div class="space-y-8">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight">Site Configuration</h3>
                <div class="flex gap-2">
                    ${categories.map(cat => `
                        <a href="#cat-${cat}" class="px-4 py-2 bg-white border border-gray-100 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-primary transition-all">${cat}</a>
                    `).join('')}
                </div>
            </div>

            <form onsubmit="saveSiteSettings(event)" class="space-y-8">
                ${categories.map(cat => `
                    <div id="cat-${cat}" class="card p-8 scroll-mt-20">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-10 h-10 rounded-2xl bg-primary/10 flex items-center justify-center text-primary text-xl">
                                ${cat === 'payment' ? '💳' : cat === 'branding' ? '🏷️' : cat === 'contact' ? '📞' : cat === 'style' ? '🎨' : cat === 'placeholders' ? '🖼️' : '⚙️'}
                            </div>
                            <h4 class="text-lg font-black text-gray-900 uppercase tracking-tight">${cat} Settings</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            ${(cat === 'placeholders' 
                                ? groups[cat].filter(s => ['placeholder_blog_image', 'placeholder_destination_image', 'placeholder_trip_cover_image', 'placeholder_trip_map_image', 'placeholder_trip_gallery_image'].includes(s.s_key)) 
                                : groups[cat]).map(s => {
                                 const isToggle   = s.s_key.includes('enabled');
                                 const isSecret   = s.s_key.includes('secret') || s.s_key.includes('key');
                                 const isTextarea = s.s_key === 'site_icon_svg' || s.s_key.includes('address');
                                 const isImage    = !isTextarea && (s.s_key.includes('placeholder') || s.s_key.includes('logo') || s.s_key.includes('icon'));
                                const labelMap = {
                                    'placeholder_blog_image': '1. Blog Image Thumbnail',
                                    'placeholder_destination_image': '2. Destination Image',
                                    'placeholder_trip_cover_image': '3. Trip Cover Image',
                                    'placeholder_trip_map_image': '4. Trip Map Image',
                                    'placeholder_trip_gallery_image': '5. Trip Gallery Image',
                                    'site_icon_svg': 'Brand Icon SVG (Manual Code)'
                                };
                                 const label = labelMap[s.s_key] || s.s_key.replace(/_/g,' ').replace('payment ','').replace('placeholder ','');
                                 
                                 // Robust Image Pathing
                                 const getImgUrl = (url) => {
                                     if (!url) return '';
                                     if (url.startsWith('http')) return url;
                                     const base = window.APP_CONFIG?.BASE_URL || window.location.origin + '/IBCCTRIP'; // Default to /IBCCTRIP if APP_CONFIG.BASE_URL is not set
                                     return base.replace(/\/$/, '') + '/' + url.replace(/^\//, '');
                                 };
                                 const imgUrl = getImgUrl(s.s_value);
                                
                                return `
                                    <div class="${isToggle || isImage ? 'flex items-center justify-between bg-gray-50/50 p-4 rounded-2xl border border-gray-100' : ''} ${isImage ? 'col-span-2' : ''}">
                                        <div class="${isToggle || isImage ? '' : 'space-y-2'}">
                                            <label class="block text-[10px] font-black uppercase tracking-widest ${isToggle || isImage ? 'text-gray-900' : 'text-gray-400'}">${label}</label>
                                            ${isToggle ? `<p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Turn this feature ON or OFF</p>` : ''}
                                            ${isImage ? `<p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">SEO Dummy Image Fallback</p>` : ''}
                                        </div>
                                        
                                        ${isToggle ? `
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" data-key="${s.s_key}" class="sr-only peer" ${s.s_value === '1' ? 'checked' : ''}>
                                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                            </label>
                                        ` : isImage ? `
                                             <div class="relative w-32 h-32 group cursor-pointer border-2 border-dashed border-gray-200 rounded-2xl overflow-hidden bg-gray-50 flex items-center justify-center" 
                                                  onclick="this.querySelector('input').click()">
                                                 <img id="${s.s_key}-prev" src="${imgUrl}" class="w-full h-full object-cover ${s.s_value?'':'hidden'}">
                                                 <div id="${s.s_key}-placeholder" class="text-center ${s.s_value?'hidden':''}">
                                                     <span class="text-2xl text-gray-300">📷</span>
                                                     <p class="text-[9px] text-gray-400 font-bold uppercase mt-1">Select</p>
                                                 </div>
                                                 <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white">
                                                     <span class="text-xs font-bold uppercase tracking-tighter">${s.s_value?'Replace':'Upload'}</span>
                                                 </div>
                                                 <input type="file" data-key="${s.s_key}" accept="image/*" class="hidden" onchange="previewImage(this, '${s.s_key}-prev', '${s.s_key}-placeholder')">
                                             </div>
                                         ` : isTextarea ? `
                                             <textarea data-key="${s.s_key}" rows="5" 
                                                       class="w-full bg-gray-50 border border-gray-100 rounded-2xl py-3.5 px-5 text-sm font-mono focus:outline-none focus:border-primary focus:bg-white transition-all"
                                                       placeholder="Paste information here...">${esc(s.s_value)}</textarea>
                                        ` : `
                                            <input type="${isSecret ? 'password' : 'text'}" 
                                                   data-key="${s.s_key}" 
                                                   value="${esc(s.s_value)}" 
                                                   placeholder="Enter ${label}..."
                                                   class="w-full bg-gray-50 border border-gray-100 rounded-2xl py-3.5 px-5 text-sm font-medium focus:outline-none focus:border-primary focus:bg-white transition-all">
                                        `}
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    </div>
                `).join('')}
                
                <div class="sticky bottom-8 z-10 flex justify-center">
                    <button type="submit" class="bg-primary text-white font-black uppercase tracking-widest px-12 py-5 rounded-3xl shadow-2xl shadow-primary/40 hover:scale-105 active:scale-95 transition-all">
                        Save Configuration
                    </button>
                </div>
            </form>
        </div>`;
    }
    
    window.saveSiteSettings = async (e) => {
        e.preventDefault();
        const btn = e.target.querySelector('button');
        btn.disabled = true; btn.textContent = 'Saving...';
        
        const fd = new FormData();
        const files = {};
        
        // First pass: gather files
        e.target.querySelectorAll('input[type="file"]').forEach(inp => {
            if (inp.files[0]) {
                const key = inp.dataset.key;
                fd.append(key, inp.files[0]);
                files[key] = true;
            }
        });

        // Second pass: gather other fields, skip text value if file exists for same key
        e.target.querySelectorAll('[data-key]').forEach(inp => { 
            const key = inp.dataset.key;
            if (inp.type === 'file') return; // Handled in first pass
            
            if (inp.type === 'checkbox') {
                fd.append(key, inp.checked ? '1' : '0');
            } else {
                // Only send text value if no file was selected for this key
                if (!files[key]) {
                    fd.append(key, inp.value);
                }
            }
        });

        try {
            const r = await API.admin.updateSiteSettings(fd);
            btn.textContent = 'Save Configuration';
            btn.disabled = false;
            
            if (r.success) {
                Utils.toast('Settings updated successfully');
                loadSiteSettings();
            } else {
                Utils.toast(r.message || 'Failed to update settings', 'error');
            }
        } catch (err) {
            Utils.toast('Connection error', 'error');
            btn.disabled = false; btn.textContent = 'Save Configuration';
        }
    };
    
    // --- GALLERY ---
    async function loadGallery() {
        try {
            const r = await fetch(API_URL + '/admin.php?resource=gallery&action=list', { credentials: 'include' }).then(res => res.json());
            const list = r?.data || [];
            
            document.getElementById('page-content').innerHTML = `
            <div class="flex items-center justify-between mb-8">
                <div><h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight">Gallery Media</h3><p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Manage global site media assets</p></div>
                <button onclick="addGalleryImage()" class="bg-primary text-white font-black px-8 py-3.5 rounded-2xl shadow-xl shadow-primary/20 hover:scale-105 active:scale-95 transition-all text-[10px] uppercase tracking-widest whitespace-nowrap">+ Upload New</button>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                ${list.map(img => `
                    <div class="group relative aspect-square bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all">
                        <img src="${img.image_url}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-all flex flex-col items-center justify-center p-4">
                            <span class="text-[9px] text-white/50 font-bold uppercase tracking-widest mb-1">Category</span>
                            <p class="text-[11px] text-white font-black uppercase tracking-widest mb-4 line-clamp-1">${img.category || 'General'}</p>
                            <button onclick="deleteEntity('gallery', ${img.id})" class="bg-red-500 text-white px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-600 transition-all border-2 border-white/20 shadow-lg">Delete</button>
                        </div>
                    </div>
                `).join('') || '<div class="col-span-full py-20 text-center text-gray-400 font-black uppercase tracking-widest border-2 border-dashed border-gray-200 rounded-3xl">Your media gallery is empty</div>'}
            </div>
        `;
        } catch (err) {
            console.error(err);
            document.getElementById('page-content').innerHTML = `
                <div class="py-20 text-center">
                    <p class="text-red-500 font-bold uppercase tracking-widest">Error Loading Gallery</p>
                    <p class="text-xs text-gray-400 mt-2">${err.message}</p>
                </div>
            `;
        }
    }

    window.addGalleryImage = () => {
        CRUD.show('Add Media', `
            <form onsubmit="saveGalleryImage(event)" class="space-y-6">
                <div class="space-y-4">
                    <label class="block text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Upload File</label>
                    <div class="flex justify-center">
                        ${renderImagePicker('g-file')}
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1 mb-2">Category</label>
                    <input type="text" id="g-category" placeholder="e.g. India, Adventure, Hotel" class="w-full bg-gray-50 border border-gray-100 rounded-2xl py-3.5 px-5 text-sm font-medium focus:outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1 mb-2">Caption (Optional)</label>
                    <input type="text" id="g-caption" placeholder="Short description..." class="w-full bg-gray-50 border border-gray-100 rounded-2xl py-3.5 px-5 text-sm font-medium focus:outline-none focus:border-primary">
                </div>
                <button type="submit" class="w-full bg-primary text-white font-black uppercase tracking-widest py-4 rounded-2xl shadow-xl hover:shadow-2xl transition-all">Upload to Gallery</button>
            </form>
        `);
    };

    window.saveGalleryImage = async (e) => {
        e.preventDefault();
        const btn = e.target.querySelector('button');
        const fileInp = document.getElementById('g-file');
        
        if (!fileInp.files[0]) return Utils.toast('Please select an image', 'error');
        
        btn.disabled = true; btn.textContent = 'Uploading...';
        
        const fd = new FormData();
        fd.append('file', fileInp.files[0]);
        fd.append('category', document.getElementById('g-category').value);
        fd.append('caption', document.getElementById('g-caption').value);
        
        try {
            const r = await fetch(API_URL + '/admin.php?resource=gallery&action=create', {
                method: 'POST',
                body: fd,
                credentials: 'include'
            }).then(res => res.json());
            
            if(r.success) {
                Utils.toast('Media added to gallery');
                CRUD.close();
                loadGallery();
            }
        } catch (err) {
            Utils.toast('Upload error', 'error');
        } finally {
            btn.disabled = false; btn.textContent = 'Upload to Gallery';
        }
    };

    // --- MESSAGES ---
    let currentMsgStatus = '';
    async function loadMessages(status = '') { 
        currentMsgStatus = status;
        const r = await API.admin.getMessages({ status });
        const list = r?.data || [];
        const statuses = ['','New','Trying to Contact','Talked','Replied'];
        
        document.getElementById('page-content').innerHTML = `
        <div class="flex flex-wrap gap-2 mb-5">
            ${statuses.map(s => `
                <button onclick="loadMessages('${s}')" class="px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-wider transition-all ${currentMsgStatus===s?'bg-primary text-white shadow-lg scale-105':'bg-white text-gray-400 hover:bg-gray-50'}">${s||'All'}</button>
            `).join('')}
        </div>
        <div class="card overflow-hidden">
          <table class="w-full tbl text-left">
            <thead class="bg-gray-50 border-b border-gray-100"><tr><th class="p-4">Date</th><th class="p-4">Sender</th><th class="p-4">Subject</th><th class="p-4">Work Status</th><th class="p-4">Action</th></tr></thead>
            <tbody>${list.map(m => `<tr>
              <td class="p-4 text-xs text-gray-400 font-medium">${Utils.formatDate(m.created_at)}</td>
              <td class="p-4">
                <p class="font-extrabold text-gray-900">${m.name}</p>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">${m.email}</p>
              </td>
              <td class="p-4"><p class="text-sm font-bold text-gray-700">${m.subject}</p></td>
              <td class="p-4">
                <span class="text-[10px] px-2.5 py-1 rounded-full font-black uppercase tracking-tighter ${m.status==='New'?'bg-blue-50 text-blue-600':m.status==='Talked'?'bg-green-50 text-green-600':'bg-gray-100 text-gray-500'}">
                    ${m.status}
                </span>
                ${!m.is_read ? '<span class="ml-1 w-2 h-2 rounded-full bg-red-500 inline-block animate-pulse"></span>' : ''}
              </td>
              <td class="p-4">
                <div class="flex gap-3">
                    <button onclick="viewMessage(${m.id})" class="text-primary font-black text-xs hover:underline uppercase tracking-widest">Detail</button>
                    <button onclick="deleteEntity('messages', ${m.id})" class="text-red-500 font-black text-xs hover:underline uppercase tracking-widest">Del</button>
                </div>
              </td>
            </tr>`).join('') || '<tr><td colspan="5" class="p-10 text-center text-gray-400 font-bold">Inquiries Inbox is empty</td></tr>'}</tbody>
          </table>
        </div>`;
    }

    window.viewMessage = async (id) => {
        const r = await API.admin.getMessages({ status: currentMsgStatus }); 
        const m = r.data.find(x => x.id === id);
        if(!m) return;
        if(!m.is_read) { await API.admin.updateMessage(id, { is_read: 1 }); updateUnreadCount(); }
        
        const statuses = ['New','Trying to Contact','Talked','Replied'];
        
        CRUD.show('Inquiry Workflow', `
            <div class="space-y-6">
                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-6 bg-gray-50 p-5 rounded-3xl border border-gray-100 mb-2">
                    <div>
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">From</p>
                        <p class="font-black text-gray-900">${m.name}</p>
                        <p class="text-xs text-primary font-bold">${m.email}</p>
                        <p class="text-xs text-gray-500 mt-0.5">${m.phone||'No Phone'}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">Received</p>
                        <p class="text-xs font-bold text-gray-900">${Utils.formatDate(m.created_at)}</p>
                        <p class="text-[10px] text-gray-400 mt-1">${m.subject}</p>
                    </div>
                </div>

                <!-- Message Content -->
                <div class="space-y-2">
                    <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Message Content</p>
                    <div class="bg-primary/5 p-5 rounded-3xl text-gray-700 text-sm leading-relaxed border border-primary/10 italic">
                        "${m.message}"
                    </div>
                </div>

                <!-- Admin Workflow -->
                <form onsubmit="saveMessageWorkflow(event, ${id})" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1 mb-2 block">Inquiry Status</label>
                            <select id="m-status" class="w-full bg-white border border-gray-100 py-3 px-4 rounded-2xl text-sm focus:outline-none focus:border-primary">
                                ${statuses.map(s => `<option value="${s}" ${m.status===s?'selected':''}>${s}</option>`).join('')}
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1 mb-2 block">Quick Actions</label>
                            <div class="flex gap-2">
                                <a href="tel:${m.phone}" class="flex-1 bg-green-500 text-white p-3 rounded-2xl text-center text-xs font-black shadow-lg">Call</a>
                                <a href="mailto:${m.email}" class="flex-1 bg-blue-500 text-white p-3 rounded-2xl text-center text-xs font-black shadow-lg">Mail</a>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1 mb-2 block">Admin Notes (What happened on call?)</label>
                        <textarea id="m-notes" rows="4" class="w-full bg-gray-50 border border-gray-100 py-3 px-4 rounded-2xl text-sm focus:outline-none focus:border-primary" placeholder="Type customer response or follow-up details here...">${m.admin_notes||''}</textarea>
                    </div>
                    <button type="submit" class="w-full bg-secondary text-white font-black uppercase tracking-widest py-4 rounded-2xl shadow-xl hover:shadow-2xl transition-all">Save & Update Inquiry</button>
                </form>
            </div>
        `);
    };

    window.saveMessageWorkflow = async (e, id) => {
        e.preventDefault();
        const btn = e.target.querySelector('button');
        btn.disabled = true; btn.textContent = 'Saving...';
        
        try {
            const data = {
                status: document.getElementById('m-status').value,
                admin_notes: document.getElementById('m-notes').value
            };
            const r = await API.admin.updateMessage(id, data);
            if(r.success) {
                Utils.toast('Workflow updated');
                CRUD.close();
                loadMessages(currentMsgStatus);
            } else {
                btn.disabled = false; btn.textContent = 'Save & Update Inquiry';
            }
        } catch (err) {
            Utils.toast('Error saving workflow', 'error');
            btn.disabled = false; btn.textContent = 'Save & Update Inquiry';
        }
    };

    // --- REUSABLE DELETE ---
    window.deleteEntity = async (type, id) => {
        if(!confirm('Are you sure you want to delete this item? This action cannot be undone.')) return;
        let r;
        if(type==='trips') r = await API.admin.deleteTrip(id);
        else if(type==='blogs') r = await API.admin.deleteBlog(id);
        else if(type==='bookings') r = await API.admin.deleteBooking(id);
        else if(type==='testimonials') r = await API.admin.deleteTestimonial(id);
        else if(type==='messages') r = await API.admin.deleteMessage(id);
        else if(type==='gallery') r = await fetch(API_URL + '/admin.php?resource=gallery&action=delete&id=' + id, { method: 'POST', credentials: 'include' }).then(res => res.json());
        else r = await API.admin.deleteDestination(type, id);
        
        if(r?.success) {
            Utils.toast('Deleted successfully');
            if(type==='trips') loadTrips();
            else if(type==='blogs') loadBlogs();
            else if(type==='bookings') loadBookings();
            else if(type==='testimonials') loadTestimonials();
            else if(type==='messages') loadMessages();
            else if(type==='gallery') loadGallery();
            else loadDestinations(type);
        } else {
            Utils.toast(r?.message || 'Delete failed', 'error');
        }
    };
    async function updateUnreadCount() {
       const r = await API.admin.getDashboardStats();
       const count = r?.data?.unread_messages || 0;
       const el = document.getElementById('unread-count');
       if (count > 0) { el.textContent = count; el.classList.replace('hidden', 'flex'); }
       else { el.classList.replace('flex', 'hidden'); }
    }

    function logout() { Session.logout(); }

    init();
  </script>
</body>
</html>
