<?php
$host = 'localhost';
$username = 'root'; // Sesuaikan dengan username MySQL Anda
$password = ''; // Sesuaikan dengan password MySQL Anda
$dbname = 'airdrop_db'; // Nama database

// Membuat koneksi
$conn = mysqli_connect($host, $username, $password, $dbname);

// Cek koneksi
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
