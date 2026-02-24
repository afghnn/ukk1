<?php 
session_start();
include '../config/koneksi.php'; 


$total_item = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;

// --- LOGIKA FILTER (TETAP SAMA) ---
$where = "WHERE 1=1"; 
$order = "ORDER BY NovelID DESC"; 

// 1. Filter Kategori
if(isset($_GET['kategori'])){
    $kategori_id = mysqli_real_escape_string($koneksi, $_GET['kategori']);
    $where .= " AND KategoriID = '$kategori_id'";
}

// 2. Search
if(isset($_GET['s']) && $_GET['s'] != ''){
    $keyword = mysqli_real_escape_string($koneksi, $_GET['s']);
    $where .= " AND JudulNovel LIKE '%$keyword%'";
}

// 3. Sortir
if(isset($_GET['sort'])){
    if($_GET['sort'] == 'price_asc') { $order = "ORDER BY Harga ASC"; }
    elseif($_GET['sort'] == 'price_desc') { $order = "ORDER BY Harga DESC"; }
    elseif($_GET['sort'] == 'oldest') { $order = "ORDER BY NovelID ASC"; }
}

// Query Novel & Kategori
$query = mysqli_query($koneksi, "SELECT * FROM novel $where $order");
$queryKategori = mysqli_query($koneksi, "SELECT * FROM kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog | Novel Shop</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* --- STYLE MATCHING DASHBOARD --- */
        :root {
            --primary: #2d3436;
            --accent: #6c5ce7;
            --bg-light: #f9f9f9;
            --text-gray: #636e72;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg-light); color: var(--primary); }
        a { text-decoration: none; color: inherit; }

        /* NAVBAR (SAMA PERSIS DENGAN DASHBOARD) */
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
        .cart-badge {
            background: var(--accent); color: white; padding: 2px 8px; border-radius: 50px;
            font-size: 11px; margin-left: 2px; font-weight: 700; vertical-align: top;
        }

        /* LAYOUT KATALOG */
        .main-container {
            display: flex; gap: 40px; padding: 40px 5%; max-width: 1400px; margin: auto;
        }

        /* SIDEBAR (Dipercantik & Pakai Icon) */
        .sidebar {
            width: 260px; flex-shrink: 0; 
            background: white; padding: 30px; border-radius: 12px; height: fit-content;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid #eee;
            position: sticky; top: 100px;
        }
        .sidebar h3 { font-size: 16px; margin-bottom: 20px; color: var(--primary); letter-spacing: 0.5px; font-weight: 700; display:flex; align-items:center; gap:10px; }
        .category-list { list-style: none; }
        .category-list li { margin-bottom: 8px; }
        .category-list a {
            display: block; padding: 12px 15px; border-radius: 8px; 
            color: var(--text-gray); font-size: 14px; transition: 0.3s; font-weight: 500;
        }
        .category-list a:hover { background: #f0f3ff; color: var(--accent); transform: translateX(5px); }
        .category-list a.active { 
            background: var(--accent); color: white; font-weight: 600; 
            box-shadow: 0 4px 10px rgba(108, 92, 231, 0.3); transform: translateX(5px);
        }

        /* KONTEN KANAN */
        .content-area { flex-grow: 1; }

        /* FILTER BAR */
        .filter-header { 
            display: flex; justify-content: space-between; margin-bottom: 30px; align-items: center; 
            background: white; padding: 20px; border-radius: 12px; border: 1px solid #eee;
        }
        .search-input { 
            padding: 12px 25px; border: 2px solid #eee; border-radius: 50px; 
            outline: none; width: 350px; transition: 0.3s; font-size: 14px;
        }
        .search-input:focus { border-color: var(--accent); }
        .sort-select { 
            padding: 10px 15px; border: 2px solid #eee; border-radius: 8px; 
            outline: none; cursor: pointer; color: var(--text-gray); font-size: 14px;
        }

        /* GRID BUKU */
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 30px; }
        
        .book-card { 
            background: white; transition: 0.3s; border-radius: 10px; overflow: hidden; border: 1px solid #eee;
            display: flex; flex-direction: column;
        }
        .book-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.08); border-color: var(--accent); }
        
        .book-img { 
            width: 100%; height: 280px; background: #f4f4f4; 
            display: flex; align-items: center; justify-content: center; overflow: hidden;
            position: relative;
        }
        .book-img img { width: 100%; height: 100%; object-fit: cover; transition: 0.4s; }
        .book-card:hover .book-img img { transform: scale(1.05); }
        
        .book-info { padding: 15px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; }
        .book-info h3 { font-size: 15px; margin-bottom: 5px; font-weight: 600; line-height: 1.4; color: var(--primary); }
        .book-info h3 a:hover { color: var(--accent); text-decoration: underline; }
        .book-info .author { font-size: 12px; color: var(--text-gray); margin-bottom: 15px; display: block; }
        
        .book-footer { display: flex; justify-content: space-between; align-items: center; margin-top: auto; }
        .price { font-weight: 700; color: var(--accent); font-size: 16px; }
        
        /* Tombol Cart Kecil */
        .btn-add-mini { 
            background: var(--primary); color: white; border: none; padding: 8px 15px;
            border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: 0.3s;
            display: flex; align-items: center; gap: 5px;
        }
        .btn-add-mini:hover { background: var(--accent); }
        .btn-disabled { background: #eee; color: #aaa; cursor: not-allowed; }

        /* Responsive */
        @media (max-width: 900px) {
            .main-container { flex-direction: column; }
            .sidebar { width: 100%; position: static; margin-bottom: 20px; }
            .filter-header { flex-direction: column; gap: 15px; align-items: stretch; }
            .search-input { width: 100%; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="dashboard.php" class="logo">NOVEL<span style="color:var(--accent);">SHOP</span>.</a>
        <div class="nav-menu">
            <a href="dashboard.php">Home</a>
            <a href="katalog.php" class="active">Katalog Buku</a>
            <a href="pesanan_saya.php">Pesanan Saya</a>
        </div>
        <div class="nav-icons">
            <a href="cart.php" style="position: relative;">
                <i class="fa-solid fa-cart-shopping"></i> 
                <span id="cart-count" class="cart-badge"><?= $total_item ?></span>
            </a>
            <a href="../auth/logout.php" style="color:#d63031; font-weight:600; font-size:14px;">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </div>
    </nav>

    <div class="main-container">
        
        <aside class="sidebar">
            <h3><i class="fa-solid fa-layer-group"></i> Filter Kategori</h3>
            <ul class="category-list">
                <li>
                    <a href="katalog.php" class="<?= !isset($_GET['kategori']) ? 'active' : '' ?>">
                        <i class="fa-solid fa-border-all" style="width:20px;"></i> Semua Koleksi
                    </a>
                </li>
                <?php while($kat = mysqli_fetch_array($queryKategori)) { 
                    $active = (isset($_GET['kategori']) && $_GET['kategori'] == $kat['KategoriID']) ? 'active' : '';
                ?>
                <li>
                    <a href="katalog.php?kategori=<?= $kat['KategoriID']; ?>" class="<?= $active ?>">
                        <i class="fa-solid fa-chevron-right" style="width:20px; font-size:10px;"></i> <?= $kat['NamaKategori']; ?>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </aside>

        <main class="content-area">
            
            <div class="filter-header">
                <form action="" method="GET" style="flex-grow: 1; margin-right: 20px; position:relative;">
                    <?php if(isset($_GET['kategori'])): ?><input type="hidden" name="kategori" value="<?= $_GET['kategori'] ?>"><?php endif; ?>
                    <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:15px; top:14px; color:#ccc;"></i>
                    <input type="text" name="s" class="search-input" placeholder="Cari judul novel..." value="<?= isset($_GET['s']) ? $_GET['s'] : '' ?>" style="padding-left:40px;">
                </form>

                <form action="" method="GET">
                    <?php if(isset($_GET['kategori'])): ?><input type="hidden" name="kategori" value="<?= $_GET['kategori'] ?>"><?php endif; ?>
                    <?php if(isset($_GET['s'])): ?><input type="hidden" name="s" value="<?= $_GET['s'] ?>"><?php endif; ?>
                    <select name="sort" class="sort-select" onchange="this.form.submit()">
                        <option value="newest">Terbaru</option>
                        <option value="price_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : '' ?>>Harga Terendah</option>
                        <option value="price_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : '' ?>>Harga Tertinggi</option>
                    </select>
                </form>
            </div>

            <div class="grid">
                <?php if(mysqli_num_rows($query) > 0) { ?>
                    <?php while($d = mysqli_fetch_array($query)){ ?>
                    
                    <div class="book-card">
                        <div class="book-img">
                            <a href="detail.php?id=<?= $d['NovelID'] ?>">
                                <img src="../images/<?= $d['cover'] ?>" alt="Cover">
                            </a>
                        </div>
                        <div class="book-info">
                            <div>
                                <h3><a href="detail.php?id=<?= $d['NovelID'] ?>"><?= $d['JudulNovel'] ?></a></h3>
                                <span class="author">Stok Tersedia: <?= $d['Stok'] ?></span>
                            </div>
                            
                            <div class="book-footer">
                                <span class="price">Rp <?= number_format($d['Harga']) ?></span>
                                
                                <?php if($d['Stok'] > 0): ?>
                                    <a href="detail.php?id=<?= $d['NovelID'] ?>" class="btn-add-mini" style="text-decoration: none;">
                                        <i class="fa-solid fa-eye"></i> Detail
                                    </a>
                                <?php else: ?>
                                    <button class="btn-add-mini btn-disabled">Habis</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php } ?>
                <?php } else { ?>
                    <div style="grid-column: 1/-1; text-align: center; padding: 60px; color:#aaa;">
                        <div style="font-size: 40px; margin-bottom: 10px;"><i class="fa-regular fa-face-frown-open"></i></div>
                        <h3>Produk tidak ditemukan</h3>
                        <p>Coba kata kunci lain atau reset filter kategori.</p>
                        <a href="katalog.php" style="display:inline-block; margin-top:15px; color:var(--accent); font-weight:600;">Reset Filter</a>
                    </div>
                <?php } ?>
            </div>
        </main>

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