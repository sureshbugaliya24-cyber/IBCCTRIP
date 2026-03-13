/**
 * IBCC Admin CMS Forms
 * All comprehensive edit/create modal forms for Trips, Blogs, Destinations, Users
 */

/* ============================================================
   HELPERS
   ============================================================ */
const buildSel = (list, sel, idKey='id', lbl='name') =>
    `<option value="">-- Select --</option>` +
    list.map(x => `<option value="${x[idKey]}" ${x[idKey]==sel?'selected':''}>${x[lbl]}</option>`).join('');

function fldLabel(text){ return `<label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">${text}</label>`; }
function fldWrap(label, input){ return `<div>${fldLabel(label)}${input}</div>`; }
const tx  = (id,val='',rows=2,ph='') => `<textarea id="${id}" rows="${rows}" class="w-full border rounded-lg p-2 text-sm" placeholder="${ph}">${val}</textarea>`;
const inp = (id,val='',type='text',ph='') => `<input type="${type}" id="${id}" value="${val}" placeholder="${ph}" class="w-full border rounded-lg p-2 text-sm">`;
const sel = (id,opts) => `<select id="${id}" class="w-full border rounded-lg p-2 text-sm">${opts}</select>`;
const fil = (id,multi=false) => `<input type="file" id="${id}" accept="image/*" ${multi?'multiple':''} class="w-full border rounded-lg p-1.5 text-sm bg-white">`;
const sec = (icon,title) => `<div class="border-t pt-4 mt-2"><p class="text-xs font-black text-gray-700 uppercase mb-3">${icon} ${title}</p>`;

/**
 * Renders a square image picker box with hover replace
 */
function renderImagePicker(id, currentUrl=null) {
    return `
    <div class="relative w-32 h-32 group cursor-pointer border-2 border-dashed border-gray-200 rounded-2xl overflow-hidden bg-gray-50 flex items-center justify-center" 
         onclick="document.getElementById('${id}').click()">
        <img id="${id}-prev" src="${currentUrl || ''}" class="w-full h-full object-cover ${currentUrl?'':'hidden'}">
        <div id="${id}-placeholder" class="text-center ${currentUrl?'hidden':''}">
            <span class="text-2xl text-gray-300">📷</span>
            <p class="text-[9px] text-gray-400 font-bold uppercase mt-1">Select Image</p>
        </div>
        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white">
            <span class="text-xs font-bold uppercase tracking-tighter">${currentUrl?'Replace':'Upload'}</span>
        </div>
        <input type="file" id="${id}" accept="image/*" class="hidden" onchange="previewImage(this, '${id}-prev', '${id}-placeholder')">
    </div>`;
}

window.previewImage = function(input, prevId, placeId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const prev = document.getElementById(prevId);
            const place = document.getElementById(placeId);
            prev.src = e.target.result;
            prev.classList.remove('hidden');
            if(place) place.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
};

window.filterStates = function(countryId) {
    const sSelect = document.getElementById('t-sid');
    const filtered = globals.states.filter(s => !countryId || s.country_id == countryId);
    sSelect.innerHTML = buildSel(filtered, '');
    filterCities(''); // Reset child
};

window.filterCities = function(stateId) {
    const cSelect = document.getElementById('t-cityid');
    const filtered = globals.cities.filter(c => !stateId || c.state_id == stateId);
    cSelect.innerHTML = buildSel(filtered, '');
    filterPlaces(''); // Reset child
};

window.filterPlaces = function(cityId) {
    const pSelect = document.getElementById('t-plid');
    const filtered = (globals.places || []).filter(p => !cityId || p.city_id == cityId);
    pSelect.innerHTML = buildSel(filtered, '');
};

/* ============================================================
   TRIPS
   ============================================================ */
