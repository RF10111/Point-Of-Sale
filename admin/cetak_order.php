<?php
require '../vendor/autoload.php'; // Autoload Dompdf
include("config_query.php");

use Dompdf\Dompdf;
use Dompdf\Options;

// Inisialisasi database
$db = new Database();

// Ambil filter bulan, tahun, dan supplier dari parameter GET
$filter_month = isset($_GET['month']) ? $_GET['month'] : '';
$filter_year = isset($_GET['year']) ? $_GET['year'] : '';
$filter_supplier = isset($_GET['supplier']) ? $_GET['supplier'] : '';

// Ambil data order berdasarkan filter
$data_order = $db->tampil_order_by($filter_month, $filter_year, $filter_supplier);

function formatRupiah($number) {
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
    <p style="text-align: center;">Periode: ' . ($filter_month ?: '-') . '/' . ($filter_year ?: '-') . '</p>
    <p style="text-align: center;">Supplier: ' . ($filter_supplier ?: '-') . '</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Spare Part</th>
                <th>Nomor Part</th>
                <th>Quantity</th>
                <th>Harga</th>
                <th>Supplier</th>
            </tr>
        </thead>
        <tbody>';

// Periksa apakah data tersedia
if ($data_order == '0') {
    $html .= '<tr><td colspan="7">Data Tidak Tersedia!</td></tr>';
} else {
    $no = 1;
    $total_harga = 0;

    foreach ($data_order as $row) {
        $total_harga += $row['total_payment']; // Menghitung total pengeluaran
        $html .= '<tr>
            <td>' . $no++ . '</td>
            <td>' . date('d/m/Y', strtotime($row['arrival_date'])) . '</td>
            <td>' . $row['sparepart_name'] . '</td>
            <td>' . $row['part_number'] . '</td>
            <td>' . $row['quantity'] . '</td>
            <td>' . formatRupiah($row['price']) . '</td>
            <td>' . $row['supplier_name'] . '</td>
        </tr>';
    }

    // Menambahkan baris total pengeluaran
    $html .= '<tr style="font-weight: bold; background-color: #f9f9f9;">
        <td colspan="5" style="text-align: right;">Total Pengeluaran:</td>
        <td colspan="2">' . formatRupiah($total_harga) . '</td>
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
$dompdf->stream('Laporan_Order.pdf', ['Attachment' => false]);
?>
