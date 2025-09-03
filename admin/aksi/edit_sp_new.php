<?php
include("template/header.php");
include("../config_query.php");

$db = new database();
$id = $_GET['id'];
$new = $db->tampil_sp_new(); // Ambil data supplier
// Fetch order data by ID
$new = $db->get_order_by_id_new($id);
if (!$new) {
    echo "<script>alert('Data tidak ditemukan');window.location.href='../sp_new.php';</script>";
    exit;
}
?>
<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Form</span> Edit Spare Parts Baru</h4>

    <div class="row">
      <div class="col-xxl">
        <div class="card mb-4">
          <div class="card-body">
            <form action="../aksi/sp_new_aksi.php?aksi=edit" method="POST">
                <input type="hidden" name="id" value="<?php echo $new['id']; ?>">
              <!-- Nama Spare Part -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="sparepart_name">Nama Spare Part</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="sparepart_name" name="sparepart_name" value="<?php echo $new['sparepart_name'];?>">
                </div>
              </div>
              <!-- No Spare Part -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="part_number">No Spare Part</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="part_number" name="part_number" value="<?php echo $new['part_number'];?>">
                </div>
              </div>
              <!-- Quantity -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="quantity">Quantity</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="quantity" name="quantity" value=<?php echo $new['quantity'];?>>
                </div>
              </div>
              <!-- Harga Pokok -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="unit_price">Harga Pokok</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="unit_price" name="unit_price" value=<?php echo ceil($new['harga_pokok']);?>>
                </div>
              </div>
              <!-- Jumlah Bayar -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="tempat">Keterangan</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="tempat" name="tempat" value="<?php echo $new['tempat'];?>">
                </div>
              </div>
              <!-- Submit and Cancel Buttons -->
              <div class="row justify-content-end">
                <div class="col-sm-10">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <button type="button" class="btn btn-secondary" onclick="window.location.href='../sp_new.php';">Cancel</button>
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