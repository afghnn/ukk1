<?php
session_start();
include '../config/koneksi.php';

if (isset($_POST['checkout'])) {
    // 1. Ambil UserID dari tabel login (yang ada di session)
    $user_id_login = $_SESSION['user_id']; 

    // 2. KITA CEK: Apakah UserID ini punya data di tabel pelanggan?
    // Catatan: Asumsinya UserID di tabel login SAMA dengan PelangganID di tabel pelanggan
    $cek_pelanggan = mysqli_query($koneksi, "SELECT PelangganID FROM pelanggan WHERE PelangganID = '$user_id_login'");
    
    if (mysqli_num_rows($cek_pelanggan) == 0) {
        // Jika ternyata di tabel pelanggan belum ada ID yang sama, kita buatkan dulu (Auto-register ke tabel pelanggan)
        $nama = $_SESSION['nama'];
        mysqli_query($koneksi, "INSERT INTO pelanggan (PelangganID, NamaPelanggan) VALUES ('$user_id_login', '$nama')");
    }

    $id_user = $user_id_login; // Sekarang ID ini pasti sudah ada di tabel pelanggan
    $tgl = date("Y-m-d");
    $total = $_POST['total_harga'];
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $no_hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);

    // 3. Simpan ke penjualan
    $query_penjualan = "INSERT INTO penjualan (TanggalPenjualan, TotalHarga, PelangganID, AlamatPengiriman, NoHP, Status) 
                        VALUES ('$tgl', '$total', '$id_user', '$alamat', '$no_hp', 'Pending')";
    
    if (mysqli_query($koneksi, $query_penjualan)) {
        $id_penjualan_barusan = mysqli_insert_id($koneksi);

        foreach ($_SESSION['cart'] as $id_novel => $jumlah) {
            $ambil_novel = mysqli_query($koneksi, "SELECT Harga FROM novel WHERE NovelID = '$id_novel'");
            $pecah_novel = mysqli_fetch_array($ambil_novel);
            $subtotal = $pecah_novel['Harga'] * $jumlah;

            mysqli_query($koneksi, "INSERT INTO detail_penjualan (PenjualanID, NovelID, Jumlah, Subtotal) 
                                    VALUES ('$id_penjualan_barusan', '$id_novel', '$jumlah', '$subtotal')");
        }

        unset($_SESSION['cart']);
        echo "<script>alert('Pesanan berhasil dibuat!'); window.location='pesanan_saya.php';</script>";
    } else {
        // Jika masih error, tampilkan detailnya
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>