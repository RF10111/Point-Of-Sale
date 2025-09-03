<!-- header -->
<?php
include("template/header.php");
include("config_query.php");
$db = new database();
$data_mekanik = $db->tampil_mekanik();
?>
<!-- /header -->         
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Data </span>Mekanik</h4>
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <div class="row">
          <div class="col-lg-6">
            <h5>Daftar Mekanik</h5>
          </div>
          <div class="col-lg-6">
            <div class="float-end">
              <a href="tambah/mekanik.php" class="btn btn-primary">
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
              <th>Nama Mekanik</th>
              <th>Alamat</th>
              <th>No Telphone</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if($data_mekanik == '0'){
                          echo "<tr><td>Data Tidak Tersedia!</td></tr>";
                        } else{
                          $no = 1;
                          foreach ($data_mekanik as $row){
                        ?>
                        <tr>
                          <th style="text-align:center;"><?= $no++; ?></th>
                          <td class="text-center"><?= $row ['name']; ?></td>
                          <td class="text-center"><?= $row['alamat']; ?></td>
                          <td class="text-center"><?= $row['phone_no']; ?></td>
                          <td class="text-center">
                            <a href="aksi/edit_mekanik.php?id_mekanik=<?= $row['id_mekanik']; ?>" class="btn btn-sm btn-warning"><i class="bx bx-edit"></i> Edit</a>
                            <a href="aksi/mekanik_aksi.php?id_mekanik=<?= $row['id_mekanik']; ?> &aksi=delete" class="btn btn-sm btn-danger"
                            onclick="return confirm('Apakah anda yakin ingin menghapus data mekanik ini?')"><i class="bx bx-trash"></i> Hapus</a>
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
