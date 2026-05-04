<?php
// Mendefinisikan konstanta koneksi
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '123'); 
define('DB_NAME', 'db_absensi_siswa'); 
define('DB_PORT', 3310); 

// Membuat koneksi dengan menyertakan port
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set karakter
$conn->set_charset("utf8");
?>