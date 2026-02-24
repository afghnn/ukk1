<?php 
include '../config/koneksi.php'; 

// Query Data Real
$query_novel = mysqli_query($koneksi, "SELECT * FROM novel");
$total_produk = ($query_novel) ? mysqli_num_rows($query_novel) : 0;

$query_stok = mysqli_query($koneksi, "SELECT SUM(stok) as total FROM novel");
$data_stok = mysqli_fetch_assoc($query_stok);
$total_stok = $data_stok['total'] ?? 0;

$query_pendapatan = mysqli_query($koneksi, "SELECT SUM(total_harga) as total FROM penjualan");
$data_pendapatan = ($query_pendapatan) ? mysqli_fetch_assoc($query_pendapatan) : null;
$total_pendapatan = $data_pendapatan['total'] ?? 0;

// Logika Menu Aktif
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Das thhboard</title>
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

        <main class="main-content">
            <header class="content-header">
                <h2>Dashboard Overview</h2>
                <p>Selamat datang Admin!</p>
            </header>

            <section class="stats-grid">
                <div class="card card-blue">
                    <h3>Total Produk</h3>
                    <div class="card-val"><?php echo $total_produk; ?> <span>Judul</span></div>
                </div>
                <div class="card card-cyan">
                    <h3>Total Stok</h3>
                    <div class="card-val"><?php echo number_format($total_stok, 0, ',', '.'); ?> <span>Buku</span></div>
                </div>
                <div class="card card-green">
                    <h3>Total Pendapatan</h3>
                    <div class="card-val">Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></div>
                </div>
            </section>

            <section class="activity-card">
                <h3>Ringkasan Aktivitas</h3>
                <p>Data terbaru dari toko anda akan muncul di sini.</p>
            </section>
        </main>
    </div>
</body>
</html>