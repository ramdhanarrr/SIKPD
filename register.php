<?php
// Menyertakan file koneksi
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mendapatkan input dari formulir
    $id_petani = uniqid();
    $email_petani = $_POST['email_petani'];
    $nama_petani = $_POST['nama_petani'];
    $pasw_petani = $_POST['pasw_petani'];

    // Validasi input
    if (empty($email_petani) || empty($nama_petani) || empty($pasw_petani)) {
        echo "Semua kolom harus diisi!";
        exit;
    }

    // Memeriksa apakah email sudah terdaftar
    $sql = "SELECT * FROM petani WHERE email_petani = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email_petani);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email sudah terdaftar!";
        exit;
    }

    // Menyimpan petani baru ke database
    $sql = "INSERT INTO petani (id_petani, email_petani, nama_petani, pasw_petani) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $id_petani, $email_petani, $nama_petani, $pasw_petani);

    if ($stmt->execute()) {
        echo "Pendaftaran berhasil!";
        header("Location: login_index.html");
    } else {
        echo "Terjadi kesalahan: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>