<?php
include '../config/koneksi.php';

$id = $_GET['id'];

// Ambil nama file cover untuk dihapus dari folder images
$data = mysqli_query($koneksi, "SELECT cover FROM novel WHERE NovelID = '$id'");
$row = mysqli_fetch_array($data);
if ($row['cover'] != "") {
    unlink("../images/".$row['cover']);
}

$query = mysqli_query($koneksi, "DELETE FROM novel WHERE NovelID = '$id'");

if($query) {
    header("location:produk.php");
}
?>