<!-- header -->
<?php
include("template/header.php");
include("config_query.php");
$db = new database();
$data_artikel = $db->tampil_data();
?>
<!-- /header -->         
            <!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Data </span>Services</h4>
              <!-- Responsive Table -->
              <div class="card">
                <div class="card-header">
                  <div class="card-title">
                    <div class="row">
                      <div class="col-lg-6">
                        <h5>Data Services</h5>
                      </div>
                      <div class="col-lg-6">
                        <div class="float-end">
                          <a href="tambah_data.php" class="btn btn-primary">
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
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>No Invoice</th>
                          <th>Customer</th>
                          <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <td>1</td>
                        <td>B561722</td>
                        <td>Rifqi</td>
                        <td>Lunas</td>
                        <td class="text-center">
                            <a href="edit.php?id=<?= $row['id_artikel']; ?>" class="btn btn-sm btn-warning">Ubah</a>
                            <a href="proses_aksi.php?id=<?= $row['id_artikel']; ?> &aksi=delete" class="btn btn-sm btn-danger"
                            onclick="return confirm('Apakah anda yakin ingin menghapus artikel ini?')">Hapus</a>
                          </td>
                      </tbody>
                  </div>
                </div>
              </div>
            </div>
<!-- footer -->
<?php
include("template/footer.php");
?>
<!-- /footer -->