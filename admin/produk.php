<?php 
include '../config/koneksi.php'; 
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Produk | Novel Shop</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Styling untuk icon di sidebar */
        .nav-menu li a i {
            width: 25px; /* Lebar tetap supaya teks sejajar vertikal */
            margin-right: 10px;
            font-size: 18px;
            text-align: center;
            transition: 0.3s;
        }

        /* Efek saat hover atau active agar icon lebih 'nyala' */
        .nav-menu li a:hover i, 
        .nav-menu li a.active i {
            color: var(--accent); /* Mengikuti warna tema lo */
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <nav class="sidebar">
            <div class="sidebar-header"><div class="logo-circle"></div><h3>Novel Shop</h3></div>
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

        <main class="main-content">
            <div class="activity-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                    <h2>Daftar Novel</h2>
                    <a href="tambah_produk.php" class="btn-save" style="text-decoration:none; background:#1a1a1a; color:#fff; padding:10px 20px; border-radius:10px;">+ Tambah Novel</a>
                </div>

                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="text-align:left; padding: 15px; border-bottom: 2px solid #f0f2f5;">Cover</th>
                            <th style="text-align:left; padding: 15px; border-bottom: 2px solid #f0f2f5;">Judul & Kategori</th>
                            <th style="text-align:left; padding: 15px; border-bottom: 2px solid #f0f2f5;">Harga</th>
                            <th style="text-align:left; padding: 15px; border-bottom: 2px solid #f0f2f5;">Stok</th>
                            <th style="text-align:left; padding: 15px; border-bottom: 2px solid #f0f2f5;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($koneksi, "SELECT novel.*, kategori.NamaKategori 
                                                         FROM novel 
                                                         LEFT JOIN kategori ON novel.KategoriID = kategori.KategoriID 
                                                         ORDER BY NovelID DESC");
                        while($data = mysqli_fetch_array($query)) {
                            // Logika Warna Stok
                            $stok_warna = ($data['Stok'] <= 5) ? 'color: #e74c3c; font-weight: bold;' : 'color: #2ecc71;';
                        ?>
                        <tr>
                            <td style="padding: 15px; border-bottom: 1px solid #eee;">
                                <img src="../images/<?= $data['cover']; ?>" style="width:50px; height:70px; object-fit:cover; border-radius:5px;">
                            </td>
                            <td style="padding: 15px; border-bottom: 1px solid #eee;">
                                <strong><?= $data['JudulNovel']; ?></strong><br>
                                <small style="color: #6366f1; font-weight: 600;"><?= $data['NamaKategori'] ?? 'Tanpa Kategori'; ?></small>
                            </td>
                            <td style="padding: 15px; border-bottom: 1px solid #eee;">Rp <?= number_format($data['Harga'], 0, ',', '.'); ?></td>
                            
                            <td style="padding: 15px; border-bottom: 1px solid #eee; <?= $stok_warna ?>">
                                <?= $data['Stok']; ?> pcs
                                <?php if($data['Stok'] <= 5): ?>
                                    <br><small style="font-size: 10px;">(Hampir Habis!)</small>
                                <?php endif; ?>
                            </td>

                            <td style="padding: 15px; border-bottom: 1px solid #eee;">
                                <a href="edit_produk.php?id=<?= $data['NovelID']; ?>" style="color:#6366f1; text-decoration:none; font-weight:bold;">EDIT</a> | 
                                <a href="proses_hapus.php?id=<?= $data['NovelID']; ?>" style="color:#e74c3c; text-decoration:none; font-weight:bold;" onclick="return confirm('Hapus?')">HAPUS</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>