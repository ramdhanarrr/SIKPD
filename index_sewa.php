<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_petani'])) {
    header("Location: login_sewa.php");
    exit();
}

$nama_petani = isset($_POST['nama_petani']) ? $_POST ['id_alatbahan'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>SIKPD - Sistem Informasi Kegiatan Pertanian Desa</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
    rel="stylesheet">

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
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1 class="sitename">SIKPD</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="">Beranda</a></li>
          <li><a href="#tentang">Tentang GAPOKTAN</a></li>
          <li class="dropdown"><a href="#"><span>Layanan</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="index_sewa.php">Daftar Alat dan Bahan</a></li>
              <li><a href="index_pupuk.html">Jadwal Subsidi Pupuk</a></li>
            </ul>
          </li>
          <li><a href="potensi-desa.html">Potensi Desa</a></li>
          <li><a href="#informasi">Informasi Pertanian</a></li>
          <li><a href="#kontak">Kontak</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-login" href="<?php echo isset($_SESSION['id_petani']) ? 'logout.php' : 'login_sewa.php'; ?>">
        <?php echo isset($_SESSION['id_petani']) ? 'Hai, ' . $nama_petani : 'Masuk'; ?>
      </a>
      
    </div>
  </header>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title" data-aos="fade">
      <div class="container">
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Beranda</a></li>
            <li class="current">Pemesanan Alat dan Bahan</li>
          </ol>
        </nav>
        <h1>Pemesanan Alat dan Bahan</h1>
      </div>
    </div><!-- End Page Title -->

    <!-- Service Details Section -->
    <section id="service-details" class="service-details section">

      <div class="container">

        <div class="center-content" data-aos="fade-up" data-aos-delay="100">
          <h3>Daftar Alat dan Bahan</h3>
          <p style="text-align: center;">Gabungan Kelompok Tani desa Bendorejo memiliki beberapa komoditas yang
            terbagi menjadi 2 sektor, yakni
            persawahan dan perkebunan. Di sektor persawahan berbagai macam tanaman ditanam diantaranya jagung, tebu,
            tomat, buncis, cabai, dll. Pada sektor perkebunan, berbagai macam jenis pisang, cacao, dan juga kopi
            ditanam.</p>
        </div>

        <!-- New Table Section -->
        <div class="row gy-4">
          <div class="col-lg-12" data-aos="fade-up" data-aos-delay="200">
            <div class="table-responsive">
              <table class="table table-striped table-bordered">
              <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Stock</th>
                        <th>Jenis</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="alatbahan-table-body">
                  <?php
                  include 'koneksi.php';

                   // Query untuk mengambil data dari tabel alatbahan
                   $sql = "SELECT id_alatbahan, nama_alatbahan, stock_alatbahan, jenis_alatbahan, harga_alatbahan FROM alatbahan";
                   $result = $conn->query($sql);
                   
                   if ($result->num_rows > 0) {
                       while ($row = $result->fetch_assoc()) {
                           echo "<tr>";
                           echo "<td>" . htmlspecialchars($row['id_alatbahan']) . "</td>";
                           echo "<td>" . htmlspecialchars($row['nama_alatbahan']) . "</td>";
                           echo "<td style='text-align: center;'>" . htmlspecialchars($row['stock_alatbahan']) . "</td>";
                           echo "<td style='text-align: center;'>" . htmlspecialchars($row['jenis_alatbahan']) . "</td>";
                           echo "<td style='text-align: center;'>" . htmlspecialchars($row['harga_alatbahan']) . "</td>";
                           if ($row['jenis_alatbahan'] == 'Alat') {
                               echo "<td style='text-align: center;'><a href='buat_sewa.php?id_alatbahan=" . htmlspecialchars($row['id_alatbahan']) . "' class='btn btn-primary'>Sewa</a> <a href='buat_pesan.php?id_alatbahan=" . htmlspecialchars($row['id_alatbahan']) . "' class='btn btn-primary'>Pesan</a></td>";
                           } else {
                               echo "<td style='text-align: center;'><a href='buat_pesan.php?id_alatbahan=" . htmlspecialchars($row['id_alatbahan']) . "' class='btn btn-primary'>Pesan</a></td>";
                           }
                           echo "</tr>";
                       }
                   } else {
                       echo "<tr><td colspan='6'>Tidak ada data ditemukan</td></tr>";
                   }                   
                  ?>
                </tbody>
              </table>
            </div>
            <!-- End Table Section -->

          </div>

        </div>

      </div>

    </section><!-- /Service Details Section -->

  </main>

  <footer id="footer" class="footer">


    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-tentang">
          <a href="index.html" class="d-flex align-items-center">
            <span class="sitename">SIKPD</span>
          </a>
          <div class="footer-contact pt-3">
            <p>Gabungan Kelompok Tani Bendorejo</p>
            <p>Udanawu, Blitar, Jawa Timur</p>
            <p class="mt-3"><strong>Phone:</strong> <span>+1 5589 55488 55</span></p>
            <p><strong>Email:</strong> <span>info@example.com</span></p>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Kerjasama</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="https://www.blitarkab.go.id/">Kabupaten Blitar</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="https://bendorejo.desa.id/">Desa Bendorejo</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="https://dkpp.blitarkab.go.id/">DKPP Blitar</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Layanan Kami</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Sewa Alat Pertanian</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Pesan Alat Pertanian</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Jadwal Subsidi Pupuk</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-12">
          <h4>Kontak</h4>
          <p>Anda tetap bisa terhubung dengan kami melalui media sosial.</p>
          <div class="social-links d-flex">
            <a href=""><i class="bi bi-twitter"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">SIKPD</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>

  </footer>
  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>