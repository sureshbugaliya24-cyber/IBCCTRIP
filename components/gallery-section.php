<?php
// components/gallery-section.php

function renderGallerySection($limit = 8) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM gallery ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        $images = $stmt->fetchAll();
    } catch (Exception $e) {
        $images = [];
    }
    
    // Show empty state message if gallery is empty
    if (empty($images)) {
        return '
        <section class="py-24 px-4 bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto space-y-8 text-center">
                <span class="inline-block px-4 py-2 rounded-full bg-secondary/10 text-secondary text-[10px] font-black uppercase tracking-widest">Moments Captured</span>
                <h2 class="text-3xl md:text-5xl font-black text-gray-900 uppercase tracking-tight">Gallery is being updated</h2>
                <p class="text-gray-500 font-medium">Check back soon for new travel inspirations.</p>
                <div class="w-24 h-1.5 bg-primary mx-auto rounded-full mt-8"></div>
            </div>
        </section>';
    }

    $html = '
    <section class="py-24 px-4 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto space-y-16">
            <div class="text-center space-y-4">
                <span class="inline-block px-4 py-2 rounded-full bg-secondary/10 text-secondary text-[10px] font-black uppercase tracking-widest">Moments Captured</span>
                <h2 class="text-5xl font-black text-gray-900 uppercase tracking-tight">Our Travel Gallery</h2>
                <div class="w-24 h-1.5 bg-primary mx-auto rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                ' . array_reduce($images, function($acc, $img) {
                    return $acc . '
                    <div class="group relative aspect-square overflow-hidden rounded-[2.5rem] shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 cursor-pointer">
                        <img src="' . $img['image_url'] . '" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-8">
                            <span class="text-primary text-[10px] font-black uppercase tracking-widest mb-1">' . ($img['category'] ?? 'Travel') . '</span>
                            <p class="text-white text-sm font-bold">' . ($img['caption'] ?? 'Beautiful Journey') . '</p>
                        </div>
                    </div>';
                }, '') . '
            </div>
            
            <div class="text-center pt-8">
                <a href="' . FRONTEND_URL . '/gallery" class="group inline-flex items-center gap-3 text-gray-900 font-black uppercase text-xs tracking-widest hover:text-primary transition-all">
                    View Full Gallery
                    <span class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all">→</span>
                </a>
            </div>
        </div>
    </section>';
    
    return $html;
}
