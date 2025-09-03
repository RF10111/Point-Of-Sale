<!-- header -->
<?php
include("template/header.php");
include("../config_query.php");
$db = new database();
?>
<script>
    async function fetchSpareparts(rowId) {
        const category = document.getElementById(`category_${rowId}`).value;
        const response = await fetch(`../aksi/fetch_spareparts.php?category=${category}`);
        const spareparts = await response.json();

        const sparepartSelect = document.getElementById(`sparepart_id_${rowId}`);
        sparepartSelect.innerHTML = '<option value="">Pilih Spare Part</option>';

        spareparts.forEach(sp => {
            sparepartSelect.innerHTML += `<option value="${sp.id}" 
                data-quantity="${sp.quantity}"  
                data-nama="${sp.name}" 
                data-harga-pokok="${sp.harga_pokok}" 
                data-number="${sp.part_number}">
                ${sp.name} (${sp.part_number})
            </option>`;
        });
    }
    function calculateTotalSpareparts() {
    let totalSparepart = 0;

    document.querySelectorAll('[id^="total_harga_"]').forEach(input => {
        totalSparepart += parseFloat(input.value) || 0;
    });

    document.getElementById('total_sparepart').value = totalSparepart.toFixed(0);
    calculateGrandTotal();
}

    function calculateTotalLabour() {
        let totalLabour = 0;

        document.querySelectorAll('[id^="labour_cost_"]').forEach(input => {
            totalLabour += parseFloat(input.value) || 0;
        });

        document.getElementById('total_labour').value = totalLabour.toFixed(0);
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        const totalSparepart = parseFloat(document.getElementById('total_sparepart').value) || 0;
        const totalLabour = parseFloat(document.getElementById('total_labour').value) || 0;

        const grandTotal = totalSparepart + totalLabour;
        document.getElementById('grand_total').value = grandTotal.toFixed(0);
    }


    function updateDetails(rowId) {
        const selectedOption = document.getElementById(`sparepart_id_${rowId}`).selectedOptions[0];
        document.getElementById(`quantity_${rowId}`).value = selectedOption.getAttribute('data-quantity');
        document.getElementById(`sparepart_name_${rowId}`).value = selectedOption.getAttribute('data-nama');
        document.getElementById(`part_number_${rowId}`).value = selectedOption.getAttribute('data-number');
        document.getElementById(`harga_pokok_${rowId}`).value = selectedOption.getAttribute('data-harga-pokok');
    }

    function filterSpareparts(rowId) {
        const searchInput = document.getElementById(`search_sparepart_${rowId}`).value.toLowerCase();
        const sparepartSelect = document.getElementById(`sparepart_id_${rowId}`);
        const options = sparepartSelect.querySelectorAll('option');

        options.forEach(option => {
            const text = option.textContent.toLowerCase();
            option.style.display = text.includes(searchInput) ? 'block' : 'none';
        });
    }

    function addSpare() {
    const rowId = Date.now();
    const newRow = `
    <div class="row mb-2" id="row_${rowId}">
        <div class="col-sm-2" style="margin-bottom: 10px;">
            <select class="form-control" id="category_${rowId}" name="category[]" onchange="fetchSpareparts(${rowId})" required>
                <option value="">Pilih Kategori</option>
                <option value="new">Baru</option>
                <option value="used">Used</option>
            </select>
        </div>
        <div class="col-sm-2">
            <input type="text" id="search_sparepart_${rowId}" placeholder="Cari Spare Part" onkeyup="filterSpareparts(${rowId})" class="form-control"/>
        </div>
        <div class="col-sm-2">
            <select class="form-control" id="sparepart_id_${rowId}" name="sparepart_id[]" onchange="updateDetails(${rowId})" required>
                <option value="">Pilih Spare Part</option>
            </select>
        </div>
        <div class="col-sm-2">
            <input type="number" class="form-control" id="jumlah_${rowId}" name="jumlah[]" placeholder="Quantity" oninput="calculateRowTotal(${rowId})" required>
        </div>
        <div class="col-sm-2">
            <input type="number" class="form-control" id="quantity_${rowId}" name="quantity[]" placeholder="Stock" readonly>
        </div>
        <div class="col-sm-2">
            <input type="number" class="form-control" id="harga_pokok_${rowId}" name="harga_pokok[]" placeholder="Harga Pokok" readonly>
        </div>
        <div class="col-sm-2">
            <input type="number" class="form-control" id="harga_jual_${rowId}" name="harga_jual[]" placeholder="Harga Jual" oninput="calculateRowTotal(${rowId})" required>
        </div>
        <div class="col-sm-2">
            <input type="number" class="form-control" id="total_harga_${rowId}" name="total_harga[]" placeholder="Total Harga" readonly>
        </div>
        <div class="col-sm-1" style="text-align: right;">
            <button type="button" class="btn btn-danger" onclick="removeRow(${rowId})"><i class="bx bx-minus"></i></button>
        </div>
        <input type="hidden" id="sparepart_name_${rowId}" name="sparepart_name[]">
        <input type="hidden" id="part_number_${rowId}" name="part_number[]">
    </div>`;
    document.getElementById('spareparts-container').insertAdjacentHTML('beforeend', newRow);
    }

    function calculateRowTotal(rowId) {
        const jumlah = parseFloat(document.getElementById(`jumlah_${rowId}`).value) || 0;
        const hargaJual = parseFloat(document.getElementById(`harga_jual_${rowId}`).value) || 0;
        const totalHarga = jumlah * hargaJual;

        document.getElementById(`total_harga_${rowId}`).value = totalHarga.toFixed(0); // Total harga ditampilkan tanpa desimal
        calculateTotalSpareparts();
    }

    function removeRow(rowId) {
        document.getElementById(`row_${rowId}`).remove();
        alculateTotalSpareparts();
    }



    function filterWorkOrders() {
        const searchInput = document.getElementById('search_wo').value.toLowerCase();
        const woSelect = document.getElementById('wo_id');
        const options = woSelect.querySelectorAll('option');

        options.forEach(option => {
            const text = option.textContent.toLowerCase();
            if (text.includes(searchInput)) {
                option.style.display = 'block'; // Tampilkan opsi yang sesuai
            } else {
                option.style.display = 'none'; // Sembunyikan opsi yang tidak sesuai
            }
        });
    }

    
    // Fungsi untuk mengambil daftar Work Order dari server
