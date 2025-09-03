<!-- header -->
<?php
include("template/header.php");
include("../config_query.php");

$db = new database();
$id = $_GET['id_mekanik'];

// Fetch mekanik data by ID
$mekanik = $db->get_mekanik_by_id($id);
if (!$mekanik) {
    echo "<script>alert('Data tidak ditemukan');window.location.href='../mekanik.php';</script>";
    exit;
}
?>

<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Form</span> Edit Mekanik</h4>

    <!-- Basic Layout for Mekanik Form -->
        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="../aksi/mekanik_aksi.php?aksi=edit">
                            <input type="hidden" name="id_mekanik" value="<?php echo $mekanik['id_mekanik']; ?>">
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="mekanik_name">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="mekanik_name" name="mekanik_name" value="<?php echo $mekanik['name']; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="address">Address</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="address" name="address" value="<?php echo $mekanik['alamat']; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="phone_no">Phone No</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="phone_no" name="phone_no" value="<?php echo $mekanik['phone_no']; ?>">
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="button" class="btn btn-secondary" onclick="window.location.href='../mekanik.php';">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("template/footer.php"); ?>