<?php
// Menyertakan file koneksi
include 'koneksi.php';

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
            // Lakukan sesuatu setelah login berhasil
            header("Location: index.html");
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
