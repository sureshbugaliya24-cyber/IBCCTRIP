<?php
// frontend/layouts/footer.php
// ─────────────────────────────────────────────────────────────
// Global site footer — include at the bottom of every page
// ─────────────────────────────────────────────────────────────

if (!defined('FRONTEND_URL')) require_once __DIR__ . '/../components/config.php';
?>

<footer class="bg-gray-900 text-white pt-16 pb-8 mt-16">
  <div class="max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">

      <!-- Brand Column -->
      <div class="md:col-span-1">
        <a href="<?= FRONTEND_URL ?>/" class="mb-4 block">
          <?= renderLogo() ?>
        </a>
        <p class="text-gray-400 text-sm leading-relaxed mb-5">
          India's most trusted premium travel agency since 2010.<br>
          ₹500 Crore+ in travel experiences delivered.
        </p>
        <!-- Social Links -->
        <div class="flex gap-3">
          <a href="<?= FACEBOOK_URL ?>" target="_blank" rel="noopener"
             class="w-9 h-9 rounded-xl bg-gray-800 hover:bg-secondary flex items-center justify-center transition-colors group">
            <svg class="w-4 h-4 text-gray-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
              <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125
                       11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0
                       2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532
                       3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
          </a>
          <a href="<?= INSTAGRAM_URL ?>" target="_blank" rel="noopener"
             class="w-9 h-9 rounded-xl bg-gray-800 hover:bg-secondary flex items-center justify-center transition-colors group">
            <svg class="w-4 h-4 text-gray-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058
                       1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919
                       4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849
                       0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057
                       1.645-.069 4.849-.069zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0
                       10.162a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
            </svg>
          </a>
          <a href="https://wa.me/<?= WHATSAPP_NO ?>" target="_blank" rel="noopener"
             class="w-9 h-9 rounded-xl bg-gray-800 hover:bg-green-600 flex items-center justify-center transition-colors group">
            <svg class="w-4 h-4 text-gray-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94
                       1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198
                       0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489
                       1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421
                       7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884
                       9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885
                       9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588
                       5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821
                       11.821 0 00-3.48-8.413z"/>
            </svg>
          </a>
        </div>
      </div>

      <!-- Quick Links -->
      <div>
        <h4 class="text-xs font-extrabold uppercase tracking-widest text-gray-400 mb-5">Quick Links</h4>
        <ul class="space-y-2.5 text-sm text-gray-400">
          <li><a href="<?= FRONTEND_URL ?>/trips"   class="hover:text-white transition-colors flex items-center gap-1.5"><span class="text-secondary text-xs">▸</span> All Trips</a></li>
          <li><a href="<?= FRONTEND_URL ?>/country" class="hover:text-white transition-colors flex items-center gap-1.5"><span class="text-secondary text-xs">▸</span> Destinations</a></li>
          <li><a href="<?= FRONTEND_URL ?>/blog"    class="hover:text-white transition-colors flex items-center gap-1.5"><span class="text-secondary text-xs">▸</span> Travel Blog</a></li>
          <li><a href="<?= FRONTEND_URL ?>/about"   class="hover:text-white transition-colors flex items-center gap-1.5"><span class="text-secondary text-xs">▸</span> About Us</a></li>
          <li><a href="<?= FRONTEND_URL ?>/contact" class="hover:text-white transition-colors flex items-center gap-1.5"><span class="text-secondary text-xs">▸</span> Contact</a></li>
        </ul>
      </div>

      <!-- Trip Types -->
      <div>
        <h4 class="text-xs font-extrabold uppercase tracking-widest text-gray-400 mb-5">Trip Types</h4>
        <ul class="space-y-2.5 text-sm text-gray-400">
          <li><a href="<?= FRONTEND_URL ?>/trips?type=Domestic"      class="hover:text-white transition-colors flex items-center gap-1.5"><span class="text-secondary text-xs">▸</span> Domestic Tours</a></li>
          <li><a href="<?= FRONTEND_URL ?>/trips?type=International" class="hover:text-white transition-colors flex items-center gap-1.5"><span class="text-secondary text-xs">▸</span> International</a></li>
          <li><a href="<?= FRONTEND_URL ?>/trips?type=Adventure"     class="hover:text-white transition-colors flex items-center gap-1.5"><span class="text-secondary text-xs">▸</span> Adventure Trips</a></li>
          <li><a href="<?= FRONTEND_URL ?>/trips?type=Luxury"        class="hover:text-white transition-colors flex items-center gap-1.5"><span class="text-secondary text-xs">▸</span> Luxury Tours</a></li>
          <li><a href="<?= FRONTEND_URL ?>/trips"                    class="hover:text-white transition-colors flex items-center gap-1.5"><span class="text-secondary text-xs">▸</span> Honeymoon Packages</a></li>
        </ul>
      </div>

      <!-- Contact Info -->
      <div>
        <h4 class="text-xs font-extrabold uppercase tracking-widest text-gray-400 mb-5">Get In Touch</h4>
        <ul class="space-y-3 text-sm text-gray-400">
          <li class="flex items-start gap-2">
            <span class="text-secondary mt-0.5 shrink-0">📍</span>
            <?= e(CONTACT_ADDRESS) ?>
          </li>
          <li class="flex items-center gap-2">
            <span class="text-secondary shrink-0">📞</span>
            <a href="tel:<?= preg_replace('/\s+/', '', CONTACT_PHONE) ?>" class="hover:text-white transition-colors">
              <?= e(CONTACT_PHONE) ?>
            </a>
          </li>
          <li class="flex items-center gap-2">
            <span class="text-secondary shrink-0">✉️</span>
            <a href="mailto:<?= e(CONTACT_EMAIL) ?>" class="hover:text-white transition-colors">
              <?= e(CONTACT_EMAIL) ?>
            </a>
          </li>
          <li class="flex items-center gap-2">
            <span class="text-secondary shrink-0">🕐</span>
            Mon–Sat: 9:00 AM – 7:00 PM
          </li>
        </ul>
      </div>
    </div>

    <!-- Bottom bar -->
    <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-600">
      <p>&copy; <?= date('Y') ?> <?= (defined('SITE_NAME_PART1') ? SITE_NAME_PART1 : 'IBCC') ?> <?= (defined('SITE_NAME_PART2') ? SITE_NAME_PART2 : 'Trip') ?>. All rights reserved.</p>
      <div class="flex flex-wrap gap-4">
        <a href="<?= FRONTEND_URL ?>/about"   class="hover:text-gray-300 transition-colors">About</a>
        <a href="#" class="hover:text-gray-300 transition-colors">Privacy Policy</a>
        <a href="#" class="hover:text-gray-300 transition-colors">Terms & Conditions</a>
        <a href="#" class="hover:text-gray-300 transition-colors">Refund Policy</a>
        <a href="<?= FRONTEND_URL ?>/sitemap" class="hover:text-gray-300 transition-colors">Sitemap</a>
      </div>
    </div>
  </div>
</footer>

<!-- WhatsApp Floating Button -->
<a href="https://wa.me/<?= WHATSAPP_NO ?>?text=Hi!+I'm+interested+in+booking+a+trip+with+IBCC+Trip."
   target="_blank" rel="noopener"
   title="Chat on WhatsApp"
   class="fixed bottom-6 right-6 z-50 w-14 h-14 bg-green-500 rounded-full shadow-2xl
          flex items-center justify-center hover:bg-green-600 transition-all
          hover:scale-110 transform duration-200">
  <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94
             1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198
             0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306
             1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347
             m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45
             4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885
             9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588
             5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335
             11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
  </svg>
</a>
