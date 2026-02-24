<?php 
include '../config/koneksi.php'; 
$current_page = basename($_SERVER['PHP_SELF']);

if(isset($_POST['tambah'])) {
    $nama = $_POST['nama_kategori'];
    mysqli_query($koneksi, "INSERT INTO kategori (NamaKategori) VALUES ('$nama')");
    header("location:kategori.php");
}

if(isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM kategori WHERE KategoriID = '$id'");
    header("location:kategori.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kategori | Novel Shop</title>
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
                <h2>Kategori Novel</h2>
                <p style="color: #888; margin-bottom: 25px;">Kelola kelompok jenis novel kamu.</p>

                <form action="" method="POST" style="display: flex; gap: 10px; margin-bottom: 30px; align-items: flex-end;">
                    <div style="flex: 1;">
                        <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Nama Kategori Baru</label>
                        <input type="text" name="nama_kategori" required placeholder="Fantasi, Horor, dll" style="width:100%; padding:12px; border-radius:10px; border:1px solid #ddd;">
                    </div>
                    <button type="submit" name="tambah" style="background:#1a1a1a; color:#fff; padding:12px 25px; border:none; border-radius:10px; cursor:pointer; font-weight:600;">TAMBAH</button>
                </form>

                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="text-align:left; padding: 15px; border-bottom: 2px solid #f0f2f5;">ID</th>
                            <th style="text-align:left; padding: 15px; border-bottom: 2px solid #f0f2f5;">Nama Kategori</th>
                            <th style="text-align:left; padding: 15px; border-bottom: 2px solid #f0f2f5;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $q = mysqli_query($koneksi, "SELECT * FROM kategori");
                        while($k = mysqli_fetch_array($q)) {
                        ?>
                        <tr>
                            <td style="padding: 15px; border-bottom: 1px solid #eee;"><?= $k['KategoriID']; ?></td>
                            <td style="padding: 15px; border-bottom: 1px solid #eee;"><strong><?= $k['NamaKategori']; ?></strong></td>
                            <td style="padding: 15px; border-bottom: 1px solid #eee;">
                                <a href="kategori.php?hapus=<?= $k['KategoriID']; ?>" style="color:#e74c3c; font-weight:bold; text-decoration:none;" onclick="return confirm('Hapus?')">HAPUS</a>
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