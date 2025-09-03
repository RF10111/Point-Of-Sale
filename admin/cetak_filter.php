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

// Ambil data invoice berdasarkan filter
$data_invoice = $db->tampil_invoice_by_month($filter_month, $filter_year);

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
    <title>Laporan Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Laporan Invoice</h1>
    <p style="text-align: center;">Periode: ' . ($filter_month ?: '-') . '/' . ($filter_year ?: '-') . '</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>No Invoice</th>
                <th>Nama Customer</th>
                <th>Registration No</th>
                <th>Grand Total</th>
            </tr>
        </thead>
        <tbody>';

if ($data_invoice == '0') {
    $html .= '<tr><td colspan="6">Data Tidak Tersedia!</td></tr>';
} else {
    $no = 1;
    $total_income = 0;

    foreach ($data_invoice as $row) {
        $total_income += $row['grand_total'];
        $html .= '<tr>
            <td>' . $no++ . '</td>
            <td>' . date('d/m/Y', strtotime($row['created_at'])) . '</td>
            <td>' . $row['invoice_no'] . '</td>
            <td>' . $row['customer_name'] . '</td>
            <td>' . $row['registration_no'] . '</td>
            <td style="color: ' . ($row['payment_status'] == 'Lunas' ? 'black' : 'red') . ';">' . formatRupiah($row['grand_total']) . '</td>
        </tr>';
    }

    // Total penghasilan
    $html .= '<tr style="font-weight: bold; background-color: #f9f9f9;">
        <td colspan="5">Total Penghasilan:</td>
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
$dompdf->stream('Laporan_Invoice.pdf', ['Attachment' => false]);
?>
