<!-- header -->
<?php
include("template/header.php");
?>
          <!-- Content wrapper for Mekanik Form -->
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Form</span> Input Mekanik</h4>

        <!-- Basic Layout for Mekanik Form -->
        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="../aksi/mekanik_aksi.php?aksi=add">
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="mekanik_name">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="mekanik_name" name="mekanik_name" placeholder="Nama Mekanik" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="address">Address</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Alamat Mekanik" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="phone_no">Phone No</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="phone_no" name="phone_no" placeholder="No Telp Mekanik" required>
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