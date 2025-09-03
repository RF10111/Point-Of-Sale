<!-- header -->
<?php
include("template/header.php");
include("../config_query.php");

$db = new Database();
$id = $_GET['id_customer'];

// Fetch customer data by ID
$customer = $db->get_customer_by_id($id);
if (!$customer) {
    echo "<script>alert('Data tidak ditemukan');window.location.href='customer.php';</script>";
    exit;
}
?>

<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Form</span> Edit Customer</h4>

    <!-- Basic Layout -->
    <div class="row">
      <div class="col-xxl">
        <div class="card mb-4">
          <div class="card-body">
            <form method="POST" action="customer_aksi.php?aksi=edit">
              <input type="hidden" name="id_customer" value="<?php echo $customer['id_customer']; ?>">
              
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="customer_name">Name</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="customer_name" name="customer_name" value="<?php echo htmlspecialchars($customer['name']); ?>" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="registration_no">Registration No</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="registration_no" name="registration_no" value="<?php echo htmlspecialchars($customer['registration_no']); ?>" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="chassis_no">Chassis No</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="chassis_no" name="chassis_no" value="<?php echo htmlspecialchars($customer['chassis_no']); ?>" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="chassis_no">Engine No</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="chassis_no" name="engine_no" value="<?php echo htmlspecialchars($customer['engine_no']); ?>" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="chassis_no">VIN No</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="chassis_no" name="vin_no" value="<?php echo htmlspecialchars($customer['vin_no']); ?>" required>
                </div>

              </div>
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="chassis_no">Description</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="chassis_no" name="desc" value="<?php echo htmlspecialchars($customer['description']); ?>" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="phone_no">Phone No</label>
                <div class="col-sm-10">
                  <input type="text" id="phone_no" name="phone_no" class="form-control" value="<?php echo htmlspecialchars($customer['phone_no']); ?>" required>
                </div>
              </div>

              <div class="row justify-content-end">
                <div class="col-sm-10">
                  <button type="submit" class="btn btn-primary">Update</button>
                  <button type="button" class="btn btn-secondary" onclick="window.location.href='../index.php';">Cancel</button>
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
