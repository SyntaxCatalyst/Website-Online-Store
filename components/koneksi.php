<?php

$host = '192.168.1.68';
$username = 'root';
$passwd = 'root';
$dbname = 'zizshop';

// Membuat koneksi
$conn = mysqli_connect($host, $username, $passwd, $dbname);

// Mengecek status koneksi
if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

return 'Sukses';

?>
