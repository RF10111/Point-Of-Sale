<!-- header -->
<?php
include("template/header.php");
include("config_query.php");
$db = new database();

$filter_day = isset($_GET['day']) ? $_GET['day'] : '';
$filter_month = isset($_GET['month']) ? $_GET['month'] : '';
$filter_year = isset($_GET['year']) ? $_GET['year'] : '';
$data_cashflow = $db->tampil_cashflow($filter_day, $filter_month, $filter_year);

function formatRupiah($number) {
    // Pastikan $number tidak null, jika null ganti dengan 0
    $number = $number ?? 0;
    return "Rp " . number_format($number, 0, ',', '.');
}

?>
<!-- /header -->         
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Data </span>Pengeluaran</h4>

  <form method="GET" action="">
    <div class="row mb-3">
      <div class="col-md-2">
        <select name="day" class="form-control">
          <option value="">Pilih Hari</option>
          <?php for ($d = 1; $d <= 31; $d++): ?>
          <option value="<?= str_pad($d, 2, '0', STR_PAD_LEFT); ?>" <?= ($filter_day == str_pad($d, 2, '0', STR_PAD_LEFT)) ? 'selected' : ''; ?>>
            <?= $d; ?>
          </option>
          <?php endfor; ?>
        </select>
      </div>
      <div class="col-md-2">
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
      <div class="col-md-2 d-flex align-items-center">
  <button type="submit" class="btn btn-primary me-1">Filter</button>
  <a href="?" class="btn btn-secondary">Reset</a>
</div>

      <div class="col-md-3 text-end">
         <a href="cetak_cashflow.php?day=<?= $filter_day; ?>&month=<?= $filter_month; ?>&year=<?= $filter_year; ?>" target="_blank" class="btn btn-danger">
          <i class="bx bx-printer"></i> Print Berdasarkan Filter
        </a>
      </div>
    </div>
  </form>

  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <div class="row">
          <div class="col-lg-6">
            <h5>Data Pengeluaran Cashflow</h5>
          </div>
          <div class="col-lg-6">
            <div class="float-end">
              <a href="tambah/cashflow.php" class="btn btn-primary">
                <i class="bx bx-plus"></i> Tambah Data
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
              <th>Tanggal Transaksi</th>
              <th>Jumlah Transaksi</th>
              <th>Keterangan Transaksi</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $total_expense_all = 0; // Inisialisasi total pengeluaran
            if ($data_cashflow == '0') {
                echo "<tr><td colspan='12' class='text-center'>Data Tidak Tersedia!</td></tr>";
            } else {
                $no = 1;
                foreach ($data_cashflow as $row) {
                    $total_expense_all += $row['jumlah']; // Menghitung total pengeluaran
            ?>
            <tr>
              <td class="text-center"><?= $no++; ?></td>
              <td class="text-center"><?= date('d/m/Y', strtotime($row['tanggal'])); ?></td>
              <td class="text-center"><?= formatRupiah($row['jumlah']); ?></td>
              <td class="text-center"><?= $row['ket']; ?></td>
              <td class="text-center">
                <a href="aksi/edit_cashflow.php?id_cashflow=<?= $row['id_cashflow']; ?>" class="btn btn-sm btn-warning"><i class="bx bx-edit"></i> Edit</a>
                <a href="aksi/cashflow_aksi.php?id_cashflow=<?= $row['id_cashflow']; ?> &aksi=delete" class="btn btn-sm btn-danger"
                onclick="return confirm('Apakah anda yakin ingin menghapus data cashflow ini?')"><i class="bx bx-trash"></i> Hapus</a>
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
    <!-- Menampilkan Total Pengeluaran -->
    <div class="card-footer text-end">
      <h5>Total Cashflow : <span class="text-danger"><?= formatRupiah($total_expense_all); ?></span></h5>
  </div>
</div>

<!-- footer -->
<?php include("template/footer.php"); ?>
<!-- /footer -->
