<?php
include("template/header.php");
include("config_query.php");
$db = new Database();
$filter_month = isset($_GET['month']) ? $_GET['month'] : '';
$filter_year = isset($_GET['year']) ? $_GET['year'] : '';
$data_jual = $db->tampil_jual($filter_month, $filter_year);

function formatRupiah($number) {
    // Pastikan $number tidak null, jika null ganti dengan 0
    $number = $number ?? 0;
    return "Rp " . number_format($number, 0, ',', '.');
}

?>

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
        <a href="cetak_jual.php?month=<?= $filter_month; ?>&year=<?= $filter_year; ?>" target="_blank" class="btn btn-danger">
          <i class="bx bx-printer"></i> Print Berdasarkan Filter
        </a>
      </div>
    </div>
  </form>

  <!-- Data Table -->
  <div class="card">
    <div class="card-header">
      <h5>Daftar Penjualan Spare Parts</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive text-nowrap">
        <table class="table table-bordered">
          <thead class="text-center">
            <tr>
              <th>No</th>
              <th>Tanggal</th>
              <th>Nama Spare Part</th>
              <th>Nama Pembeli</th>
              <th>Quantity</th>
              <th>Total Harga</th>
              <th>Sisa Bayar</th>
              <th>Keterangan</th>
              <th>Keuntungan Bersih</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $total_income_bersih = 0;
            $total_income = 0;
            if ($data_jual == '0') {
                echo "<tr><td colspan='9' class='text-center'>Data Tidak Tersedia!</td></tr>";
            } else {
                $no = 1;
                foreach ($data_jual as $row) {
                    $total_income_bersih += $row['profit']; // Menghitung total penghasilan
                    $total_income += $row['total']; // Menghitung total penghasilan
            ?>
            <tr>
              <td class="text-center"><?= $no++; ?></td>
              <td class="text-center"><?= date('d/m/Y', strtotime($row['tgl_transaksi'])); ?></td>
              <td class="text-center"><?= $row['sparepart_name']; ?></td>
              <td class="text-center"><?= $row['customer_name']; ?></td>
              <td class="text-center"><?= $row['jumlah']; ?></td>
              <td class="text-center"><?= formatRupiah($row['total']); ?></td>
              <td class="text-center"><?= formatRupiah($row['remaining_payment']); ?></td>
              <td class="text-center" style="color: <?= $row['payment_status'] == 'Lunas' ? 'green' : 'red'; ?>;">
                <?= $row['payment_status']; ?>
              </td>
              <td class="text-center"><?= formatRupiah($row['profit']); ?></td>
            </tr>
            <?php
                }
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Total Penghasilan -->
    <div class="card-footer text-end">
      <h5>Total Keuntungan Bersih : <span style="color: green;"><?= formatRupiah($total_income_bersih); ?></span></h5>
      <h5>Total Keuntungan : <span style="color: green;"><?= formatRupiah($total_income); ?></span></h5>

    </div>
  </div>
</div>

<!-- Footer -->
<?php include("template/footer.php"); ?>
