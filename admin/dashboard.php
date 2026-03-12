<?php
// admin/dashboard.php — Unified Admin Dashboard
// ─────────────────────────────────────────────────────────────

require_once __DIR__ . '/../components/config.php';
require_once __DIR__ . '/../components/helpers.php';

// Simple guard: in a real app, we'd check session role
// Frontend JS handles the actual redirect to login if no admin session
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | IBCC Trip</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { primary: '#0B3D91', secondary: '#FF6B00', dark: '#0A1628' },
          fontFamily: { sans: ['Inter', 'sans-serif'] }
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- Quill Editor Setup -->
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
  <style>
    body { font-family: 'Inter', sans-serif; background: #F3F4F6; }
    .sidebar-link.active { background: #0B3D91; color: white; box-shadow: 0 10px 15px -3px rgba(11, 61, 145, 0.3); }
    .card { background: white; border-radius: 1.5rem; border: 1px solid #F3F4F6; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .tbl th { background: #F9FAFB; padding: 12px 16px; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #6B7280; text-align: left; }
    .tbl td { padding: 14px 16px; font-size: 13px; border-bottom: 1px solid #F3F4F6; }
    .spinner { width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #0B3D91; border-radius: 50%; animation: spin 1s linear infinite; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
  </style>
</head>
<body class="flex min-h-screen">

  <!-- Sidebar -->
  <aside class="w-64 bg-white border-r border-gray-100 flex flex-col sticky top-0 h-screen shrink-0 z-20">
    <div class="p-6 border-b border-gray-50">
      <div class="flex items-center gap-2">
        <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-white font-bold italic">IT</div>
        <span class="font-black text-gray-900 tracking-tight">IBCC <span class="text-secondary font-extrabold">ADMIN</span></span>
      </div>
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
      <button onclick="switchTab('blogs')" id="nav-blogs" class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-gray-500 hover:bg-gray-50">
        <span>📝</span> Blogs
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
      else if (tabId === 'customers') loadCustomers();
      else if (tabId === 'users') loadUsers();
      else if (tabId === 'messages') loadMessages();
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
              <tbody>${(data.recent_bookings || []).map(b => `<tr><td>${b.full_name || b.customer_name}</td><td>${b.trip_title}</td><td><span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase bg-blue-50 text-blue-600">${b.status}</span></td></tr>`).join('') || '<tr><td colspan="3" class="text-center py-4 text-gray-400">No recent bookings</td></tr>'}</tbody>
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
            <thead class="bg-gray-50 border-b border-gray-100"><tr><th class="p-4">Ref</th><th class="p-4">Customer</th><th class="p-4">Trip</th><th class="p-4">Amount</th><th class="p-4">Status</th><th class="p-4">Action</th></tr></thead>
            <tbody>${list.map(b => `<tr>
              <td class="p-4 font-bold text-primary">#${b.booking_reference||b.id}</td>
              <td class="p-4">${b.customer_name||b.full_name}</td>
              <td class="p-4 line-clamp-1">${b.trip_title}</td>
              <td class="p-4 font-bold text-green-600">₹${Number(b.total_price||b.total_amount||0).toLocaleString()}</td>
              <td class="p-4">${Utils.statusBadge(b.status)}</td>
              <td class="p-4">
                <select onchange="updateBookingStatus(${b.id}, this.value)" class="text-xs border rounded-lg p-1.5 outline-none focus:ring-2 focus:ring-primary/20">
                  <option value="">Status...</option>
                  <option value="Pending">Pending</option>
                  <option value="Scheduled">Scheduled</option>
                  <option value="Completed">Completed</option>
                  <option value="Cancelled">Cancelled</option>
                </select>
              </td>
            </tr>`).join('') || '<tr><td colspan="6" class="text-center text-gray-400 py-10">No bookings found</td></tr>'}</tbody>
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

    // --- MESSAGES ---
    async function loadMessages() { 
        updateUnreadCount(); 
        document.getElementById('page-content').innerHTML = `
        <div class="card p-20 flex flex-col items-center justify-center text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-3xl mb-4">📬</div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Message Inbox</h3>
            <p class="text-gray-400 max-w-sm mb-6">Contact form submissions and user messages will appear here. This section is currently in read-only mode.</p>
            <button class="bg-primary text-white text-xs font-black uppercase tracking-wider px-8 py-3 rounded-2xl shadow-lg opacity-50 cursor-not-allowed">Coming Soon</button>
        </div>`; 
    }

    // --- REUSABLE DELETE ---
    window.deleteEntity = async (type, id) => {
        if(!confirm('Are you sure you want to delete this item? This action cannot be undone.')) return;
        let r;
        if(type==='trips') r = await API.admin.deleteTrip(id);
        else if(type==='blogs') r = await API.admin.deleteBlog(id);
        else r = await API.admin.deleteDestination(type, id);
        
        if(r?.success) {
            Utils.toast('Deleted successfully');
            if(type==='trips') loadTrips();
            else if(type==='blogs') loadBlogs();
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