async function fetchWorkOrders() {
    const response = await fetch('../aksi/fetch_work_orders.php'); // Sesuaikan path file
    const workOrders = await response.json();

    const woSelect = document.getElementById('wo_id');
    woSelect.innerHTML = '<option value="">Pilih Work Order</option>';

    workOrders.forEach(wo => {
    woSelect.innerHTML += `
        <option value="${wo.id_wo}" 
            data-name="${wo.customer_name}"
            data-registration_no="${wo.registration_no}"
            data-chassis_no="${wo.chassis_no}"
            data-engine_no="${wo.engine_no}"
            data-phone_no="${wo.phone_no}"
            data-vin_no="${wo.vin_no}"
            data-mileage="${wo.mileage}"
            data-desc="${wo.desk}"
            data-created_at="${wo.created_at}">
            ${wo.customer_name} - ${wo.registration_no} - ${wo.chassis_no} - ${wo.mileage} Km
        </option>`;
});
}
function updateWoDetails() {
        const selectedOption = document.getElementById('wo_id').selectedOptions[0];
        document.getElementById('name').value = selectedOption.getAttribute('data-name');
        document.getElementById('registration_no').value = selectedOption.getAttribute('data-registration_no');
        document.getElementById('chassis_no').value = selectedOption.getAttribute('data-chassis_no');
        document.getElementById('engine_no').value = selectedOption.getAttribute('data-engine_no');
        document.getElementById('phone_no').value = selectedOption.getAttribute('data-phone_no');
        document.getElementById('wo_mileage').value = selectedOption.getAttribute('data-mileage');
    }


