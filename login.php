<?php
// frontend/login.php — Login and Registration Page
// ─────────────────────────────────────────────────────────────

require_once __DIR__ . '/components/config.php';
require_once __DIR__ . '/components/helpers.php';

// If already logged in, redirect to home or intended page
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header('Location: ' . (($_SESSION['user_role'] === 'admin') ? 'admin/dashboard.php' : FRONTEND_URL . '/'));
    exit();
}

$pageTitle = 'Login / Register';
$activePage = 'login';
$redirect = qParam('redirect', FRONTEND_URL . '/');

require_once __DIR__ . '/layouts/head.php';
require_once __DIR__ . '/layouts/header.php';
?>

<div class="min-h-[80vh] flex items-center justify-center py-12 px-4">
  <div class="max-w-md w-full">
    <!-- Card Container -->
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden transform transition-all">
      
      <!-- Tabs Header -->
      <div class="flex border-b border-gray-100">
        <button onclick="switchLoginTab('login')" id="btn-login-tab"
                class="flex-1 py-4 text-sm font-bold border-b-2 transition-all border-primary text-primary">
          Login
        </button>
        <button onclick="switchLoginTab('register')" id="btn-register-tab"
                class="flex-1 py-4 text-sm font-bold border-b-2 transition-all border-transparent text-gray-400 hover:text-gray-600">
          Create Account
        </button>
      </div>

      <div class="p-8">
        <!-- Logo/Welcome -->
        <div class="text-center mb-8">
          <div class="mx-auto mb-4 flex justify-center">
            <?= renderLogo('icon', 'w-16 h-16 !rounded-2xl text-3xl') ?>
          </div>
          <h2 id="auth-title" class="text-2xl font-extrabold text-gray-900">Welcome Back!</h2>
          <p id="auth-subtitle" class="text-gray-400 text-sm mt-1">Sign in to manage your trips and bookings.</p>
        </div>

        <!-- Alert display -->
        <div id="auth-alert" class="hidden mb-6 p-4 rounded-xl text-sm font-medium"></div>

        <!-- Login Form -->
        <form id="login-form" onsubmit="handleLogin(event)" class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5 ml-1">Email Address</label>
            <input type="email" id="login-email" required placeholder="name@example.com"
                   class="w-full bg-gray-50 border border-gray-100 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
          </div>
          <div class="relative">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5 ml-1">Password</label>
            <input type="password" id="login-password" required placeholder="••••••••"
                   class="w-full bg-gray-50 border border-gray-100 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            <button type="button" onclick="togglePassword('login-password')"
                    class="absolute top-[34px] right-3 text-gray-400 hover:text-gray-600 p-1">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </button>
            <div class="text-right mt-1.5 px-1">
              <a href="#" class="text-xs text-primary font-bold hover:underline">Forgot password?</a>
            </div>
          </div>
          <button type="submit" id="btn-login"
                  class="w-full bg-primary text-white font-extrabold py-4 rounded-xl shadow-lg shadow-primary/20 hover:bg-blue-800 transition-all flex items-center justify-center gap-2">
            Sign In
          </button>
          
          <div class="mt-8 pt-6 border-t border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest text-center mb-4">Demo Credentials</p>
            <div class="grid grid-cols-2 gap-3 text-center">
              <div onclick="fillDemo('admin@ibcctrip.com', 'password')" class="p-2.5 rounded-xl border border-dashed border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors">
                <p class="text-xs font-bold text-primary">Admin Access</p>
                <p class="text-[10px] text-gray-400">admin@ibcctrip.com</p>
              </div>
              <div onclick="fillDemo('rajesh@example.com', 'password')" class="p-2.5 rounded-xl border border-dashed border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors">
                <p class="text-xs font-bold text-secondary">Customer Access</p>
                <p class="text-[10px] text-gray-400">rajesh@example.com</p>
              </div>
            </div>
          </div>
        </form>

        <!-- Register Form -->
        <form id="register-form" onsubmit="handleRegister(event)" class="space-y-4 hidden">
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5 ml-1">Full Name</label>
              <input type="text" id="reg-name" required placeholder="John Doe"
                     class="w-full bg-gray-50 border border-gray-100 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-primary transition-all">
            </div>
            <div>
              <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5 ml-1">Phone</label>
              <input type="tel" id="reg-phone" required placeholder="9876543210"
                     class="w-full bg-gray-50 border border-gray-100 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-primary transition-all">
            </div>
          </div>
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5 ml-1">Email Address</label>
            <input type="email" id="reg-email" required placeholder="name@example.com"
                   class="w-full bg-gray-50 border border-gray-100 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-primary transition-all">
          </div>
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5 ml-1">Create Password</label>
            <input type="password" id="reg-password" required placeholder="At least 6 characters"
                   class="w-full bg-gray-50 border border-gray-100 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-primary transition-all">
          </div>
          <div class="flex items-start gap-3 px-1 mt-2">
            <input type="checkbox" required id="reg-terms" class="mt-1 accent-primary">
            <label for="reg-terms" class="text-xs text-gray-500 leading-normal">
              I agree to the <a href="#" class="text-primary font-bold hover:underline">Terms of Service</a> and <a href="#" class="text-primary font-bold hover:underline">Privacy Policy</a>.
            </label>
          </div>
          <button type="submit" id="btn-register"
                  class="w-full bg-secondary text-white font-extrabold py-4 rounded-xl shadow-lg shadow-secondary/20 hover:bg-orange-600 transition-all">
            Create Free Account
          </button>
        </form>

      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
