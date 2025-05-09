<?php
include('../includes/db.php');
$id = $_POST['id'];
$sql = "UPDATE airdrop_data SET is_dropped = 0 WHERE id = $id";
$conn->query($sql);
header("Location: index.php");
