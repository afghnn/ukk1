<?php 
include '../config/koneksi.php'; 
$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM novel WHERE NovelID = '$id'");
$data = mysqli_fetch_array($query);

if(isset($_POST['update'])) {
    $judul     = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $penulis   = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $harga     = $_POST['harga'];
    $stok      = $_POST['stok'];
    $foto_nama = $_FILES['cover']['name'];
    $foto_tmp  = $_FILES['cover']['tmp_name'];

    if($foto_nama != "") {
        $nama_file_baru = rand(100,999)."-".$foto_nama;
        move_uploaded_file($foto_tmp, "../images/".$nama_file_baru);
        // Update termasuk Deskripsi dan Cover
        $update = mysqli_query($koneksi, "UPDATE novel SET JudulNovel='$judul', Penulis='$penulis', Deskripsi='$deskripsi', Harga='$harga', Stok='$stok', cover='$nama_file_baru' WHERE NovelID='$id'");
    } else {
        // Update termasuk Deskripsi tanpa ganti Cover
        $update = mysqli_query($koneksi, "UPDATE novel SET JudulNovel='$judul', Penulis='$penulis', Deskripsi='$deskripsi', Harga='$harga', Stok='$stok' WHERE NovelID='$id'");
    }

    if($update) {
        echo "<script>alert('Data diperbarui!'); window.location='produk.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Novel | Admin Panel</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --accent: #6366f1; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #444; }
        .form-group label i { margin-right: 5px; color: var(--accent); }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 10px; font-family: inherit; }
        .form-group textarea { resize: vertical; min-height: 120px; }
        .btn-save { background: var(--accent); color: #fff; padding: 12px 30px; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; transition: 0.3s; }
        .btn-save:hover { opacity: 0.9; transform: translateY(-2px); }
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
            </ul>
        </nav>

        <main class="main-content">
            <div class="activity-card">
                <h2 style="margin-bottom: 10px;"><i class="fa-solid fa-pen-to-square"></i> Edit Novel</h2>
                <p style="color: #888; margin-bottom: 30px;">Perbarui deskripsi dan informasi stok novel.</p>

                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label><i class="fa-solid fa-heading"></i> Judul Novel</label>
                        <input type="text" name="judul" value="<?= $data['JudulNovel']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fa-solid fa-user-pen"></i> Penulis</label>
                        <input type="text" name="penulis" value="<?= $data['Penulis']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fa-solid fa-align-left"></i> Edit Sinopsis</label>
                        <textarea name="deskripsi" required><?= $data['Deskripsi']; ?></textarea>
                    </div>

                    <div style="display: flex; gap: 20px;">
                        <div class="form-group" style="flex: 1;">
                            <label><i class="fa-solid fa-money-bill-wave"></i> Harga (Rp)</label>
                            <input type="number" name="harga" value="<?= $data['Harga']; ?>" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label><i class="fa-solid fa-cubes"></i> Stok</label>
                            <input type="number" name="stok" value="<?= $data['Stok']; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fa-solid fa-image"></i> Cover (Biarkan kosong jika tidak diganti)</label>
                        <input type="file" name="cover">
                        <div style="margin-top: 10px; display: flex; align-items: center; gap: 10px;">
                            <img src="../images/<?= $data['cover']; ?>" width="60" style="border-radius: 5px; border: 1px solid #eee;">
                            <span style="font-size: 12px; color: #888;">File saat ini: <?= $data['cover']; ?></span>
                        </div>
                    </div>

                    <div style="margin-top: 30px;">
                        <button type="submit" name="update" class="btn-save">Simpan Perubahan</button>
                        <a href="produk.php" style="margin-left: 15px; color: #888; text-decoration: none;">Batal</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>