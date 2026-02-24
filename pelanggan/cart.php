<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'pelanggan') {
    header("Location: ../auth/login.php");
    exit;
}
// LOGIC TAMBAH VIA AJAX (Tanpa Refresh)
if (isset($_GET['act']) && $_GET['act'] == 'add_ajax') {
    $id = $_GET['id'];
    
    if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }
    
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += 1;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    
    // Hitung total item sekarang
    $total_item = array_sum($_SESSION['cart']);
    
    // Kirim jawaban balik ke Dashboard
    echo json_encode([
        'status' => 'success',
        'total_item' => $total_item
    ]);
    exit; // Berhenti di sini, jangan tampilkan HTML cart
}

// --- LOGIC KERANJANG (BACKEND) ---

// 1. Inisialisasi Keranjang jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// 2. Tambah Barang ke Keranjang
if (isset($_GET['act']) && $_GET['act'] == 'add' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += 1; // Kalau sudah ada, tambah jumlahnya
    } else {
        $_SESSION['cart'][$id] = 1; // Kalau belum ada, set jadi 1
    }
    header("Location: cart.php"); // Refresh biar URL bersih
    exit;
}

// 3. Kurangi Barang
if (isset($_GET['act']) && $_GET['act'] == 'min' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] -= 1;
        if ($_SESSION['cart'][$id] <= 0) {
            unset($_SESSION['cart'][$id]); // Hapus jika 0
        }
    }
    header("Location: cart.php");
    exit;
}

// 4. Hapus Barang
if (isset($_GET['act']) && $_GET['act'] == 'del' && isset($_GET['id'])) {
    $id = $_GET['id'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Gaya CSS yang senada dengan Dashboard */
        :root { --primary: #2d3436; --accent: #6c5ce7; }
        body { font-family: 'Poppins', sans-serif; background: #f9f9f9; color: var(--primary); padding: 40px; }
        
        .container { max-width: 900px; margin: auto; background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        h1 { margin-bottom: 30px; font-weight: 700; }
        
        /* Table Styling */
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { text-align: left; padding: 15px; border-bottom: 2px solid #eee; color: #b2bec3; font-size: 14px; text-transform: uppercase; }
        td { padding: 20px 15px; border-bottom: 1px solid #eee; vertical-align: middle; }
        
        .item-info { display: flex; align-items: center; gap: 15px; }
        .item-info img { width: 60px; height: 80px; object-fit: cover; border-radius: 5px; }
        
        .btn-qty { 
            background: #eee; color: #333; text-decoration: none; width: 30px; height: 30px; 
            display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold;
        }
        .btn-delete { color: #ff7675; text-decoration: none; font-size: 14px; font-weight: 600; }
        
        .total-section { text-align: right; margin-top: 30px; }
        .total-price { font-size: 2rem; font-weight: 700; color: var(--accent); display: block; margin: 10px 0 20px; }
        
        .btn-checkout { 
            padding: 15px 40px; background: var(--primary); color: white; text-decoration: none; 
            border-radius: 50px; font-weight: 600; transition: 0.3s; 
        }
        .btn-checkout:hover { background: var(--accent); box-shadow: 0 10px 20px rgba(108, 92, 231, 0.3); }
        .btn-back { color: #b2bec3; text-decoration: none; margin-right: 20px; }
    </style>
</head>
<body>

<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h1>Keranjang Anda</h1>
        <a href="dashboard.php" class="btn-back">← Kembali Belanja</a>
    </div>

    <?php if (empty($_SESSION['cart'])): ?>
        <div style="text-align: center; padding: 50px;">
            <h3 style="color: #b2bec3;">Keranjang masih kosong nih...</h3>
            <p>Yuk cari novel favoritmu dulu!</p>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th width="50%">Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $grand_total = 0;
                // Loop semua barang di session cart
                foreach ($_SESSION['cart'] as $novel_id => $jumlah): 
                    // Ambil detail buku dari database berdasarkan ID
                    $query = mysqli_query($koneksi, "SELECT * FROM novel WHERE NovelID = '$novel_id'");
                    $buku = mysqli_fetch_assoc($query);
                    $subtotal = $buku['Harga'] * $jumlah;
                    $grand_total += $subtotal;
                ?>
                <tr>
                    <td>
                        <div class="item-info">
                            <img src="../images/<?= $buku['cover']; ?>">
                            <div>
                                <b><?= $buku['JudulNovel']; ?></b><br>
                                <span style="font-size:12px; color:#aaa;">Stok: <?= $buku['Stok']; ?></span>
                            </div>
                        </div>
                    </td>
                    <td>Rp <?= number_format($buku['Harga']); ?></td>
                    <td>
                        <a href="cart.php?act=min&id=<?= $novel_id; ?>" class="btn-qty">-</a>
                        <span style="margin: 0 10px;"><?= $jumlah; ?></span>
                        <a href="cart.php?act=add&id=<?= $novel_id; ?>" class="btn-qty">+</a>
                    </td>
                    <td style="font-weight:bold;">Rp <?= number_format($subtotal); ?></td>
                    <td><a href="cart.php?act=del&id=<?= $novel_id; ?>" class="btn-delete">Hapus</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total-section">
            <span>Total Pembayaran</span>
            <span class="total-price">Rp <?= number_format($grand_total); ?></span>
            <a href="checkout.php" class="btn-checkout">Lanjut ke Pembayaran</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>