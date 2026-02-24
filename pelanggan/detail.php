<?php 
session_start();
include '../config/koneksi.php'; 

// Cek ID di URL
if(!isset($_GET['id'])){
    header("Location: katalog.php"); exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Jika perlu user session

// Ambil Detail Novel + Nama Kategorinya
$query = mysqli_query($koneksi, "
    SELECT novel.*, kategori.NamaKategori 
    FROM novel 
    JOIN kategori ON novel.KategoriID = kategori.KategoriID 
    WHERE NovelID = '$id'
");
$d = mysqli_fetch_array($query);

// Jika ID tidak ditemukan/salah
if(!$d){ header("Location: katalog.php"); exit; }

$total_item = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $d['JudulNovel'] ?> | Detail Produk</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* --- STYLE DASAR (SAMA DENGAN DASHBOARD) --- */
        :root { --primary: #2d3436; --accent: #6c5ce7; --bg-light: #f9f9f9; --text-gray: #636e72; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg-light); color: var(--primary); }
        a { text-decoration: none; color: inherit; }

        /* NAVBAR */
        .navbar {
            display: flex; justify-content: space-between; align-items: center;
            padding: 20px 5%; background: white; border-bottom: 1px solid #eee;
            position: sticky; top: 0; z-index: 100;
        }
        .logo { font-size: 24px; font-weight: 700; color: var(--primary); }
        .nav-menu { display: flex; gap: 30px; }
        .nav-menu a { font-size: 14px; font-weight: 500; color: var(--text-gray); transition: 0.3s; }
        .nav-menu a:hover { color: var(--accent); }
        .cart-badge { background: var(--accent); color: white; padding: 2px 8px; border-radius: 50px; font-size: 11px; font-weight: 700; vertical-align: top; }

        /* BREADCRUMB (Navigasi Kecil) */
        .breadcrumb { padding: 20px 5%; font-size: 14px; color: var(--text-gray); }
        .breadcrumb a:hover { color: var(--accent); text-decoration: underline; }

        /* DETAIL CONTAINER */
        .detail-container {
            display: flex; gap: 50px; padding: 20px 5% 60px; max-width: 1200px; margin: auto;
            background: white; border-radius: 15px; margin-bottom: 40px;
        }

        /* FOTO PRODUK (KIRI) */
        .product-image { flex: 1; max-width: 400px; }
        .img-wrapper {
            width: 100%; border-radius: 15px; overflow: hidden; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 1px solid #eee;
        }
        .img-wrapper img { width: 100%; height: auto; display: block; transition: 0.3s; }
        .img-wrapper:hover img { transform: scale(1.03); }

        /* INFO PRODUK (KANAN) */
        .product-info { flex: 1.5; padding-top: 10px; }
        
        .category-tag {
            display: inline-block; background: #f0f3ff; color: var(--accent);
            padding: 5px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; margin-bottom: 15px;
        }
        .product-title { font-size: 28px; font-weight: 700; margin-bottom: 10px; line-height: 1.3; }
        .product-price { font-size: 24px; color: var(--accent); font-weight: 700; margin-bottom: 20px; }
        
        .meta-info { display: flex; gap: 20px; margin-bottom: 25px; font-size: 14px; color: var(--text-gray); }
        .meta-item i { margin-right: 5px; color: var(--accent); }

        .description { line-height: 1.8; color: var(--text-gray); margin-bottom: 30px; font-size: 15px; text-align: justify; }

        /* TOMBOL AKSI */
        .action-area { display: flex; gap: 15px; align-items: center; }
        .btn-main {
            background: var(--primary); color: white; padding: 15px 40px; border-radius: 10px;
            font-weight: 600; border: none; cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 10px;
        }
        .btn-main:hover { background: var(--accent); transform: translateY(-3px); box-shadow: 0 10px 20px rgba(108, 92, 231, 0.2); }
        
        .btn-disabled { background: #ccc; cursor: not-allowed; color: #fff; padding: 15px 40px; border-radius: 10px; border:none; }

        /* FITUR SERVICE (TRUST BADGE) */
        .features-grid {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; 
            padding: 40px 5%; background: white; border-top: 1px solid #eee; margin-top: 20px;
        }
        .feature-item { text-align: center; }
        .feature-item i { font-size: 24px; color: var(--accent); margin-bottom: 10px; }
        .feature-item h4 { font-size: 14px; font-weight: 600; margin-bottom: 2px; }
        .feature-item p { font-size: 12px; color: #888; }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .detail-container { flex-direction: column; }
            .product-image { max-width: 100%; }
            .features-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="dashboard.php" class="logo">NOVEL<span style="color:var(--accent);">SHOP</span>.</a>
        <div class="nav-menu">
            <a href="dashboard.php">Home</a>
            <a href="katalog.php">Katalog</a>
            <a href="cart.php">Keranjang</a>
        </div>
        <div style="display:flex; gap:20px; align-items:center;">
            <a href="cart.php">
                <i class="fa-solid fa-cart-shopping"></i> 
                <span id="cart-count" class="cart-badge"><?= $total_item ?></span>
            </a>
            <a href="../auth/logout.php" style="color:#d63031;"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
    </nav>

    <div class="breadcrumb">
        <a href="dashboard.php">Home</a> &nbsp; / &nbsp; 
        <a href="katalog.php">Katalog</a> &nbsp; / &nbsp; 
        <span>Detail Produk</span>
    </div>

    <div class="detail-container">
        
        <div class="product-image">
            <div class="img-wrapper">
                <img src="../images/<?= $d['cover'] ?>" alt="Cover Novel">
            </div>
        </div>

        <div class="product-info">
            <span class="category-tag"><i class="fa-solid fa-tag"></i> <?= $d['NamaKategori'] ?></span>
            
            <h1 class="product-title"><?= $d['JudulNovel'] ?></h1>
            <div class="product-price">Rp <?= number_format($d['Harga']) ?></div>

            <div class="meta-info">
                <div class="meta-item"><i class="fa-solid fa-box-open"></i> Stok: <strong><?= $d['Stok'] ?></strong></div>
                <div class="meta-item"><i class="fa-solid fa-check-circle"></i> Kondisi: <strong>Baru</strong></div>
            </div>

            <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 20px;">
            
            <h3 style="font-size: 16px; margin-bottom: 10px; font-weight:600;">Sinopsis</h3>
            <p class="description">
                <?= !empty($d['Deskripsi']) ? nl2br($d['Deskripsi']) : 'Belum ada deskripsi untuk novel ini. Namun novel ini sangat direkomendasikan untuk dibaca karena memiliki alur cerita yang menarik dan penuh inspirasi.' ?>
            </p>

            <div class="action-area">
                <?php if($d['Stok'] > 0): ?>
                    <button onclick="addToCart(<?= $d['NovelID'] ?>)" class="btn-main">
                        <i class="fa-solid fa-cart-plus"></i> Masukkan Keranjang
                    </button>
                    <a href="cart.php" style="font-size:14px; color:var(--text-gray); border-bottom:1px solid #ddd;">Lihat Keranjang</a>
                <?php else: ?>
                    <button class="btn-disabled">Stok Habis</button>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <div class="features-grid">
        <div class="feature-item">
            <i class="fa-solid fa-truck-fast"></i>
            <h4>Gratis Ongkir</h4>
            <p>Min. belanja 100rb</p>
        </div>
        <div class="feature-item">
            <i class="fa-solid fa-shield-halved"></i>
            <h4>Pembayaran Aman</h4>
            <p>100% Terlindungi</p>
        </div>
        <div class="feature-item">
            <i class="fa-solid fa-arrow-rotate-left"></i>
            <h4>Garansi Return</h4>
            <p>30 Hari pengembalian</p>
        </div>
        <div class="feature-item">
            <i class="fa-solid fa-headset"></i>
            <h4>24/7 Support</h4>
            <p>Bantuan setiap saat</p>
        </div>
    </div>

    <script>
    function addToCart(id) {
        fetch('cart.php?act=add_ajax&id=' + id)
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                document.getElementById('cart-count').innerText = data.total_item;
                Swal.fire({
                    icon: 'success', title: 'Berhasil!', text: 'Novel berhasil masuk keranjang',
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 1500
                });
            }
        });
    }
    </script>

</body>
</html>