<?php
include("template/header.php");
include("config_query.php");
$db = new Database();
$data_invoice = $db->tampil_invoice();

$filter_month = isset($_GET['month']) ? $_GET['month'] : '';
$filter_year = isset($_GET['year']) ? $_GET['year'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$data_invoice = $db->tampil_invoice($filter_month, $filter_year, $search_query);

function formatRupiah($number) {
    // Pastikan $number tidak null, jika null ganti dengan 0
    $number = $number ?? 0;
    return "Rp " . number_format($number, 0, ',', '.');
}

?>
<!-- Content -->
 
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Data </span>Administrasi</h4>
  <!-- Responsive Table -->
   <form method="GET" action="">
  <div class="row mb-3">
    <div class="col-md-3">
      <select name="month" class="form-control">
        <option value="">Pilih Bulan</option>
        <?php for ($m = 1; $m <= 12; $m++): ?>
        <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT); ?>" <?= ($filter_month == str_pad($m, 2, '0', STR_PAD_LEFT)) ? 'selected' : ''; ?>>
          <?= date('F', mktime(0, 0, 0, $m, 1)); ?>
        </option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="col-md-3">
      <select name="year" class="form-control">
        <option value="">Pilih Tahun</option>
        <?php
        $current_year = date('Y');
        for ($y = $current_year; $y >= $current_year - 10; $y--): ?>
        <option value="<?= $y; ?>" <?= ($filter_year == $y) ? 'selected' : ''; ?>><?= $y; ?></option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="col-md-3">
      <button type="submit" class="btn btn-primary">Filter</button>
      <a href="?" class="btn btn-secondary">Reset</a>
    </div>
    <div class="col-md-3">
      <input type="text" name="search" class="form-control" placeholder="Cari Nama/Plat No/Chassis" value="<?= htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES); ?>">
    </div>
  </div>
