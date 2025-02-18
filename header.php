<?php
session_start();
include 'koneksi.php';

$nama_petani = isset($_SESSION['nama_petani']) ? $_SESSION['nama_petani'] : '';

?>

<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

        <a href="index.html" class="logo d-flex align-items-center me-auto">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <!-- <img src="assets/img/logo.png" alt=""> -->
            <h1 class="sitename">SIKPD</h1>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="index.html" class="">Beranda</a></li>
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
