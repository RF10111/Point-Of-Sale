<?php
require '../vendor/autoload.php'; // Autoload Composer
include("config_query.php");

use Dompdf\Dompdf;
use Dompdf\Options;

$db = new Database();

// Ambil parameter filter dari URL
$invoice_no = isset($_GET['invoice_no']) ? urldecode($_GET['invoice_no']) : '';

// Fungsi untuk format Rupiah
function formatRupiah($number) {
    // Pastikan $number tidak null, jika null ganti dengan 0
    $number = $number ?? 0;
    return "Rp " . number_format($number, 0, ',', '.');
}


// Query untuk mendapatkan detail invoice berdasarkan nomor invoice
$data_invoice = $db->tampil_invoice_by_filter($invoice_no);

// Mulai buffer output
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice Mercedes-Benz</title>
    <style>
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
        }
        .header img {
            max-width: 150px;
            height: auto;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid black;
            padding: 10px;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 10px;
        }
        .header {
            font-weight: bold;
        }
        .details, .items, .description, .totals {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .details td, .items td, .items th, .description td, .totals td {
            border: 1px solid black;
            padding: 5px;
        }
       .items {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        .items td, .items th {
            border: 1px solid black;
            padding: 5px;
        }

        .totals {
            margin-top: 20px;
        }
        .totals td {
            text-align: right;
        }
        .totals .label {
            text-align: left;
        }
        .footer {
            text-align: left;
        }
        .address {
            text-align: left;
            margin-bottom: 10px;
        }
        .items td{
           text-align: center; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
    <!-- Invoice Title -->
    <div style="text-align: left; width: 60%; position: relative;">
        <h1 style="margin: 0; font-size: 24px;">INVOICE</h1>
        <p style="margin: 5px 0; font-size: 14px;">
            Jl. Raya Cikaret Gg. H. Toha No. 94<br>
            Cibinong-Bogor<br>
            Telp: 0817-710-618
        </p>
    </div>
    
    <!-- Logo Section -->
    <div style="text-align: right; width: 40%; position: absolute; top: 0; right: 0;">
        <img src="data:image/png;base64,<?= base64_encode(file_get_contents('logo.png')); ?>" alt="Logo Bengkel" style="max-width: 150px; height: auto;">
    </div>
</div>

        <!-- Vehicle and Customer Details Table -->
        <table class="details" style="width: 100%; table-layout: fixed;">
            <tr>
                <td style="width: 40%; border-left: none; border-top: none;" rowspan="3">
                    <p>Contact : <?= $data_invoice[0]['customer_name'] ?? '-'; ?></p>
                </td>
                <td colspan="3" style="text-align: right;">For reference please quote the following no.</td>
            </tr>
            <tr>
                <td colspan="3">No. <?= $data_invoice[0]['invoice_no'] ?? '-'; ?></td>
            </tr>
            <tr>
                <td>Date <br><?= date('d F Y', strtotime($data_invoice[0]['created_at'] ?? 'now')); ?></td>
                <td>Customer No. <br><?= $data_invoice[0]['phone_no'] ?? '-'; ?></td>
                <td>Page <br>&nbsp;</td>
            </tr>
            <tr>
                <td>Registration No.<br><?= $data_invoice[0]['registration_no'] ?? '-'; ?></td>
                <td>Chassis No.<br><?= $data_invoice[0]['chassis_no'] ?? '-'; ?></td>
                <td>Sales designation <br>&nbsp;</td>
                <td>Date / time received <br> <?= ($data_invoice[0]['received'] == '0000-00-00' || empty($data_invoice[0]['received'])) ? '-' : date('d F Y', strtotime($data_invoice[0]['received']));?></td>
            </tr>
            <tr>
                <td>Mileage / Km<br><?= $data_invoice[0]['mileage'] ?? '-'; ?> Km</td>
                <td>Engine No.<br><?= $data_invoice[0]['engine_no'] ?? '-'; ?></td>
                <td>Account No. <br>&nbsp;</td>
                <td>Received No. <br>&nbsp;</td>
            </tr>
            <tr>
                <td>Routing No. <br>&nbsp;</td>
                <td>Last service date / mileage / Km <br>&nbsp;</td>
                <td>Date of 1st registration <br>&nbsp;</td>
                <td>Deadline <br>
    <?= ($data_invoice[0]['deadline'] == '0000-00-00' || empty($data_invoice[0]['deadline'])) ? '-' : date('d F Y', strtotime($data_invoice[0]['deadline']));?>
</td>

            </tr>
            <tr>
                <td>VIN No.<br><?= $data_invoice[0]['vin_no'] ?? '-'; ?></td>
                <td colspan="3">Description<br><?= $data_invoice[0]['description'] ?? '-'; ?></td>
            </tr>
        </table>

        <table class="items" style="width: 100%; table-layout: fixed;">
    <tr>
        <th style="width: 10%;">Item No</th>
        <th style="width: 50%;">Service and Supplies</th>
        <th style="width: 20%;">DISC</th>
        <th style="width: 20%;">Amount</th>
    </tr>
    <?php 
$no = 1;
$grand_total = 0;

// Tampilkan Spare Parts hanya jika ada data valid

foreach ($data_invoice as $row) { 
    // Skip iterasi jika sparepart_name kosong dan total_harga 0
    if (empty($row['sparepart_name']) && empty($row['total_harga'])) {
        continue;
    }
    ?>
    <tr>
        <td style="border-bottom: none; border-top: none;"><?= $no++; ?></td>
        <td style="border-bottom: none; border-top: none;">
            <?= $row['sparepart_name']; ?>
            <?php if (!empty($row['quantity'])): ?>
                <span style="color: #666; font-size: 12px;"> (Qty: <?= $row['quantity'] ?>)</span>
            <?php endif; ?>
        </td>
        <td style="border-bottom: none; border-top: none; font-size: 14px;"><?= !empty($row['discount']) ? formatRupiah($row['discount']) : '-' ?></td>
        <td style="border-bottom: none; border-top: none; font-size: 14px;"><?= !empty($row['total_harga']) ? formatRupiah($row['total_harga']) : '-' ?></td>
    </tr>
    <?php 
    // Hanya tambahkan ke grand_total jika total_harga ada
    if (!empty($row['total_harga'])) {
        $grand_total += $row['total_harga'];
    }
}

    // Tampilkan Manual Spare Parts
    foreach ($data_invoice as $row) {
        // Skip iterasi jika sparepart_name kosong dan total_harga 0
    if (empty($row['manual_name']) && empty($row['manual_jml'])) {
        continue;
    }
    ?>
    ?>
    <tr>
        <td style="border-bottom: none; border-top: none;"><?= $no++; ?></td>
        <td style="border-bottom: none; border-top: none;"><?= $row['manual_name']; ?></td>
        <td style="border-bottom: none; border-top: none;">-</td>
        <td style="border-bottom: none; border-top: none; font-size: 14px;"><?= !empty($row['manual_jml']) ? formatRupiah($row['manual_jml']) : '-' ?></td>
    </tr>
    <?php 
    if (!empty($row['manual_jml'])) {
        $grand_total += $row['manual_jml'];
    }
    }

    echo '<tr>
                <td style="border-bottom: none; border-top: none;">&nbsp;</td>
                <td style="border-bottom: none; border-top: none;">&nbsp;</td>
                <td style="border-bottom: none; border-top: none;">&nbsp;</td>
                <td style="border-bottom: none; border-top: none;">&nbsp;</td>
            </tr>';
    // Tambahkan Labour
    foreach ($data_invoice as $invoice) {
        // Skip iterasi jika sparepart_name kosong dan total_harga 0
    if (empty($invoice['labour_name']) && empty($invoice['labour_cost'])) {
        continue;
    }
    ?>
    <tr>
        <td style="border-bottom: none; border-top: none;"></td>
        <td style="border-bottom: none; border-top: none;"><?= $invoice['labour_name'] ?? ' '; ?></td>
        <td style="border-bottom: none; border-top: none;">-</td>
        <td style="border-bottom: none; border-top: none; font-size: 14px;"><?= !empty($invoice['labour_cost']) ? formatRupiah($invoice['labour_cost']) : '-'; ?></td>
    </tr>
    <?php 
        $grand_total += $invoice['labour_cost']; 
    } 
    ?>
    <tr>
        <td style="border-bottom: none; border-top: none;">&nbsp;</td>
        <td style="border-bottom: none; border-top: none;">&nbsp;</td>
        <td style="border-bottom: none; border-top: none;">&nbsp;</td>
        <td style="border-bottom: none; border-top: none;">&nbsp;</td>
    </tr>
    <!-- Baris Deskripsi dan Total -->
    <tr>
        <td colspan="2" rowspan="2" style="text-align: left;">
            <p><strong style="font-size: 14px;">Description</strong> <br>
            *Garansi perbaikan adalah 1(satu) bulan sejak tanggal Invoice<br>
            (yang mana terlebih dahulu) apabila masih ada kekurangan<br>
            tentang pelayanan kami mohon hubungi petugas Customer Service.<br>
            <strong>SOLD ITEMS CANNOT BE RETURNED</strong><br><br>
            Mercedes-Benz Genuine Parts Quality & Value</p>
        </td>
        <td class="label">TOTAL</td>
        <td style="font-size: 14px;"><?= formatRupiah($grand_total); ?></td>
    </tr>
    <tr>
        <td class="label">GRAND TOTAL</td>
        <td style="color: <?= ($data_invoice[0]['payment_status'] ?? 'Belum Lunas') == 'Lunas' ? 'black' : 'red'; ?>; font-size: 14px;">
            <?= formatRupiah($grand_total); ?>
        </td>
    </tr>
</table>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Putih :</strong> Customer - <strong>Kuning :</strong> File - <strong>Merah :</strong> Kasir<br>
            <strong>Rek. BCA 552 027 7163</strong> a/n <strong>WAHYU WIBIKSANA</strong></p>
            <p style="text-align: right;"><strong>Hormat kami,</strong></p>
        </div>
    </div>
</body>
</html>

<?php
// Ambil konten yang di-buffer
$html = ob_get_clean();

// Konfigurasi Dompdf
$options = new Options();
$options->set('defaultFont', 'Arial');
$options->set('isHtml5ParserEnabled', true);

// Buat instance Dompdf
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Tampilkan PDF
$dompdf->stream("invoice_" . str_replace(' ', '_', $invoice_no) . ".pdf", ['Attachment' => 0]);
exit();
?>