window.renderTripForm = function(t={}) {
    const id = t.id || null;
    return `<form onsubmit="saveTrip(event,${id})" class="space-y-1">
    <!-- Basic -->
    <div class="grid grid-cols-2 gap-3">
      ${fldWrap('Title *','<input required id="t-title" value="'+esc(t.title||'')+'" class="w-full border rounded-lg p-2 text-sm">')}
      ${fldWrap('Slug *','<input required id="t-slug" value="'+esc(t.slug||'')+'" class="w-full border rounded-lg p-2 text-sm">')}
    </div>
    <div class="grid grid-cols-4 gap-3">
      ${fldWrap('Base Price (₹)',inp('t-price',t.base_price||'','number'))}
      ${fldWrap('Discount Price',inp('t-dprice',t.discounted_price||'','number'))}
      ${fldWrap('Days',inp('t-days',t.duration_days||'','number'))}
      ${fldWrap('Max Members',inp('t-members',t.max_members||'','number'))}
      ${fldWrap('Difficulty',sel('t-diff',['Easy','Moderate','Hard'].map(v=>`<option value="${v}" ${t.difficulty===v?'selected':''}>${v}</option>`).join('')))}
      ${fldWrap('Trip Type',sel('t-type',['Domestic','International','Adventure','Luxury'].map(v=>`<option value="${v}" ${t.trip_type===v?'selected':''}>${v}</option>`).join('')))}
      ${fldWrap('Featured',sel('t-feat',`<option value="0" ${!t.is_featured?'selected':''}>No</option><option value="1" ${t.is_featured?'selected':''}>Yes</option>`))}
      ${fldWrap('Status',sel('t-active',`<option value="1" ${t.is_active!=0?'selected':''}>Active</option><option value="0" ${t.is_active==0?'selected':''}>Inactive</option>`))}
    </div>

    ${sec('📍','Location')}
    <div class="grid grid-cols-4 gap-3">
      ${fldWrap('Country', `<select id="t-cid" onchange="filterStates(this.value)" class="w-full border rounded-lg p-2 text-sm">${buildSel(globals.countries, t.country_id)}</select>`)}
      ${fldWrap('State', `<select id="t-sid" onchange="filterCities(this.value)" class="w-full border rounded-lg p-2 text-sm">${buildSel(globals.states.filter(s => !t.country_id || s.country_id == t.country_id), t.state_id)}</select>`)}
      ${fldWrap('City', `<select id="t-cityid" onchange="filterPlaces(this.value)" class="w-full border rounded-lg p-2 text-sm">${buildSel(globals.cities.filter(c => !t.state_id || c.state_id == t.state_id), t.city_id)}</select>`)}
      ${fldWrap('Place', `<select id="t-plid" class="w-full border rounded-lg p-2 text-sm">${buildSel((globals.places || []).filter(p => !t.city_id || p.city_id == t.city_id), t.place_id)}</select>`)}
    </div></div>

    ${sec('🖼️','Featured Images')}
    <div class="grid grid-cols-2 gap-5 py-2">
      <div class="flex gap-4">
        <div>${fldLabel('Cover Image')}${renderImagePicker('t-image', t.cover_image)}</div>
        <div>${fldLabel('Route Map')}${renderImagePicker('t-mapimg', t.map_image)}</div>
      </div>
    </div></div>

    ${sec('📝','Description')}
    <div id="t-desc-editor" style="height:160px;background:white;border:1px solid #e5e7eb;border-radius:0.5rem;"></div></div>

    ${sec('✅','Highlights, Inclusions & Exclusions')}
    <div class="grid grid-cols-3 gap-3">
      <div>${fldLabel('Highlights')}<div id="t-high" class="bg-gray-50 p-2 rounded-lg border min-h-[60px]"></div></div>
      <div>${fldLabel('Inclusions')}<div id="t-incl" class="bg-gray-50 p-2 rounded-lg border min-h-[60px]"></div></div>
      <div>${fldLabel('Exclusions')}<div id="t-excl" class="bg-gray-50 p-2 rounded-lg border min-h-[60px]"></div></div>
    </div></div>

    ${sec('🗓️','Day-by-Day Itinerary')}
    <div class="flex justify-end mb-2"><button type="button" onclick="addItnDay()" class="text-xs bg-primary text-white px-3 py-1.5 rounded-lg font-bold">+ Add Day</button></div>
    <div id="t-itn" class="space-y-2">
      ${(t.itinerary||[]).map(renderItnDay).join('')}
    </div></div>

    ${sec('📷','Gallery')}
    <div id="t-gallery-grid" class="flex flex-wrap gap-3 mb-2 py-2">
      ${(t.gallery||[]).map(g=>`
        <div class="relative w-20 h-20 group rounded-xl overflow-hidden border bg-gray-50 flex items-center justify-center shadow-sm" data-gid="${g.id}">
            <img src="${g.image_url}" class="w-full h-full object-cover">
            <button type="button" onclick="delGalleryImg(${g.id},this)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-4 h-4 text-[10px] font-bold flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-opacity">✕</button>
        </div>`).join('')}
      <button type="button" onclick="${id ? "document.getElementById('t-gallery').click()" : "Utils.toast('Please save the trip first to add gallery images','info')"}" 
              class="w-20 h-20 border-2 border-dashed border-gray-200 rounded-xl flex flex-col items-center justify-center hover:bg-gray-50 transition-colors group">
          <span class="text-xl text-gray-300 group-hover:text-primary">+</span>
          <span class="text-[9px] text-gray-400 font-bold uppercase group-hover:text-primary">Add</span>
      </button>
      <input type="file" id="t-gallery" multiple accept="image/*" class="hidden" onchange="uploadGalleryImg(this, ${id})">
    </div></div>

    ${sec('🎥','Videos')}
    <div class="bg-blue-50/50 p-3 rounded-xl border border-blue-100">
        <p class="text-[10px] text-blue-600 font-bold uppercase mb-2">YouTube Video URLs (one per line)</p>
        <div id="t-vids" class="bg-white p-2 rounded-lg border min-h-[60px]"></div>
    </div></div>

    ${sec('🔍','SEO / Meta')}
    <div class="grid grid-cols-1 gap-3">
      ${fldWrap('Meta Title',inp('t-mTitle',t.meta_title||'','text','Page title for Google'))}
      ${fldWrap('Meta Description',tx('t-mDesc',t.meta_description||'',2,'Brief summary for search results'))}
      ${fldWrap('Meta Keywords',inp('t-mKw',t.meta_keywords||'','text','keyword1, keyword2'))}
    </div></div>

    <button type="submit" class="w-full bg-secondary text-white font-bold py-3 rounded-xl mt-3 relative z-50 shadow-lg hover:shadow-xl transition-all">${id?'Update Trip':'Create Trip'}</button>
    </form>`;
};

