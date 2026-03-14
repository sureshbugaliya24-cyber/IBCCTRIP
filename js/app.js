/**
 * IBCC Trip — Global API Client & Utilities
 * Handles all fetch calls to the backend REST API
 */

const IBCC = {
    BASE: window.APP_CONFIG?.API_URL || '/ibcctrip/backend/api',

    // ---------- HTTP helpers ----------
    async get(endpoint, params = {}) {
        const url = new URL(this.BASE + endpoint, location.origin);
        Object.entries(params).forEach(([k, v]) => v !== undefined && url.searchParams.set(k, v));
        const res = await fetch(url.toString(), { credentials: 'include' });
        return res.json();
    },

    async post(endpoint, body = {}) {
        const isFormData = body instanceof FormData;
        const headers = isFormData ? {} : { 'Content-Type': 'application/json' };
        const reqBody = isFormData ? body : JSON.stringify(body);
        const res = await fetch(this.BASE + endpoint, {
            method: 'POST',
            headers,
            credentials: 'include',
            body: reqBody,
        });
        return res.json();
    },

    async put(endpoint, body = {}) {
        // Warning: PHP natively struggles with PUT requests containing multipart/form-data.
        // If we are sending FormData, it's safer to POST and let backend handle it as an update via action params.
        const isFormData = body instanceof FormData;
        // For our API, if it's FormData, we often override to POST method to let $_FILES populate.
        const method = isFormData ? 'POST' : 'PUT';
        const headers = isFormData ? {} : { 'Content-Type': 'application/json' };
        const reqBody = isFormData ? body : JSON.stringify(body);
        
        const res = await fetch(this.BASE + endpoint, {
            method,
            headers,
            credentials: 'include',
            body: reqBody,
        });
        return res.json();
    },

    // ---------- Auth ----------
    auth: {
        async session()              { return IBCC.get('/auth.php', { action: 'session' }); },
        async login(data)     { return IBCC.post('/auth.php?action=login', data); },
        async register(data)  { return IBCC.post('/auth.php?action=register', data); },
        async logout()               { return IBCC.get('/auth.php', { action: 'logout' }); },
        async updateProfile(data)    { return IBCC.post('/auth.php?action=profile', data); },
        async me()                   { return IBCC.get('/auth.php', { action: 'me' }); },
    },

    // ---------- Trips ----------
    trips: {
        featured()         { return IBCC.get('/trips.php', { action: 'featured' }); },
        search(q)          { return IBCC.get('/trips.php', { action: 'search', q }); },
        list(params = {})  { return IBCC.get('/trips.php', { action: 'list', ...params }); },
        detail(slug)       { return IBCC.get('/trips.php', { action: 'detail', slug }); },
    },

    // ---------- Bookings ----------
    bookings: {
        create(data)       { return IBCC.post('/bookings.php?action=create', data); },
        myBookings()       { return IBCC.get('/bookings.php', { action: 'my-bookings' }); },
        invoice(id)        { return IBCC.get('/bookings.php', { action: 'invoice', id }); },
        cancel(id)         { return IBCC.get('/bookings.php', { action: 'cancel', id }); },
        verifyPayment(data) { return IBCC.post('/bookings.php?action=verify-payment', data); },
    },

    // ---------- Blogs ----------
    blogs: {
        list(params = {})  { return IBCC.get('/blogs.php', { action: 'list', ...params }); },
        detail(slug)       { return IBCC.get('/blogs.php', { action: 'detail', slug }); },
        categories()       { return IBCC.get('/blogs.php', { action: 'categories' }); },
        recent()           { return IBCC.get('/blogs.php', { action: 'recent' }); },
    },

    // ---------- Locations ----------
    locations: {
        countries()                    { return IBCC.get('/locations.php', { action: 'countries' }); },
        country(slug)                  { return IBCC.get('/locations.php', { action: 'country', slug }); },
        states(country_id)             { return IBCC.get('/locations.php', { action: 'states', country_id }); },
        state(slug)                    { return IBCC.get('/locations.php', { action: 'state', slug }); },
        cities(state_id)               { return IBCC.get('/locations.php', { action: 'cities', state_id }); },
        city(slug)                     { return IBCC.get('/locations.php', { action: 'city', slug }); },
        places(city_id)                { return IBCC.get('/locations.php', { action: 'places', city_id }); },
        place(slug)                    { return IBCC.get('/locations.php', { action: 'place', slug }); },
    },

    // ---------- Testimonials & Stats ----------
    testimonials: {
        list(limit)  { return IBCC.get('/testimonials.php', { limit }); },
        stats()      { return IBCC.get('/testimonials.php', { action: 'stats' }); }
    },

    // ---------- Contact ----------
    contact: {
        submit(data) { return IBCC.post('/contact.php', data); }
    },

    // ---------- Admin ----------
    admin: {
        getDashboardStats() { return IBCC.get('/admin.php', { resource: 'stats' }); },
        
        getTrips(params)    { return IBCC.get('/admin.php', { resource: 'trips', action: 'list', ...params }); },
        getTripDetail(id)    { return IBCC.get('/admin.php', { resource: 'trips', action: 'detail', id }); },
        createTrip(data)    { return IBCC.post('/admin.php?resource=trips&action=create', data); },
        updateTrip(id, data){ return IBCC.put('/admin.php?resource=trips&action=update&id=' + id, data); },
        deleteTrip(id)      { return IBCC.get('/admin.php', { resource: 'trips', action: 'delete', id }); },
        
        getTestimonials()     { return IBCC.get('/admin.php', { resource: 'testimonials', action: 'list' }); },
        createTestimonial(fd) { return IBCC.post('/admin.php?resource=testimonials&action=create', fd); },
        updateTestimonial(id, fd) { return IBCC.post('/admin.php?resource=testimonials&action=update&id=' + id, fd); },
        deleteTestimonial(id) { return IBCC.get('/admin.php', { resource: 'testimonials', action: 'delete', id }); },

        getSiteStats()        { return IBCC.get('/admin.php', { resource: 'site_stats', action: 'list' }); },
        updateSiteStat(id, data) { return IBCC.put('/admin.php?resource=site_stats&action=update&id=' + id, data); },

        getSiteSettings() { return IBCC.get('/admin.php', { resource: 'site_settings', action: 'list' }); },
        updateSiteSettings(data) { 
            if (data instanceof FormData) return IBCC.post('/admin.php?resource=site_settings&action=update', data);
            return IBCC.post('/admin.php?resource=site_settings&action=update', { settings: data }); 
        },

        getBookings(params) { return IBCC.get('/admin.php', { resource: 'bookings', action: 'list', ...params }); },
        updateBooking(id, status) { return IBCC.put('/admin.php?resource=bookings&action=update-status&id=' + id, { status }); },
        deleteBooking(id) { return IBCC.get('/admin.php', { resource: 'bookings', action: 'delete', id }); },

        getMessages(params)   { return IBCC.get('/admin.php', { resource: 'messages', action: 'list', ...params }); },
        updateMessage(id, data) { return IBCC.post('/admin.php?resource=messages&action=update&id=' + id, data); },
        markMessageRead(id) { return IBCC.get('/admin.php', { resource: 'messages', action: 'mark-read', id }); },
        deleteMessage(id)   { return IBCC.get('/admin.php', { resource: 'messages', action: 'delete', id }); },
        
        getCustomers(params){ return IBCC.get('/admin.php', { resource: 'customers', action: 'list', ...params }); },
        
        getBlogs(params)    { return IBCC.get('/admin.php', { resource: 'blogs', action: 'list', ...params }); },
        createBlog(data)    { return IBCC.post('/admin.php?resource=blogs&action=create', data); },
        updateBlog(id, data){ return IBCC.put('/admin.php?resource=blogs&action=update&id=' + id, data); },
        deleteBlog(id)      { return IBCC.get('/admin.php', { resource: 'blogs', action: 'delete', id }); },
        
        getDestinations(type) { return IBCC.get('/admin.php', { resource: type, action: 'list' }); },
        createDestination(type, data) { return IBCC.post('/admin.php?resource='+type+'&action=create', data); },
        updateDestination(type, id, data) { return IBCC.put('/admin.php?resource='+type+'&action=update&id=' + id, data); },
        deleteDestination(type, id) { return IBCC.get('/admin.php', { resource: type, action: 'delete', id }); }
    },
};

