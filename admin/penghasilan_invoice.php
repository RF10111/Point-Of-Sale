<?php
include("template/header.php");
include("config_query.php");
$db = new Database();
$filter_month = isset($_GET['month']) ? $_GET['month'] : '';
$filter_year = isset($_GET['year']) ? $_GET['year'] : '';
$data_invoice = $db->tampil_invoice_by_month($filter_month, $filter_year);

function formatRupiah($number) {
    // Pastikan $number tidak null, jika null ganti dengan 0
    $number = $number ?? 0;
    return "Rp " . number_format($number, 0, ',', '.');
}

?>
<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Data </span>Penghasilan</h4>
  
  <!-- Filter Form -->
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
      <div class="col-md-3 text-end">
        <a href="cetak_filter.php?month=<?= $filter_month; ?>&year=<?= $filter_year; ?>" target="_blank" class="btn btn-danger">
          <i class="bx bx-printer"></i> Print Berdasarkan Filter
        </a>
      </div>
    </div>
  </form>

  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <h5>Data Invoice</h5>
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
              <th>Total Spare Part</th>
              <th>Total Labour</th>
              <th>Grand Total</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $all_sparepart = 0;
            $all_labour = 0;
            $total_income = 0;
            if ($data_invoice == '0') {
                echo "<tr><td colspan='7' class='text-center'>Data Tidak Tersedia!</td></tr>";
            } else {
                $no = 1;
                foreach ($data_invoice as $row) {
                    $all_sparepart += $row['total_sparepart']; // Menghitung total penghasilan
                    $all_labour += $row['total_labour']; // Menghitung total penghasilan
                    $total_income += $row['grand_total']; // Menghitung total penghasilan
            ?>
            <tr>
              <td class="text-center"><?= $no++; ?></td>
              <td class="text-center"><?= date('d/m/Y', strtotime($row['created_at'])); ?></td>
              <td class="text-center"><?= $row['invoice_no']; ?></td>
              <td class="text-center"><?= $row['customer_name']; ?></td>
              <td class="text-center"><?= $row['registration_no']; ?></td>
              <td class="text-center"><?= formatRupiah($row['total_sparepart']); ?></td>
              <td class="text-center"><?= formatRupiah($row['total_labour']); ?></td>
              <td class="text-center" style="color: <?= $row['payment_status'] == 'Lunas' ? 'green' : 'red'; ?>;">
                <?= formatRupiah($row['grand_total']); ?>
            </tr>
            <?php
                }
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- Menampilkan Total Penghasilan -->
    <div class="card-footer text-end">
      <h5>Total Penjualan Spare Part : <span style="color: green;"><?= formatRupiah($all_sparepart); ?></span></h5>
      <h5>Total Penghasilan Labour : <span style="color: green;"><?= formatRupiah($all_labour); ?></span></h5>
      <h5>Total Seluruh Penghasilan : <span style="color: green;"><?= formatRupiah($total_income); ?></span></h5>
    </div>
  </div>
</div>

<!-- footer -->
<?php include("template/footer.php"); ?>
<!-- /footer -->
