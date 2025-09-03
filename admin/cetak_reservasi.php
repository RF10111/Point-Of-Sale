<?php
require '../vendor/autoload.php'; // Autoload Composer
include("config_query.php");

use Dompdf\Dompdf;
use Dompdf\Options;

$db = new Database();

// Ambil parameter filter dari URL
$created_at = isset($_GET['created_at']) ? urldecode($_GET['created_at']) : '';
$mekanik_name = isset($_GET['mekanik_name']) ? urldecode($_GET['mekanik_name']) : '';
$customer = isset($_GET['customer']) ? urldecode($_GET['customer']) : '';

// Query untuk mendapatkan detail reservasi berdasarkan filter
$data_reservasi_detail = $db->tampil_reservasi_detail_filter($created_at, $mekanik_name, $customer);

// Mulai buffer output
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cetak Reservasi</title>
    <style>
        .signature {
        margin-top: 40px;
        }
        
        .signature div {
        width: 45%;
        float: left; /* Menggunakan float untuk menempatkan div di sebelah-sebelahan */
        text-align: center;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        .header-left {
            width: 60%;
            text-align: justify;
        }
        .header-right {
            width: 35%;
            text-align: right;
            position: absolute;
            top: 0;
            right: 0;
        }
        .header-right img {
            max-width: 150px;
            height: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
        }
        .table-header th {
            text-align: center;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Info -->
        <div class="header">
            <div class="header-left">
                <h1>Detail Reservasi</h1>
                <p>Jl. Raya Cikaret Gg. H. Toha No. 94</p>
                <p>Cibinong-Bogor</p>
                <p>Telp: 0817-710-618</p>
            </div>
            <div class="header-right">
                <img src="data:image/png;base64,<?= base64_encode(file_get_contents('logo.png')); ?>" alt="Logo Bengkel">
            </div>
        </div>

        <!-- Detail Info -->
        <p><strong>Tanggal:</strong> <?= date('d/m/Y', strtotime($created_at)); ?></p>
        <p><strong>Mekanik:</strong> <?= htmlspecialchars($mekanik_name); ?></p>
        <p><strong>Customer:</strong> <?= htmlspecialchars($customer); ?></p>

        <!-- Table Section -->
        <table>
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama Sparepart</th>
                    <th class="text-center">Part Number</th>
                    <th class="text-center">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (empty($data_reservasi_detail)) {
                    echo "<tr><td colspan='4' class='text-center'>Data Tidak Tersedia!</td></tr>";
                } else {
                    $no = 1;
                    foreach ($data_reservasi_detail as $row) {
                ?>
                <tr>
                    <td class="text-center"><?= $no++; ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['sparepart_name']); ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['part_number']); ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['jumlah']); ?></td>
                </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>

        <!-- Footer Notes -->
<div class="signature">
        <div>
            <strong>Supervisor,</strong><br><br><br><br><br>
            (................)
        </div>
        <div>
            <strong>Mekanik</strong><br><br><br><br><br>
            (................)
        </div>
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
$options->set('isRemoteEnabled', true);

// Buat instance Dompdf
$dompdf = new Dompdf($options);

// Muat HTML ke Dompdf
$dompdf->loadHtml($html);

// Atur ukuran dan orientasi kertas
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Tampilkan PDF di browser (preview)
$dompdf->stream("detail_reservasi_" . str_replace(' ', '_', $customer) . ".pdf", ['Attachment' => 0]);
exit();
?>
