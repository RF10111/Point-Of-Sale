<?php
require '../vendor/autoload.php'; // Autoload Composer
include("config_query.php");

use Dompdf\Dompdf;
use Dompdf\Options;


$db = new Database();

// Ambil parameter filter dari URL
$created_at = isset($_GET['created_at']) ? urldecode($_GET['created_at']) : '';
$registration_no = isset($_GET['registration_no']) ? urldecode($_GET['registration_no']) : '';

// Fungsi untuk format Rupiah
function formatRupiah($number) {
    // Pastikan $number tidak null, jika null ganti dengan 0
    $number = $number ?? 0;
    return "Rp " . number_format($number, 0, ',', '.');
}


// Query untuk mendapatkan detail estimasi berdasarkan filter
$data_estimasi = $db->tampil_estimasi_by_filter($created_at, $registration_no);

// Mulai buffer output
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cetak Estimasi</title>
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
        .text-justify {
            text-align: justify;
        }
        .wide-col {
            width: 20%; /* Adjust as needed */
        }
        .price-col {
            width: 15%; /* Wider for price-related columns */
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Info -->
        <div class="header">
            <div class="header-left">
                <h1>ESTIMASI</h1>
                <p>Jl. Raya Cikaret Gg. H. Toha No. 94</p>
                <p>Cibinong-Bogor</p>
                <p>Telp: 0817-710-618</p>
            </div>
            <div class="header-right">
                <img src="data:image/png;base64,<?= base64_encode(file_get_contents('logo.png')); ?>" alt="Logo Bengkel">
            </div>
        </div>

        <!-- Table Section -->
        <table>
            <thead>
                <tr>
                    <th class="text-center" colspan="6"><strong>ESTIMASI BIAYA PERBAIKAN (ESTIMATE COST)</strong></th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: left; vertical-align: top;">
                        <p><strong>Nama: <?= isset($data_estimasi[0]['customer_name']) ? $data_estimasi[0]['customer_name'] : '-'; ?></strong></p>
                        <p><strong>Alamat:</strong> <?= isset($data_estimasi[0]['address']) ? $data_estimasi[0]['address'] : '-'; ?></p>
                        <p><strong>Telp.: <?= isset($data_estimasi[0]['phone_no']) ? $data_estimasi[0]['phone_no'] : '-'; ?></strong></p>
                    </th>
                    <th colspan="4" style="text-align: left; vertical-align: top;">
                        <p><strong>Tanggal:</strong> <?= isset($data_estimasi[0]['created_at']) ? date('d/m/Y', strtotime($data_estimasi[0]['created_at'])) : '-'; ?></p>
                        <p><strong>No. Polisi/Type:</strong> <?= isset($data_estimasi[0]['registration_no']) ? $data_estimasi[0]['registration_no'] : '-'; ?></p>
                        <p><strong>No. Chassis:</strong> <?= isset($data_estimasi[0]['chassis_no']) ? $data_estimasi[0]['chassis_no'] : '-'; ?></p>
                        <p><strong>No. Engine:</strong> <?= isset($data_estimasi[0]['engine_no']) ? $data_estimasi[0]['engine_no'] : '-'; ?></p>
                        <p><strong>Milage/Km:</strong> <?= isset($data_estimasi[0]['mileage']) ? $data_estimasi[0]['mileage'] : '-'; ?> Km</p>
                    </th>
                </tr>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center wide-col">Name of Spare Part / Labour</th>
                    <th class="text-center">Qty</th>
                    <th class="text-center price-col">Price</th>
                    <th class="text-center price-col">Labour</th>
                    <th class="text-center price-col">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($data_estimasi)) {
                    $no = 1;
                    $total_price = 0;
                    $total_labour = 0;
                    $grand_total = 0;
                    
                    // Tampilkan Spare Parts hanya jika ada data valid
                    $has_sparepart = false;
                    foreach ($data_estimasi as $row) {
                        if (!empty($row['sparepart_name']) && !empty($row['total_harga'])) {
                            $has_sparepart = true;
                            break;
                        }
                    }
                    
                    if ($has_sparepart) {
                        foreach ($data_estimasi as $row) {
                            if (empty($row['sparepart_name']) || empty($row['total_harga'])) {
                                continue;
                            }
                            $total_price += $row['total_harga'];
                            ?>
                            <tr>
                                <td class="text-center" style="border-bottom: none; border-top: none;"> <?= $no++; ?> </td>
                                <td class="text-center" style="border-bottom: none; border-top: none;"> <?= $row['sparepart_name']; ?> </td>
                                <td class="text-center" style="border-bottom: none; border-top: none;"> <?= $row['quantity']; ?> </td>
                                <td class="text-center" style="border-bottom: none; border-top: none;"> <?= formatRupiah($row['total_harga']); ?> </td>
                                <td class="text-center" style="border-bottom: none; border-top: none;"> - </td>
                                <td class="text-center" style="border-bottom: none; border-top: none;"> <?= formatRupiah($row['total_harga']); ?> </td>
                            </tr>
                            <?php
                        }
                        echo '
                            <tr>
                                <td class="text-center" style="border-bottom: none; border-top: none;">&nbsp;</td>
                                <td class="text-center" style="border-bottom: none; border-top: none;">&nbsp;</td>
                                <td class="text-center" style="border-bottom: none; border-top: none;">&nbsp;</td>
                                <td class="text-center" style="border-bottom: none; border-top: none;">&nbsp;</td>
                                <td class="text-center" style="border-bottom: none; border-top: none;">&nbsp;</td>
                                <td class="text-center" style="border-bottom: none; border-top: none;">&nbsp;</td>
                            </tr>';
                    }
                    
                    // Tampilkan Labour hanya jika ada data valid
                    $has_labour = false;
                    foreach ($data_estimasi as $row) {
                        if (!empty($row['labour_name']) && !empty($row['labour_cost'])) {
                            $has_labour = true;
                            break;
                        }
                    }
                    
                    if ($has_labour) {
                        foreach ($data_estimasi as $row) {
                            if (empty($row['labour_name']) || empty($row['labour_cost'])) {
                                continue;
                            }
                            $total_labour += $row['labour_cost'];
                            ?>
                            <tr>
                                <td class="text-center" style="border-bottom: none; border-top: none;">&nbsp;</td>
                                <td class="text-center" style="border-bottom: none; border-top: none;"> <?= $row['labour_name']; ?> </td>
                                <td class="text-center" style="border-bottom: none; border-top: none;"> - </td>
                                <td class="text-center" style="border-bottom: none; border-top: none;"> - </td>
                                <td class="text-center" style="border-bottom: none; border-top: none;"> <?= formatRupiah($row['labour_cost']); ?> </td>
                                <td class="text-center" style="border-bottom: none; border-top: none;"> <?= formatRupiah($row['labour_cost']); ?> </td>
                            </tr>
                            <?php
                        }
                    }
                    
                    // Tampilkan Total Estimasi jika ada data
                    if ($has_sparepart || $has_labour) {
                        ?>
                        <tr>
                            <td colspan="3" rowspan="3" class="text-justify" style="padding: 10px;">
                                <strong>Notes:</strong>
                                <p>Estimasi Biaya Ini Tidak Mengikat, Dalam Arti Apabila
                                Terdapat Perubahan Harga Atau Ada Spare Part Tambahan Yang
                                Tidak Tercantum Saat Estimasi Ini Dibuat, Dengan Demikian
                                Estimasi Ini Akan Berubah.</p>
                            </td>
                            <td colspan="2" class="text-center"><strong>Total Price:</strong></td>
                            <td class="text-center"><strong><?= formatRupiah($total_price); ?></strong></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-center"><strong>Total Labour:</strong></td>
                            <td class="text-center"><strong><?= formatRupiah($total_labour); ?></strong></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-center"><strong>Total Estimasi:</strong></td>
                            <td class="text-center"><strong><?= formatRupiah($total_price + $total_labour); ?></strong></td>
                        </tr>
                        <?php
                    }
                }
                ?>

            </tbody>
        </table>

        <p class="text-right" style="margin-top: 20px;"><strong>Hormat Kami,</strong></p>
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
$dompdf->stream("estimasi_" . str_replace(' ', '_', $registration_no) . ".pdf", ['Attachment' => 0]);
exit();
?>