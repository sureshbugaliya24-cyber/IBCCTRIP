<?php
// components/about-section.php
function renderAboutSection() {
    $img1 = 'https://images.unsplash.com/photo-1503220317375-aaad61436b1b?auto=format&fit=crop&w=800&q=80';
    $img2 = 'https://images.unsplash.com/photo-1527631746610-bca00a040d60?auto=format&fit=crop&w=800&q=80';
    
    return '
    <section class="py-24 px-4 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Image Grid -->
                <div class="relative">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-4">
                            <div class="aspect-[3/4] rounded-[2.5rem] overflow-hidden shadow-2xl">
                                <img src="' . $img1 . '" class="w-full h-full object-cover">
                            </div>
                            <div class="bg-primary p-8 rounded-[2.5rem] text-white space-y-2">
                                <h4 class="text-3xl font-black">10+</h4>
                                <p class="text-xs font-bold uppercase tracking-widest opacity-80">Years Experience</p>
                            </div>
                        </div>
                        <div class="pt-12 space-y-4">
                            <div class="bg-secondary p-8 rounded-[2.5rem] text-white space-y-2">
                                <h4 class="text-3xl font-black">5K+</h4>
                                <p class="text-xs font-bold uppercase tracking-widest opacity-80">Happy Travelers</p>
                            </div>
                            <div class="aspect-[3/4] rounded-[2.5rem] overflow-hidden shadow-2xl">
                                <img src="' . $img2 . '" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="space-y-8">
                    <div class="space-y-4">
                        <span class="inline-block px-4 py-2 rounded-full bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest">About Our Agency</span>
                        <h2 class="text-5xl font-black text-gray-900 uppercase tracking-tight leading-tight">We help you discover the world differently</h2>
                        <div class="w-20 h-1.5 bg-secondary rounded-full"></div>
                    </div>
                    
                    <p class="text-gray-500 text-lg leading-relaxed">
                        At IBCCTRIP, we believe that traveling is more than just visiting new places. It\'s about creating lasting memories, discovering new cultures, and finding yourself along the way.
                    </p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-500 flex items-center justify-center font-bold text-lg shrink-0">✓</div>
                            <div>
                                <h4 class="text-gray-900 font-extrabold text-lg uppercase tracking-tight">Personalized Itineraries</h4>
                                <p class="text-gray-500 text-sm">We tailor every trip to your specific preferences and budget.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center font-bold text-lg shrink-0">✓</div>
                            <div>
                                <h4 class="text-gray-900 font-extrabold text-lg uppercase tracking-tight">Expert Local Guides</h4>
                                <p class="text-gray-500 text-sm">Our guides are locals who know the hidden gems of every destination.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-4">
                        <a href="' . FRONTEND_URL . '/about" class="inline-flex items-center gap-4 bg-gray-900 text-white font-black px-10 py-5 rounded-3xl hover:bg-primary transition-all shadow-xl text-xs uppercase tracking-widest group">
                            Learn More About Us
                            <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>';
}
