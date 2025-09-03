<?php
require '../vendor/autoload.php'; // Autoload Composer
include("config_query.php");

use Dompdf\Dompdf;
use Dompdf\Options;

$db = new Database();

// Ambil parameter filter dari URL
$mileage = isset($_GET['mileage']) ? urldecode($_GET['mileage']) : '';
$registration_no = isset($_GET['registration_no']) ? urldecode($_GET['registration_no']) : '';
$created_at = isset($_GET['created_at']) ? urldecode($_GET['created_at']) : '';

// Query untuk mendapatkan data Work Order berdasarkan filter
$data_work_order = $db->get_work_order_by_filter($mileage, $registration_no, $created_at);

// Jika tidak ada data, tampilkan pesan error atau redirect
if (empty($data_work_order)) {
    die("Data tidak ditemukan.");
}

// Mulai buffer output
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Work Order</title>
    <style>
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
            border: none;
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
        .text-justify {
            text-align: justify;
        }
        .signature {
        margin-top: 50px;
        }
        
        .signature div {
        width: 45%;
        float: left; /* Menggunakan float untuk menempatkan div di sebelah-sebelahan */
        text-align: center;
        }

        .notes {
            border: 1px solid black;
            padding: 10px;
            margin-top: 20px;
            text-align: justify;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Info -->
        <div class="header">
            <div class="header-left">
                <h1>WORK ORDER</h1>
                <p>Jl. Raya Cikaret Gg. H. Toha No. 94</p>
                <p>Cibinong-Bogor</p>
                <p>Telp: 0817-710-618</p>
            </div>
            <div class="header-right">
                <img src="data:image/png;base64,<?= base64_encode(file_get_contents('logo.png')); ?>" alt="Logo Bengkel">
            </div>
        </div>

        <!-- Detail Info -->
        <table>
            <tr>
                <td><strong>No. Pol.</strong></td>
                <td>: <?= $data_work_order[0]['registration_no'] ?? '-'; ?></td>
            </tr>
            <tr>
                <td><strong>Pemilik</strong></td>
                <td>: <?= $data_work_order[0]['customer_name'] ?? '-'; ?></td>
            </tr>
            <tr>
                <td><strong>No. Rangka</strong></td>
                <td>: <?= $data_work_order[0]['chassis_no'] ?? '-'; ?></td>
            </tr>
            <tr>
                <td><strong>No. Mesin</strong></td>
                <td>: <?= $data_work_order[0]['engine_no'] ?? '-'; ?></td>
            </tr>
            <tr>
                <td><strong>Milage/Km.</strong></td>
                <td>: <?= $data_work_order[0]['mileage'] ?? '-'; ?> Km</td>
            </tr>
            <tr>
                <td><strong>Tanggal</strong></td>
                <td>: <?= ($data_work_order[0]['created_at'] == '0000-00-00' || empty($data_work_order[0]['created_at'])) ? '-' : date('d F Y', strtotime($data_work_order[0]['created_at']));?></td>
            </tr>
        </table>

        <!-- Job List -->
        <table style="margin-top: 20px;">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Job Order</th>
                    <th class="text-center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (empty($data_work_order)) {
                    echo "<tr><td colspan='4' class='text-center'>Data Tidak Tersedia!</td></tr>";
                } else {
                    $no = 1;
                    foreach ($data_work_order as $row) {
                ?>
                <tr>
                    <td class="text-center"><?= $no++; ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['labour_name']); ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['labour_ket']); ?></td>
                </tr>
                <?php
                    }
                }
                ?>
                <tr>
                        <td style="border-bottom: none; border-top: none;">&nbsp;</td>
                        <td style="border-bottom: none; border-top: none;">&nbsp;</td>
                        <td style="border-bottom: none; border-top: none;">&nbsp;</td>
                        <td style="border-bottom: none; border-top: none;">&nbsp;</td>
                </tr>
                <tr>
                        <td style="border-bottom: none; border-top: none;">&nbsp;</td>
                        <td style="border-bottom: none; border-top: none;">&nbsp;</td>
                        <td style="border-bottom: none; border-top: none;">&nbsp;</td>
                        <td style="border-bottom: none; border-top: none;">&nbsp;</td>
                </tr>
                
            </tbody>
        </table>
        <!-- Notes -->
        <div class="notes">
            <strong>Keterangan:</strong><br>
            Semua pekerjaan dan pergantian suku cadang yang tertulis pada perintah kerja ini telah disetujui oleh pemilik. Kami tidak bertanggung jawab atas kehilangan, kerusakan kendaraan atau benda-benda lain yang ada di kendaraan yang diakibatkan oleh sebab-sebab diluar pengawasan kami.
        </div>

        <!-- Tanda Tangan -->
        <div class="signature">
            <div>
                <strong>Diterima Oleh</strong><br><br><br><br>(................................)
            </div>
            <div>
                <strong>Diserahkan Oleh</strong><br><br><br><br>(................................)
            </div>
        </div>
    </div>
</body>
</html>
<?php
$html = ob_get_clean();

$options = new Options();
$options->set('defaultFont', 'Arial');
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("work_order_" . str_replace(' ', '_', $registration_no) . ".pdf", ['Attachment' => 0]);
exit;
?>