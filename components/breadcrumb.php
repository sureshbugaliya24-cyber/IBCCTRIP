<?php
// frontend/components/breadcrumb.php
// Renders a breadcrumb nav
// Usage: renderBreadcrumb([ ['Home', '/'], ['Trips', '/trips'], ['Trip Name', ''] ])
// Last item is assumed to be the current page (no link if URL is empty)

if (!defined('FRONTEND_URL'))
  require_once __DIR__ . '/config.php';

function renderBreadcrumb(array $items, string $theme = 'dark'): void
{ 
  $textClass = ($theme === 'dark') ? 'text-white/70' : 'text-gray-500';
  $activeClass = ($theme === 'dark') ? 'text-white' : 'text-gray-900';
  $hoverClass = ($theme === 'dark') ? 'hover:text-white' : 'hover:text-gray-900';
?>
<nav aria-label="Breadcrumb" class="flex items-center flex-wrap gap-1.5 text-sm <?= $textClass ?>">
  <?php foreach ($items as $i => $item):
    $label = e($item[0] ?? '');
    $href = $item[1] ?? '';
    $last = $i === count($items) - 1;
?>
  <?php if ($i > 0): ?>
  <svg class="w-3.5 h-3.5 opacity-50 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
  </svg>
  <?php
    endif; ?>

  <?php if ($last || !$href): ?>
  <span class="<?= $activeClass ?> font-semibold">
    <?= $label?>
  </span>
  <?php
    else: ?>
  <a href="<?= e($href)?>" class="<?= $hoverClass ?> transition-colors">
    <?= $label?>
  </a>
  <?php
    endif; ?>
  <?php
  endforeach; ?>
</nav>
<?php
}