function renderItnDay(day, i) {
    return `<div class="itn-day bg-gray-50 border rounded-xl p-4 relative shadow-sm">
      <button type="button" onclick="this.closest('.itn-day').remove()" class="absolute top-3 right-3 text-red-400 font-bold hover:text-red-600">✕</button>
      <div class="grid grid-cols-4 gap-3 mb-3">
        <div>${fldLabel('Day #')}<input type="number" class="idn w-full border rounded-lg p-2 text-sm" value="${day.day_number||i+1}"></div>
        <div class="col-span-3">${fldLabel('Title')}<input class="idt w-full border rounded-lg p-2 text-sm" value="${esc(day.title||'')}" placeholder="e.g. Arrival in Jaipur"></div>
        <div>${fldLabel('Meals')}<input class="idm w-full border rounded-lg p-2 text-sm" value="${esc(day.meals||'')}" placeholder="B, L, D"></div>
        <div class="col-span-3">${fldLabel('Accommodation')}<input class="idh w-full border rounded-lg p-2 text-sm" value="${esc(day.accommodation||'')}"></div>
      </div>
      ${fldLabel('Description')}<textarea class="idd w-full border rounded-lg p-3 text-sm" rows="3">${esc(day.description||'')}</textarea>
    </div>`;
}

window.addItnDay = function() {
    const c = document.getElementById('t-itn');
    const n = c.querySelectorAll('.itn-day').length + 1;
    c.insertAdjacentHTML('beforeend', renderItnDay({day_number:n,title:'',meals:'',accommodation:'',description:''},n-1));
};

