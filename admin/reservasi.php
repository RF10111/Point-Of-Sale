<?php
include("template/header.php");
include("config_query.php");
$db = new Database();
$filter_month = isset($_GET['month']) ? $_GET['month'] : '';
$filter_year = isset($_GET['year']) ? $_GET['year'] : '';
$data_reservasi = $db->tampil_reservasi($filter_month, $filter_year);
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
    </div>
  </form>

  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <div class="row">
          <div class="col-lg-6">
            <h5>Data Reservasi</h5>
          </div>
          <div class="col-lg-6">
            <div class="float-end">
              <a href="tambah/reservasi.php" class="btn btn-primary">
                <i class="bx bx-plus"></i>
                Tambah Data
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
              <th>Plat Nomor</th>
              <th>Nama Mekanik</th>
              <th>Detail</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($data_reservasi == '0') {
                echo "<tr><td colspan='7' class='text-center'>Data Tidak Tersedia!</td></tr>";
            } else {
                $grouped_data = [];
                foreach ($data_reservasi as $row) {
                    $key = $row['customer'] . '_' . $row['created_at'];
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
              <td class="text-center"><?= $first_row['customer']; ?></td>
              <td class="text-center"><?= $first_row['mekanik_name']; ?></td>
              <td class="text-center">
                <!-- Modified print link with URL encoding -->
                <a href="aksi/reservasi_aksi.php?aksi=delete&created_at=<?= urlencode($first_row['created_at']); ?>&mekanik_name=<?= urlencode($first_row['mekanik_name']); ?>&customer=<?= urlencode($first_row['customer']); ?>" target="_blank" class="btn btn-sm btn-danger">
                  <i class="bx bx-trash"></i> Hapus
                </a>
                <button class="btn btn-sm btn-primary" onclick="showDetails('<?= $key; ?>')">Detail</button>
                <a href="cetak_reservasi.php?created_at=<?= urlencode($first_row['created_at']); ?>&mekanik_name=<?= urlencode($first_row['mekanik_name']); ?>&customer=<?= urlencode($first_row['customer']); ?>" target="_blank" class="btn btn-sm btn-danger">
                  <i class="bx bx-printer"></i> Print
                </a>
              </td>
            </tr>
            <!-- Hidden details row -->
            <tr id="details-<?= $key; ?>" style="display: none;">
              <td colspan="7">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Kategori Sparepart</th>
                      <th>Nama Sparepart</th>
                      <th>Part Number</th>
                      <th>Jumlah</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $detail_no = 1;
                    foreach ($rows as $detail_row) {
                    ?>
                    <tr>
                      <td><?= $detail_no++; ?></td>
                      <td><?= $detail_row['category']; ?></td>
                      <td><?= $detail_row['sparepart_name']; ?></td>
                      <td><?= $detail_row['part_number']; ?></td>
                      <td><?= $detail_row['jumlah']; ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
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