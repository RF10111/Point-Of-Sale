<?php
require '../vendor/autoload.php'; // Autoload Dompdf
include("config_query.php");

use Dompdf\Dompdf;
use Dompdf\Options;

// Inisialisasi database
$db = new Database();

// Ambil filter bulan, tahun, dan supplier dari parameter GET
$filter_day = isset($_GET['day']) ? $_GET['day'] : '';
$filter_month = isset($_GET['month']) ? $_GET['month'] : '';
$filter_year = isset($_GET['year']) ? $_GET['year'] : '';

// Ambil data cashflow berdasarkan filter
$data_cashflow = $db->tampil_cashflow($filter_day, $filter_month, $filter_year);

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
    <title>Laporan Pembelian Spare Parts</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Laporan Data Pembelian Spare Parts</h1>
    <p style="text-align: center;">Periode: ' .($filter_day ?: '-'). '/'.($filter_month ?: '-') . '/' . ($filter_year ?: '-') . '</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Transaksi</th>
                <th>Jumlah Transaksi</th>
                <th>Keterangan Transaksi</th>
            </tr>
        </thead>
        <tbody>';

// Periksa apakah data tersedia
if ($data_cashflow == '0') {
    $html .= '<tr><td colspan="4">Data Tidak Tersedia!</td></tr>';
} else {
    $no = 1;
    $total_harga = 0;

    foreach ($data_cashflow as $row) {
        $total_harga += $row['jumlah']; // Menghitung total pengeluaran
        $html .= '<tr>
            <td>' . $no++ . '</td>
            <td>' . date('d/m/Y', strtotime($row['tanggal'])) . '</td>
            <td>' . formatRupiah($row['jumlah']) . '</td>
            <td>' . $row['ket'] . '</td>
        </tr>';
    }

    // Menambahkan baris total pengeluaran
    $html .= '<tr style="font-weight: bold; background-color: #f9f9f9;">
        <td colspan="3" style="text-align: right;">Total Pengeluaran:</td>
        <td colspan="1">' . formatRupiah($total_harga) . '</td>
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
$dompdf->stream('Laporan_Cashflow.pdf', ['Attachment' => false]);
?>
