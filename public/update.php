<?php
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nama = $_POST['nama_airdrop'];
    $link1 = $_POST['link_1'];
    $link2 = $_POST['link_2'];
    $tipe = $_POST['tipe'];

    $stmt = $conn->prepare("UPDATE airdrop_data SET nama_airdrop=?, link_1=?, link_2=?, tipe=? WHERE id=?");
    $stmt->bind_param("ssssi", $nama, $link1, $link2, $tipe, $id);

    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        echo "Gagal update data.";
    }

    $stmt->close();
    $conn->close();
}
?>
