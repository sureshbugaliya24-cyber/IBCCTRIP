<?php
// frontend/gallery.php — Dedicated Gallery Page
// ─────────────────────────────────────────────────────────────

require_once __DIR__ . '/components/config.php';
require_once __DIR__ . '/components/helpers.php';

$pageTitle = 'Travel Gallery';
$pageDesc  = 'Glimpses of beautiful destinations captured by our travelers. View our latest trip photos.';
$activePage = 'gallery';
$transparent = true;

require_once __DIR__ . '/layouts/head.php';
require_once __DIR__ . '/layouts/header.php';

// Fetch gallery images
$gallery = apiGet('gallery.php', ['action' => 'list']) ?? [];
?>

<div class="bg-primary pt-32 pb-20 px-4 text-center text-white">
  <div class="max-w-3xl mx-auto">
    <h1 class="text-4xl md:text-6xl font-black mb-6">Visual <span class="text-secondary">Stories</span></h1>
    <p class="text-white/70 text-lg leading-relaxed">
      A curated collection of moments and memories from across the globe. 
      Every photo tells a story of adventure and discovery.
    </p>
  </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-20">
    <?php if (count($gallery) > 0): ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="gallery-grid">
        <?php foreach ($gallery as $img): ?>
        <div class="group relative aspect-square overflow-hidden rounded-3xl bg-gray-100 shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
            <img src="<?= e($img['image_url']) ?>" 
                 alt="<?= e($img['caption'] ?: 'Gallery Image') ?>" 
                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                <?php if ($img['caption']): ?>
                    <p class="text-white font-bold text-sm translate-y-4 group-hover:translate-y-0 transition-transform duration-300"><?= e($img['caption']) ?></p>
                <?php endif; ?>
                <p class="text-white/60 text-[10px] font-black uppercase tracking-widest mt-1 translate-y-4 group-hover:translate-y-0 transition-transform duration-300 delay-75">
                    <?= e($img['category'] ?: 'General') ?>
                </p>
            </div>
            
            <!-- Lightbox Trigger (Simple) -->
            <a href="<?= e($img['image_url']) ?>" target="_blank" class="absolute inset-0 z-10"></a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-20">
        <div class="text-6xl mb-6">📸</div>
        <h2 class="text-2xl font-bold text-gray-900">Gallery is being updated</h2>
        <p class="text-gray-400 mt-2">Check back soon for new travel inspirations.</p>
    </div>
    <?php endif; ?>
</div>

<!-- CTA -->
<div class="max-w-7xl mx-auto px-4 pb-20">
    <div class="bg-secondary rounded-[3rem] p-12 md:p-20 text-center text-white relative overflow-hidden shadow-2xl shadow-secondary/20">
        <div class="relative z-10">
            <h2 class="text-3xl md:text-5xl font-black mb-6">Ready to create your own memories?</h2>
            <p class="text-white/80 text-lg mb-10 max-w-2xl mx-auto">Book a handcrafted trip with IBCC Trip and start your next adventure today.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= FRONTEND_URL ?>/trips" class="bg-white text-secondary font-black uppercase tracking-widest px-10 py-5 rounded-2xl hover:bg-gray-50 transition-all">Explore Packages</a>
                <a href="<?= FRONTEND_URL ?>/contact" class="bg-primary text-white font-black uppercase tracking-widest px-10 py-5 rounded-2xl hover:bg-blue-800 transition-all">Get a Quote</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
</body>
</html>
