<?php
session_start();
// Jika user sudah login, redirect ke dashboard (atau halaman utama)
if (isset($_SESSION['user'])) {
    header('Location: ../dashboard/');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login & Register - Zizz Shop</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          animation: {
            'fade-in': 'fadeIn 0.5s ease-in-out',
            'slide-up': 'slideUp 0.5s ease-out',
          }
        }
      }
    }
  </script>
  <style>
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    @keyframes slideUp {
      from { transform: translateY(20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
  </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
  <div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center animate-fade-in">
        <div class="mx-auto h-16 w-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mb-6">
          <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
        </div>
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang</h2>
        <p class="text-gray-600">Silakan masuk atau daftar akun baru</p>
      </div>

      <div class="flex bg-gray-100 rounded-lg p-1 animate-slide-up">
        <button id="loginTab" class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition bg-white text-blue-600 shadow-sm">Masuk</button>
        <button id="registerTab" class="flex-1 py-2 px-4 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700">Daftar</button>
      </div>

      <!-- LOGIN -->
      <div id="loginForm" class="bg-white py-8 px-6 rounded-xl shadow-lg border border-gray-100 animate-slide-up">
        <form id="loginFormElement" class="space-y-6">
          <input type="email" id="loginEmail" name="email" placeholder="Email" required class="w-full px-4 py-2 border rounded" />
          <input type="password" id="loginPassword" name="password" placeholder="Password" required class="w-full px-4 py-2 border rounded" />
          <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded hover:bg-blue-600">Masuk</button>
        </form>
      </div>

      <!-- REGISTER -->
      <div id="registerForm" class="bg-white py-8 px-6 rounded-xl shadow-lg border border-gray-100 hidden animate-slide-up">
        <form id="registerFormElement" class="space-y-6">
          <input type="text" id="registerUsername" name="username" placeholder="Username" required class="w-full px-4 py-2 border rounded" />
          <input type="email" id="registerEmail" name="email" placeholder="Email" required class="w-full px-4 py-2 border rounded" />
          <input type="password" id="registerPassword" name="password" placeholder="Password" required class="w-full px-4 py-2 border rounded" />
          <button type="submit" class="w-full bg-green-500 text-white py-3 rounded hover:bg-green-600">Daftar</button>
        </form>
      </div>

      <div id="alertContainer" class="text-center text-sm mt-4 text-red-600 hidden"></div>
    </div>
  </div>

  <script>
    const loginTab = document.getElementById('loginTab');
    const registerTab = document.getElementById('registerTab');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    loginTab.addEventListener('click', () => {
      loginTab.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
      registerTab.classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
      loginForm.classList.remove('hidden');
      registerForm.classList.add('hidden');
    });

    registerTab.addEventListener('click', () => {
      registerTab.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
      loginTab.classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
      registerForm.classList.remove('hidden');
      loginForm.classList.add('hidden');
    });

    // Handle login
    document.getElementById('loginFormElement').addEventListener('submit', async (e) => {
      e.preventDefault();
      const email = document.getElementById('loginEmail').value;
      const password = document.getElementById('loginPassword').value;

      const response = await fetch('../api/auth/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
      });

      const data = await response.json();
      if (data.status === 'success') {
        window.location.href = '../dashboard/';
      } else {
        showError(data.message || 'Login gagal');
      }
    });

    // Handle register
    document.getElementById('registerFormElement').addEventListener('submit', async (e) => {
      e.preventDefault();
      const username = document.getElementById('registerUsername').value;
      const email = document.getElementById('registerEmail').value;
      const password = document.getElementById('registerPassword').value;

      const response = await fetch('../api/auth/register.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, email, password })
      });

      const data = await response.json();
      if (data.status === 'success') {
        showError('Registrasi berhasil! Silakan login.', 'green');
        setTimeout(() => loginTab.click(), 1500);
      } else {
        showError(data.message || 'Registrasi gagal');
      }
    });

    function showError(msg, color = 'red') {
      const el = document.getElementById('alertContainer');
      el.classList.remove('hidden');
      el.classList.remove('text-red-600', 'text-green-600');
      el.classList.add(`text-${color}-600`);
      el.textContent = msg;
    }

    // Auto tab from URL
    const hash = window.location.hash;
    if (hash === '#register') {
      registerTab.click();
    } else {
      loginTab.click();
    }
  </script>
</body>
</html>
