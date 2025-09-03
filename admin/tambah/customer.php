<!-- header -->
<?php
include("template/header.php");
?>
<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Form</span> Input Customer</h4>
    <!-- Basic Layout -->
    <div class="row">
      <div class="col-xxl">
        <div class="card mb-4">
          <div class="card-body">
            <form method="POST" action="../aksi/customer_aksi.php?aksi=add">
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="customer_name">Name</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Nama Customer">
                </div>
              </div>
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="registration_no">Registration No</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="registration_no" name="registration_no" placeholder="Masukkan Plat No">
                </div>
              </div>
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="chassis_no">Chassis No</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="chassis_no" name="chassis_no" placeholder="Masukkan Chassis No">
                </div>
              </div>
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="chassis_no">Engine No</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="chassis_no" name="engine_no" placeholder="Masukkan Engine No" >
                </div>
              </div>
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="chassis_no">VIN No</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="chassis_no" name="vin_no" placeholder="Masukkan VIN No" >
                </div>
              </div>
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="chassis_no">Description</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="chassis_no" name="desc" placeholder="Masukkan Description">
                </div>
              </div>
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="phone_no">Phone No</label>
                <div class="col-sm-10">
                  <input type="text" id="phone_no" name="phone_no" class="form-control" placeholder="No telp customer">
                </div>
              </div>
              <div class="row justify-content-end">
                <div class="col-sm-10">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <button type="button" class="btn btn-secondary" onclick="window.location.href='../index.php';">Cancel</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <!-- / Content -->

<!-- footer -->
<?php
include("template/footer.php");
?>
<!-- /footer -->

