<?php 
session_start();
include '../config/koneksi.php'; 

// --- LOGIKA PHP TETAP SAMA (DARI KODE ASLI) ---


// Hitung total item di keranjang untuk angka di navbar
$total_item = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;

// Ambil 5 novel acak untuk slideshow (Fungsi Asli)
$querySlides = mysqli_query($koneksi, "SELECT * FROM novel ORDER BY RAND() LIMIT 5");
$slides = [];
while($row = mysqli_fetch_assoc($querySlides)) {
    $slides[] = $row;
}

// Ambil 8 novel terbaru (Fungsi Asli)
$queryTerbaru = mysqli_query($koneksi, "SELECT * FROM novel ORDER BY NovelID DESC LIMIT 8");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Novel Shop</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* --- GAYA TAMPILAN BARU (DARI KODE TARGET) --- */
        :root {
            --primary: #2d3436;
            --accent: #6c5ce7; /* Saya sesuaikan dikit biar ungu kayak request awal */
            --bg-light: #f9f9f9;
            --text-gray: #636e72;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #fff; color: var(--primary); }
        a { text-decoration: none; color: inherit; }

        /* NAVBAR KEREN */
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
        
        /* Badge Keranjang */
        .cart-badge {
            background: var(--accent); color: white; padding: 2px 8px; border-radius: 50px;
            font-size: 11px; margin-left: 2px; font-weight: 700; vertical-align: top;
        }

        /* HERO SECTION (BANNER) */
        .hero {
            display: flex; align-items: center; justify-content: space-between;
            padding: 60px 10%; background: var(--bg-light); min-height: 500px;
            overflow: hidden; /* Supaya animasi slide rapi */
        }
        .hero-text { max-width: 50%; transition: opacity 0.5s ease; }
        .hero-tag { font-size: 12px; letter-spacing: 2px; color: var(--accent); text-transform: uppercase; margin-bottom: 15px; display: block; font-weight: 700; }
        .hero h1 { font-size: 48px; line-height: 1.2; margin-bottom: 20px; font-weight: 300; }
        .hero h1 strong { font-weight: 700; display: block; color: var(--primary); }
        .hero-price { font-size: 24px; color: var(--accent); font-weight: 600; margin-bottom: 30px; }
        
        .btn-hero {
            padding: 15px 40px; background: var(--primary); color: white;
            font-weight: 600; border-radius: 50px; transition: 0.3s;
            border: 2px solid var(--primary); cursor: pointer; display: inline-block;
        }
        .btn-hero:hover { background: transparent; color: var(--primary); }
        
        /* Efek Buku 3D Sederhana */
        .hero-img img {
            max-width: 300px; border-radius: 5px;
            box-shadow: 20px 20px 0px rgba(0,0,0,0.1);
            transform: rotate(-3deg); transition: 0.5s;
            display: block;
        }
        .hero-img img:hover { transform: rotate(0deg) scale(1.05); }

        /* SERVICE BAR (Tambahan Keren) */
        .services {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;
            padding: 40px 10%; border-bottom: 1px solid #eee;
        }
        .service-item { display: flex; align-items: center; gap: 15px; }
        .service-icon { font-size: 24px; color: var(--accent); }
        .service-info h4 { font-size: 14px; font-weight: 700; }
        .service-info p { font-size: 12px; color: var(--text-gray); }

        /* BOOK GRID SECTION */
        .section-title { text-align: center; margin: 60px 0 40px; }
        .section-title h2 { font-size: 28px; font-weight: 400; letter-spacing: 1px; }
        
        .book-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 40px; padding: 0 10% 80px;
        }
        .book-card { background: white; transition: 0.3s; border-radius: 10px; overflow: hidden; }
        .book-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .book-img { 
            width: 100%; height: 320px; background: #f4f4f4; margin-bottom: 15px; 
            display: flex; align-items: center; justify-content: center; overflow: hidden;
            position: relative;
        }
        /* Perbaikan agar gambar tidak penyok di card baru */
        .book-img img { width: 100%; height: 100%; object-fit: cover; transition: 0.3s; }
        .book-card:hover .book-img img { transform: scale(1.05); }
        
        .book-info { padding: 0 10px 20px; }
        .book-info h3 { font-size: 16px; margin-bottom: 5px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .book-info .author { font-size: 12px; color: var(--text-gray); margin-bottom: 10px; display: block; }
        .book-footer { display: flex; justify-content: space-between; align-items: center; }
        .price { font-weight: 700; color: var(--primary); }
        
        /* Tombol Add to Cart Text Style */
        .btn-add-text { 
            font-size: 12px; font-weight: 600; border-bottom: 2px solid var(--primary); 
            padding-bottom: 2px; cursor: pointer; border: none; background: none; color: var(--primary);
        }
        .btn-add-text:hover { color: var(--accent); border-bottom-color: var(--accent); }

        /* ANIMASI FADE UNTUK SLIDESHOW (WAJIB ADA) */
        .fade-out { opacity: 0; transform: translateY(-10px); }
        .fade-in { opacity: 1; transform: translateY(0); }
        
        /* Responsive Mobile */
        @media (max-width: 768px) {
            .navbar { flex-direction: column; gap: 15px; }
            .nav-menu { gap: 15px; }
            .hero { flex-direction: column-reverse; padding: 40px 5%; text-align: center; }
            .hero-text { max-width: 100%; margin-top: 30px; }
            .services { grid-template-columns: 1fr 1fr; }
        }
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

    <header class="hero">
        <div class="hero-text" id="slide-text">
            <span class="hero-tag">Rekomendasi Terbaik</span>
            <h1>
                Kisah Menarik<br>
                <strong id="slide-title"><?= $slides[0]['JudulNovel']; ?></strong>
            </h1>
            <p id="slide-desc" style="margin-bottom: 20px; color: #666; font-size: 14px;">
                Stok Tersedia: <?= $slides[0]['Stok']; ?> | Petualangan epik menantimu di setiap halaman.
            </p>
            <div class="hero-price" id="slide-price">Rp <?= number_format($slides[0]['Harga']); ?></div>
            
            <a href="detail.php?id=<?= $slides[0]['NovelID']; ?>" id="slide-btn" class="btn-hero" style="text-decoration: none; display: inline-block;">
                Lihat Detail
            </a>
        </div>
        
        <div class="hero-img">
            <img src="../images/<?= $slides[0]['cover']; ?>" id="slide-img" alt="Cover Buku">
        </div>
    </header>

    <div class="features-container" style="display: flex; justify-content: space-around; padding: 40px 5%; background: #fff; border-top: 1px solid #eee;">
    
    <div class="feature-item" style="text-align: center;">
        <i class="fa-solid fa-truck-fast" style="font-size: 24px; color: var(--accent); margin-bottom: 10px;"></i>
        <h4 style="font-size: 14px;">Gratis Ongkir</h4>
        <p style="font-size: 12px; color: #888;">Min. belanja 100rb</p>
    </div>

    <div class="feature-item" style="text-align: center;">
        <i class="fa-solid fa-shield-halved" style="font-size: 24px; color: var(--accent); margin-bottom: 10px;"></i>
        <h4 style="font-size: 14px;">Pembayaran Aman</h4>
        <p style="font-size: 12px; color: #888;">100% Terlindungi</p>
    </div>

    <div class="feature-item" style="text-align: center;">
        <i class="fa-solid fa-arrow-rotate-left" style="font-size: 24px; color: var(--accent); margin-bottom: 10px;"></i>
        <h4 style="font-size: 14px;">Garansi Retur</h4>
        <p style="font-size: 12px; color: #888;">30 Hari pengembalian</p>
    </div>

    <div class="feature-item" style="text-align: center;">
        <i class="fa-solid fa-headset" style="font-size: 24px; color: var(--accent); margin-bottom: 10px;"></i>
        <h4 style="font-size: 14px;">24/7 Support</h4>
        <p style="font-size: 12px; color: #888;">Bantuan setiap saat</p>
    </div>

</div>

    <section>
        <div class="section-title">
            <h2>Buku Terbaru & Populer</h2>
            <p style="color:#aaa; font-size:14px; margin-top:10px;">Koleksi yang baru saja mendarat di toko kami</p>
        </div>

        <div class="book-grid">
            <?php while($d = mysqli_fetch_array($queryTerbaru)) { ?>
            <div class="book-card">
                <a href="detail.php?id=<?= $d['NovelID']; ?>"> 
            <div class="book-img">
                <img src="../images/<?= $d['cover']; ?>" alt="<?= $d['JudulNovel']; ?>">
            </div>
                </a>
                <a href="detail.php?id=<?= $d['NovelID']; ?>" class="btn-add-text" style="display:inline-block; text-decoration:none;">
                    <i class="fa-solid fa-eye"></i> Detail
                </a>
            </div>
            <?php } ?>
        </div>
        
        <div style="text-align: center; margin-bottom: 80px;">
            <a href="katalog.php" style="border: 1px solid #333; padding: 10px 30px; font-weight: 600; transition: 0.3s; color:var(--primary); border-radius:50px;">LIHAT SEMUA BUKU</a>
        </div>
    </section>

    <script>
    // 1. Fungsi Tambah ke Keranjang (AJAX)
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

    // 2. Fungsi Slideshow Hero Section
    const slides = <?= json_encode($slides); ?>;
    let currentSlide = 0;

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    function changeSlide() {
        const textContainer = document.getElementById('slide-text');
        const imgElement = document.getElementById('slide-img');
        
        // Efek Fade Out
        textContainer.classList.add('fade-out');
        imgElement.style.opacity = '0'; // Manual opacity untuk gambar krn ada transform rotate

        setTimeout(() => {
            // Ganti Data
            currentSlide = (currentSlide + 1) % slides.length;
            const novel = slides[currentSlide];

            document.getElementById('slide-title').innerText = novel.JudulNovel;
            document.getElementById('slide-desc').innerText = `Stok Tersedia: ${novel.Stok} | Petualangan epik menantimu.`;
            document.getElementById('slide-price').innerText = `Rp ${formatRupiah(novel.Harga)}`;
            
            // Update Fungsi Tombol Hero
            const btn = document.getElementById('slide-btn');
            btn.setAttribute('href', `detail.php?id=${novel.NovelID}`);
            btn.removeAttribute('onclick'); // Hapus fungsi keranjang biar jadi link biasa
            
            imgElement.src = `../images/${novel.cover}`;

            // Efek Fade In
            textContainer.classList.remove('fade-out');
            imgElement.style.opacity = '1';
        }, 500); 
    }

    // Jalankan slideshow setiap 5 detik
    setInterval(changeSlide, 5000);
    </script>

</body>
</html>