<?php
$host = "localhost";  // Nama host, biasanya "localhost"
$user = "root";       // Username database
$pass = "";           // Password database (kosong jika tidak ada)
$db   = "kegiatan"; // Nama database

// Membuat koneksi ke MySQL
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (mysqli_connect_errno()) {
    echo "Koneksi database gagal: " . mysqli_connect_error();
    exit();
} else {
    echo "Koneksi berhasil!";
}

?>
