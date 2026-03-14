<?php
// frontend/blog-single.php — Dynamic Single Blog Page
// ─────────────────────────────────────────────────────────────

require_once __DIR__ . '/components/config.php';
require_once __DIR__ . '/components/helpers.php';
require_once __DIR__ . '/components/breadcrumb.php';
require_once __DIR__ . '/components/blog-card.php';

$slug = qParam('slug');
if (!$slug && isset($_SERVER['PATH_INFO'])) {
    $slug = trim($_SERVER['PATH_INFO'], '/');
}
if (!$slug) redirect(FRONTEND_URL . '/blog.php');

$resp = apiGetFull('blogs.php', ['action' => 'detail', 'slug' => $slug]);
if (empty($resp['success'])) {
    http_response_code(404);
    $pageTitle  = 'Post Not Found';
    $activePage = 'blog';
    require_once __DIR__ . '/layouts/head.php';
    require_once __DIR__ . '/layouts/header.php';
    echo '<div class="max-w-xl mx-auto text-center py-40">
            <div class="text-6xl mb-4">📄</div>
            <h1 class="text-2xl font-extrabold text-gray-900 mb-3">Blog Post Not Found</h1>
            <a href="' . FRONTEND_URL . '/blog.php" class="bg-primary text-white font-bold px-6 py-3 rounded-xl text-sm">← All Blog Posts</a>
          </div>';
    require_once __DIR__ . '/layouts/footer.php';
    echo '</body></html>';
    exit;
}

$blog     = $resp['data'] ?? [];
$related  = $blog['related'] ?? [];

$pageTitle    = $blog['title'] ?? 'Blog Post';
$pageDesc     = $blog['meta_description'] ?: ($blog['excerpt'] ?? '');
$ogImage      = $blog['featured_image'] ?? '';
$pageKeywords = $blog['meta_keywords'] ?? '';
$activePage   = 'blog';
$transparent = true;

require_once __DIR__ . '/layouts/head.php';
require_once __DIR__ . '/layouts/header.php';
?>

<!-- ===== HERO ===== -->
<div class="relative h-80 md:h-[28rem] overflow-hidden">
  <img src="<?= e($blog['featured_image'] ?? '') ?>"
       alt="<?= e($blog['title'] ?? '') ?>"
       class="w-full h-full object-cover">
  <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>

  <div class="absolute inset-0 flex flex-col justify-end pb-10 px-4 max-w-4xl mx-auto left-0 right-0">
    <?php renderBreadcrumb([
      ['Home', FRONTEND_URL . '/'],
      ['Blog', FRONTEND_URL . '/blog'],
      [$blog['title'] ?? ''],
    ]); ?>
    <?php if (!empty($blog['category_name'])): ?>
    <span class="mt-3 inline-block bg-secondary text-white text-xs font-extrabold px-3 py-1 rounded-full w-fit mb-3">
      <?= e($blog['category_name']) ?>
    </span>
    <?php endif; ?>
    <h1 class="text-2xl md:text-4xl font-extrabold text-white leading-tight mb-3">
      <?= e($blog['title'] ?? '') ?>
    </h1>
    <div class="flex items-center gap-4 text-white/70 text-sm">
      <div class="flex items-center gap-2">
        <div class="w-8 h-8 rounded-full bg-secondary text-white flex items-center justify-center font-bold text-xs">
          <?= strtoupper(substr($blog['author'] ?? 'A', 0, 1)) ?>
        </div>
        <span><?= e($blog['author'] ?? 'IBCC Trip Team') ?></span>
      </div>
      <span>•</span>
      <span><?= formatDate($blog['created_at'] ?? '') ?></span>
      <?php if (!empty($blog['country_name'])): ?>
      <span>•</span><span>📍 <?= e($blog['country_name']) ?></span>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- ===== CONTENT ===== -->
