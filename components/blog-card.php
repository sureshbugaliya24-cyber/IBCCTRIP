<?php
// frontend/components/blog-card.php
// ─────────────────────────────────────────────────────────────
// Reusable Blog Card Component
// Usage:  renderBlogCard($blog)
// ─────────────────────────────────────────────────────────────

if (!defined('FRONTEND_URL')) require_once __DIR__ . '/config.php';
if (!function_exists('formatDate')) require_once __DIR__ . '/helpers.php';

function renderBlogCard(array $blog, string $size = 'normal'): void {
    $slug     = e($blog['slug'] ?? '');
    $title    = e($blog['title'] ?? 'Blog Post');
    $excerpt  = e($blog['excerpt'] ?? '');
    $author   = e($blog['author'] ?? 'IBCC Trip Team');
    $catName  = e($blog['category_name'] ?? '');
    $catSlug  = e($blog['category_slug']  ?? '');
    $img      = e($blog['featured_image'] ?? 'https://images.unsplash.com/photo-1488085061387-422e29b40080?w=600');
    $date     = formatDate($blog['created_at'] ?? '');
    $country  = e($blog['country_name'] ?? '');

    $url = FRONTEND_URL . '/blog-single.php/' . urlencode($blog['slug'] ?? '');
    $catUrl = $catSlug ? FRONTEND_URL . '/blog.php?category=' . $catSlug : FRONTEND_URL . '/blog.php';
    ?>
<article class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl
               transition-all duration-300 group border border-gray-100 flex flex-col">
  <!-- Thumbnail -->
  <a href="<?= $url ?>" class="relative block overflow-hidden <?= $size === 'large' ? 'h-60' : 'h-48' ?> shrink-0">
    <img src="<?= $img ?>"
         alt="<?= $title ?>"
         loading="lazy"
         onerror="this.src='https://images.unsplash.com/photo-1488085061387-422e29b40080?w=600'"
         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
    <?php if ($country): ?>
    <span class="absolute top-3 right-3 bg-white/90 backdrop-blur text-xs font-bold px-2 py-1 rounded-full text-gray-700">
      <?= $country ?>
    </span>
    <?php endif; ?>
  </a>

  <!-- Body -->
  <div class="p-5 flex flex-col flex-1">
    <?php if ($catName): ?>
    <a href="<?= $catUrl ?>"
       class="inline-block bg-blue-50 text-primary text-xs font-extrabold px-3 py-1 rounded-full mb-2
              hover:bg-primary hover:text-white transition-colors w-fit">
      <?= $catName ?>
    </a>
    <?php endif; ?>

    <a href="<?= $url ?>">
      <h3 class="font-extrabold text-gray-900 text-base mb-2 line-clamp-2
                 group-hover:text-primary transition-colors leading-snug">
        <?= $title ?>
      </h3>
    </a>

    <?php if ($excerpt): ?>
    <p class="text-gray-500 text-sm line-clamp-2 flex-1 leading-relaxed"><?= $excerpt ?></p>
    <?php endif; ?>

    <!-- Meta -->
    <div class="flex items-center justify-between pt-3 mt-3 border-t border-gray-100 text-xs text-gray-400">
      <div class="flex items-center gap-1.5">
        <div class="w-6 h-6 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xs">
          <?= strtoupper(substr($author, 0, 1)) ?>
        </div>
        <span><?= $author ?></span>
      </div>
      <span><?= $date ?></span>
    </div>
  </div>
</article>
<?php
}
if (isset($blog) && is_array($blog)) {
    renderBlogCard($blog);
}
