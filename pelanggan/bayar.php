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