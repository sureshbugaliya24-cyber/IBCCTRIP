<?php
// components/cta-section.php
function renderCTASection() {
    $bgImage = 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=1920&q=80';
    return '
    <section class="py-20 px-4">
        <div class="max-w-7xl mx-auto relative rounded-[3rem] overflow-hidden bg-primary py-24 px-8 text-center shadow-2xl shadow-primary/20">
            <div class="absolute inset-0 opacity-20">
                <img src="' . $bgImage . '" class="w-full h-full object-cover grayscale">
            </div>
            <div class="relative z-10 max-w-2xl mx-auto space-y-8">
                <span class="inline-block px-4 py-2 rounded-full bg-white/20 text-white text-[10px] font-black uppercase tracking-widest backdrop-blur-md">Start Your Journey</span>
                <h2 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tight leading-tight">Ready to explore the world with us?</h2>
                <p class="text-white/80 text-lg font-medium">Join 5000+ happy travelers who discovered unique destinations with IBCCTRIP.</p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="' . FRONTEND_URL . '/trips" class="bg-secondary text-white font-black px-10 py-5 rounded-2xl shadow-xl hover:scale-105 active:scale-95 transition-all text-xs uppercase tracking-widest">Book Your Trip</a>
                    <a href="' . FRONTEND_URL . '/contact" class="bg-white/10 text-white font-black px-10 py-5 rounded-2xl backdrop-blur-md border border-white/20 hover:bg-white/20 transition-all text-xs uppercase tracking-widest">Contact Support</a>
                </div>
            </div>
        </div>
    </section>';
}
