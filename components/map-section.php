<?php
// components/map-section.php
function renderMapSection() {
    $address = defined('CONTACT_ADDRESS') ? CONTACT_ADDRESS : 'New Delhi, India';
    $mapUrl = "https://www.google.com/maps?q=" . urlencode($address) . "&output=embed";
    
    return '
    <section class="py-20 bg-gray-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-8">
                    <div class="space-y-4 text-center lg:text-left">
                        <span class="inline-block px-4 py-2 rounded-full bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest">Our Presence</span>
                        <h2 class="text-4xl font-black text-gray-900 uppercase tracking-tight">Visit Our Office</h2>
                        <p class="text-gray-500 text-lg">We are located in the heart of the city. Feel free to drop by for a cup of coffee and discuss your next adventure.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-secondary/10 flex items-center justify-center text-secondary text-xl shrink-0">📍</div>
                            <div><h4 class="font-black text-gray-900 uppercase text-xs tracking-widest mb-1">Our Address</h4><p class="text-sm text-gray-500">' . $address . '</p></div>
                        </div>
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary text-xl shrink-0">📞</div>
                            <div><h4 class="font-black text-gray-900 uppercase text-xs tracking-widest mb-1">Contact Us</h4><p class="text-sm text-gray-500">' . (defined('CONTACT_PHONE') ? CONTACT_PHONE : '+91 12345 67890') . '</p></div>
                        </div>
                    </div>
                </div>
                
                <div class="h-[500px] rounded-[3rem] overflow-hidden shadow-2xl shadow-gray-200 border-8 border-white bg-white">
                    <iframe src="' . $mapUrl . '" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </section>';
}