// ---------- Session State ----------
const Session = {
    user: null,
    async init() {
        try {
            const r = await IBCC.auth.session();
            if (r?.data?.logged_in) {
                this.user = r.data;
            } else if (r?.data) {
                // Not logged in, but we still get public data like active_payments
                this.user = { logged_in: false, active_payments: r.data.active_payments || [] };
            }
        } catch (_) {}
        this.updateNavbar();
        return this.user;
    },
    isLoggedIn() { return this.user?.logged_in === true; },
    isAdmin()    { return this.user?.logged_in === true && this.user?.role === 'admin'; },
    async logout() {
        await IBCC.auth.logout();
        this.user = null;
        location.href = './';
    },
    updateNavbar() {
        const loginBtn      = document.getElementById('nav-login');
        const dashboardBtn  = document.getElementById('nav-dashboard');
        const userNameEl    = document.getElementById('nav-user-name');

        const mLoginBtn     = document.getElementById('mobile-login');
        const mUserWrap     = document.getElementById('mobile-user');
        const mNameEl       = document.getElementById('mobile-user-name');
        const mAvatarEl     = document.getElementById('mobile-avatar');
        const mDashBtn      = document.getElementById('mobile-dashboard-btn');

        if (this.isLoggedIn()) {
            const firstName = this.user.name.split(' ')[0];
            const dashLink = this.isAdmin() ? dashboardBtn?.href.replace('/dashboard', '/admin/dashboard.php') : dashboardBtn?.href.replace('/admin/dashboard.php', '/dashboard');
            
            // Desktop
            if (loginBtn) loginBtn.style.display = 'none';
            if (dashboardBtn) {
                dashboardBtn.style.display = 'flex';
                dashboardBtn.href = dashLink || dashboardBtn.href;
            }
            if (userNameEl) userNameEl.textContent = firstName;
            const navAvatar = document.getElementById('nav-avatar');
            if (navAvatar) navAvatar.textContent = this.user.name[0].toUpperCase();
            
            // Mobile
            if (mLoginBtn) mLoginBtn.style.display = 'none';
            if (mUserWrap) mUserWrap.classList.remove('hidden');
            if (mNameEl) mNameEl.textContent = this.user.name;
            if (mAvatarEl) mAvatarEl.textContent = this.user.name[0].toUpperCase();
            if (mDashBtn && dashLink) mDashBtn.href = dashLink;
        } else {
            // Desktop
            if (loginBtn) loginBtn.style.display = 'inline-block';
            if (dashboardBtn) dashboardBtn.style.display = 'none';
            
            // Mobile
            if (mLoginBtn) mLoginBtn.style.display = 'block';
            if (mUserWrap) mUserWrap.classList.add('hidden');
        }
    }
};

