<?php
session_start();
include '../config/koneksi.php';

$id = $_GET['id'];

// 1. Update status penjualan jadi Selesai
$update_status = mysqli_query($koneksi, "UPDATE penjualan SET Status = 'Selesai' WHERE PenjualanID = '$id'");

if ($update_status) {
    // 2. Ambil data barang yang dibeli di transaksi ini
    $ambil_barang = mysqli_query($koneksi, "SELECT * FROM detail_penjualan WHERE PenjualanID = '$id'");
    
    while ($row = mysqli_fetch_array($ambil_barang)) {
        $id_novel = $row['NovelID'];
        $jumlah_beli = $row['Jumlah'];

        // 3. Kurangi stok di tabel novel
        mysqli_query($koneksi, "UPDATE novel SET Stok = Stok - $jumlah_beli WHERE NovelID = '$id_novel'");
    }

    echo "<script>alert('Pembayaran dikonfirmasi & stok telah diperbarui!'); window.location='penjualan.php';</script>";
} else {
    echo "Gagal konfirmasi: " . mysqli_error($koneksi);
}
?>