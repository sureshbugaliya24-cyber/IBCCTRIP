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

$pageTitle     = $pageTitle     ?? APP_NAME;
$pageDesc      = $pageDesc      ?? 'IBCC Trip – India\'s premium travel agency. Book curated tours to Rajasthan, Dubai, Thailand & more.';
$ogImage       = $ogImage       ?? 'https://images.unsplash.com/photo-1506929562872-bb421503ef21?w=1200';
$canonicalPath = $canonicalPath ?? '';
$fullTitle     = ($pageTitle !== APP_NAME) ? $pageTitle . ' | ' . APP_NAME : APP_NAME . ' — ' . APP_TAGLINE;
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

  <!-- Tailwind CSS (CDN) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary:   '#0B3D91',
            secondary: '#FF6B00',
            accent:    '#F5F7FA',
            dark:      '#0A1628',
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

  <!-- Global styles -->
  <style>
    *, *::before, *::after { box-sizing: border-box; }
    body   { font-family: 'Inter', system-ui, sans-serif; background: #F5F7FA; }
    :root  { --primary: #0B3D91; --secondary: #FF6B00; }

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

    /* Scroll snap for destination carousel */
    .snap-x { scroll-snap-type:x mandatory; -webkit-overflow-scrolling:touch; }
    .snap-x > * { scroll-snap-align:start; }

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
</head>
<body class="font-sans text-gray-900 overflow-x-hidden">
