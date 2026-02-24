<?php
include 'novel_shop/config/koneksi.php';

$id = $_GET['id'];

// Ambil nama file gambar dulu supaya bisa dihapus dari folder
$data = mysqli_query($koneksi, "SELECT cover FROM novel WHERE NovelID = '$id'");
$row = mysqli_fetch_array($data);
unlink("../images/".$row['cover']); // Menghapus file di folder images

// Hapus data di database
$query = mysqli_query($koneksi, "DELETE FROM novel WHERE NovelID = '$id'");

if($query) {
    header("location:produk.php");
} else {
    echo "Gagal menghapus data";
}
?>