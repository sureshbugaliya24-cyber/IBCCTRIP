<?php
// frontend/layouts/head.php
// ─────────────────────────────────────────────────────────────
// Reusable <head> section — include at top of every page
// Variables expected (set before including this file):
//   $pageTitle       string  — page-specific title
//   $pageDesc        string  — meta description
//   $pageKeywords    string  — meta keywords (optional)
//   $ogImage         string  — OG image URL (optional)
//   $canonicalPath   string  — e.g. '/frontend/trips.php' (optional)
// ─────────────────────────────────────────────────────────────

if (!defined('BASE_URL')) require_once __DIR__ . '/../components/config.php';
require_once __DIR__ . '/../components/logo.php';

$pageTitle     = $pageTitle     ?? APP_NAME;
$pageDesc      = $pageDesc      ?? 'IBCC Trip – India\'s premium travel agency. Book curated tours to Rajasthan, Dubai, Thailand & more.';
$ogImage       = $ogImage       ?? 'https://images.unsplash.com/photo-1506929562872-bb421503ef21?w=1200';
$canonicalPath = $canonicalPath ?? '';

$siteFullName = (defined('SITE_NAME_PART1') ? SITE_NAME_PART1 : 'IBCC') . ' ' . (defined('SITE_NAME_PART2') ? SITE_NAME_PART2 : 'Trip');
$fullTitle     = ($pageTitle !== APP_NAME) ? $pageTitle . ' | ' . $siteFullName : $siteFullName . ' — ' . APP_TAGLINE;
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($fullTitle) ?></title>
  <meta name="description" content="<?= e($pageDesc) ?>">
  <?php if (!empty($pageKeywords)): ?>
  <meta name="keywords" content="<?= e($pageKeywords) ?>">
  <?php endif; ?>

  <!-- Favicon (Dynamic from Logo Icon) -->
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml;utf8,<?= rawurlencode(SITE_ICON_SVG) ?>">

  <!-- Open Graph -->
  <meta property="og:title"       content="<?= e($fullTitle) ?>">
  <meta property="og:description" content="<?= e($pageDesc) ?>">
  <meta property="og:image"       content="<?= e($ogImage) ?>">
  <meta property="og:type"        content="website">
  <meta name="twitter:card"       content="summary_large_image">
  <meta name="twitter:image"      content="<?= e($ogImage) ?>">

  <?php if ($canonicalPath): ?>
  <link rel="canonical" href="<?= BASE_URL . e($canonicalPath) ?>">
  <?php endif; ?>

  <!-- Global Design System -->
  <link rel="stylesheet" href="<?= FRONTEND_URL ?>/css/style.css?v=<?= APP_VERSION ?>">
  
  <style>
    :root {
      --primary:   <?= COLOR_PRIMARY ?>;
      --secondary: <?= COLOR_SECONDARY ?>;
      --accent:    <?= COLOR_ACCENT ?>;
      --dark:      <?= COLOR_DARK ?>;
    }
    /* Sync Tailwind with our PHP/CSS variables */
    @layer base {
      :root {
        --tw-color-primary: <?= COLOR_PRIMARY ?>;
        --tw-color-secondary: <?= COLOR_SECONDARY ?>;
      }
    }
  </style>

  <!-- Tailwind CSS (CDN) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary:   '<?= COLOR_PRIMARY ?>',
            secondary: '<?= COLOR_SECONDARY ?>',
            accent:    '<?= COLOR_ACCENT ?>',
            dark:      '<?= COLOR_DARK ?>',
          },
          fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] }
        }
      }
    }
  </script>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Legacy inline styles (Consider moving to style.css) -->
  <style>
    *, *::before, *::after { box-sizing: border-box; }
    body   { font-family: 'Inter', system-ui, sans-serif; background: #F5F7FA; }
    :root  { --primary: <?= COLOR_PRIMARY ?>; --secondary: <?= COLOR_SECONDARY ?>; }

    /* Utility classes */
    .line-clamp-2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
    .line-clamp-3 { display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }

    /* Glass morphism */
    .glass { background:rgba(255,255,255,.15); backdrop-filter:blur(12px); border:1px solid rgba(255,255,255,.25); }

    /* Hero overlay */
    .hero-overlay { background:linear-gradient(135deg, rgba(11,61,145,.85) 0%, rgba(255,107,0,.60) 100%); }

    /* Animations */
    @keyframes fadeUp   { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
    @keyframes fadeIn   { from{opacity:0} to{opacity:1} }
    @keyframes spin     { to{transform:rotate(360deg)} }
    .fade-up   { animation: fadeUp  .65s ease forwards; }
    .fade-in   { animation: fadeIn  .5s  ease forwards; }
    .anim-spin { animation: spin    1s   linear infinite; }

    /* Spinner */
    .spinner { width:44px; height:44px; border:4px solid #e5e7eb; border-top-color:var(--primary); border-radius:50%; animation:spin 1s linear infinite; }

    /* Toast */
    .toast { position:fixed; top:20px; right:20px; z-index:9999; padding:12px 20px; border-radius:12px; font-weight:600; font-size:14px; color:#fff; transform:translateX(140%); transition:transform .3s ease; box-shadow:0 10px 30px rgba(0,0,0,.15); }
    .toast.show { transform:translateX(0); }

    /* Table base */
    .tbl th { background:#F9FAFB; padding:11px 14px; font-weight:700; color:#6B7280; text-transform:uppercase; font-size:11px; letter-spacing:.04em; text-align:left; }
    .tbl td { padding:12px 14px; font-size:13px; border-bottom:1px solid #F3F4F6; color:#374151; }
    .tbl tr:last-child td { border-bottom:none; }
    .tbl tr:hover td { background:#F9FAFB; }
  </style>
  <!-- Global App Config -->
  <script>
    window.APP_CONFIG = {
      BASE_URL: '<?= BASE_URL ?>',
      ADMIN_URL: '<?= ADMIN_URL ?>',
      API_URL: '<?= API_URL ?>',
      VERSION: '<?= APP_VERSION ?>',
      BRAND: {
        NAME_PART1: '<?= SITE_NAME_PART1 ?>',
        NAME_PART2: '<?= SITE_NAME_PART2 ?>',
        FULL_NAME:  '<?= $siteFullName ?>',
        ICON_SVG:   `<?= SITE_ICON_SVG ?>`
      }
    };
  </script>
</head>
<body class="font-sans text-gray-900 overflow-x-hidden">