<script src="<?= FRONTEND_URL ?>/js/app.js?v=<?= APP_VERSION ?>"></script>
<script>
const REDIRECT_URL = '<?= e($redirect) ?>';

async function handleLogin(e) {
  e.preventDefault();
  const alertEl = document.getElementById('auth-alert');
  const btn     = document.getElementById('btn-login');
  
  alertEl.classList.add('hidden');
  btn.disabled = true;
  btn.innerHTML = '<span class="anim-spin inline-block w-4 h-4 border-2 border-white/30 border-t-white rounded-full"></span> Signing in...';

  const data = {
    email:    document.getElementById('login-email').value,
    password: document.getElementById('login-password').value
  };

  const response = await IBCC.auth.login(data);
  
  if (response?.success) {
    showAlert('Success! Redirecting...', 'success');
    
    setTimeout(() => {
      // Redirect based on role
      if (response.data.role === 'admin') {
        window.location.href = '<?= FRONTEND_URL ?>/admin/dashboard.php';
      } else {
        window.location.href = REDIRECT_URL;
      }
    }, 1000);
  } else {
    showAlert(response?.message || 'Invalid credentials. Please try again.', 'error');
    btn.disabled = false;
    btn.innerHTML = 'Sign In';
  }
}

async function handleRegister(e) {
  e.preventDefault();
  const alertEl = document.getElementById('auth-alert');
  const btn     = document.getElementById('btn-register');

  alertEl.classList.add('hidden');
  btn.disabled = true;
  btn.innerHTML = '<span class="anim-spin inline-block w-4 h-4 border-2 border-white/30 border-t-white rounded-full"></span> Creating account...';

  const data = {
    name:     document.getElementById('reg-name').value,
    email:    document.getElementById('reg-email').value,
    phone:    document.getElementById('reg-phone').value,
    password: document.getElementById('reg-password').value
  };

  const response = await IBCC.auth.register(data);

  if (response?.success) {
    showAlert('Account created! Logging you in...', 'success');
    setTimeout(() => {
      window.location.href = REDIRECT_URL;
    }, 1500);
  } else {
    showAlert(response?.message || 'Registration failed.', 'error');
    btn.disabled = false;
    btn.innerHTML = 'Create Free Account';
  }
}

function switchLoginTab(tab) {
  const loginForm    = document.getElementById('login-form');
  const registerForm = document.getElementById('register-form');
  const loginBtn     = document.getElementById('btn-login-tab');
  const registerBtn  = document.getElementById('btn-register-tab');
  const title        = document.getElementById('auth-title');
  const subtitle     = document.getElementById('auth-subtitle');
  const alertEl      = document.getElementById('auth-alert');

  alertEl.classList.add('hidden');

  if (tab === 'login') {
    loginForm.classList.remove('hidden');
    registerForm.classList.add('hidden');
    loginBtn.classList.add('border-primary', 'text-primary');
    loginBtn.classList.remove('border-transparent', 'text-gray-400');
    registerBtn.classList.remove('border-primary', 'text-primary');
    registerBtn.classList.add('border-transparent', 'text-gray-400');
    title.textContent = 'Welcome Back!';
    subtitle.textContent = 'Sign in to manage your trips and bookings.';
  } else {
    loginForm.classList.add('hidden');
    registerForm.classList.remove('hidden');
    registerBtn.classList.add('border-primary', 'text-primary');
    registerBtn.classList.remove('border-transparent', 'text-gray-400');
    loginBtn.classList.remove('border-primary', 'text-primary');
    loginBtn.classList.add('border-transparent', 'text-gray-400');
    title.textContent = 'Join IBCC Trip';
    subtitle.textContent = 'Experience premium travel with India\'s best.';
  }
}

function showAlert(msg, type) {
  const alertEl = document.getElementById('auth-alert');
  alertEl.textContent = msg;
  alertEl.classList.remove('hidden', 'bg-red-50', 'text-red-700', 'bg-green-50', 'text-green-700');
  
  if (type === 'success') {
    alertEl.classList.add('bg-green-50', 'text-green-700');
  } else {
    alertEl.classList.add('bg-red-50', 'text-red-700');
  }
}

function togglePassword(id) {
  const el = document.getElementById(id);
  el.type = el.type === 'password' ? 'text' : 'password';
}

function fillDemo(email, pass) {
  document.getElementById('login-email').value = email;
  document.getElementById('login-password').value = pass;
  switchLoginTab('login');
}
</script>
</body>
</html>
