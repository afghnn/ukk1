<?php
include '../config/koneksi.php';
$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT penjualan.*, pelanggan.NamaPelanggan 
                                FROM penjualan 
                                JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                                WHERE PenjualanID = '$id'");
$p = mysqli_fetch_array($data);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran #<?= $id ?></title>
    <style>
        body { font-family: monospace; width: 300px; padding: 10px; }
        .center { text-align: center; }
        hr { border-top: 1px dashed #000; }
    </style>
</head>
<body onload="window.print()">
    <div class="center">
        <h2>NOVEL SHOP</h2>
        <p>Jl. Cerita Indah No. 123</p>
    </div>
    <hr>
    <p>ID: #<?= $p['PenjualanID'] ?> / <?= $p['TanggalPenjualan'] ?></p>
    <p>Cust: <?= $p['NamaPelanggan'] ?></p>
    <hr>
    <table>
        <?php
        $items = mysqli_query($koneksi, "SELECT detail_penjualan.*, novel.JudulNovel FROM detail_penjualan JOIN novel ON detail_penjualan.NovelID = novel.NovelID WHERE PenjualanID = '$id'");
        while($i = mysqli_fetch_array($items)):
        ?>
        <tr>
            <td width="150"><?= $i['JudulNovel'] ?> x<?= $i['Jumlah'] ?></td>
            <td>Rp <?= number_format($i['Subtotal']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <hr>
    <p><strong>TOTAL: Rp <?= number_format($p['TotalHarga']) ?></strong></p>
    <div class="center">
        <p>--- TERIMA KASIH ---</p>
    </div>
</body>
</html>