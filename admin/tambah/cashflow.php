<?php
// Tambahkan include untuk file konfigurasi
include('../config_query.php');
$db = new database(); // Pastikan class Database tersedia dan sudah di-instantiate
?>

<!-- Header -->
<?php
include("template/header.php");
?>
<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Form</span> Tambah Cashflow</h4>

    <div class="row">
      <div class="col-xxl">
        <div class="card mb-4">
          <div class="card-body">
            <form action="../aksi/cashflow_aksi.php?aksi=add" method="POST">
              <!-- Tanggal Datang -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="arrival_date">Tanggal Transaksi</label>
                <div class="col-sm-10">
                  <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                </div>
              </div>
              <!-- Harga -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="jumlah">Jumlah Transaksi</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="Jumlah Transaksi" required>
                </div>
              </div>
              <!-- Keterangan -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="ket">Keterangan Transaksi</label>
                <div class="col-sm-10">
                  <textarea class="form-control" id="ket" name="ket" placeholder="Keterangan Pengeluaran" required></textarea>
                </div>
              </div>
              <!-- Submit and Cancel Buttons -->
              <div class="row justify-content-end">
                <div class="col-sm-10">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <button type="button" class="btn btn-secondary" onclick="window.location.href='../cashflow.php';">Cancel</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- / Content -->
<!-- Footer -->
<?php
include("template/footer.php");
?>