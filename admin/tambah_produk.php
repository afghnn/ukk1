<?php 
include '../config/koneksi.php'; 
$current_page = basename($_SERVER['PHP_SELF']);

if(isset($_POST['simpan'])) {
    $judul       = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $penulis     = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $deskripsi   = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $kategori_id = $_POST['kategori_id'];
    $harga       = $_POST['harga'];
    $stok        = $_POST['stok'];
    $foto_nama   = $_FILES['cover']['name'];
    
    $nama_file_baru = rand(100,999)."-".$foto_nama;
    move_uploaded_file($_FILES['cover']['tmp_name'], "../images/".$nama_file_baru);

    // Tambahkan kolom Deskripsi ke dalam Query
    $insert = mysqli_query($koneksi, "INSERT INTO novel (JudulNovel, Penulis, KategoriID, Harga, Deskripsi, Stok, cover) 
                                      VALUES ('$judul', '$penulis', '$kategori_id', '$harga', '$deskripsi', '$stok', '$nama_file_baru')");
    
    if($insert) echo "<script>alert('Berhasil!'); window.location='produk.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Novel | Admin Panel</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --accent: #6c5ce7; --primary: #1a1a1a; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #444; }
        .form-group label i { margin-right: 5px; color: var(--accent); }
        .form-group input, .form-group select, .form-group textarea { 
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 10px; font-family: inherit;
        }
        .form-group textarea { resize: vertical; min-height: 120px; }
        .btn-save { background: var(--primary); color: #fff; padding: 12px 30px; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; transition: 0.3s; }
        .btn-save:hover { background: var(--accent); }
    </style>
</head>
<body>
    <div class="wrapper">
        <nav class="sidebar">
            <div class="sidebar-header"><div class="logo-circle"></div><h3>Novel Shop</h3></div>
            <ul class="nav-menu">
                <li><a href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
                <li><a href="produk.php" class="active"><i class="fa-solid fa-book"></i> Kelola Produk</a></li>
                <li><a href="kategori.php"><i class="fa-solid fa-tags"></i> Kategori</a></li>
                <li><a href="users.php"><i class="fa-solid fa-users"></i> Kelola User</a></li>
                <li><a href="penjualan.php"><i class="fa-solid fa-cart-shopping"></i> Penjualan</a></li>
            </ul>
        </nav>

        <main class="main-content">
            <div class="activity-card">
                <h2><i class="fa-solid fa-plus-circle"></i> Tambah Novel Baru</h2>
                <form action="" method="POST" enctype="multipart/form-data" style="margin-top:25px;">
                    <div class="form-group">
                        <label><i class="fa-solid fa-heading"></i> Judul Novel</label>
                        <input type="text" name="judul" placeholder="Contoh: Laskar Pelangi" required>
                    </div>

                    <div style="display: flex; gap: 20px;">
                        <div class="form-group" style="flex:1;">
                            <label><i class="fa-solid fa-user-pen"></i> Penulis</label>
                            <input type="text" name="penulis" required>
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label><i class="fa-solid fa-list"></i> Kategori</label>
                            <select name="kategori_id" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php
                                $kat = mysqli_query($koneksi, "SELECT * FROM kategori");
                                while($k = mysqli_fetch_array($kat)) echo "<option value='".$k['KategoriID']."'>".$k['NamaKategori']."</option>";
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fa-solid fa-align-left"></i> Sinopsis Novel</label>
                        <textarea name="deskripsi" placeholder="Tuliskan ringkasan cerita novel di sini..." required></textarea>
                    </div>

                    <div style="display: flex; gap: 20px;">
                        <div class="form-group" style="flex:1;"><label><i class="fa-solid fa-money-bill-wave"></i> Harga</label><input type="number" name="harga" required></div>
                        <div class="form-group" style="flex:1;"><label><i class="fa-solid fa-cubes"></i> Stok</label><input type="number" name="stok" required></div>
                    </div>

                    <div class="form-group">
                        <label><i class="fa-solid fa-image"></i> Cover Novel</label>
                        <input type="file" name="cover" required>
                    </div>

                    <div style="margin-top: 10px;">
                        <button type="submit" name="simpan" class="btn-save">Simpan Novel</button>
                        <a href="produk.php" style="margin-left: 15px; color: #888; text-decoration: none;">Batal</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>