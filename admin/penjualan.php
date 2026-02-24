<?php
session_start();
include '../config/koneksi.php';
// Cek jika bukan admin, tendang ke login
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Penjualan | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f7f6; margin: 0; padding: 20px; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #6c5ce7; color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #ddd; font-size: 14px; }
        .img-bukti { width: 80px; cursor: pointer; border-radius: 5px; border: 1px solid #ddd; }
        .btn-approve { background: #2ecc71; color: white; padding: 6px 12px; border-radius: 5px; text-decoration: none; font-size: 12px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; }
        .bg-pending { background: #ffeaa7; color: #d63031; }
        .bg-success { background: #55efc4; color: #00b894; }
    </style>
</head>
<body>
    <nav class="sidebar">
            <div class="sidebar-header">
                <div class="logo-circle"></div>
                <h3>Novel Shop</h3>
            </div>
            <ul class="nav-menu">
                <li>
                    <a href="dashboard.php" class="<?= ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-gauge-high"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="produk.php" class="<?= ($current_page == 'produk.php' || $current_page == 'tambah_produk.php' || $current_page == 'edit_produk.php') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-book"></i> Kelola Produk
                    </a>
                </li>
                <li>
                    <a href="kategori.php" class="<?= ($current_page == 'kategori.php') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-tags"></i> Kategori
                    </a>
                </li>
                <li>
                    <a href="users.php" class="<?= ($current_page == 'users.php') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-users-gear"></i> Kelola User
                    </a>
                </li>
                <li>
                    <a href="penjualan.php" class="<?= ($current_page == 'penjualan.php') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-cart-shopping"></i> Penjualan
                    </a>
                </li>
                <li style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
                    <a href="../auth/logout.php" style="color: #e74c3c;">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>

<div class="card">
    <h2><i class="fa fa-shopping-cart"></i> Daftar Transaksi Masuk</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Bukti</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = mysqli_query($koneksi, "SELECT penjualan.*, pelanggan.NamaPelanggan 
                                            FROM penjualan 
                                            JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                                            ORDER BY PenjualanID DESC");
            while($row = mysqli_fetch_array($query)):
            ?>
            <tr>
                <td>#<?= $row['PenjualanID'] ?></td>
                <td><?= $row['NamaPelanggan'] ?></td>
                <td>Rp <?= number_format($row['TotalHarga']) ?></td>
                <td>
                    <?php if($row['BuktiBayar']): ?>
                        <a href="../images/bukti/<?= $row['BuktiBayar'] ?>" target="_blank">
                            <img src="../images/bukti/<?= $row['BuktiBayar'] ?>" class="img-bukti">
                        </a>
                    <?php else: ?>
                        <i style="color: #ccc;">Belum upload</i>
                    <?php endif; ?>
                </td>
                <td>
                    <span class="badge <?= $row['Status'] == 'Selesai' ? 'bg-success' : 'bg-pending' ?>">
                        <?= $row['Status'] ?>
                    </span>
                </td>
                <td>
                    <?php if($row['Status'] == 'Pending' && !empty($row['BuktiBayar'])): ?>
                        <a href="konfirmasi_proses.php?id=<?= $row['PenjualanID'] ?>" class="btn-approve" onclick="return confirm('Konfirmasi pembayaran ini?')">Terima & Kurangi Stok</a>
                    <?php elseif($row['Status'] == 'Selesai'): ?>
                        <i class="fa fa-check-circle" style="color: #2ecc71;"></i> Selesai
                    <?php else: ?>
                        <small>Menunggu Bukti</small>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>