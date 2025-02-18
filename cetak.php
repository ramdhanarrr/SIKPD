<?php
session_start();
include 'koneksi.php';

// Pastikan autoload Composer dimuat
require_once __DIR__ . '/vendor/autoload.php';

// Use statement untuk namespace Mpdf
use \Mpdf\Mpdf;

if (isset($_POST['cetak_struk'])) {
    // Ambil data dari $_POST
    $nama_alatbahan = isset($_POST['nama_alatbahan']) ? $_POST['nama_alatbahan'] : '';
    $nama_pemesan = isset($_POST['nama_pemesan']) ? $_POST['nama_pemesan'] : '';
    $jumlah_pesan = isset($_POST['jumlah_pesan']) ? $_POST['jumlah_pesan'] : '';
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
    $tgl_pesan = isset($_POST['tgl_pesan']) ? $_POST['tgl_pesan'] : '';
    $harga_alatbahan = isset($_POST['harga_alatbahan']) ? $_POST['harga_alatbahan'] : '';
    $total_biaya = isset($_POST['total_biaya']) ? $_POST['total_biaya'] : '';

    // Query atau proses lainnya jika diperlukan

    try {
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
                    <tr>
                        <th>Harga Satuan</th>
                        <td>{$harga_alatbahan}</td>
                    </tr>
                    <tr>
                        <th>Total Biaya</th>
                        <td>{$total_biaya}</td>
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
    } catch (Exception $e) {
        echo "Gagal membuat faktur pemesanan.";
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
} else {
    // Jika pengguna mencoba mengakses halaman ini secara langsung tanpa mengirimkan data dari form rincian_pesanan.php
    echo "Metode pengiriman tidak valid.";
}
?>