// ---------- Utilities ----------
const Utils = {
    formatPrice(price) {
        return '₹' + parseFloat(price).toLocaleString('en-IN', { minimumFractionDigits: 0 });
    },

    formatDate(dateStr) {
        if (!dateStr) return '—';
        return new Date(dateStr).toLocaleDateString('en-IN', {
            day: '2-digit', month: 'short', year: 'numeric'
        });
    },

    slugify(str) {
        return str.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
    },

    getParam(key) {
        return new URLSearchParams(location.search).get(key);
    },

    statusBadge(status) {
        const colors = {
            'Pending':     'bg-yellow-100 text-yellow-800',
            'Scheduled':   'bg-blue-100 text-blue-800',
            'In Progress': 'bg-orange-100 text-orange-800',
            'Completed':   'bg-green-100 text-green-800',
            'Cancelled':   'bg-red-100 text-red-800',
        };
        return `<span class="px-2 py-1 rounded-full text-xs font-semibold ${colors[status] || 'bg-gray-100 text-gray-600'}">${status}</span>`;
    },

    toast(msg, type = 'success') {
        const el = document.createElement('div');
        const colors = { success: 'bg-green-600', error: 'bg-red-600', info: 'bg-blue-600' };
        el.className = `fixed top-5 right-5 z-[9999] px-6 py-3 rounded-xl text-white font-medium shadow-xl transform translate-x-full transition-transform duration-300 ${colors[type] || 'bg-gray-700'}`;
        el.textContent = msg;
        document.body.appendChild(el);
        setTimeout(() => el.classList.remove('translate-x-full'), 50);
        setTimeout(() => {
            el.classList.add('translate-x-full');
            setTimeout(() => el.remove(), 300);
        }, 3500);
    },

    setMeta(title, description, image) {
        if (title) document.title = title + ' | IBCC Trip';
        const desc = document.querySelector('meta[name="description"]');
        if (desc && description) desc.setAttribute('content', description);
        const og_title = document.querySelector('meta[property="og:title"]');
        if (og_title && title) og_title.setAttribute('content', title);
        const og_desc  = document.querySelector('meta[property="og:description"]');
        if (og_desc && description) og_desc.setAttribute('content', description);
        const og_img   = document.querySelector('meta[property="og:image"]');
        if (og_img && image) og_img.setAttribute('content', image);
    },

    tripCard(trip) {
        const price    = trip.discounted_price || trip.base_price;
        const original = trip.discounted_price ? Utils.formatPrice(trip.base_price) : null;
        const badge    = trip.discounted_price ? `<span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">SALE</span>` : '';
        const origHtml = original ? `<span class="text-gray-400 line-through text-xs mr-1">${original}</span>` : '';

        return `
        <article class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 group border border-gray-100 cursor-pointer" onclick="location.href='trip/${trip.slug}'">
          <div class="relative overflow-hidden h-52">
            <img src="${trip.cover_image || 'https://images.unsplash.com/photo-1488085061387-422e29b40080?w=600'}"
                 alt="${trip.title}" loading="lazy"
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            ${badge}
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
            <div class="absolute bottom-3 left-3 flex gap-2">
              <span class="bg-white/90 backdrop-blur text-xs font-semibold px-2 py-1 rounded-full text-blue-800">${trip.duration_days} Days</span>
              <span class="bg-white/90 backdrop-blur text-xs font-semibold px-2 py-1 rounded-full text-gray-700">${trip.trip_type || 'Tour'}</span>
            </div>
          </div>
          <div class="p-4">
            <p class="text-xs text-orange-500 font-semibold mb-1">${trip.country_name || ''}</p>
            <h3 class="font-bold text-gray-900 text-base mb-2 line-clamp-2 group-hover:text-blue-700 transition-colors">${trip.title}</h3>
            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
              <div>
                ${origHtml}
                <span class="text-blue-700 font-bold text-lg">${Utils.formatPrice(price)}</span>
                <span class="text-gray-400 text-xs">/person</span>
              </div>
              <button class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition-colors">Book Now</button>
            </div>
          </div>
        </article>`;
    },

    blogCard(blog) {
        return `
        <article class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 group cursor-pointer border border-gray-100" onclick="location.href='blog/${blog.slug}'">
          <div class="relative overflow-hidden h-48">
            <img src="${blog.featured_image || 'https://images.unsplash.com/photo-1488085061387-422e29b40080?w=600'}"
                 alt="${blog.title}" loading="lazy"
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
          </div>
          <div class="p-4">
            ${blog.category_name ? `<span class="text-xs bg-blue-50 text-blue-700 font-semibold px-2 py-1 rounded-full">${blog.category_name}</span>` : ''}
            <h3 class="font-bold text-gray-900 mt-2 mb-1 line-clamp-2 group-hover:text-blue-700 transition-colors">${blog.title}</h3>
            <p class="text-gray-500 text-sm line-clamp-2">${blog.excerpt || ''}</p>
            <p class="text-xs text-gray-400 mt-3">${Utils.formatDate(blog.created_at)}</p>
          </div>
        </article>`;
    }
};

// ---------- Lazy load images ----------
document.addEventListener('DOMContentLoaded', () => {
    if ('IntersectionObserver' in window) {
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    const img = e.target;
                    if (img.dataset.src) { img.src = img.dataset.src; img.removeAttribute('data-src'); }
                    obs.unobserve(img);
                }
            });
        }, { rootMargin: '200px' });
        document.querySelectorAll('img[data-src]').forEach(img => obs.observe(img));
    }
});
