<!-- header -->
<?php
include("template/header.php");
include("../config_query.php");
$db = new database();
$mekanik = $db->tampil_mekanik();
$customer = $db->tampil_customers();
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
                ${sp.name} (${sp.part_number})
            </option>`;
        });
    }

    function updateDetails(rowId) {
        const selectedOption = document.getElementById(`sparepart_id_${rowId}`).selectedOptions[0];
        document.getElementById(`quantity_${rowId}`).value = selectedOption.getAttribute('data-quantity');
        document.getElementById(`harga_pokok_${rowId}`).value = selectedOption.getAttribute('data-harga-pokok');
        document.getElementById(`sparepart_name_${rowId}`).value = selectedOption.getAttribute('data-nama');
        document.getElementById(`part_number_${rowId}`).value = selectedOption.getAttribute('data-number');
    }

    function addRow() {
        const rowId = Date.now();
        const newRow = `
        <div class="row mb-2" id="row_${rowId}">
            <div class="col-sm-2">
                <select class="form-control" id="category_${rowId}" name="category[]" onchange="fetchSpareparts(${rowId})" required>
                    <option value="">Pilih Kategori</option>
                    <option value="new">Baru</option>
                    <option value="used">Used</option>
                </select>
            </div>
            <div class="col-sm-3">
                <select class="form-control" id="sparepart_id_${rowId}" name="sparepart_id[]" onchange="updateDetails(${rowId})" required>
                    <option value="">Pilih Spare Part</option>
                </select>
            </div>
            <div class="col-sm-2">
                <input type="number" class="form-control" id="jumlah_${rowId}" name="jumlah[]" placeholder="Quantity" required>
            </div>
            <div class="col-sm-2">
                <input type="number" class="form-control" id="quantity_${rowId}" name="quantity[]" placeholder="Stock" readonly>
            </div>
            <div class="col-sm-2">
                <input type="number" class="form-control" id="harga_pokok_${rowId}" name="harga_pokok[]" placeholder="Harga Pokok" readonly>
            </div>
            <div class="col-sm-1">
                <button type="button" class="btn btn-danger" onclick="removeRow(${rowId})"><i class="bx bx-minus"></i></button>
            </div>
            <input type="hidden" id="sparepart_name_${rowId}" name="sparepart_name[]">
            <input type="hidden" id="part_number_${rowId}" name="part_number[]">
        </div>`;
        document.getElementById('spareparts-container').insertAdjacentHTML('beforeend', newRow);
    }

    function removeRow(rowId) {
        document.getElementById(`row_${rowId}`).remove();
    }
</script>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Form</span> Input Reservasi</h4>

        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="../aksi/reservasi_aksi.php?aksi=add">
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="mekanik_name">Nama Mekanik</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="mekanik_name" name="mekanik_name" required>
                                        <option value="" selected disabled>Pilih Mekanik</option>
                                        <?php foreach ($mekanik as $mekanik) : ?>
                                            <option value="<?= $mekanik['name'] ?>"><?= htmlspecialchars($mekanik['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="customer_regist">Data Customer</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="customer_regist" name="customer_regist" required>
                                        <option value="" selected disabled>Pilih Customer</option>
                                        <?php foreach ($customer as $customer) : ?>
                                            <option value="<?= $customer['registration_no'] ?>"><?= htmlspecialchars($customer['name']) ?> - <?= htmlspecialchars($customer['registration_no'])?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <h5>Spare Parts</h5>
                            <div id="spareparts-container"></div>
                            <button type="button" class="btn btn-success" onclick="addRow()">Tambah Spare Part</button>

                            <div class="row justify-content-end mt-3">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="button" class="btn btn-secondary" onclick="window.location.href='../reservasi.php';">Cancel</button>
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