window.delGalleryImg = async function(gid, btn) {
    if(!confirm('Remove this image?')) return;
    const r = await fetch(`${IBCC.BASE}/admin.php?resource=trip_gallery&action=delete&id=${gid}`, {credentials:'include'}).then(r=>r.json());
    if(r?.success) btn.closest('[data-gid]').remove();
    else Utils.toast('Failed to delete', 'error');
};

window.uploadGalleryImg = async function(input, tripId) {
    if (!tripId) return Utils.toast('Save trip first', 'info');
    if (!input.files || input.files.length === 0) return;

    const grid = document.getElementById('t-gallery-grid');
    const addBtn = grid.querySelector('button[onclick*="t-gallery"]');
    
    for (let i = 0; i < input.files.length; i++) {
        const file = input.files[i];
        const formData = new FormData();
        formData.append('file', file);

        // Show temporary box/loader
        const tempId = 'temp-' + Math.random().toString(36).substr(2, 9);
        const tempBox = document.createElement('div');
        tempBox.id = tempId;
        tempBox.className = "relative w-20 h-20 group rounded-xl overflow-hidden border bg-gray-50 flex items-center justify-center shadow-sm animate-pulse";
        tempBox.innerHTML = `<span class="text-[10px] text-gray-400 font-bold uppercase">Uploading...</span>`;
        grid.insertBefore(tempBox, addBtn);

        try {
            const r = await fetch(`${IBCC.BASE}/admin.php?resource=trip_gallery&action=upload&id=${tripId}`, {
                method: 'POST',
                body: formData,
                credentials: 'include'
            }).then(r => r.json());

            if (r?.success) {
                tempBox.classList.remove('animate-pulse');
                tempBox.setAttribute('data-gid', r.data.id);
                tempBox.innerHTML = `
                    <img src="${r.data.image_url}" class="w-full h-full object-cover">
                    <button type="button" onclick="delGalleryImg(${r.data.id},this)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-4 h-4 text-[10px] font-bold flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-opacity">✕</button>
                `;
            } else {
                Utils.toast(r?.message || 'Upload failed', 'error');
                tempBox.remove();
            }
        } catch (err) {
            console.error(err);
            tempBox.remove();
        }
    }
    input.value = ''; // Reset input
};

window.saveTrip = async function(e, id) {
    e.preventDefault();
    const btn = e.target.querySelector('[type="submit"]');
    const og = btn.textContent; btn.textContent='Saving…'; btn.disabled=true;

    const d = new FormData();
    const gv = x => (document.getElementById(x)||{}).value||'';
    d.append('title',gv('t-title')); d.append('slug',gv('t-slug'));
    d.append('base_price',gv('t-price')); d.append('discounted_price',gv('t-dprice'));
    d.append('duration_days',gv('t-days')); d.append('max_members',gv('t-members'));
    d.append('difficulty',gv('t-diff')); d.append('trip_type',gv('t-type'));
    d.append('country_id',gv('t-cid')); d.append('state_id',gv('t-sid'));
    d.append('city_id',gv('t-cityid')); d.append('place_id',gv('t-plid'));
    d.append('is_featured',gv('t-feat')); d.append('is_active',gv('t-active'));
    d.append('highlights',CRUD.getDynamicListData('t-high'));
    d.append('inclusions',CRUD.getDynamicListData('t-incl'));
    d.append('exclusions',CRUD.getDynamicListData('t-excl'));
    d.append('description',window.activeQuill?window.activeQuill.root.innerHTML:'');
    d.append('meta_title',gv('t-mTitle'));
    d.append('meta_description',gv('t-mDesc'));
    d.append('meta_keywords',gv('t-mKw'));

    const days=[];
    document.querySelectorAll('.itn-day').forEach(el=>days.push({
        day_number:el.querySelector('.idn')?.value,
        title:el.querySelector('.idt')?.value,
        meals:el.querySelector('.idm')?.value,
        accommodation:el.querySelector('.idh')?.value,
        description:el.querySelector('.idd')?.value
    }));
    d.append('itinerary',JSON.stringify(days));
    d.append('videos', CRUD.getDynamicListData('t-vids'));

    const ci=document.getElementById('t-image'); if(ci?.files[0]) d.append('cover_image',ci.files[0]);
    const mi=document.getElementById('t-mapimg'); if(mi?.files[0]) d.append('map_image',mi.files[0]);
    
    // Gallery images are now uploaded in real-time

    const r = id ? await API.admin.updateTrip(id,d,true) : await API.admin.createTrip(d,true);
    btn.textContent=og; btn.disabled=false;
    if(r?.success){Utils.toast('Saved!'); CRUD.close(); loadTrips();}
    else Utils.toast(r?.message||'Error','error');
};

