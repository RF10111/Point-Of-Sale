<?php
require '../vendor/autoload.php'; // Autoload Dompdf
include("config_query.php");

use Dompdf\Dompdf;
use Dompdf\Options;

// Inisialisasi database
$db = new Database();

// Ambil filter bulan dan tahun dari parameter GET
$filter_month = isset($_GET['month']) ? $_GET['month'] : '';
$filter_year = isset($_GET['year']) ? $_GET['year'] : '';

// Ambil data penjualan berdasarkan filter
$data_jual = $db->tampil_jual($filter_month, $filter_year);

function formatRupiah($number) {
    // Pastikan $number tidak null, jika null ganti dengan 0
    $number = $number ?? 0;
    return "Rp " . number_format($number, 0, ',', '.');
}


// HTML untuk laporan
$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Spare Parts</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Laporan Penjualan Spare Parts</h1>
    <p style="text-align: center;">Periode: ' . ($filter_month ?: '-') . '/' . ($filter_year ?: '-') . '</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Spare Part</th>
                <th>Total Harga</th>
                <th>Nama Pembeli</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>';

if ($data_jual == '0') {
    $html .= '<tr><td colspan="9">Data Tidak Tersedia!</td></tr>';
} else {
    $no = 1;
    $total_income = 0;

    foreach ($data_jual as $row) {
        $total_income += $row['total'];
        $html .= '<tr>
            <td>' . $no++ . '</td>
            <td>' . date('d/m/Y', strtotime($row['tgl_transaksi'])) . '</td>
            <td>' . $row['sparepart_name'] . '</td>
            <td>' . formatRupiah($row['total']) . '</td>
            <td>' . $row['customer_name'] . '</td>
            <td style="color: ' . ($row['payment_status'] == 'Lunas' ? 'black' : 'red') . ';">' . $row['payment_status'] . '</td>
        </tr>';
    }

    // Total penghasilan
    $html .= '<tr style="font-weight: bold; background-color: #f9f9f9;">
        <td colspan="5">Total Keuntungan:</td>
        <td>' . formatRupiah($total_income) . '</td>
    </tr>';
}

$html .= '
        </tbody>
    </table>
</body>
</html>';

// Konfigurasi Dompdf
$options = new Options();
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);

// Load HTML ke Dompdf
$dompdf->loadHtml($html);

// Set ukuran dan orientasi kertas
$dompdf->setPaper('A4', 'portrait');

// Render HTML menjadi PDF
$dompdf->render();

// Output PDF ke browser
$dompdf->stream('Laporan_Penjualan.pdf', ['Attachment' => false]);
?>
