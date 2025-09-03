<?php
include("template/header.php");
include("config_query.php");
$db = new Database();
$data_estimasi = $db->tampil_estimasi();

function formatRupiah($number) {
    return "Rp " . number_format($number, 0, ',', '.');
}
?>
<!-- Content -->
<div class="container mt-4">
  <!-- Header Info -->
  <div class="d-flex justify-content-between align-items-start mb-4">
    <!-- Left Section: Title and Address -->
    <div>
      <h5 class="fw-bold">ESTIMASI</h5>
      <p class="mb-0">Jl. Raya Cikaret Gg. H. Toha No. 94</p>
      <p class="mb-0">Cibinong-Bogor</p>
      <p class="mb-0">Telp: 0817-710-618</p>
    </div>
    <!-- Right Section: Logo -->
    <div>
      <img src="logo-bengkel.png" alt="Logo Bengkel" style="width: 150px; height: auto;">
    </div>
  </div>

  <!-- Table Section -->
  <table class="table table-bordered mt-4">
    <thead class="text-start">
        <tr>
            <th class="text-center" colspan ="6"><strong>ESTIMASI BIAYA PERBAIKAN (ESTIMATE COST)</strong></th>
        </tr>
      <tr>
        <th colspan="3" style="width: 50%; vertical-align: top;">
          <div>
            <p><strong>Nama:(diisi dengan customer_name)</strong> </p>
            <p><strong>Alamat:</strong> </p>
            <p><strong>Telp. : (diisi dengan phone_no)</strong> </p>
          </div>
        </th>
        <th colspan="3" class="text-start">
            <p><strong>Tanggal:</strong> diisi dengan created_at</p>
          <p><strong>No. Polisi/Type:</strong> diisi dengan registration_no</p>
          <p><strong>No. Chassis:</strong> diisi dengan chassis_no</p>
          <p><strong>No. Engine:</strong> diisi dengan engine_no</p>
          <p><strong>Milage/Km:</strong> diisi dengan mileage</p>
        </th>
      </tr>
      <tr>
        <th class="text-center">No</th>
        <th class="text-center">Name of Spare Part / Labour</th>
        <th class="text-center">Qty</th>
        <th class="text-center" style="width: 150px;">Price</th>
        <th class="text-center" style="width: 150px;">Labour</th>
        <th class="text-center" style="width: 150px;">Jumlah</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($data_estimasi == '0') {
          echo "<tr><td colspan='6' class='text-center'>Data Tidak Tersedia!</td></tr>";
      } else {
          $no = 1;
          $total_price = 0;
          $total_labour = 0;

          // Tampilkan Spare Parts
          foreach ($data_estimasi as $row) {
              $jumlah = $row['total_harga']; // Harga spare part saja
              $total_price += $row['total_harga'];
      ?>
      <tr>
        <td style="text-align:center;"><?= $no++; ?></td>
        <td class="text-center"><?= $row['sparepart_name']; ?></td>
        <td class="text-center"><?= $row['quantity']; ?></td>
        <td class="text-center"><?= formatRupiah($row['total_harga'], 2); ?></td>
        <td class="text-center">-</td>
        <td class="text-center"><?= formatRupiah($jumlah, 2); ?></td>
      </tr>
      <?php
          }

          // Menambahkan 2 baris kosong sebagai jarak antara Spare Parts dan Labour
          echo '<tr><td colspan="6">&nbsp;</td></tr>';

          // Tampilkan Labours
          foreach ($data_estimasi as $row) {
              $jumlah = $row['labour_cost']; // Biaya labour saja
              $total_labour += $row['labour_cost'];
      ?>
      <tr>
        <td style="text-align:center;">&nbsp;</td>
        <td class="text-center"><?= $row['labour_name']; ?></td>
        <td class="text-center">-</td>
        <td class="text-center">-</td>
        <td class="text-center"><?= formatRupiah($row['labour_cost'], 2); ?></td>
        <td class="text-center"><?= formatRupiah($jumlah, 2); ?></td>
      </tr>
      <?php
          }
      ?>
      <!-- Notes and Total Rows -->
      <tr>
        <td colspan="3" rowspan="3" class="align-top border p-2">
          <strong>Notes:</strong>
          <p>Estimasi Biaya Ini Tidak Mengikat, Dalam Arti Apabila
            Terdapat Perubahan Harga Atau Ada Spare Part Tambahan Yang
            Tidak Tercantum Saat Estimasi Ini Dibuat, Dengan Demikian
            Estimasi Ini Akan Berubah.</p>
        </td>
        <td colspan="2" class="text-center"><strong>Total Price:</strong></td>
        <td class="text-center"><strong><?= formatRupiah($total_price, 2); ?></strong></td>
      </tr>
      <tr>
        <td colspan="2" class="text-center"><strong>Total Labour:</strong></td>
        <td class="text-center"><strong><?= formatRupiah($total_labour, 2); ?></strong></td>
      </tr>
      <tr>
        <td colspan="2" class="text-center"><strong>Grand Total:</strong></td>
        <td class="text-center"><strong><?= formatRupiah($total_price + $total_labour, 2); ?></strong></td>
      </tr>
      <?php
      }
      ?>
    </tbody>
  </table>

  <p class="mt-4 text-end"><strong>Hormat Kami,</strong><br>(....................)</p>
</div>
<!-- footer -->
<?php
include("template/footer.php");
?>
<!-- /footer -->
