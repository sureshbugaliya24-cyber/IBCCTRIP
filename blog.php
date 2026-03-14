<?php
// frontend/blog.php — Blog Listing Page
// ─────────────────────────────────────────────────────────────

require_once __DIR__ . '/components/config.php';
require_once __DIR__ . '/components/helpers.php';
require_once __DIR__ . '/components/blog-card.php';
require_once __DIR__ . '/components/pagination.php';

$page     = max(1, (int)($_GET['page'] ?? 1));
$category = qParam('category');  // category slug
$q        = qParam('q');

// Fetch categories
$categories = apiGet('blogs.php', ['action' => 'categories']);

// Fetch blogs
$params = ['page' => $page, 'per_page' => 9];
if ($category) $params['category'] = $category;
if ($q)        $params['q']        = $q;
$resp       = apiGetFull('blogs.php', array_merge($params, ['action' => 'list']));
$blogs      = $resp['data'] ?? [];
$totalPages = $resp['pagination']['last_page'] ?? 1;

$filterParams  = http_build_query(array_filter(compact('category', 'q')));
$paginationUrl = 'blog' . ($filterParams ? '?' . $filterParams : '');

$pageTitle  = $category ? ucfirst($category) . ' — Travel Blog' : 'Travel Blog';
$pageDesc   = 'Expert travel tips, destination guides, and inspiring stories. Regularly updated by the IBCC Trip team.';
$activePage = 'blog';
$transparent = true;

require_once __DIR__ . '/layouts/head.php';
require_once __DIR__ . '/layouts/header.php';
?>

<!-- Hero Banner -->
<div class="bg-gradient-to-r from-primary to-blue-800 py-14 text-center text-white">
  <h1 class="text-3xl md:text-5xl font-extrabold mb-3">
    Travel <span class="text-secondary">Blog</span>
  </h1>
  <p class="text-white/80 max-w-lg mx-auto">Expert insights, destination guides, and tips for your next adventure.</p>
</div>

<div class="max-w-7xl mx-auto px-4 py-10">

  <!-- Category Filters + Search -->
  <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
    <div class="flex flex-wrap gap-2">
      <a href="blog"
         class="px-4 py-2 rounded-full text-sm font-semibold transition-colors
                <?= !$category ? 'bg-primary text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-primary hover:text-white' ?>">
        All Posts
      </a>
      <?php foreach ($categories as $c): ?>
      <a href="blog?category=<?= urlencode($c['slug'] ?? $c['name']) ?>"
         class="px-4 py-2 rounded-full text-sm font-semibold transition-colors
                <?= $category === ($c['slug'] ?? $c['name']) ? 'bg-primary text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-primary hover:text-white' ?>">
        <?= e($c['name']) ?>
        <span class="opacity-60 text-xs">(<?= (int)($c['post_count'] ?? 0) ?>)</span>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- Search -->
    <form method="GET" action="blog" class="flex items-center gap-2">
      <?php if ($category): ?><input type="hidden" name="category" value="<?= e($category) ?>"><?php endif; ?>
      <input name="q" type="text" value="<?= e($q) ?>" placeholder="Search articles..."
             class="border border-gray-200 rounded-xl py-2 px-4 text-sm focus:outline-none focus:border-primary w-52">
      <button type="submit" class="bg-primary text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-blue-800 transition-colors">
        Search
      </button>
    </form>
  </div>

  <!-- Blog Grid -->
  <?php if (count($blogs) > 0): ?>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($blogs as $blog): renderBlogCard($blog); endforeach; ?>
  </div>

  <?php renderPagination($page, $totalPages, $paginationUrl); ?>

  <?php else: ?>
  <div class="text-center py-20">
    <div class="text-5xl mb-4">📝</div>
    <h3 class="text-xl font-extrabold text-gray-900 mb-2">No Posts Found</h3>
    <p class="text-gray-400 mb-5">Try a different category or search term.</p>
    <a href="blog" class="bg-primary text-white font-bold px-6 py-3 rounded-xl text-sm">View All Posts</a>
  </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
<script src="<?= FRONTEND_URL ?>/js/app.js?v=<?= APP_VERSION ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
  const user = await Session.init();
  if (user) {
    document.getElementById('nav-login').style.display   = 'none';
    document.getElementById('nav-dashboard').style.display = 'flex';
    document.getElementById('nav-logout').style.display  = 'block';
    document.getElementById('nav-avatar').textContent = user.name[0];
  }
});
</script>
</body>
</html>
