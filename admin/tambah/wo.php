<?php
include("template/header.php");
include("../config_query.php");
$db = new database();
?>
<script>
    // Fungsi untuk menambahkan baris labour
    function addLabour() {
        const rowId = Date.now();
        const newRow = `
        <div class="row mb-2" id="labour_row_${rowId}">
            <div class="col-sm-4">
                <input type="text" class="form-control" id="labour_name_${rowId}" name="labour_name[]" placeholder="Nama Pekerjaan">
            </div>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="labour_ket_${rowId}" name="labour_ket[]" placeholder="Keterangan Pekerjaan">
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-danger" onclick="removeLabour(${rowId})"><i class="bx bx-minus"></i></button>
            </div>
        </div>`;
        document.getElementById('labour-container').insertAdjacentHTML('beforeend', newRow);
    }

    // Fungsi untuk menghapus baris labour
    function removeLabour(rowId) {
        document.getElementById(`labour_row_${rowId}`).remove();
    }

    // Fungsi untuk mengambil data customer dari server
    async function fetchCustomers() {
    const response = await fetch('../aksi/fetch_customer.php');
    const customers = await response.json();
    const customerSelect = document.getElementById('customer_id');

    // Hapus semua opsi sebelumnya
    customerSelect.innerHTML = "";
    let defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = "Pilih Customer";
    customerSelect.appendChild(defaultOption);

    // Tambahkan opsi customer
    customers.forEach(customer => {
        let option = document.createElement("option");
        option.value = customer.id_customer;
        option.textContent = `${customer.name} - ${customer.registration_no}`;
        
        // Set dataset untuk data tambahan
        option.dataset.name = customer.name;
        option.dataset.registration_no = customer.registration_no;
        option.dataset.chassis_no = customer.chassis_no;
        option.dataset.engine_no = customer.engine_no;
        option.dataset.vin_no = customer.vin_no;
        option.dataset.desc = customer.description;
        option.dataset.phone_no = customer.phone_no;

        customerSelect.appendChild(option);
    });
}


    // Fungsi untuk mengupdate detail customer berdasarkan pilihan
    function updateCustomerDetails() {
        const selectedOption = document.getElementById('customer_id').selectedOptions[0];
        document.getElementById('name').value = selectedOption.getAttribute('data-name');
        document.getElementById('registration_no').value = selectedOption.getAttribute('data-registration_no');
        document.getElementById('chassis_no').value = selectedOption.getAttribute('data-chassis_no');
        document.getElementById('engine_no').value = selectedOption.getAttribute('data-engine_no');
        document.getElementById('phone_no').value = selectedOption.getAttribute('data-phone_no');
        document.getElementById('vin_no').value = selectedOption.getAttribute('data-vin_no');
        document.getElementById('description').value = selectedOption.getAttribute('data-desc');
    }

    // Memuat data pelanggan saat halaman di-load
    window.onload = fetchCustomers;
</script>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Form</span> Input Work Order</h4>

        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="../aksi/wo_aksi.php?aksi=add">
                            <!-- Data Customer -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="customer_id">Data Customer</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="customer_id" name="customer_id" onchange="updateCustomerDetails()" required>
                                        <option value="">Pilih Customer</option>
                                    </select>
                                </div>
                            </div>
                            <!-- milleage -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="mileage">Mileage/Km</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="mileage" name="mileage" placeholder="Mileage/Km" required>
                                </div>
                            </div>   
                            <!-- Detail Pelanggan (Hidden) -->
                            <input type="hidden" class="form-control" id="name" name="name" readonly>
                            <input type="hidden" class="form-control" id="registration_no" name="registration_no" readonly>
                            <input type="hidden" class="form-control" id="chassis_no" name="chassis_no" readonly>
                            <input type="hidden" class="form-control" id="engine_no" name="engine_no" readonly>
                            <input type="hidden" class="form-control" id="phone_no" name="phone_no" readonly>
                            <input type="hidden" class="form-control" id="vin_no" name="vin_no" readonly>
                            <input type="hidden" class="form-control" id="description" name="description" readonly>

                            <!-- Labour Section -->
                            <h5>Labour</h5>
                            <div id="labour-container"></div>
                            <button type="button" class="btn btn-success" onclick="addLabour()">Tambah Labour</button>
                            <hr>

                            <!-- Tombol Submit dan Cancel -->
                            <div class="row justify-content-end mt-3">
                                <div class="col-sm-10 text-end">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="button" class="btn btn-secondary" onclick="window.location.href='../wo.php';">Cancel</button>
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