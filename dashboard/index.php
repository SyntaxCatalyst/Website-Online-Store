<?php
session_start();

if (!isset($_SESSION['user'])) {
    echo '
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative text-center mt-4 mx-auto max-w-xl" role="alert">
        <strong class="font-bold">⚠️ Anda belum login!</strong>
        <span class="block sm:inline">Silakan <a href="../auth/index.php#login" class="underline text-blue-600">Login</a> atau <a href="../auth/index.php#register" class="underline text-blue-600">Daftar</a> terlebih dahulu.</span>
    </div>';
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <h1>
    INI DASHBOARD
  </h1>
</body>
</html>