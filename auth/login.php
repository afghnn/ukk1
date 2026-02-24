<?php
session_start();
include '../config/koneksi.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $query = mysqli_query($koneksi, "SELECT * FROM login WHERE Username = '$username' AND Password = '$password'");
    $data  = mysqli_fetch_assoc($query);

    if ($data) {
        $_SESSION['login']   = true;
        $_SESSION['user_id'] = $data['UserID'];
        $_SESSION['nama']    = $data['Nama'];
        $_SESSION['role']    = $data['Role'];

        if ($data['Role'] == 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../pelanggan/dashboard.php");
        }
        exit;
    } else {
        echo "<script>alert('Username atau Password Salah!'); window.location='login.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Novel Shop</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: #ffffff; padding: 40px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); width: 100%; max-width: 400px; text-align: center; }
        .login-card h2 { color: #333; margin-bottom: 10px; font-size: 28px; }
        .login-card p { color: #777; margin-bottom: 30px; font-size: 14px; }
        .input-group { margin-bottom: 20px; text-align: left; }
        .input-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; font-size: 14px; }
        .input-group input { width: 100%; padding: 12px 15px; border: 2px solid #eee; border-radius: 10px; outline: none; transition: 0.3s; font-size: 16px; }
        .input-group input:focus { border-color: #667eea; }
        .btn-login { width: 100%; padding: 14px; background: #667eea; border: none; border-radius: 10px; color: white; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        .btn-login:hover { background: #5a67d8; transform: translateY(-2px); }
        .footer-text { margin-top: 25px; font-size: 14px; color: #888; }
        .footer-text a { color: #667eea; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Selamat Datang</h2>
        <p>Silakan masuk ke akun Novel Shop Anda</p>
        <form method="POST">
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required autofocus>
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="login" class="btn-login">Masuk Sekarang</button>
        </form>
        <div class="footer-text">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
        </div>
    </div>
</body>
</html>