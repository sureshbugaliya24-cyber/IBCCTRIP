<?php
// frontend/components/pagination.php
// Renders pagination buttons
// Usage: renderPagination(currentPage, totalPages, baseUrl)
// baseUrl should include existing GET params EXCEPT 'page', e.g. "trips.php?q=rajasthan"

function renderPagination(int $current, int $total, string $baseUrl): void {
    if ($total <= 1) return;
    $sep = strpos($baseUrl, '?') !== false ? '&' : '?';
    ?>
<nav aria-label="Pagination" class="flex items-center justify-center gap-2 mt-10 flex-wrap">
  <?php if ($current > 1): ?>
  <a href="<?= e($baseUrl . $sep . 'page=' . ($current - 1)) ?>"
     class="flex items-center gap-1 px-4 py-2.5 bg-white border border-gray-200 rounded-xl
            text-sm font-semibold text-gray-700 hover:bg-primary hover:text-white
            hover:border-primary transition-all shadow-sm">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Prev
  </a>
  <?php endif; ?>

  <?php
  $start = max(1, $current - 2);
  $end   = min($total, $current + 2);
  if ($start > 1):
  ?>
    <a href="<?= e($baseUrl . $sep . 'page=1') ?>"
       class="w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200
              text-sm font-semibold text-gray-700 hover:bg-primary hover:text-white transition-all">1</a>
    <?php if ($start > 2): ?>
    <span class="px-2 text-gray-400">…</span>
    <?php endif; ?>
  <?php endif; ?>

  <?php for ($i = $start; $i <= $end; $i++): ?>
  <a href="<?= e($baseUrl . $sep . 'page=' . $i) ?>"
     class="w-10 h-10 flex items-center justify-center rounded-xl border text-sm font-semibold
            transition-all <?= $i === $current
                ? 'bg-primary text-white border-primary shadow-lg shadow-primary/20'
                : 'bg-white border-gray-200 text-gray-700 hover:bg-primary hover:text-white hover:border-primary' ?>">
    <?= $i ?>
  </a>
  <?php endfor; ?>

  <?php if ($end < $total):
    if ($end < $total - 1): ?>
    <span class="px-2 text-gray-400">…</span>
    <?php endif; ?>
    <a href="<?= e($baseUrl . $sep . 'page=' . $total) ?>"
       class="w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200
              text-sm font-semibold text-gray-700 hover:bg-primary hover:text-white transition-all">
      <?= $total ?>
    </a>
  <?php endif; ?>

  <?php if ($current < $total): ?>
  <a href="<?= e($baseUrl . $sep . 'page=' . ($current + 1)) ?>"
     class="flex items-center gap-1 px-4 py-2.5 bg-white border border-gray-200 rounded-xl
            text-sm font-semibold text-gray-700 hover:bg-primary hover:text-white
            hover:border-primary transition-all shadow-sm">
    Next
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
  </a>
  <?php endif; ?>
</nav>
<?php }