/* ============================================================
   BLOGS
   ============================================================ */
window.renderBlogForm = function(b={}) {
    const id = b.id||null;
    return `<form onsubmit="saveBlog(event,${id})" class="space-y-2">
    <div class="grid grid-cols-2 gap-3">
      ${fldWrap('Title *','<input required id="b-title" value="'+esc(b.title||'')+'" class="w-full border rounded-lg p-2 text-sm">')}
      ${fldWrap('Slug *','<input required id="b-slug" value="'+esc(b.slug||'')+'" class="w-full border rounded-lg p-2 text-sm">')}
      ${fldWrap('Author',inp('b-author',b.author||'','text','Author name'))}
      ${fldWrap('Status',sel('b-status',`<option value="Draft" ${b.status==='Draft'?'selected':''}>Draft</option><option value="Published" ${b.status==='Published'?'selected':''}>Published</option>`))}
      ${fldWrap('Category',sel('b-cat',buildSel(globals.categories,b.category_id)))}
      ${fldWrap('Tags',inp('b-tags',b.tags||'','text','tag1, tag2'))}
    </div>

    ${sec('📍','Destination (Article About)')}
    <div class="grid grid-cols-3 gap-3">
      ${fldWrap('Country',sel('b-cid',buildSel(globals.countries,b.country_id)))}
      ${fldWrap('State',sel('b-sid',buildSel(globals.states,b.state_id)))}
      ${fldWrap('City',sel('b-cityid',buildSel(globals.cities,b.city_id)))}
    </div></div>

    ${sec('🖼️','Featured Image')}
    <div>${renderImagePicker('b-image', b.featured_image)}</div></div>

    ${fldWrap('Excerpt',tx('b-exc',b.excerpt||'',2,'Short summary...'))}

    ${sec('📝','Content')}
    <div id="b-content-editor" style="height:220px;background:white;border:1px solid #e5e7eb;border-radius:0.5rem;"></div></div>

    ${sec('🔍','SEO / Meta')}
    <div class="space-y-2">
      ${fldWrap('Meta Title',inp('b-mTitle',b.meta_title||'','text','Page title for Google'))}
      ${fldWrap('Meta Description',tx('b-mDesc',b.meta_description||'',2,'Brief summary for search results'))}
      ${fldWrap('Meta Keywords',inp('b-mKw',b.meta_keywords||'','text','keyword1, keyword2'))}
    </div></div>

    <button type="submit" class="w-full bg-secondary text-white font-bold py-3 rounded-xl mt-3 relative z-50">${id?'Update Blog':'Create Blog'}</button>
    </form>`;
};

window.saveBlog = async function(e, id) {
    e.preventDefault();
    const btn = e.target.querySelector('[type="submit"]');
    const og=btn.textContent; btn.textContent='Saving…'; btn.disabled=true;
    const gv = x=>(document.getElementById(x)||{}).value||'';
    const d = new FormData();
    d.append('title',gv('b-title')); d.append('slug',gv('b-slug'));
    d.append('author',gv('b-author')); d.append('status',gv('b-status'));
    d.append('category_id',gv('b-cat')); d.append('tags',gv('b-tags'));
    d.append('country_id',gv('b-cid')); d.append('state_id',gv('b-sid'));
    d.append('city_id',gv('b-cityid'));
    d.append('excerpt',gv('b-exc'));
    d.append('content',window.activeQuill?window.activeQuill.root.innerHTML:'');
    d.append('meta_title',gv('b-mTitle'));
    d.append('meta_description',gv('b-mDesc'));
    d.append('meta_keywords',gv('b-mKw'));
    const f=document.getElementById('b-image'); if(f?.files[0]) d.append('featured_image',f.files[0]);
    const r = id ? await API.admin.updateBlog(id,d,true) : await API.admin.createBlog(d,true);
    btn.textContent=og; btn.disabled=false;
    if(r?.success){Utils.toast('Saved!'); CRUD.close(); loadBlogs();}
    else Utils.toast(r?.message||'Error','error');
};

