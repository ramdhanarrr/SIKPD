<?php
// Menyertakan file koneksi
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mendapatkan input dari formulir
    $email_petani = isset($_POST['email_petani']) ? $_POST['email_petani'] : '';
    $pasw_petani = isset($_POST['pasw_petani']) ? $_POST['pasw_petani'] : '';

    // Validasi input
    if (empty($email_petani) || empty($pasw_petani)) {
        echo "Semua kolom harus diisi!";
        exit;
    }

    // Memeriksa apakah email dan password sesuai
    $sql = "SELECT * FROM petani WHERE email_petani = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email_petani);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($pasw_petani === $row['pasw_petani']) { // Menggunakan perbandingan sederhana jika password tidak di-hash
            echo "Login berhasil!";
            // Simpan informasi pengguna dalam sesi
            $_SESSION['id_petani'] = $row['id_petani'];
            $_SESSION['email_petani'] = $row['email_petani'];
            $_SESSION['nama_petani'] = $row['nama_petani'];
            // Lakukan sesuatu setelah login berhasil
            header("Location: index_sewa.php");
            exit();
        } else {
            echo "Password salah!";
        }
    } else {
        echo "Email tidak ditemukan!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | Daftar</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form action="register.php" method="POST">
                <h1>Buat Akun</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class='bx bxl-google' color="#FF70AB"></i></a>
                </div>
                <samp>atau masukkan no. telp atau email</samp>
                <input type="email" name="email_petani" placeholder="Email" required>
                <input type="text" name="nama_petani" placeholder="Nama Petani" required>
                <input type="password" name="pasw_petani" placeholder="Password" required>
                <button type="submit">Daftar</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form action="login_sewa.php" method="POST">
                <h1>Masuk</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class='bx bxl-google'></i></a>
                </div>
                <samp>Atau gunakan Email/No Telp</samp>
                <input type="email" name="email_petani" placeholder="Email/No. Telp">
                <input type="password" name="pasw_petani" placeholder="Password">
                <a href="#" class="fp">Lupa password?</a>
                <button type="submit">Masuk</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Selamat datang, Sobat Tani!</h1>
                    <p>Masuk ke akun pribadi anda untuk menikmati semua fitur yang ada!!</p>
                    <button class="hidden" id="login">Masuk</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hallo, Sobat Tani!!</h1>
                    <p>Belum punya akun? silahkan buat akun pribadi anda untuk menikmati semua fitur yang ada!!</p>
                    <button class="hidden" id="register">Daftar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>

</html>