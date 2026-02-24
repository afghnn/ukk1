<?php
session_start();
include '../config/koneksi.php';

// Kalau keranjang kosong, usir balik ke katalog
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('Keranjang lo kosong, belanja dulu dong!'); window.location='katalog.php';</script>";
    exit;
}

// Ambil ID User dari session
$id_user = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout | Novel Shop</title>
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f9f9f9; padding: 40px; }
        .checkout-container { max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .item-list { border-bottom: 2px solid #eee; margin-bottom: 20px; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; }
        textarea, input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        .btn-konfirmasi { background: #6c5ce7; color: white; border: none; padding: 15px; width: 100%; border-radius: 10px; cursor: pointer; font-weight: 700; font-size: 16px; }
        .total-box { font-size: 20px; font-weight: 700; text-align: right; margin: 20px 0; color: #6c5ce7; }
    </style>
</head>
<body>

<div class="checkout-container">
    <h2><i class="fa-solid fa-cart-check"></i> Konfirmasi Pesanan</h2>
    <hr><br>

    <div class="item-list">
        <?php 
        $total_belanja = 0;
        foreach ($_SESSION['cart'] as $id_novel => $jumlah): 
            $ambil = mysqli_query($koneksi, "SELECT * FROM novel WHERE NovelID = '$id_novel'");
            $pecah = mysqli_fetch_array($ambil);
            $subtotal = $pecah['Harga'] * $jumlah;
            $total_belanja += $subtotal;
        ?>
            <p style="display: flex; justify-content: space-between;">
                <span><?= $pecah['JudulNovel'] ?> (x<?= $jumlah ?>)</span>
                <span>Rp <?= number_format($subtotal) ?></span>
            </p>
        <?php endforeach; ?>
    </div>

    <div class="total-box">Total Bayar: Rp <?= number_format($total_belanja) ?></div>

    <form action="checkout_proses.php" method="POST">
        <input type="hidden" name="total_harga" value="<?= $total_belanja ?>">

        <div class="form-group">
            <label>No. HP Aktif</label>
            <input type="text" name="no_hp" placeholder="Contoh: 08123456789" required>
        </div>

        <div class="form-group">
            <label>Alamat Lengkap Pengiriman</label>
            <textarea name="alamat" rows="4" placeholder="Jl. Nama Jalan, No Rumah, Kec, Kota/Kab" required></textarea>
        </div>

        <button type="submit" name="checkout" class="btn-konfirmasi">BUAT PESANAN SEKARANG</button>
    </form>
</div>

</body>
</html>