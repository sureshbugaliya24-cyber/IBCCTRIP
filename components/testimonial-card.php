<?php
/**
 * components/testimonial-card.php
 * Renders a premium traveler testimonial card.
 */
function renderTestimonialCard($t) {
    $name    = $t['name'] ?? 'Traveler';
    $role    = $t['role'] ?? 'Verified Explorer';
    $rating  = (int)($t['rating'] ?? 5);
    $comment = $t['comment'] ?? '';
    $image   = $t['image'] ?? '';
    $stars   = str_repeat('⭐', $rating);
    $initial = strtoupper(substr($name, 0, 1));
    ?>
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
        <div class="flex items-center gap-4 mb-6">
            <div class="relative w-14 h-14 shrink-0">
                <img src="<?= e($image) ?>" 
                     alt="<?= e($name) ?>" 
                     class="w-full h-full rounded-2xl object-cover border-2 border-primary/10"
                     onerror="this.src='https://placehold.co/100?text=<?= $initial ?>'; this.classList.add('bg-primary/5', 'text-primary')">
                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-white flex items-center justify-center text-[8px] text-white">✓</div>
            </div>
            <div>
                <p class="font-black text-gray-900 leading-tight"><?= e($name) ?></p>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5"><?= e($role) ?></p>
            </div>
            <div class="ml-auto text-[10px] text-orange-400 font-black"><?= $stars ?></div>
        </div>
        <div class="relative">
            <span class="absolute -top-4 -left-2 text-4xl text-primary/5 font-serif select-none">“</span>
            <p class="text-gray-600 text-sm leading-relaxed italic relative z-10">
                <?= e($comment) ?>
            </p>
        </div>
    </div>
    <?php
}
