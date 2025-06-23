<?php 
session_start();
require 'components/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zizz Shop</title>
  <link rel="icon" type="image/png" href="https://files.catbox.moe/435ni4.jpg">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>
    html {
      scroll-behavior: smooth;
    }
    .slide-down {
      transform: translateY(0);
      transition: transform 0.3s ease-in-out;
    }
    .slide-up {
      transform: translateY(-100%);
      transition: transform 0.3s ease-in-out;
    }
    .glass {
      background: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
    }

    /* Preloader Styles */
    .preloader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, #3b82f6, #14b8a6);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      opacity: 1;
      transition: opacity 0.5s ease-out;
    }

    .preloader.fade-out {
      opacity: 0;
      pointer-events: none;
    }

    .loader {
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 20px;
    }

    .loader-logo {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      animation: pulse 2s infinite;
      box-shadow: 0 0 30px rgba(255, 255, 255, 0.3);
    }

    .loader-spinner {
      width: 50px;
      height: 50px;
      border: 4px solid rgba(255, 255, 255, 0.3);
      border-top: 4px solid white;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    .loader-text {
      color: white;
      font-size: 18px;
      font-weight: 600;
      animation: fadeInOut 2s infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.1); }
    }

    @keyframes fadeInOut {
      0%, 100% { opacity: 0.7; }
      50% { opacity: 1; }
    }

    /* Hide main content during loading */
    .main-content {
      opacity: 0;
      transition: opacity 0.5s ease-in;
    }

    .main-content.loaded {
      opacity: 1;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-blue-100 via-white to-teal-100 text-gray-800 font-sans">

  <!-- Preloader -->
  <div id="preloader" class="preloader">
    <div class="loader">
      <img src="https://files.catbox.moe/435ni4.jpg" alt="Loading..." class="loader-logo">
      <div class="loader-spinner"></div>
      <div class="loader-text">Loading....</div>
    </div>
  </div>

  <!-- Main Content -->
  <div id="main-content" class="main-content">
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 w-full glass shadow-md z-50">
      <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-2">
          <img src="https://files.catbox.moe/435ni4.jpg" alt="Zizz Logo" class="w-9 h-9 rounded-full shadow-md">
          <span class="text-xl font-bold text-blue-700 tracking-wide">Zizz Shop</span>
        </div>
        <div class="hidden md:flex space-x-6 font-medium text-sm">
          <a href="#home" class="hover:text-teal-500 transition">🏠 Home</a>
          <a href="#about" class="hover:text-teal-500 transition">📖 About</a>
          <a href="#product" class="hover:text-teal-500 transition">🛍️ Product</a>
          <a href="#support" class="hover:text-teal-500 transition">💬 Support</a>
        </div>
        <div class="hidden md:flex gap-3">
          <a href="auth/index.php#register" class="bg-gradient-to-r from-blue-200 to-teal-200 text-blue-900 px-4 py-2 rounded-full hover:scale-105 transition">Registrasi</a>
          <a href="auth/index.php#login" class="bg-gradient-to-r from-blue-600 to-teal-500 text-white px-4 py-2 rounded-full hover:scale-105 transition">Login</a>
        </div>
        <button id="menu-btn" class="md:hidden">
          <svg class="w-7 h-7 text-blue-700" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>

      <!-- Mobile Menu -->
      <div id="mobile-menu" class="md:hidden fixed top-0 left-0 w-full h-screen bg-white slide-up z-40">
        <div class="p-6">
          <div class="flex justify-between mb-6">
            <span class="text-xl font-bold text-blue-600">Zizz Shop</span>
            <button id="close-menu">
              <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <nav class="flex flex-col gap-4 text-lg">
            <a href="#home" onclick="closeMobileMenu()">🏠 Home</a>
            <a href="#about" onclick="closeMobileMenu()">📖 About</a>
            <a href="#product" onclick="closeMobileMenu()">🛍️ Product</a>
            <a href="#support" onclick="closeMobileMenu()">💬 Support</a>
            <a href="auth/index.php#register" class="bg-blue-200 text-blue-700 px-4 py-2 rounded-full mt-4">Registrasi</a>
            <a href="auth/index.php#login" class="bg-blue-600 text-white px-4 py-2 rounded-full mt-2">Login</a>
          </nav>
        </div>
      </div>
    </nav>

    <!-- Hero -->
    <section id="home" class="pt-[140px] pb-20 px-6 text-center">
      <h1 class="text-4xl md:text-6xl font-bold bg-gradient-to-r from-blue-700 to-teal-500 text-transparent bg-clip-text mb-6">Datang di Zizz Shop</h1>
      <p class="text-gray-700 max-w-xl mx-auto mb-8 text-sm md:text-base leading-relaxed">Toko digital terpercaya untuk kebutuhan  lorem , ipsum , dan dolor dengan pelayanan kilat!</p>
      <a href="#product" class="inline-block bg-gradient-to-r from-blue-600 to-teal-500 text-white px-8 py-4 rounded-full font-semibold text-lg hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">Lihat Produk</a>
    </section>

    <!-- About -->
    <section id="about" class="py-20 px-6 bg-white/50 backdrop-blur-sm">
      <h2 class="text-3xl font-bold text-center text-blue-700 mb-6">Tentang Kami</h2>
      <p class="max-w-3xl mx-auto text-center text-gray-700">
        Zizz Shop adalah penyedia layanan digital terpercaya. Dari lorem, ipsum, hingga dolor, kami hadir untuk memenuhi kebutuhan digital Anda dengan harga terjangkau dan pelayanan cepat.
      </p>
    </section>

    <!-- Product -->
    <section id="product" class="py-20 px-6 bg-gradient-to-br from-white via-blue-50 to-teal-50">
      <h2 class="text-3xl font-bold text-center text-blue-700 mb-8">Produk Kami</h2>
      <div class="grid md:grid-cols-3 gap-6">
        <div class="p-6 bg-white rounded-2xl shadow-lg hover:scale-[1.03] transition">
          <h3 class="text-xl font-bold text-teal-600 mb-2">Panel Pterodactyl</h3>
          <p class="text-gray-600">lorem ipsum dolor sit amet jeow owjdn </p>
        </div>
        <div class="p-6 bg-white rounded-2xl shadow-lg hover:scale-[1.03] transition">
          <h3 class="text-xl font-bold text-teal-600 mb-2">📱 Pulsa & Data</h3>
          <p class="text-gray-600">Tersedia semua operator, isi ulang cepat dan praktis.</p>
        </div>
        <div class="p-6 bg-white rounded-2xl shadow-lg hover:scale-[1.03] transition">
          <h3 class="text-xl font-bold text-teal-600 mb-2">🎁 Voucher Digital</h3>
          <p class="text-gray-600">Google Play, iTunes, Spotify, dan masih banyak lagi!</p>
        </div>
      </div>
    </section>

    <!-- Support -->
    <section id="support" class="py-20 px-6 bg-white/60 backdrop-blur-md">
      <h2 class="text-3xl font-bold text-center text-blue-700 mb-6">Bantuan & Informasi</h2>
      <div class="max-w-2xl mx-auto text-center text-gray-700 space-y-4">
        <p>🔹 Telegram CS: <a href="https://t.me/zizzshop88" class="text-blue-600 underline">t.me/zizzshop88</a></p>
        <p>📩 Kirim struk: <a href="https://wa.me/6285705390430" class="text-blue-600 underline">WhatsApp Admin</a></p>
        <p>💬 Testimoni: <a href="https://whatsapp.com/channel/0029VbBBWkn0rGiCnzsMpQ0s" class="text-blue-600 underline">Channel Testi</a></p>
        <p>📢 Info SC: <a href="https://whatsapp.com/channel/0029VbAgdqrElagwZRgY" class="text-blue-600 underline">Saluran Utama</a></p>
      </div>
    </section>

<?php include 'components/footer.php'; ?>

  <!-- Script -->
  <script>
    // Preloader functionality
    window.addEventListener('load', function() {
      const preloader = document.getElementById('preloader');
      const mainContent = document.getElementById('main-content');
      
      // Minimum loading time untuk efek visual yang baik
      setTimeout(() => {
        preloader.classList.add('fade-out');
        mainContent.classList.add('loaded');
        
        // Remove preloader from DOM after animation
        setTimeout(() => {
          preloader.style.display = 'none';
        }, 500);
      }, 1500); // 1.5 detik minimum loading time
    });

    // Mobile menu functionality
    const menuBtn = document.getElementById('menu-btn');
    const closeBtn = document.getElementById('close-menu');
    const mobileMenu = document.getElementById('mobile-menu');

    menuBtn.addEventListener('click', () => {
      mobileMenu.classList.remove('slide-up');
      mobileMenu.classList.add('slide-down');
    });

    closeBtn.addEventListener('click', () => {
      mobileMenu.classList.remove('slide-down');
      mobileMenu.classList.add('slide-up');
    });

    function closeMobileMenu() {
      mobileMenu.classList.remove('slide-down');
      mobileMenu.classList.add('slide-up');
    }
  </script>
</body>
</html>