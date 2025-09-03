<!-- header -->
<?php
include("template/header.php");
include("config_query.php");
$db = new database();

$filter_month = isset($_GET['month']) ? $_GET['month'] : '';
$filter_year = isset($_GET['year']) ? $_GET['year'] : '';
$filter_supplier = isset($_GET['supplier']) ? $_GET['supplier'] : '';
$filter_payment_status = isset($_GET['payment_status']) ? $_GET['payment_status'] : '';

$data_order = $db->tampil_order_by($filter_month, $filter_year, $filter_supplier, $filter_payment_status);

// Ambil daftar supplier
$suppliers = $db->get_all_suppliers();
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
  <div class="row gy-2 align-items-end">

    <!-- Filter Bulan -->
    <div class="col-md-2">
      <label class="form-label">Bulan</label>
      <select name="month" class="form-control">
        <option value="">Pilih Bulan</option>
        <?php for ($m = 1; $m <= 12; $m++): ?>
        <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT); ?>" <?= ($filter_month == str_pad($m, 2, '0', STR_PAD_LEFT)) ? 'selected' : ''; ?>>
          <?= date('F', mktime(0, 0, 0, $m, 1)); ?>
        </option>
        <?php endfor; ?>
      </select>
    </div>

    <!-- Filter Tahun -->
    <div class="col-md-2">
      <label class="form-label">Tahun</label>
      <select name="year" class="form-control">
        <option value="">Pilih Tahun</option>
        <?php
        $current_year = date('Y');
        for ($y = $current_year; $y >= $current_year - 10; $y--): ?>
        <option value="<?= $y; ?>" <?= ($filter_year == $y) ? 'selected' : ''; ?>><?= $y; ?></option>
        <?php endfor; ?>
      </select>
    </div>

    <!-- Filter Supplier -->
    <div class="col-md-2">
      <label class="form-label">Supplier</label>
      <select name="supplier" class="form-control">
        <option value="">Pilih Supplier</option>
        <?php foreach ($suppliers as $supplier): ?>
        <option value="<?= htmlspecialchars($supplier); ?>" <?= ($filter_supplier == $supplier) ? 'selected' : ''; ?>>
          <?= htmlspecialchars($supplier); ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- Filter Status Bayar -->
    <div class="col-md-2">
      <label class="form-label">Status Bayar</label>
      <select name="payment_status" class="form-control">
        <option value="">Pilih Status Bayar</option>
        <option value="Lunas" <?= ($filter_payment_status == 'Lunas') ? 'selected' : ''; ?>>Lunas</option>
        <option value="Belum Lunas" <?= ($filter_payment_status == 'Belum Lunas') ? 'selected' : ''; ?>>Belum Lunas</option>
      </select>
    </div>

    <!-- Tombol Filter dan Reset -->
    <div class="col-md-2">
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
        <a href="?" class="btn btn-secondary w-100">Reset</a>
      </div>
    </div>

    <!-- Tombol Cetak -->
    <div class="col-md-2 text-md-end">
      <a href="cetak_order.php?month=<?= $filter_month; ?>&year=<?= $filter_year; ?>&supplier=<?= urlencode($filter_supplier); ?>&payment_status=<?= urlencode($filter_payment_status); ?>" target="_blank" class="btn btn-danger w-100">
        <i class="bx bx-printer"></i> Cetak
      </a>
    </div>
  </div>
</form>
<br>

  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <h5>Data Pembelian Spare Part</h5>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive text-nowrap">
        <table class="table table-bordered">
          <thead class="text-center">
            <tr>
              <th>No</th>
              <th>Tanggal Terima</th>
              <th>Nama Supplier</th>
              <th>Nama Spare Part</th>
              <th>Nomor Spare Part</th>
              <th>Kategori</th>
              <th>Quantity</th>
              <th>Total Harga</th>
              <th>Harga Pokok</th>
              <th>Total Bayar</th>
              <th>Sisa Bayar</th>
              <th>Status Bayar</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $total_expense = 0; // Inisialisasi total pengeluaran
            $total_expense_all = 0; // Inisialisasi total pengeluaran
            if ($data_order == '0') {
                echo "<tr><td colspan='12' class='text-center'>Data Tidak Tersedia!</td></tr>";
            } else {
                $no = 1;
                foreach ($data_order as $row) {
                    $total_expense += $row['total_payment']; // Menghitung total pengeluaran
                    $total_expense_all += $row['price']; // Menghitung total pengeluaran
            ?>
            <tr>
              <td class="text-center"><?= $no++; ?></td>
              <td class="text-center"><?= date('d/m/Y', strtotime($row['arrival_date'])); ?></td>
              <td class="text-center"><?= $row['supplier_name']; ?></td>
              <td class="text-center"><?= $row['sparepart_name']; ?></td>
              <td class="text-center"><?= $row['part_number']; ?></td>
              <td class="text-center"><?= $row['category']; ?></td>
              <td class="text-center"><?= $row['quantity']; ?></td>
              <td class="text-center"><?= formatRupiah($row['price']); ?></td>
              <td class="text-center"><?= formatRupiah($row['harga_pokok']); ?></td>
              <td class="text-center"><?= formatRupiah($row['total_payment']); ?></td>
              <td class="text-center"><?= formatRupiah($row['remaining_payment']); ?></td>
              <td class="text-center" style="color: <?= $row['payment_status'] == 'Lunas' ? 'green' : 'red'; ?>;">
                <?= $row['payment_status']; ?>
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
      <h5>Total Yang Sudah Dibayar : <span class="text-danger"><?= formatRupiah($total_expense); ?></span></h5>
      <h5>Total Seluruh Pembayaran : <span class="text-danger"><?= formatRupiah($total_expense_all); ?></span></h5>
  </div>
</div>

<!-- footer -->
<?php include("template/footer.php"); ?>
<!-- /footer -->
