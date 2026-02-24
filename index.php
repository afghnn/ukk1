<?php

session_start();

include '../config/koneksi.php';



if (!isset($_SESSION['login'])) { header("Location: ../auth/login.php"); exit; }



$id_user = $_SESSION['user_id'];

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Pesanan Saya | Novel Shop</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>

        :root { --accent: #6c5ce7; --bg: #f8f9fa; }

        body { font-family: 'Poppins', sans-serif; background: var(--bg); margin: 0; padding: 20px; }

        .container { max-width: 900px; margin: auto; }

        .order-card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }

        .table-modern { width: 100%; border-collapse: collapse; margin-top: 20px; }

        .table-modern th { text-align: left; padding: 12px; border-bottom: 2px solid #eee; color: #888; font-size: 13px; }

        .table-modern td { padding: 15px 12px; border-bottom: 1px solid #f1f1f1; font-size: 14px; }

        /* navbar */

        .navbar {

            display: flex; justify-content: space-between; align-items: center;

            padding: 20px 5%; border-bottom: 1px solid #eee; background: white;

            position: sticky; top: 0; z-index: 100;

        }

        .logo { font-size: 24px; font-weight: 700; letter-spacing: 1px; color: var(--primary); }

        .nav-menu { display: flex; gap: 30px; }

        .nav-menu a { font-size: 14px; font-weight: 500; color: var(--text-gray); transition: 0.3s; text-transform: uppercase; }

        .nav-menu a:hover, .nav-menu a.active { color: var(--accent); font-weight: 700; }

        .nav-icons { display: flex; gap: 20px; align-items: center; }

        

        /* Badges */

        .badge { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }

        .badge-pending { background: #fff9db; color: #f59f00; }

        .badge-process { background: #e7f5ff; color: #1c7ed6; }

        .badge-success { background: #ebfbee; color: #37b24d; }



        .btn-pay { background: var(--accent); color: white; padding: 8px 15px; border-radius: 8px; text-decoration: none; font-size: 12px; }

        .btn-pay:hover { opacity: 0.8; }

    </style>

</head>

<body>



    <nav class="navbar">

        <a href="dashboard.php" class="logo">NOVEL<span style="color:var(--accent);">SHOP</span>.</a>

        <div class="nav-menu">

            <a href="dashboard.php" class="active">Home</a>

            <a href="katalog.php">Katalog Buku</a>

            <a href="pesanan_saya.php">Pesanan Saya</a>

        </div>

        <div class="nav-icons">

            <a href="cart.php" style="position: relative;">

                <i class="fa-solid fa-cart-shopping"></i> 

                <span id="cart-count" class="cart-badge"><?= $total_item ?></span>

            </a>

            <a href="../auth/logout.php" style="color:#d63031; font-weight:600; font-size:14px;">Logout</a>

        </div>

    </nav>



<div class="container">

    <div class="order-card">

        <h3><i class="fa-solid fa-bag-shopping" style="color: var(--accent);"></i> Riwayat Pesanan</h3>

        <table class="table-modern">

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Tanggal</th>

                    <th>Total</th>

                    <th>Status</th>

                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

                <?php

                $q = mysqli_query($koneksi, "SELECT * FROM penjualan WHERE PelangganID = '$id_user' ORDER BY PenjualanID DESC");

                while($row = mysqli_fetch_array($q)):

                ?>

                <tr>

                    <td>#<?= $row['PenjualanID'] ?></td>

                    <td><?= date('d/m/Y', strtotime($row['TanggalPenjualan'])) ?></td>

                    <td><strong>Rp <?= number_format($row['TotalHarga']) ?></strong></td>

                    <td>

                        <?php if($row['Status'] == 'Pending' && empty($row['BuktiBayar'])): ?>

                            <span class="badge badge-pending">Belum Bayar</span>

                        <?php elseif($row['Status'] == 'Pending' && !empty($row['BuktiBayar'])): ?>

                            <span class="badge badge-process">Verifikasi Admin</span>

                        <?php else: ?>

                            <span class="badge badge-success">Selesai</span>

                        <?php endif; ?>

                    </td>

                    <td>

                        <?php if(empty($row['BuktiBayar'])): ?>

                            <a href="bayar.php?id=<?= $row['PenjualanID'] ?>" class="btn-pay">Upload Bukti</a>

                        <?php else: ?>

                            <span style="color: #aaa; font-size: 12px;">Sudah Upload</span>

                        <?php endif; ?>

                    </td>

                </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

        <br>

        <a href="dashboard.php" style="color: var(--accent); text-decoration: none; font-size: 13px;"><i class="fa-solid fa-arrow-left"></i> Kembali Belanja</a>

    </div>

</div>



</body>

</html>

ini file pesanan_saya.php yang sekarang dan lo perbarui code nya menjadi siap pakai 



<nav class="navbar">

        <a href="dashboard.php" class="logo">NOVEL<span style="color:var(--accent);">SHOP</span>.</a>

        <div class="nav-menu">

            <a href="dashboard.php" class="active">Home</a>

            <a href="katalog.php">Katalog Buku</a>

            <a href="pesanan_saya.php">Pesanan Saya</a>

        </div>

        <div class="nav-icons">

            <a href="cart.php" style="position: relative;">

                <i class="fa-solid fa-cart-shopping"></i> 

                <span id="cart-count" class="cart-badge"><?= $total_item ?></span>

            </a>

            <a href="../auth/logout.php" style="color:#d63031; font-weight:600; font-size:14px;">Logout</a>

        </div>

    </nav>

ini navbar dari dahboard.php pelanggan



.navbar {

            display: flex; justify-content: space-between; align-items: center;

            padding: 20px 5%; border-bottom: 1px solid #eee; background: white;

            position: sticky; top: 0; z-index: 100;

        }

        .logo { font-size: 24px; font-weight: 700; letter-spacing: 1px; color: var(--primary); }

        .nav-menu { display: flex; gap: 30px; }

        .nav-menu a { font-size: 14px; font-weight: 500; color: var(--text-gray); transition: 0.3s; text-transform: uppercase; }

        .nav-menu a:hover, .nav-menu a.active { color: var(--accent); font-weight: 700; }

        .nav-icons { display: flex; gap: 20px; align-items: center; }

        

dan ini css navbar nya



<?php

session_start();

include '../config/koneksi.php';



$id_penjualan = $_GET['id'];



if (isset($_POST['upload'])) {

    $nama_file = $_FILES['bukti']['name'];

    $tmp_file = $_FILES['bukti']['tmp_name'];

    

    // Pastikan folder ini ada: images/bukti/

    $ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);

    $nama_baru = "B_". $id_penjualan . "_" . time() . "." . $ekstensi;

    

    if (move_uploaded_file($tmp_file, "../images/bukti/" . $nama_baru)) {

        mysqli_query($koneksi, "UPDATE penjualan SET BuktiBayar = '$nama_baru' WHERE PenjualanID = '$id_penjualan'");

        echo "<script>alert('Bukti berhasil dikirim!'); window.location='pesanan_saya.php';</script>";

    }

}

?>



<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Bayar Pesanan</title>

    <style>

        body { font-family: 'Poppins', sans-serif; background: #f8f9fa; display: flex; justify-content: center; padding-top: 100px; }

        .box { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 350px; text-align: center; }

        input[type="file"] { margin: 20px 0; border: 1px dashed #ccc; padding: 20px; width: 100%; box-sizing: border-box; }

        button { background: #6c5ce7; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; width: 100%; font-weight: 600; }

    </style>

</head>

<body>

    <div class="box">

        <h3>Upload Bukti Transfer</h3>

        <p style="font-size: 13px; color: #666;">Transfer ke: <br> <strong>BCA 123456789 (a.n Novel Shop)</strong></p>

        <form action="" method="POST" enctype="multipart/form-data">

            <input type="file" name="bukti" required accept="image/*">

            <button type="submit" name="upload">Kirim Konfirmasi</button>

        </form>

    </div>

    

</body>

</html>

ini file bayar lu update aja code nya yang sudah jadi

oiya gw mau desain nya yang bagus tapi tetap masuk dengan desain tampilan di dashboard dan yang lain lain nya