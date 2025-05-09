<?php
// Include database connection
include('../includes/db.php'); // Menggunakan conn.php

// Pastikan form di-submit dengan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama_airdrop = $_POST['nama_airdrop'];
    $link_1 = $_POST['link_1'];
    $link_2 = isset($_POST['link_2']) ? $_POST['link_2'] : ''; // Mengambil Link 2 jika ada, atau kosongkan
    $tipe = $_POST['tipe'];

    // Validasi: Pastikan Link 1 diisi
    if (empty($link_1)) {
        die("Link 1 harus diisi.");
    }

    // Query untuk memasukkan data ke database
    $sql = "INSERT INTO airdrop_data (nama_airdrop, link_1, link_2, tipe) VALUES (?, ?, ?, ?)";

    // Menyiapkan query
    if ($stmt = $conn->prepare($sql)) {
        // Binding parameter dan eksekusi query
        $stmt->bind_param("ssss", $nama_airdrop, $link_1, $link_2, $tipe);
        
        // Eksekusi query
        if ($stmt->execute()) {
            header("Location: index.php");
        } else {
            echo "Terjadi kesalahan dalam menyimpan data.";
        }
        
        // Menutup statement
        $stmt->close();
    } else {
        echo "Gagal menyiapkan query.";
    }
}
?>
