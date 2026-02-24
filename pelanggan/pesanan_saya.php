<?php
session_start();
include '../config/koneksi.php';


$id_user = $_SESSION['user_id'];

// --- HITUNG KERANJANG ---
$total_item = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $jumlah) {
        $total_item += $jumlah;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Saya | Novel Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --accent: #6c5ce7; --bg: #f8f9fa; --primary: #2d3436; }
        body { font-family: 'Poppins', sans-serif; background: var(--bg); margin: 0; }
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 15px 5%; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 100; }
        .logo { font-size: 22px; font-weight: 700; color: var(--primary); text-decoration: none; }
        .nav-menu { display: flex; gap: 25px; }
        .nav-menu a { font-size: 13px; font-weight: 600; color: #636e72; text-decoration: none; text-transform: uppercase; }
        .nav-menu a.active { color: var(--accent); }
        .nav-icons { display: flex; gap: 20px; align-items: center; }
        .cart-badge { background: var(--accent); color: white; font-size: 10px; padding: 2px 6px; border-radius: 50%; position: absolute; top: -10px; right: -10px; }
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .order-card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .table-modern { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table-modern th { text-align: left; padding: 12px; border-bottom: 2px solid #eee; color: #888; font-size: 12px; }
        .table-modern td { padding: 15px 12px; border-bottom: 1px solid #f1f1f1; font-size: 14px; vertical-align: top; }
        .item-detail { font-size: 12px; color: #666; background: #f9f9f9; padding: 4px 8px; border-radius: 4px; display: block; margin-bottom: 3px; }
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 10px; font-weight: 600; }
        .badge-pending { background: #fff9db; color: #f59f00; }
        .badge-success { background: #ebfbee; color: #37b24d; }
        .btn-action { padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 11px; font-weight: 600; display: inline-block; }
        .btn-pay { background: var(--accent); color: white; }
        .btn-print { background: #f1f2f6; color: #2f3542; border: 1px solid #dfe4ea; }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="dashboard.php" class="logo">NOVEL<span style="color:var(--accent);">SHOP</span>.</a>
        <div class="nav-menu">
            <a href="dashboard.php">Home</a>
            <a href="katalog.php">Katalog</a>
            <a href="pesanan_saya.php" class="active">Pesanan Saya</a>
        </div>
        <div class="nav-icons">
            <a href="cart.php" style="position: relative; color: inherit; text-decoration: none;">
                <i class="fa-solid fa-cart-shopping"></i> 
                <?php if($total_item > 0): ?>
                    <span class="cart-badge"><?= $total_item ?></span>
                <?php endif; ?>
            </a>
            <a href="../auth/logout.php" style="color:#d63031; font-weight:600; font-size:13px; text-decoration:none;">Logout</a>
        </div>
    </nav>

<div class="container">
    <div class="order-card">
        <h3><i class="fa-solid fa-bag-shopping" style="color: var(--accent);"></i> Riwayat Pesanan</h3>
        <table class="table-modern">
            <thead>
                <tr>
                    <th>ID / Tanggal</th>
                    <th>Daftar Buku</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ambil data penjualan berdasarkan PelangganID
                $q = mysqli_query($koneksi, "SELECT * FROM penjualan WHERE PelangganID = '$id_user' ORDER BY PenjualanID DESC");
                
                if (mysqli_num_rows($q) == 0) {
                    echo "<tr><td colspan='5' align='center'>Belum ada pesanan.</td></tr>";
                }

                while($row = mysqli_fetch_array($q)):
                    $idP = $row['PenjualanID'];
                ?>
                <tr>
                    <td>
                        <strong>#<?= $idP ?></strong><br>
                        <small style="color: #999"><?= date('d/m/Y', strtotime($row['TanggalPenjualan'])) ?></small>
                    </td>
                    <td>
                        <?php
                        // FIX: Menggunakan 'detailpenjualan' (tanpa underscore) sesuai database lo
                        $qd = mysqli_query($koneksi, "SELECT detailpenjualan.*, novel.JudulNovel 
                                                     FROM detailpenjualan 
                                                     JOIN novel ON detailpenjualan.NovelID = novel.NovelID 
                                                     WHERE detailpenjualan.PenjualanID = '$idP'");

                        if ($qd && mysqli_num_rows($qd) > 0) {
                            while($d = mysqli_fetch_array($qd)):
                        ?>
                            <span class="item-detail"><?= $d['JudulNovel'] ?> (x<?= $d['Jumlah'] ?>)</span>
                        <?php 
                            endwhile; 
                        } else {
                            echo "<small style='color:red;'>Detail Error: ".mysqli_error($koneksi)."</small>";
                        }
                        ?>
                    </td>
                    <td><strong>Rp <?= number_format($row['TotalHarga']) ?></strong></td>
                    <td>
                        <span class="badge <?= ($row['Status'] == 'Selesai') ? 'badge-success' : 'badge-pending' ?>">
                            <?= $row['Status'] == 'Pending' ? 'Proses' : $row['Status'] ?>
                        </span>
                    </td>
                    <td>
                        <?php if(empty($row['BuktiBayar'])): ?>
                            <a href="bayar.php?id=<?= $idP ?>" class="btn-action btn-pay">Bayar Sekarang</a>
                        <?php else: ?>
                            <a href="struk.php?id=<?= $idP ?>" target="_blank" class="btn-action btn-print"><i class="fa fa-print"></i> Struk</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>