<div class="max-w-7xl mx-auto px-4 py-12">
  <div class="flex flex-col lg:flex-row gap-10">

    <!-- Article Body -->
    <article class="flex-1 min-w-0">
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-10">
        <!-- Excerpt -->
        <?php if (!empty($blog['excerpt'])): ?>
        <p class="text-lg text-gray-600 italic border-l-4 border-secondary pl-5 mb-8 leading-relaxed">
          <?= e($blog['excerpt']) ?>
        </p>
        <?php endif; ?>

        <!-- Main Content -->
        <div class="prose-content text-gray-700 leading-relaxed text-sm md:text-base">
          <?= $blog['content'] ?? '' ?>
        </div>

        <!-- Tags -->
        <?php if (!empty($blog['tags'])): ?>
        <div class="mt-8 pt-6 border-t border-gray-100 flex flex-wrap gap-2">
          <?php foreach (explode(',', $blog['tags']) as $tag): if (trim($tag)): ?>
          <span class="bg-gray-100 text-gray-600 text-xs font-semibold px-3 py-1 rounded-full hover:bg-primary hover:text-white transition-colors cursor-default">
            #<?= e(trim($tag)) ?>
          </span>
          <?php endif; endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Share -->
        <div class="mt-8 pt-6 border-t border-gray-100 flex items-center gap-3">
          <span class="text-sm font-semibold text-gray-500">Share:</span>
          <a href="https://facebook.com/sharer.php?u=<?= urlencode(FRONTEND_URL . '/blog/' . $slug) ?>"
             target="_blank" rel="noopener"
             class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center text-white hover:bg-blue-700 transition-colors">f</a>
          <a href="https://twitter.com/intent/tweet?text=<?= urlencode($blog['title'] ?? '') ?>&url=<?= urlencode(FRONTEND_URL . '/blog/' . $slug) ?>"
             target="_blank" rel="noopener"
             class="w-9 h-9 bg-sky-500 rounded-lg flex items-center justify-center text-white hover:bg-sky-600 transition-colors">𝕏</a>
          <a href="https://wa.me/?text=<?= urlencode(($blog['title'] ?? '') . ' ' . FRONTEND_URL . '/blog/' . $slug) ?>"
             target="_blank" rel="noopener"
             class="w-9 h-9 bg-green-500 rounded-lg flex items-center justify-center text-white hover:bg-green-600 transition-colors">W</a>
        </div>
      </div>
    </article>

    <!-- Sidebar -->
    <aside class="lg:w-80 shrink-0">

      <!-- Related Posts -->
      <?php if (count($related) > 0): ?>
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5">
        <h3 class="font-extrabold text-gray-900 mb-4">Related Articles</h3>
        <div class="space-y-4">
          <?php foreach ($related as $rb): ?>
          <a href="<?= FRONTEND_URL ?>/blog/<?= urlencode($rb['slug'] ?? '') ?>"
             class="flex gap-3 hover:bg-gray-50 rounded-xl p-2 transition-colors group">
            <img src="<?= e($rb['featured_image'] ?? '') ?>"
                 class="w-14 h-14 rounded-xl object-cover shrink-0 group-hover:scale-105 transition-transform"
                 loading="lazy">
            <div>
              <p class="font-semibold text-gray-900 text-sm line-clamp-2 group-hover:text-primary transition-colors">
                <?= e($rb['title'] ?? '') ?>
              </p>
              <p class="text-xs text-gray-400 mt-1"><?= formatDate($rb['created_at'] ?? '') ?></p>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Book a Trip CTA -->
      <div class="bg-gradient-to-br from-primary to-blue-700 rounded-2xl p-6 text-white text-center">
        <div class="text-4xl mb-3">✈️</div>
        <h4 class="font-extrabold text-xl mb-2">Plan Your Trip</h4>
        <p class="text-white/70 text-sm mb-5">Inspired? Browse our curated packages.</p>
        <a href="<?= FRONTEND_URL ?>/trips"
           class="block bg-secondary text-white font-extrabold py-3 rounded-xl hover:bg-orange-600 transition-colors text-sm">
          Browse Trips →
        </a>
      </div>
    </aside>
  </div>
</div>

<style>
.prose-content h2{font-size:1.5rem;font-weight:800;margin:2rem 0 .75rem;color:#111827}
.prose-content h3{font-size:1.15rem;font-weight:700;margin:1.5rem 0 .5rem;color:#1F2937}
.prose-content p{margin-bottom:1rem;line-height:1.85;color:#4B5563}
.prose-content ul,.prose-content ol{padding-left:1.5rem;margin-bottom:1rem}
.prose-content li{margin-bottom:.4rem;color:#4B5563;line-height:1.7}
.prose-content blockquote{border-left:4px solid #FF6B00;padding-left:1rem;margin:1.5rem 0;color:#6B7280;font-style:italic}
.prose-content img{border-radius:1rem;max-width:100%;margin:1.5rem 0}
.prose-content a{color:#0B3D91;text-decoration:underline}
</style>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
<script src="<?= FRONTEND_URL ?>/js/app.js?v=<?= APP_VERSION ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
  const user = await Session.init();
  if (user) {
    document.getElementById('nav-login').style.display   = 'none';
    document.getElementById('nav-dashboard').style.display = 'flex';
  }
});
</script>
</body>
</html>
