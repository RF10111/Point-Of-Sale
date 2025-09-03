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
                data-harga-pokok="${sp.harga_pokok}" 
                data-nama="${sp.name}" 
                data-number="${sp.part_number}">
                ${sp.name} - (${sp.part_number})
            </option>`;
        });
    }
    function calculateTotalSpareparts() {
    let totalSparepart = 0;

    // Menggabungkan perhitungan untuk semua input harga spare part
    document.querySelectorAll('[id^="manual_jml_"], [id^="total_harga_"]').forEach(input => {
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
            <select class="form-control" id="category_${rowId}" name="category[]" onchange="fetchSpareparts(${rowId})" >
                <option value="">Pilih Kategori</option>
                <option value="new">Baru</option>
                <option value="used">Used</option>
            </select>
        </div>
        <div class="col-sm-2">
            <input type="text" id="search_sparepart_${rowId}" placeholder="Cari Spare Part" onkeyup="filterSpareparts(${rowId})" class="form-control"/>
        </div>
        <div class="col-sm-2">
            <select class="form-control" id="sparepart_id_${rowId}" name="sparepart_id[]" onchange="updateDetails(${rowId})" >
                <option value="">Pilih Spare Part</option>
            </select>
        </div>
        <div class="col-sm-2">
            <input type="number" class="form-control" id="jumlah_${rowId}" name="jumlah[]" placeholder="Quantity" oninput="calculateRowTotal(${rowId})" >
        </div>
        <div class="col-sm-2">
            <input type="number" class="form-control" id="quantity_${rowId}" name="quantity[]" placeholder="Stock" readonly>
        </div>
        <div class="col-sm-2">
            <input type="number" class="form-control" id="harga_jual_${rowId}" name="harga_jual[]" placeholder="Harga Jual" oninput="calculateRowTotal(${rowId})" >
        </div>
        <div class="col-sm-2">
            <input type="number" class="form-control" id="discount_${rowId}" name="discount[]" placeholder="Discount" oninput="calculateRowTotal(${rowId})" >
        </div>
        <div class="col-sm-2">
            <input type="number" class="form-control" id="harga_pokok_${rowId}" name="harga_pokok[]" placeholder="Harga Pokok" readonly>
        </div>
        <div class="col-sm-2">
            <input type="number" class="form-control" id="total_harga_${rowId}" name="total_harga[]" placeholder="Total Harga" readonly>
        </div>
        <div class="col-sm-1">
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
    const discount = parseFloat(document.getElementById(`discount_${rowId}`).value) || 0;

    // Hitung total harga sebelum diskon
    let totalHarga = jumlah * hargaJual;

    // Kurangi diskon
    totalHarga -= discount;

    // Update nilai total harga per row
    document.getElementById(`total_harga_${rowId}`).value = totalHarga.toFixed(0); // Format tanpa desimal
    calculateTotalSpareparts(); // Update total spare parts
}

    function removeRow(rowId) {
        document.getElementById(`row_${rowId}`).remove();
        calculateTotalSpareparts();
    }

    function calculateRemainingPaymentAndStatus() {
    const grandTotal = parseFloat(document.getElementById('grand_total').value) || 0;
    const totalPayment = parseFloat(document.getElementById('total_payment').value) || 0;

    // Hitung sisa pembayaran
    const remainingPayment = grandTotal - totalPayment;

    // Update nilai sisa pembayaran
    document.getElementById('remaining_payment').value = remainingPayment.toFixed(0);

    // Tentukan status pembayaran
    const paymentStatus = remainingPayment <= 0 ? "Lunas" : "Belum Lunas";
    document.getElementById('payment_status').value = paymentStatus;
}

    document.addEventListener('DOMContentLoaded', function () {
    const totalPaymentInput = document.getElementById('total_payment');
    if (totalPaymentInput) {
        totalPaymentInput.addEventListener('input', calculateRemainingPaymentAndStatus);
    }
});

    function addSpareManual() {
    const rowId = Date.now();
    const newRow = `
    <div class="row mb-2" id="manual_row_${rowId}">
        <div class="col-sm-6">
            <input type="text" class="form-control" id="manual_name_${rowId}" name="spare_name[]" placeholder="Nama SparePart">
        </div>
        <div class="col-sm-3">
            <input type="number" class="form-control" id="manual_jml_${rowId}" name="spare_jml[]" placeholder="Harga SparePart" oninput="calculateTotalSpareparts()">
        </div>
        <div class="col-sm-2">
            <button type="button" class="btn btn-danger" onclick="removeManual(${rowId})"><i class="bx bx-minus"></i></button>
        </div>
    </div>`;
    document.getElementById('manual-container').insertAdjacentHTML('beforeend', newRow);
}


    function removeManual(rowId) {
        document.getElementById(`manual_row_${rowId}`).remove();
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
        document.getElementById('vin_no').value = selectedOption.getAttribute('data-vin_no');
        document.getElementById('wo_mileage').value = selectedOption.getAttribute('data-mileage');
        document.getElementById('description').value = selectedOption.getAttribute('data-desc');
    }

// Fungsi untuk menambahkan baris labour
function addLabour() {
    const rowId = Date.now();
    const newRow = `
    <div class="row mb-2" id="labour_row_${rowId}">
        <div class="col-sm-6">
            <input type="text" class="form-control" id="labour_name_${rowId}" name="labour_name[]" placeholder="Nama Pekerjaan">
        </div>
        <div class="col-sm-4">
            <input type="number" class="form-control" id="labour_cost_${rowId}" name="labour_cost[]" placeholder="Biaya Pekerjaan" oninput="calculateTotalLabour()" >
        </div>
        <div class="col-sm-2">
            <button type="button" class="btn btn-danger" onclick="removeLabour(${rowId})"><i class="bx bx-minus"></i></button>
        </div>
    </div>`;
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
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Form</span> Input Invoice</h4>

        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="../aksi/invoice_aksi.php?aksi=add">
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="invoice_no">No Invoice</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="invoice_no" name="invoice_no" placeholder="Nomor Invoice (TIDAK BOLEH SAMA)" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="received">Date/Time Received</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" id="received" name="received" placeholder="Received Date" >
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="deadline">Deadline</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" id="deadline" name="deadline" placeholder="Deadline Date" >
                                </div>
                            </div>
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
                                    <input type="hidden" class="form-control" id="vin_no" name="vin_no" readonly>
                                    <input type="hidden" class="form-control" id="description" name="description" readonly>

                            <!-- Detail Pelanggan end-->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="mileage">Mileage/Km:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="wo_mileage" name="mileage" placeholder="Mileage/Km" readonly>
                                </div>
                            </div>                                    
                        
                            <h5>Spare Parts (Stock)</h5>
                            <div id="spareparts-container"></div>
                            <button type="button" class="btn btn-success" onclick="addSpare()">Tambah Spare Part</button>
                            <br><hr>
                            
                            <h5>Spare Parts (Manual)</h5>
                            <div id="manual-container"></div>
                            <button type="button" class="btn btn-success" onclick="addSpareManual()">Tambah Spare Part</button>
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
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="total_payment">Total Pembayaran</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="total_payment" name="total_payment" placeholder="Total Pembayaran" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="remaining_payment">Sisa Bayar</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="remaining_payment" name="remaining_payment" placeholder="Sisa Bayar" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="payment_status">Status Pembayaran</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="payment_status" name="payment_status" placeholder="Status Pembayaran" readonly>
                                </div>
                            </div>

                            <div class="row justify-content-end mt-3">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="button" class="btn btn-secondary" onclick="window.location.href='../invoice.php';">Cancel</button>
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