/* ============================================================
   DESTINATIONS
   ============================================================ */
window.renderDestForm = function(d={}, type='countries') {
    const id = d.id||null;
    let parentHtml='';
    if(type==='states') parentHtml = fldWrap('Country',sel('d-parent',buildSel(globals.countries,d.country_id)));
    if(type==='cities') parentHtml = fldWrap('State',sel('d-parent',buildSel(globals.states,d.state_id)));
    if(type==='places') parentHtml = fldWrap('City',sel('d-parent',buildSel(globals.cities,d.city_id)));
    const codeHtml = type==='countries' ? fldWrap('Country Code',inp('d-code',d.code||'','text','IN')) : '';
    const flagHtml = type==='countries' ? fldWrap('Flag Emoji',inp('d-flag',d.flag_icon||'','text','🇮🇳')) : '';

    return `<form onsubmit="saveDest(event,${id},'${type}')" class="space-y-3">
    <div class="grid grid-cols-2 gap-3">
      ${parentHtml}
      ${codeHtml}
      ${flagHtml}
      ${fldWrap('Name *','<input required id="d-name" value="'+esc(d.name||'')+'" class="w-full border rounded-lg p-2 text-sm">')}
      ${fldWrap('Slug *','<input required id="d-slug" value="'+esc(d.slug||'')+'" class="w-full border rounded-lg p-2 text-sm">')}
      ${fldWrap('Featured',sel('d-feat',`<option value="0" ${!d.is_featured?'selected':''}>No</option><option value="1" ${d.is_featured?'selected':''}>Yes</option>`))}
    </div>

    ${sec('🖼️','Cover / Featured Image')}
    <div>${renderImagePicker('d-image', d.featured_image)}</div></div>

    ${sec('📝','Description')}
    <div id="d-desc-editor" style="height:140px;background:white;border:1px solid #e5e7eb;border-radius:0.5rem;"></div>

    ${sec('🔍','SEO / Meta')}
    <div class="space-y-2">
      ${fldWrap('Meta Title',inp('d-mTitle',d.meta_title||'','text','Page title for search results'))}
      ${fldWrap('Meta Description',tx('d-mDesc',d.meta_description||'',2,'Short summary for search engines'))}
      ${fldWrap('Meta Keywords',inp('d-mKw',d.meta_keywords||'','text','keyword1, keyword2'))}
    </div>

    <button type="submit" class="w-full bg-secondary text-white font-bold py-3 rounded-xl mt-4 relative z-50 transition-transform active:scale-95">${id?'Update':'Create'} ${type.slice(0,-1)}</button>
    </form>`;
};


window.saveDest = async function(e, id, type) {
    e.preventDefault();
    const btn=e.target.querySelector('[type="submit"]');
    const og=btn.textContent; btn.textContent='Saving…'; btn.disabled=true;
    const gv=x=>(document.getElementById(x)||{}).value||'';
    const d = new FormData();
    d.append('name',gv('d-name')); d.append('slug',gv('d-slug'));
    d.append('is_featured',gv('d-feat'));
    d.append('description',window.activeQuill?window.activeQuill.root.innerHTML:'');
    d.append('meta_title',gv('d-mTitle'));
    d.append('meta_description',gv('d-mDesc'));
    d.append('meta_keywords',gv('d-mKw'));
    
    if(type==='states') d.append('country_id',gv('d-parent'));
    if(type==='cities') d.append('state_id',gv('d-parent'));
    if(type==='places') d.append('city_id',gv('d-parent'));
    if(type==='countries'){d.append('code',gv('d-code')); d.append('flag_icon',gv('d-flag'));}
    const f=document.getElementById('d-image'); if(f?.files[0]) d.append('featured_image',f.files[0]);
    const r = id ? await API.admin.updateDestination(type,id,d) : await API.admin.createDestination(type,d);
    btn.textContent=og; btn.disabled=false;
    if(r?.success){Utils.toast('Saved!'); CRUD.close(); loadDestinations(type);}
    else Utils.toast(r?.message||'Error','error');
};

