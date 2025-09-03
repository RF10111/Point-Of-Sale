<!-- header -->
<?php
include("template/header.php");
include("../config_query.php");

$db = new Database();
$id = $_GET['id_cashflow'];

// Fetch cashflow data by ID
$cashflow = $db->get_cashflow_by_id($id);
if (!$cashflow) {
    echo "<script>alert('Data tidak ditemukan');window.location.href='cashflow.php';</script>";
    exit;
}
?>

<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Form</span> Edit cashflow</h4>

    <!-- Basic Layout -->
    <div class="row">
      <div class="col-xxl">
        <div class="card mb-4">
          <div class="card-body">
            <form method="POST" action="cashflow_aksi.php?aksi=edit">
              <input type="hidden" name="id_cashflow" value="<?php echo $cashflow['id_cashflow']; ?>">
              
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="tanggal">Tanggal Transaksi</label>
                <div class="col-sm-10">
                  <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo htmlspecialchars($cashflow['tanggal']); ?>" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="jumlah">Jumlah Transaksi</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?php echo htmlspecialchars($cashflow['jumlah']); ?>" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="ket">Keterangan Transaksi</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="ket" name="ket" required><?php echo htmlspecialchars($cashflow['ket']); ?></textarea>
                </div>
            </div>
        
              <div class="row justify-content-end">
                <div class="col-sm-10">
                  <button type="submit" class="btn btn-primary">Update</button>
                  <button type="button" class="btn btn-secondary" onclick="window.location.href='../cashflow.php';">Cancel</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <!-- / Content -->
</div>

<!-- footer -->
<?php
include("template/footer.php");
?>
<!-- /footer -->
