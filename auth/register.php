<?php
include '../config/koneksi.php';
if (isset($_POST['register'])) {
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']); 
    $role     = 'pelanggan'; 

    $cek = mysqli_query($koneksi, "SELECT * FROM login WHERE Username = '$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah ada!'); window.location='register.php';</script>";
    } else {
        $insert = mysqli_query($koneksi, "INSERT INTO login (Nama, Username, Password, Role) VALUES ('$nama', '$username', '$password', '$role')");
        if ($insert) { echo "<script>alert('Berhasil Daftar!'); window.location='login.php';</script>"; }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar | Novel Shop</title>
    <style>
        /* Pakai style yang sama dengan login di atas agar konsisten */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: linear-gradient(135deg, #764ba2 0%, #667eea 100%); height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { background: #fff; padding: 40px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); width: 100%; max-width: 400px; text-align: center; }
        h2 { color: #333; margin-bottom: 20px; }
        input { width: 100%; padding: 12px; margin-bottom: 15px; border: 2px solid #eee; border-radius: 10px; outline: none; }
        input:focus { border-color: #764ba2; }
        button { width: 100%; padding: 14px; background: #764ba2; border: none; border-radius: 10px; color: white; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Daftar Akun</h2>
        <form method="POST">
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Buat Akun</button>
        </form>
        <p style="margin-top:20px; font-size:14px;">Sudah punya akun? <a href="login.php" style="color:#764ba2; text-decoration:none; font-weight:bold;">Login</a></p>
    </div>
</body>
</html>