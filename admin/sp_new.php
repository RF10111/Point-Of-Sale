<!-- header -->
<?php
include("template/header.php");
include("config_query.php");
$db = new database();
$data_sp_new = $db->tampil_sp_new();

$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$data_sp_new = $db->tampil_sp_new($search_query);

function formatRupiah($number) {
    return "Rp " . number_format($number, 0, ',', '.');
}
?>
<!-- /header -->         
            <!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Data </span>Spare Parts</h4>
  <form method="GET" action="">
  <div class="row mb-3">
    <div class="col-md-3">
      <input type="text" name="search" class="form-control" placeholder="Cari Nama/No Spare Parts" value="<?= htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES); ?>">
    </div>
  </div>
</form>
              <!-- Responsive Table -->
              <div class="card">
                <div class="card-header">
                  <div class="card-title">
                    <div class="row">
                      <div class="col-lg-6">
                        <h5>Spare Parts Baru</h5>
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
                          <th>Nama Spare Part</th>
                          <th>Nomor Spare Part</th>
                          <th>Stock</th>
                          <th>Harga Pokok</th>
                          <th>Keterangan</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                     <tbody>
<?php
if ($data_sp_new == '0') {
    echo "<tr><td colspan='7' class='text-center'>Data Tidak Tersedia!</td></tr>";
} else {
    $no = 1;
    foreach ($data_sp_new as $row) {
?>
        <tr>
            <th style="text-align:center;"><?= $no++; ?></th>
            <td class="text-center"><?= $row['sparepart_name']; ?></td>
            <td class="text-center"><?= $row['part_number']; ?></td>
            <td class="text-center"><?= $row['quantity']; ?></td>
            <td class="text-center"><?= formatRupiah($row['harga_pokok']); ?></td>
            <td class="text-center"><?= $row['tempat']; ?></td>
            <td class="text-center">
                <a href="aksi/edit_sp_new.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning"><i class="bx bx-edit"></i> Edit</a>
                <a href="aksi/sp_new_aksi.php?id=<?= $row['id']; ?>&aksi=delete" class="btn btn-sm btn-danger" onclick="return confirm('Apakah anda yakin ingin menghapus data spare part ini?')"><i class="bx bx-trash"></i> Hapus</a>
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