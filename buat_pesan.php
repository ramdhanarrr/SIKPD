<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_petani'])) {
    // Jika pengguna belum login, arahkan ke halaman login
    header("Location: login_sewa.php");
    exit();
}

// Dapatkan id_alatbahan dari parameter URL
$id_alatbahan = isset($_GET['id_alatbahan']) ? $_GET['id_alatbahan'] : '';

// Query untuk mendapatkan nama alat/bahan berdasarkan id_alatbahan
$sql = "SELECT nama_alatbahan FROM alatbahan WHERE id_alatbahan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id_alatbahan);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nama_alatbahan = $row['nama_alatbahan'];
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>SIKPD - Form Pemesanan</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    
    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    
    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    
    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">
</head>
<body class="service-details-page">

<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">
        <a href="index.html" class="logo d-flex align-items-center me-auto">
            <h1 class="sitename">SIKPD</h1>
        </a>
        <nav id="navmenu" class="navmenu">
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
        <a class="btn-login"
        href="<?php echo isset($_SESSION['id_petani']) ? 'logout.php' : 'login_index.html'; ?>">
        <?php echo isset($_SESSION['id_petani']) ? 'Keluar' : 'Masuk'; ?>
      </a>
    </div>
</header>

<main class="main">
    <div class="page-title" data-aos="fade">
        <div class="container">
            <nav class="breadcrumbs">
                <ol>
                    <li><a href="index.html">Beranda</a></li>
                    <li class="current">Form Pemesanan</li>
                </ol>
            </nav>
            <h1>Form Penyewaan</h1>
        </div>
    </div>

    <section id="service-details" class="service-details section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-title">
                        <h2>Form Pemesanan Alat dan Bahan</h2>
                    </div>
                    <form action="rincian_pesanan.php" method="post">
                            <!-- Isi form seperti pada buat_pesan.php -->
                            <input type="hidden" name="id_alatbahan" value="<?php echo htmlspecialchars($id_alatbahan); ?>">
                            <input type="hidden" name="nama_pemesan" value="<?php echo htmlspecialchars($_SESSION['nama_petani']); ?>">
                            <!-- Sisanya input seperti alamat, jumlah pesan, tanggal sewa, tanggal kembali -->

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Penyewa</label>
                            <input type="text" class="form-control" id="nama" name="nama_pemesan_display" value="<?php echo htmlspecialchars($_SESSION['nama_petani']); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="nama_alatbahan" class="form-label">Nama Alat/Bahan</label>
                            <input type="text" class="form-control" id="nama_alatbahan" name="nama_alatbahan" value="<?php echo $nama_alatbahan; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_pesan" class="form-label">Jumlah Pesanan</label>
                            <input type="number" class="form-control" id="jumlah_pesan" name="jumlah_pesan" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                                <label for="tgl_pesan" class="form-label">Tanggal Pesan</label>
                                <input type="date" class="form-control" id="tgl_pesan" name="tgl_pesan"
                                    value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim Pesanan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- (Your existing footer content) -->

</body>
</html>