/* ============================================================
   USERS / CUSTOMERS
   ============================================================ */
let currentUsers = [];

window.loadUsers = async function() {
    const r = await fetch(`${IBCC.BASE}/admin.php?resource=users&action=list`, {credentials:'include'}).then(r=>r.json());
    currentUsers = r?.data || [];
    const html = `
    <div class="mb-4 flex justify-end">
      <button onclick="editUser()" class="bg-primary text-white text-sm font-bold px-5 py-2.5 rounded-xl shadow">+ Add New User</button>
    </div>
    <div class="card overflow-hidden">
      <table class="w-full tbl">
        <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>${currentUsers.map(u=>`<tr>
          <td class="font-bold">${u.full_name}</td>
          <td class="text-gray-500">${u.email}</td>
          <td class="text-gray-500">${u.phone||'-'}</td>
          <td><span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase ${u.role==='admin'?'bg-purple-100 text-purple-700':'bg-blue-50 text-blue-600'}">${u.role}</span></td>
          <td><span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase ${u.is_active?'bg-green-100 text-green-700':'bg-red-100 text-red-500'}">${u.is_active?'Active':'Inactive'}</span></td>
          <td>
            <button onclick="editUser(${u.id})" class="text-xs text-primary font-bold hover:underline">Edit</button> |
            <button onclick="toggleUserStatus(${u.id},${u.is_active})" class="text-xs ${u.is_active?'text-red-500':'text-green-600'} font-bold hover:underline">${u.is_active?'Deactivate':'Activate'}</button>
          </td>
        </tr>`).join('')||'<tr><td colspan="6" class="text-center text-gray-400 py-4">No users found</td></tr>'}</tbody>
      </table>
    </div>`;
    document.getElementById('page-content').innerHTML = html;
};

window.editUser = function(id=null) {
    const u = id ? currentUsers.find(x=>x.id===id) : {};
    const html = `<form onsubmit="saveUser(event,${id})" class="space-y-4">
      <div class="grid grid-cols-2 gap-3">
        ${fldWrap('Full Name','<input required id="u-name" value="'+esc(u.full_name||'')+'" class="w-full border rounded-lg p-2 text-sm">')}
        ${fldWrap('Phone',inp('u-phone',u.phone||'','tel','Optional'))}
        ${fldWrap('Email Address'+(id?' (can change)':'*'),'<input '+(id?'':'required')+' type="email" id="u-email" value="'+esc(u.email||'')+'" class="w-full border rounded-lg p-2 text-sm">')}
        ${fldWrap(id?'New Password (leave blank to keep)':'Password *','<input '+(id?'':'required')+' type="password" id="u-pass" class="w-full border rounded-lg p-2 text-sm" placeholder="'+(id?'Leave blank to keep current':'Set password')+'">')}
        ${fldWrap('Role',sel('u-role',`<option value="customer" ${u.role!=='admin'?'selected':''}>Customer</option><option value="admin" ${u.role==='admin'?'selected':''}>Admin</option>`))}
        ${fldWrap('Active',sel('u-active',`<option value="1" ${u.is_active!=0?'selected':''}>Yes</option><option value="0" ${u.is_active==0?'selected':''}>No</option>`))}
      </div>
      <button type="submit" class="w-full bg-secondary text-white font-bold py-3 rounded-xl mt-2">${id?'Update':'Create'} User</button>
    </form>`;
    CRUD.show(id?'Edit User':'Add New User',html);
};

