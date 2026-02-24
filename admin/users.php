<?php 
session_start();
if (!isset($_SESSION['login'])) { header("Location: ../auth/login.php"); exit; }
include '../config/koneksi.php';
$current_page = basename($_SERVER['PHP_SELF']);

// Proses Tambah User Internal
if(isset($_POST['tambah_user'])) {
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    $insert = mysqli_query($koneksi, "INSERT INTO login (Nama, Username, Password, Role) VALUES ('$nama', '$username', '$password', '$role')");
    if($insert) echo "<script>alert('User berhasil ditambahkan!'); window.location='users.php';</script>";
}

// Proses Hapus
if(isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM login WHERE UserID = '$id'");
    header("location:users.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola User | Novel Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/style.css">
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
                <h2>Kelola User Internal</h2>
                <p style="color: #888; margin-bottom: 20px;">Tambah atau hapus akun Admin dan Petugas.</p>

                <form action="" method="POST" style="background: #f9f9f9; padding: 20px; border-radius: 15px; margin-bottom: 30px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 10px;">
                        <input type="text" name="nama" placeholder="Nama Lengkap" required style="padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                        <input type="text" name="username" placeholder="Username" required style="padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                        <input type="password" name="password" placeholder="Password" required style="padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                        <select name="role" required style="padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                            <option value="admin">Admin</option>
                            <option value="petugas">Petugas</option>
                        </select>
                    </div>
                    <button type="submit" name="tambah_user" class="btn-save" style="margin-top: 15px; width: 100%;">Tambah Akses User</button>
                </form>

                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="text-align: left; background: #f4f4f7;">
                            <th style="padding: 15px;">Nama</th>
                            <th style="padding: 15px;">Username</th>
                            <th style="padding: 15px;">Role</th>
                            <th style="padding: 15px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($koneksi, "SELECT * FROM login WHERE Role != 'pelanggan'");
                        while($u = mysqli_fetch_array($query)) {
                        ?>
                        <tr>
                            <td style="padding: 15px; border-bottom: 1px solid #eee;"><?= $u['Nama']; ?></td>
                            <td style="padding: 15px; border-bottom: 1px solid #eee;"><?= $u['Username']; ?></td>
                            <td style="padding: 15px; border-bottom: 1px solid #eee;">
                                <span style="background: <?= $u['Role'] == 'admin' ? '#fee2e2' : '#e0e7ff'; ?>; padding: 4px 10px; border-radius: 5px; font-size: 12px;">
                                    <?= strtoupper($u['Role']); ?>
                                </span>
                            </td>
                            <td style="padding: 15px; border-bottom: 1px solid #eee;">
                                <a href="users.php?hapus=<?= $u['UserID']; ?>" style="color:#e74c3c; font-weight:bold; text-decoration:none;" onclick="return confirm('Hapus user ini?')">HAPUS</a>
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