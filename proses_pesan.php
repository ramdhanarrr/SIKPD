<?php
session_start();
include 'koneksi.php';

// Pastikan autoload Composer dimuat
require_once __DIR__ . '/vendor/autoload.php';

// Use statement untuk namespace Mpdf
use \Mpdf\Mpdf;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_alatbahan = $_POST['id_alatbahan'];
    $nama_alatbahan = $_POST['nama_alatbahan'];
    $nama_pemesan = $_POST['nama_pemesan'];
    $alamat = $_POST['alamat'];
    $tgl_pesan = $_POST['tgl_pesan'];
    $jumlah_pesan = $_POST['jumlah_pesan'];

    $max_attempts = 5;
    $attempts = 0;
    $success = false;
    $stmt_insert = null;

    while ($attempts < $max_attempts && !$success) {
        $conn->begin_transaction();

        try {
            $sql_get_stock = "SELECT stock_alatbahan FROM alatbahan WHERE id_alatbahan = ?";
            $stmt_get_stock = $conn->prepare($sql_get_stock);
            $stmt_get_stock->bind_param('s', $id_alatbahan);
            $stmt_get_stock->execute();
            $result_get_stock = $stmt_get_stock->get_result();

            if ($result_get_stock->num_rows > 0) {
                $stock = $result_get_stock->fetch_assoc()['stock_alatbahan'];

                if ($stock >= $jumlah_pesan) {
                    $new_stock = $stock - $jumlah_pesan;
                    $sql_update_stock = "UPDATE alatbahan SET stock_alatbahan = ? WHERE id_alatbahan = ?";
                    $stmt_update_stock = $conn->prepare($sql_update_stock);
                    $stmt_update_stock->bind_param('is', $new_stock, $id_alatbahan);
                    $stmt_update_stock->execute();

                    $sql_max_id = "SELECT MAX(id_pesan) AS max_id FROM pemesanan";
                    $result_max_id = $conn->query($sql_max_id);
                    $max_id = $result_max_id->fetch_assoc()['max_id'];

                    if ($max_id === null) {
                        $next_id = 'PS001';
                    } else {
                        $numeric_part = intval(substr($max_id, 2)) + 1;
                        $next_id = 'PS' . sprintf('%03d', $numeric_part);
                    }

                    $sql_insert = "INSERT INTO pemesanan (id_pesan, id_alatbahan, nama_alatbahan, nama_pemesan, alamat, tgl_pesan, jumlah_pesan)
                                   VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt_insert = $conn->prepare($sql_insert);
                    $stmt_insert->bind_param('ssssssi', $next_id, $id_alatbahan, $nama_alatbahan, $nama_pemesan, $alamat, $tgl_pesan, $jumlah_pesan);
                    $stmt_insert->execute();

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
                            <h2 style='text-align: center;'>Faktur Pemesanan</h2>
                            <table class='table'>
                                <tr>
                                    <th>ID Sewa</th>
                                    <td>{$next_id}</td>
                                </tr>
                                <tr>
                                    <th>Nama Pemesan</th>
                                    <td>{$nama_pemesan}</td>
                                </tr>
                                <tr>
                                    <th>Nama Alat/Bahan</th>
                                    <td>{$nama_alatbahan}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah Pesan</th>
                                    <td>{$jumlah_pesan}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{$alamat}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pesan</th>
                                    <td>{$tgl_pesan}</td>
                                </tr>
                            </table>
                        </div>
                    </body>
                    </html>
                    ";

                    // Tulis konten ke PDF
                    $mpdf->WriteHTML($html);

                    // Simpan PDF atau tampilkan di browser
                    $mpdf->Output('faktur_pemesanan.pdf', 'I'); // Simpan ke file faktur_pemesanan.pdf

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
                echo "Gagal memproses pesanan. Silakan coba lagi nanti.";
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