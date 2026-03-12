<?php
// frontend/components/breadcrumb.php
// Renders a breadcrumb nav
// Usage: renderBreadcrumb([ ['Home', '/'], ['Trips', '/trips'], ['Trip Name', ''] ])
// Last item is assumed to be the current page (no link if URL is empty)

if (!defined('FRONTEND_URL')) require_once __DIR__ . '/config.php';

function renderBreadcrumb(array $items): void { ?>
<nav aria-label="Breadcrumb" class="flex items-center flex-wrap gap-1.5 text-sm text-white/70">
  <?php foreach ($items as $i => $item):
        $label = e($item[0] ?? '');
        $href  = $item[1] ?? '';
        $last  = $i === count($items) - 1;
  ?>
    <?php if ($i > 0): ?>
    <svg class="w-3.5 h-3.5 opacity-50 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <?php endif; ?>

    <?php if ($last || !$href): ?>
      <span class="text-white font-semibold"><?= $label ?></span>
    <?php else: ?>
      <a href="<?= e($href) ?>" class="hover:text-white transition-colors"><?= $label ?></a>
    <?php endif; ?>
  <?php endforeach; ?>
</nav>
<?php }
