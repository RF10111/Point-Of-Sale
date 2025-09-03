<!-- header -->
<?php
include("template/header.php");
include("config_query.php");
$db = new database();

$filter_month = isset($_GET['month']) ? $_GET['month'] : '';
$filter_year = isset($_GET['year']) ? $_GET['year'] : '';
$data_order = $db->tampil_order_by($filter_month, $filter_year);

function formatRupiah($number) {
    return "Rp " . number_format($number, 0, ',', '.');
}

?>
<!-- /header -->         
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Data </span>Pembelian</h4>

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
            <h5>Daftar Pembelian Spare Parts</h5>
          </div>
          <div class="col-lg-6">
            <div class="float-end">
              <a href="tambah/order.php" class="btn btn-primary">
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
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
                        if($data_order == '0'){
                          echo "<tr><td>Data Tidak Tersedia!</td></tr>";
                        } else{
                          $no = 1;
                          foreach ($data_order as $row){
                        ?>
                        <tr>
                          <th style="text-align:center;"><?= $no++; ?></th>
                          <td class="text-center"><?= date('d/m/Y', strtotime($row ['arrival_date'])); ?></td>
                          <td class="text-center"><?= $row['supplier_name']; ?></td>
                          <td class="text-center"><?= $row['sparepart_name']; ?></td>
                          <td class="text-center"><?= $row['part_number']; ?></td>
                          <td class="text-center"><?= $row['category']; ?></td>
                          <td class="text-center"><?= $row['quantity']; ?></td>
                          <td class="text-center"><?= formatRupiah($row['price']); ?></td>
                          <td class="text-center"><?= formatRupiah($row['harga_pokok']); ?></td>
                          <td class="text-center"><?= formatRupiah($row['total_payment']); ?></td>
                          <td class="text-center"><?= formatRupiah($row['remaining_payment']); ?></td>
                          <td class="text-center" style="
                              color: 
                              <?= 
                                  $row['payment_status'] == 'Lunas' ? 'green' :  // Hijau untuk lunas
                                  ($row['payment_status'] == 'Belum Lunas' ? 'red' : '') // Merah untuk belum lunas
                              ?>;">
                              <?= $row['payment_status']; ?>
                          </td>
                          <td class="text-center">
                            <a href="aksi/edit_order.php?id_order=<?= $row['id_order']; ?>" class="btn btn-sm btn-warning"><i class="bx bx-edit"></i> Edit</a>
                            <a href="aksi/order_aksi.php?id_order=<?= $row['id_order']; ?> &aksi=delete" class="btn btn-sm btn-danger"
                            onclick="return confirm('Apakah anda yakin ingin menghapus data order ini?')"><i class="bx bx-trash"></i> Hapus</a>
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
