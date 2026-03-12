<?php
// frontend/contact.php — Contact Page
// ─────────────────────────────────────────────────────────────

require_once __DIR__ . '/components/config.php';
require_once __DIR__ . '/components/helpers.php';

$pageTitle = 'Contact Us';
$pageDesc  = 'Get in touch with IBCC Trip for customized travel quotes and support.';
$activePage = 'contact';

require_once __DIR__ . '/layouts/head.php';
require_once __DIR__ . '/layouts/header.php';
?>

<div class="bg-primary py-20 px-4 text-center text-white">
  <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Contact <span class="text-secondary">Us</span></h1>
  <p class="text-white/70 max-w-2xl mx-auto">Have questions? We're here to help you plan your perfect journey.</p>
</div>

<div class="max-w-7xl mx-auto px-4 -mt-10 mb-20">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Info Cards -->
    <div class="lg:col-span-1 space-y-6">
      <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100">
        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-2xl mb-4">📍</div>
        <h3 class="font-extrabold text-gray-900 text-lg mb-2">Our Office</h3>
        <p class="text-gray-500 text-sm leading-relaxed"><?= e(CONTACT_ADDRESS) ?></p>
      </div>
      
      <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100">
        <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-2xl mb-4">📞</div>
        <h3 class="font-extrabold text-gray-900 text-lg mb-2">Call Us</h3>
        <p class="text-gray-900 font-bold"><?= e(CONTACT_PHONE) ?></p>
        <p class="text-gray-400 text-xs mt-1">Mon-Sat, 9am - 7pm</p>
      </div>
      
      <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100">
        <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-2xl mb-4">✉️</div>
        <h3 class="font-extrabold text-gray-900 text-lg mb-2">Email Support</h3>
        <p class="text-gray-900 font-bold"><?= e(CONTACT_EMAIL) ?></p>
        <p class="text-gray-400 text-xs mt-1">Response within 24 hours</p>
      </div>
    </div>

    <!-- Contact Form -->
    <div class="lg:col-span-2">
      <div class="bg-white rounded-3xl p-8 md:p-12 shadow-2xl border border-gray-50">
        <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Send us a Message</h2>
        <p class="text-gray-400 text-sm mb-8">Fill out the form below and our travel expert will contact you soon.</p>
        
        <form id="contact-form" onsubmit="handleContact(event)" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 ml-1">Full Name</label>
              <input type="text" id="c-name" required placeholder="John Doe"
                     class="w-full bg-gray-50 border border-gray-100 rounded-2xl py-4 px-5 text-sm focus:outline-none focus:border-primary transition-all">
            </div>
            <div>
              <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 ml-1">Email Address</label>
              <input type="email" id="c-email" required placeholder="john@example.com"
                     class="w-full bg-gray-50 border border-gray-100 rounded-2xl py-4 px-5 text-sm focus:outline-none focus:border-primary transition-all">
            </div>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 ml-1">Phone Number</label>
              <input type="tel" id="c-phone" required placeholder="98765 43210"
                     class="w-full bg-gray-50 border border-gray-100 rounded-2xl py-4 px-5 text-sm focus:outline-none focus:border-primary transition-all">
            </div>
            <div>
              <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 ml-1">Subject</label>
              <select id="c-subject" required 
                      class="w-full bg-gray-50 border border-gray-100 rounded-2xl py-4 px-5 text-sm focus:outline-none focus:border-primary transition-all">
                <option value="">Select Enquiry Type</option>
                <option value="New Booking">New Booking Enquiry</option>
                <option value="Group Tour">Group Tour Request</option>
                <option value="Corporate">Corporate Travel</option>
                <option value="Feedback">Special Request / Feedback</option>
                <option value="Other">Other</option>
              </select>
            </div>
          </div>
          
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 ml-1">Your Message</label>
            <textarea id="c-message" required rows="5" placeholder="Tell us about your travel plans..."
                      class="w-full bg-gray-50 border border-gray-100 rounded-2xl py-4 px-5 text-sm focus:outline-none focus:border-primary transition-all resize-none"></textarea>
          </div>
          
          <div id="c-status" class="hidden p-4 rounded-2xl text-sm font-bold"></div>

          <button type="submit" id="btn-contact"
                  class="bg-secondary text-white font-extrabold px-10 py-4 rounded-2xl shadow-xl shadow-secondary/20 hover:bg-orange-600 transition-all flex items-center gap-2">
            Send Message 🚀
          </button>
        </form>
      </div>
    </div>

  </div>
</div>

<!-- Map -->
<section class="h-[400px] w-full bg-gray-200">
  <iframe 
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14008.114827184161!2d77.2167210!3d28.6129120!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfd5d34766085%3A0xa39d39ea1472871c!2sConnaught%20Place%2C%20New%20Delhi!5e0!3m2!1sen!2sin!4v1625484832483!5m2!1sen!2sin" 
    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
</section>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
<script src="<?= FRONTEND_URL ?>/js/app.js?v=<?= APP_VERSION ?>"></script>
<script>
async function handleContact(e) {
  e.preventDefault();
  const btn    = document.getElementById('btn-contact');
  const status = document.getElementById('c-status');
  
  btn.disabled = true;
  btn.textContent = 'Sending...';
  status.classList.add('hidden');

  const data = {
    name:    document.getElementById('c-name').value,
    email:   document.getElementById('c-email').value,
    phone:   document.getElementById('c-phone').value,
    subject: document.getElementById('c-subject').value,
    message: document.getElementById('c-message').value
  };

  const response = await IBCC.contact.submit(data);
  
  btn.disabled = false;
  btn.textContent = 'Send Message 🚀';
  
  if (response?.success) {
    status.textContent = '✅ Message sent successfully! We will contact you shortly.';
    status.classList.remove('hidden', 'bg-red-50', 'text-red-700');
    status.classList.add('bg-green-50', 'text-green-700');
    document.getElementById('contact-form').reset();
  } else {
    status.textContent = '❌ ' + (response?.message || 'Something went wrong. Please try again.');
    status.classList.remove('hidden', 'bg-green-50', 'text-green-700');
    status.classList.add('bg-red-50', 'text-red-700');
  }
}
</script>
</body>
</html>
