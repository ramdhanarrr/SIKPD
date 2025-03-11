<?php
session_start();
include 'koneksi.php';

// Pastikan autoload Composer dimuat
require_once __DIR__ . '/vendor/autoload.php';

// Use statement untuk namespace Mpdf
use \Mpdf\Mpdf;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_alatbahan = $_POST['id_alatbahan'];
    $nama_alatbahan = $_POST['nama_alatbahan']; // Tambahkan ini
    $nama_penyewa = $_POST['nama_penyewa'];
    $alamat = $_POST['alamat'];
    $tgl_sewa = $_POST['tgl_sewa'];
    $tgl_kembali = $_POST['tgl_kembali'];
    $jumlah_pesan = $_POST['jumlah_pesan'];

    // Retry logic
    $max_attempts = 5;
    $attempts = 0;
    $success = false;
    $stmt_insert = null;

    while ($attempts < $max_attempts && !$success) {
        $conn->begin_transaction();

        try {
            // Mengambil stock alatbahan
            $sql_get_stock = "SELECT stock_alatbahan FROM alatbahan WHERE id_alatbahan = ?";
            $stmt_get_stock = $conn->prepare($sql_get_stock);
            $stmt_get_stock->bind_param('s', $id_alatbahan);
            $stmt_get_stock->execute();
            $result_get_stock = $stmt_get_stock->get_result();

            if ($result_get_stock->num_rows > 0) {
                $stock = $result_get_stock->fetch_assoc()['stock_alatbahan'];

                if ($stock >= $jumlah_pesan) {
                    // Mengurangi stock alatbahan
                    $new_stock = $stock - $jumlah_pesan;
                    $sql_update_stock = "UPDATE alatbahan SET stock_alatbahan = ? WHERE id_alatbahan = ?";
                    $stmt_update_stock = $conn->prepare($sql_update_stock);
                    $stmt_update_stock->bind_param('is', $new_stock, $id_alatbahan);
                    $stmt_update_stock->execute();

                    // Menghasilkan id_sewa berikutnya
                    $sql_max_id = "SELECT MAX(id_sewa) AS max_id FROM penyewaan";
                    $result_max_id = $conn->query($sql_max_id);
                    $max_id = $result_max_id->fetch_assoc()['max_id'];

                    if ($max_id === null) {
                        $next_id = 'SW001';

                    } else {
                        $numeric_part = intval(substr($max_id, 2)) + 1;
                        $next_id = 'SW' . sprintf('%03d', $numeric_part);
                    }

                    // Menyimpan data ke dalam tabel penyewaan
                    $sql_insert = "INSERT INTO penyewaan (id_sewa, id_alatbahan, nama_alatbahan, nama_penyewa, alamat, tgl_sewa, tgl_kembali, jumlah_pesan)
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt_insert = $conn->prepare($sql_insert);
                    $stmt_insert->bind_param('sssssssi', $next_id, $id_alatbahan, $nama_alatbahan, $nama_penyewa, $alamat, $tgl_sewa, $tgl_kembali, $jumlah_pesan);
                    $stmt_insert->execute();

                    // Commit transaksi
                    $conn->commit();
                    $success = true;

                    // Buat objek Mpdf
                    $mpdf = new Mpdf();

                    // Konten HTML untuk faktur
                    $html = "
                    <html>
                    <head>
                        <style>
                            .container {
                                margin-top: 50px;
                            }
                            .table {
                                width: 100%;
                                border-collapse: collapse;
                            }
                            .table th, .table td {
                                border: 1px solid black;
                                padding: 8px;
                            }
                            .table th {
                                background-color: #f2f2f2;
                            }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                            <h2 style='text-align: center;'>Faktur Penyewaan</h2>
                            <table class='table'>
                                <tr>
                                    <th>ID Sewa</th>
                                    <td>{$next_id}</td>
                                </tr>
                                <tr>
                                    <th>Nama Penyewa</th>
                                    <td>{$nama_penyea}</td>
                                </tr>
                                <tr>
                                    <th>Nama Alat/Bahan</th>
                                    <td>{$nama_alatbahan}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah Sewa</th>
                                    <td>{$jumlah_sewa}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{$alamat}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Sewa</th>
                                    <td>{$tgl_sewa}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Kembali</th>
                                    <td>{$tgl_kembali}</td>
                                </tr>
                            </table>
                        </div>
                    </body>
                    </html>
                    ";

                    // Tulis konten ke PDF
                    $mpdf->WriteHTML($html);

                    // Simpan PDF atau tampilkan di browser
                    $mpdf->Output('faktur_penyewaan.pdf', 'I'); // Simpan ke file faktur_pemesanan.pdf

                    // Exit script
                    exit;
                } else {
                    throw new Exception("Stock tidak mencukupi.");
                }
            } else {
                throw new Exception("ID Alatbahan tidak ditemukan.");
            }

        } catch (Exception $e) {
            $conn->rollback();
            $attempts++;
            if ($attempts >= $max_attempts) {
                echo "Gagal memproses penyewaan. Silakan coba lagi nanti.";
                echo "Error: " . htmlspecialchars($e->getMessage());
                exit; // Hentikan eksekusi setelah menampilkan pesan kesalahan
            }
        }
    }

    $stmt_get_stock->close();
    $stmt_update_stock->close();
    $conn->close();
} else {
    echo "Metode pengiriman tidak valid.";
}
?>