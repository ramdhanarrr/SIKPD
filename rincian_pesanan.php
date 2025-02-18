<?php
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_petani'])) {
    header("Location: login_sewa.php");
    exit();
}

// Ambil data yang dikirimkan dari form buat_pesan.php
$id_alatbahan = isset($_POST['id_alatbahan']) ? $_POST['id_alatbahan'] : '';
$nama_pemesan = isset($_POST['nama_pemesan']) ? $_POST['nama_pemesan'] : '';
$nama_alatbahan = isset($_POST['nama_alatbahan']) ? $_POST['nama_alatbahan'] : '';
$jumlah_pesan = isset($_POST['jumlah_pesan']) ? $_POST['jumlah_pesan'] : 0;
$alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
$tgl_pesan = isset($_POST['tgl_pesan']) ? $_POST['tgl_pesan'] : '';

// Query untuk mendapatkan harga alat/bahan berdasarkan id_alatbahan
include 'koneksi.php';
$sql = "SELECT nama_alatbahan, harga_alatbahan FROM alatbahan WHERE id_alatbahan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id_alatbahan);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nama_alatbahan = $row['nama_alatbahan'];
$harga_alatbahan = $row['harga_alatbahan'];
$stmt->close();

// Hitung total biaya pesanan
$total_biaya = $harga_alatbahan * $jumlah_pesan;

// Simpan data ke database (jika perlu), atau tampilkan saja di halaman rincian_pesanan.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Rincian Pesanan</title>
    <!-- Tambahkan CSS yang diperlukan -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tambahkan CSS kustom jika diperlukan -->
    <link href="assets/css/main.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js" integrity="sha512-dwEiM2aDt+WuSA1Yd8S/yDcUW0Iq+TEm2F04RbA/c4e52H1Z4j9trFg7D9JIR+phhJ9s5M4Kss5fFci5PxtVvQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js" integrity="sha512-D1OBsQ8t6PGgjauX3dPXUDNTOY17rBQdZkFy38Yp+K64R8bSvRyA4kE1wTUl8Bu56d9KIA7Pf1YtrYIQGQ/M0Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body class="service-details-page">

<header id="header" class="header d-flex align-items-center fixed-top">
    <!-- Header sesuai dengan kebutuhan -->
</header>

<main class="main">

    <section id="service-details" class="service-details section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <!-- Tampilkan rincian pesanan -->
                    <div class="section-title">
                        <h2>Detail Pesanan</h2>
                    </div>
                    <form action="cetak.php" method="post">
                        <div class="mb-3">
                            <label for="nama_alatbahan" class="form-label">Nama Alat/Bahan</label>
                            <input type="text" class="form-control" id="nama_alatbahan" name="nama_alatbahan" value="<?php echo htmlspecialchars($nama_alatbahan); ?>">
                            <label for="nama_pemesanan" class="form-label">Nama Pemesan</label>
                            <input type="text" class="form-control" id="nama_pemesan" name="nama_pemesan" value="<?php echo htmlspecialchars($nama_pemesan); ?>">
                            <label for="jumlah_pesan" class="form-label">Jumlah Pesan</label>
                            <input type="text" class="form-control" id="jumlah_pesan" name="jumlah_pesan" value="<?php echo htmlspecialchars($jumlah_pesan); ?>">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo htmlspecialchars($alamat); ?>">
                            <label for="tgl_pesan" class="form-label">Tanggal Pesan</label>
                            <input type="text" class="form-control" id="tgl_pesan" name="tgl_pesan" value="<?php echo htmlspecialchars($tgl_pesan); ?>">
                            <label for="harga_alatbahan" class="form-label">Harga Alat/Bahan</label>
                            <input type="text" class="form-control" id="harga_alatbahan" name="harga_alatbahan" value="<?php echo htmlspecialchars($harga_alatbahan); ?>">
                            <label for="total_biaya" class="form-label"> Total Biaya</label>
                            <input type="text" class="form-control" id="total_biaya" name="total_biaya" value="<?php echo htmlspecialchars($total_biaya); ?>">
                        </div>
                        <div class="mb-3">
                            <!-- Tombol untuk kembali ke beranda -->
                            <a href="index.html" class="btn btn-primary">Beranda</a>
                            <!-- Tombol untuk logout -->
                            <a href="logout.php" class="btn btn-danger">Keluar</a>
                            <!-- Tombol untuk mencetak struk menggunakan JavaScript -->
                            <button type="submit" class="btn btn-success" name="cetak_struk">Cetak Struk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Tambahkan footer jika diperlukan -->

</body>
</html>