window.saveUser = async function(e, id) {
    e.preventDefault();
    const btn=e.target.querySelector('[type="submit"]');
    const og=btn.textContent; btn.textContent='Saving…'; btn.disabled=true;
    const gv=x=>(document.getElementById(x)||{}).value||'';
    const body={full_name:gv('u-name'),email:gv('u-email'),phone:gv('u-phone'),role:gv('u-role'),is_active:gv('u-active')};
    const pw=gv('u-pass'); if(pw) body.password=pw;
    const endpoint = `${IBCC.BASE}/admin.php?resource=users&action=${id?'update&id='+id:'create'}`;
    const r = await fetch(endpoint,{method:'POST',headers:{'Content-Type':'application/json'},credentials:'include',body:JSON.stringify(body)}).then(r=>r.json());
    btn.textContent=og; btn.disabled=false;
    if(r?.success){Utils.toast('Saved!'); CRUD.close(); loadUsers();}
    else Utils.toast(r?.message||'Error','error');
};

window.toggleUserStatus = async function(id, current) {
    const newStatus = current ? 0 : 1;
    const label = newStatus ? 'activate' : 'deactivate';
    if(!confirm(`Are you sure you want to ${label} this user?`)) return;
    const r = await fetch(`${IBCC.BASE}/admin.php?resource=users&action=update&id=${id}`,{method:'POST',headers:{'Content-Type':'application/json'},credentials:'include',body:JSON.stringify({is_active:newStatus})}).then(r=>r.json());
    if(r?.success){Utils.toast('Updated!'); loadUsers();}
    else Utils.toast(r?.message||'Error','error');
};

/* ============================================================
   TESTIMONIALS
   ============================================================ */
window.renderTestimonialForm = function(t={}) {
    const id = t.id||null;
    const ratings = [1,2,3,4,5].map(r => `<option value="${r}" ${t.rating==r?'selected':''}>${r} Stars</option>`).join('');
    return `<form onsubmit="saveTestimonial(event,${id})" class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        ${fldWrap('Full Name *', inp('t-name', t.name||'', 'text', 'Customer Name'))}
        ${fldWrap('Role / City', inp('t-role', t.role||'', 'text', 'e.g. Delhi or Traveler'))}
        ${fldWrap('Rating', sel('t-rating', ratings))}
        ${fldWrap('Status', sel('t-status', `<option value="Published" ${t.status==='Published'?'selected':''}>Published</option><option value="Draft" ${t.status==='Draft'?'selected':''}>Draft</option>`))}
    </div>
    <div>${fldLabel('Comment *')}${tx('t-comm', t.comment||'', 4, 'What did the traveler say?')}</div>
    <div>${fldLabel('User Photo')}${renderImagePicker('t-user-img', t.image)}</div>
    <button type="submit" class="w-full bg-secondary text-white font-bold py-3 rounded-xl mt-2">${id?'Update':'Create'} Testimonial</button>
    </form>`;
};

window.saveTestimonial = async function(e, id) {
    e.preventDefault();
    const btn=e.target.querySelector('[type="submit"]');
    const og=btn.textContent; btn.textContent='Saving…'; btn.disabled=true;
    const gv=x=>(document.getElementById(x)||{}).value||'';
    const fd = new FormData();
    fd.append('name', gv('t-name')); fd.append('role', gv('t-role'));
    fd.append('rating', gv('t-rating')); fd.append('status', gv('t-status'));
    fd.append('comment', gv('t-comm'));
    const f=document.getElementById('t-user-img'); if(f?.files[0]) fd.append('image', f.files[0]);
    
    const r = id ? await API.admin.updateTestimonial(id, fd) : await API.admin.createTestimonial(fd);
    btn.textContent=og; btn.disabled=false;
    if(r?.success){Utils.toast('Saved!'); CRUD.close(); loadTestimonials();}
    else Utils.toast(r?.message||'Error','error');
};

/**
 * HELPER
 */
function esc(str){ return String(str).replace(/"/g,'&quot;').replace(/</g,'&lt;'); }
