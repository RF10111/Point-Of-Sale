<?php
// Tambahkan include untuk file konfigurasi
include('../config_query.php');
$db = new database(); // Pastikan class Database tersedia dan sudah di-instantiate
$suppliers = $db->tampil_supplier(); // Ambil data supplier
?>

<!-- Header -->
<?php
include("template/header.php");
?>
<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Form</span> Tambah Order</h4>

    <div class="row">
      <div class="col-xxl">
        <div class="card mb-4">
          <div class="card-body">
            <form action="../aksi/order_aksi.php?aksi=add" method="POST">
              <!-- Pilihan Nama Supplier -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="supplier_name">Nama Supplier</label>
                <div class="col-sm-10">
                  <select class="form-control" id="supplier_name" name="supplier_name" required>
                    <option value="" selected disabled>Pilih Supplier</option>
                    <?php foreach ($suppliers as $supplier) : ?>
                      <option value="<?= $supplier['name'] ?>"><?= htmlspecialchars($supplier['name']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <!-- Nama Spare Part -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="sparepart_name">Nama Spare Part</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="sparepart_name" name="sparepart_name" placeholder="Nama Spare Part" required>
                </div>
              </div>
              <!-- Kategori Spare Part -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="category">Kategori Spare Part</label>
                <div class="col-sm-10">
                  <select class="form-control" id="category" name="category" required>
                    <option value="new">Baru</option>
                    <option value="used">Used</option>
                  </select>
                </div>
              </div>
              <!-- Tanggal Datang -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="arrival_date">Tanggal Datang</label>
                <div class="col-sm-10">
                  <input type="date" class="form-control" id="arrival_date" name="arrival_date" required>
                </div>
              </div>
              <!-- No Spare Part -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="part_number">No Spare Part</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="part_number" name="part_number" placeholder="No Spare Part" required>
                </div>
              </div>
              <!-- Quantity -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="quantity">Quantity</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity" min="1" required>
                </div>
              </div>
              <!-- Harga -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="price">Total Harga</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="price" name="price" placeholder="Harga" oninput="calculateRemainingPayment()" required>
                </div>
              </div>
              <!-- Harga Pokok -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="unit_price">Harga Pokok</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="unit_price" name="unit_price" placeholder="Harga Pokok" readonly>
                </div>
              </div>
              <!-- Jumlah Bayar -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="total_payment">Jumlah Bayar</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="total_payment" name="total_payment" placeholder="Jumlah Bayar" oninput="calculateRemainingPayment()" required>
                </div>
              </div>
              <!-- Sisa Bayar -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="remaining_payment">Sisa Bayar</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="remaining_payment" name="remaining_payment" placeholder="Sisa Bayar" readonly>
                </div>
              </div>
              <!-- Keterangan -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="payment_status">Keterangan</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="payment_status" name="payment_status" placeholder="Status Pembayaran" readonly>
                </div>
              </div>
              <!-- Submit and Cancel Buttons -->
              <div class="row justify-content-end">
                <div class="col-sm-10">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <button type="button" class="btn btn-secondary" onclick="window.location.href='../order.php';">Cancel</button>
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

<!-- JavaScript -->
<script>
  function calculateUnitPrice() {
  const price = parseFloat(document.getElementById("price").value) || 0;
  const quantity = parseFloat(document.getElementById("quantity").value) || 1; // Default quantity ke 1 agar tidak terjadi pembagian nol
  const unitPrice = price / quantity;

  // Set harga pokok
  document.getElementById("unit_price").value = unitPrice.toFixed(0); // Format dengan 2 angka desimal
}

function calculateRemainingPayment() {
  const price = parseFloat(document.getElementById("price").value) || 0;
  const totalPayment = parseFloat(document.getElementById("total_payment").value) || 0;
  const remainingPayment = price - totalPayment;

  // Set sisa bayar
  document.getElementById("remaining_payment").value = remainingPayment;

  // Tentukan status pembayaran
  const paymentStatus = remainingPayment <= 0 ? "Lunas" : "Belum Lunas";
  document.getElementById("payment_status").value = paymentStatus;
}

// Panggil fungsi perhitungan harga pokok setiap kali harga atau quantity diubah
document.getElementById("price").addEventListener("input", () => {
  calculateUnitPrice();
  calculateRemainingPayment();
});

document.getElementById("quantity").addEventListener("input", calculateUnitPrice);
</script>
