<?php
include("template/header.php");
include("../config_query.php");

$db = new database();
$id = $_GET['id'];
$used = $db->tampil_sp_used(); // Ambil data supplier
// Fetch order data by ID
$used = $db->get_order_by_id_used($id);
if (!$used) {
    echo "<script>alert('Data tidak ditemukan');window.location.href='../sp_used.php';</script>";
    exit;
}
?>
<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Form</span> Edit Spare Parts Used</h4>

    <div class="row">
      <div class="col-xxl">
        <div class="card mb-4">
          <div class="card-body">
            <form action="../aksi/sp_used_aksi.php?aksi=edit" method="POST">
                <input type="hidden" name="id" value="<?php echo $used['id']; ?>">
              <!-- Nama Spare Part -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="sparepart_name">Nama Spare Part</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="sparepart_name" name="sparepart_name" value="<?php echo $used['sparepart_name'];?>" readonly>
                </div>
              </div>
              <!-- No Spare Part -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="part_number">No Spare Part</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="part_number" name="part_number" value="<?php echo $used['part_number'];?>">
                </div>
              </div>
              <!-- Quantity -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="quantity">Quantity</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="quantity" name="quantity" value=<?php echo $used['quantity'];?> readonly>
                </div>
              </div>
              <!-- Harga Pokok -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="unit_price">Harga Pokok</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="unit_price" name="unit_price" value=<?php echo ceil($used['harga_pokok']);?>>
                </div>
              </div>
              <!-- Jumlah Bayar -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="tempat">Keterangan</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="tempat" name="tempat" value="<?php echo $used['tempat'];?>">
                </div>
              </div>
              <!-- Submit and Cancel Buttons -->
              <div class="row justify-content-end">
                <div class="col-sm-10">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <button type="button" class="btn btn-secondary" onclick="window.location.href='../sp_used.php';">Cancel</button>
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
<!-- / Footer -->