</form>


  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <div class="row">
          <div class="col-lg-6">
            <h5>Data Invoice</h5>
          </div>
          <div class="col-lg-6">
            <div class="float-end">
              <a href="tambah/invoice.php" class="btn btn-primary">
                <i class="bx bx-plus"></i>
                Tambah Invoice
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive text-nowrap">
        <table class="table table-bordered">
          <thead class="text-center">
            <tr>
              <th>No</th>
              <th>Tanggal</th>
              <th>No Invoice</th>
              <th>Nama Customer</th>
              <th>Registration No</th>
              <th>Chassis No</th>
              <th>Grand Total</th>
              <th>Sisa Bayar</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <?php
            if ($data_invoice == '0') {
                echo "<tr><td colspan='6' class='text-center'>Data Tidak Tersedia!</td></tr>";
            } else {
                $grouped_data = [];
                foreach ($data_invoice as $row) {
                    $key = $row['invoice_no'] . '_' . $row['created_at'];
                    $grouped_data[$key][] = $row;
                }

                $no = 1;
                foreach ($grouped_data as $key => $rows) {
                    // Ambil data pertama dalam grup untuk ditampilkan
                    $first_row = $rows[0];
            ?>
            <tr>
              <th style="text-align:center;"><?= $no++; ?></th>
              <td class="text-center"><?= date('d/m/Y', strtotime($first_row['created_at'])); ?></td>
              <td class="text-center"><?= $first_row['invoice_no']; ?></td>
              <td class="text-center"><?= $first_row['customer_name']; ?></td>
              <td class="text-center"><?= $first_row['registration_no']; ?></td>
	      <td class="text-center"><?= $first_row['chassis_no']; ?></td>
              <td class="text-center" style="
                              color: 
                              <?= 
                                  $first_row['payment_status'] == 'Lunas' ? 'green' :  // Hijau untuk lunas
                                  ($first_row['payment_status'] == 'Belum Lunas' ? 'red' : '') // Merah untuk belum lunas
                                  ?>;">
                              <?= formatRupiah($first_row['grand_total']); ?>
              </td>
              <td class="text-center"><?= formatRupiah($first_row['remaining_payment'], 2); ?></td>
              
              <td class="text-center">
                <a href="aksi/invoice_aksi.php?aksi=delete&created_at=<?= urlencode($first_row['created_at']); ?>&invoice_no=<?= urlencode($first_row['invoice_no']); ?>"
                  class="btn btn-sm btn-danger"
                  onclick="return confirm('Apakah Anda yakin ingin menghapus invoice ini?')">
                  <i class="bx bx-trash"></i> Hapus
                </a>
                <a href="aksi/edit_invoice.php?invoice_no=<?= urlencode($first_row['invoice_no']); ?>" 
                  class="btn btn-sm btn-warning">
                  <i class="bx bx-edit"></i> Edit
                </a>
                <button class="btn btn-sm btn-primary" onclick="showDetails('<?= $key; ?>')">Detail</button>
                <a href="cetak_invoice.php?invoice_no=<?= urlencode($first_row['invoice_no']); ?>" target="_blank" class="btn btn-sm btn-danger">
                  <i class="bx bx-printer"></i> Print
              </a>
              </td>
            </tr>
            <!-- Hidden details row -->
            <tr id="details-<?= $key; ?>" style="display: none;">
              <td colspan="6">
                <!-- Tabel Spare Part -->
                <?php
                // Filter rows to only include those with non-empty sparepart_name
                $filtered_rows = array_filter($rows, function($row) {
                    return !empty($row['sparepart_name']);
                });

                if (!empty($filtered_rows)) { ?>
                <table class="table table-bordered mb-3">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Sparepart Name</th>
                      <th>Part Number</th>
                      <th>Quantity</th>
                      <th>Harga Jual</th>
                      <th>Total Harga</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    <?php
                    $total_stock = 0;
                    $detail_no = 1;
                    foreach ($filtered_rows as $detail_row) {
                      $total_stock += $detail_row['total_harga'];
                    ?>
                    <tr>
                      <td><?= $detail_no++; ?></td>
                      <td><?= $detail_row['sparepart_name']; ?></td>
                      <td><?= htmlspecialchars($detail_row['part_number']); ?></td>
                      <td><?= $detail_row['quantity']; ?></td>
                      <td><?= formatRupiah($detail_row['harga_jual']); ?></td>
                      <td><?= formatRupiah($detail_row['total_harga']); ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                      <td colspan="5" class="text-end"><strong>Total Sparepart:</strong></td>
                      <td><strong><?= formatRupiah($total_stock); ?></strong></td>
                    </tr>
                  </tbody>
                </table>
                <?php } ?>

                
                <?php
                // Filter rows to only include those with non-empty manual_name
                $filtered_manual_rows = array_filter($rows, function($row) {
                    return !empty($row['manual_name']);
                });

                if (!empty($filtered_manual_rows)) { ?>
                <!-- Tabel sparepart manual -->
                <table class="table table-bordered mb-3">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Sparepart Name(bukan stock)</th>
                      <th>Harga Sparepart</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    <?php
                    $total_manual = 0;
                    $manual_no = 1;
                    foreach ($filtered_manual_rows as $manual_row) {
                      $total_manual += $manual_row['manual_jml'];
                    ?>
                    <tr>
                      <td><?= $manual_no++; ?></td>
                      <td><?= $manual_row['manual_name']; ?></td>
                      <td><?= formatRupiah($manual_row['manual_jml']); ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                      <td colspan="2" class="text-end"><strong>Total Sparepart:</strong></td>
                      <td><strong><?= formatRupiah($total_manual); ?></strong></td>
                    </tr>
                  </tbody>
                </table>
                <?php } ?>

                
                <!-- Tabel Labour -->
                <?php
                // Filter rows to only include those with non-empty labour_name
                $filtered_labour_rows = array_filter($rows, function($row) {
                    return !empty($row['labour_name']);
                });

                if (!empty($filtered_labour_rows)) { ?>
                <!-- Tabel Labour -->
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Labour Name</th>
                      <th>Labour Cost</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    <?php
                    $labour_no = 1;
                    foreach ($filtered_labour_rows as $labour_row) {
                    ?>
                    <tr>
                      <td><?= $labour_no++; ?></td>
                      <td><?= $labour_row['labour_name']; ?></td>
                      <td><?= formatRupiah($labour_row['labour_cost']); ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                      <td colspan="2" class="text-end"><strong>Total Labour:</strong></td>
                      <td><strong><?= formatRupiah($first_row['total_labour']); ?></strong></td>
                    </tr>
                    <tr>
                      <td colspan="2" class="text-end"><strong>Grand Total:</strong></td>
                      <td><strong><?= formatRupiah($first_row['grand_total']); ?></strong></td>
                    </tr>
                  </tbody>
                </table>
                <?php } ?>
              </td>
            </tr>
            <?php
                }
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- footer -->
<?php
include("template/footer.php");
?>
<!-- /footer -->

<!-- JavaScript for showing/hiding details -->
<script>
function showDetails(groupKey) {
    const detailsRow = document.getElementById('details-' + groupKey);
    if (detailsRow.style.display === 'none') {
        detailsRow.style.display = 'table-row';
    } else {
        detailsRow.style.display = 'none';
    }
}
</script>
<!-- belum selesai -->