// Fungsi untuk mengambil daftar Labour berdasarkan Work Order yang dipilih
async function fetchLabourByWO(registration_no, mileage, created_at) {
    const response = await fetch(`../aksi/fetch_labour_by_wo.php?registration_no=${registration_no}&mileage=${mileage}&created_at=${created_at}`);
    const labourNames = await response.json();
    return labourNames; // Mengembalikan array nama pekerjaan
}

// Fungsi untuk menambahkan baris labour
async function addLabour() {
    const rowId = Date.now(); // Generate unique ID for the row
    const newRow = `
    <div class="row mb-2" id="labour_row_${rowId}">
        <div class="col-sm-6">
            <input type="text" class="form-control" id="labour_name_${rowId}" name="labour_name[]" required>
        </div>
        <div class="col-sm-4">
            <input type="number" class="form-control" id="labour_cost_${rowId}" name="labour_cost[]" placeholder="Biaya Pekerjaan" oninput="calculateTotalLabour()" required>
        </div>
        <div class="col-sm-2">
            <button type="button" class="btn btn-danger" onclick="removeLabour(${rowId})"><i class="bx bx-minus"></i></button>
        </div>
    </div>`;

    // Menambahkan baris baru ke dalam container labour
    document.getElementById('labour-container').insertAdjacentHTML('beforeend', newRow);
}

    function removeLabour(rowId) {
        document.getElementById(`labour_row_${rowId}`).remove();
        calculateTotalLabour();
    }
    window.onload = fetchWorkOrders;

</script>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Form</span> Input Estimasi</h4>

        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="../aksi/estimasi_aksi.php?aksi=add">
                            <!-- Work Order -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="wo_id">Work Order</label>
                                <div class="col-sm-10">
                                    <!-- Input Pencarian -->
                                    <input type="text" id="search_wo" class="form-control" placeholder="Cari Work Order..." oninput="filterWorkOrders()">
                                    <br>
                                    <!-- Elemen Select Work Order -->
                                    <select class="form-control" id="wo_id" name="wo_id" onchange="updateWoDetails()" size="5">
                                        <option value="">Pilih Work Order</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Detail Pelanggan -->
                                    <input type="hidden" class="form-control" id="name" name="name" readonly>
                                    <input type="hidden" class="form-control" id="registration_no" name="registration_no" readonly>
                                    <input type="hidden" class="form-control" id="chassis_no" name="chassis_no" readonly>
                                    <input type="hidden" class="form-control" id="engine_no" name="engine_no" readonly>
                                    <input type="hidden" class="form-control" id="phone_no" name="phone_no" readonly>
                            <!-- Detail Pelanggan end-->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="mileage">Mileage/Km:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="wo_mileage" name="mileage" placeholder="Mileage/Km" readonly>
                                </div>
                            </div>                                    
                        
                            <h5>Spare Parts</h5>
                            <div id="spareparts-container"></div>
                            <button type="button" class="btn btn-success" onclick="addSpare()">Tambah Spare Part</button>
                            <br><hr>

                            <h5>Labour</h5>
                            <div id="labour-container"></div>
                            <button type="button" class="btn btn-success" onclick="addLabour()">Tambah Labour</button>
                            <hr>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="total_sparepart">Total Spare Part</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="total_sparepart" name="total_sparepart" placeholder="Total Spare Part" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="total_labour">Total Labour</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="total_labour" name="total_labour" placeholder="Total Labour" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="grand_total">Grand Total</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="grand_total" name="grand_total" placeholder="Grand Total" readonly>
                                </div>
                            </div>


                            <div class="row justify-content-end mt-3">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="button" class="btn btn-secondary" onclick="window.location.href='../estimasi.php';">Cancel</button>
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

<!-- belum selesai -->