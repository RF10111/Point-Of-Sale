<?php
// Tambahkan include untuk file konfigurasi
include('../config_query.php');
$db = new database(); // Pastikan class Database tersedia dan sudah di-instantiate
$customers = $db->tampil_customers(); // Ambil data customer
?>

<!-- Header -->
<?php
include("template/header.php");
?>


<script>
        // Fungsi untuk mengambil data berdasarkan kategori
        async function fetchSpareparts() {
            const category = document.getElementById('category').value;
            const response = await fetch(`../aksi/fetch_spareparts.php?category=${category}`);
            const spareparts = await response.json();

            const sparepartSelect = document.getElementById('sparepart_id');
            sparepartSelect.innerHTML = '<option value="">Pilih Spare Part</option>';

            spareparts.forEach(sp => {
                sparepartSelect.innerHTML += `<option value="${sp.id}" data-quantity="${sp.quantity}" data-harga-pokok="${sp.harga_pokok}" data-nama="${sp.name}" data-number="${sp.part_number}" '>${sp.name} (${sp.part_number})</option>`;
            });
        }

        // Fungsi untuk menampilkan data quantity dan harga_pokok
        function updateDetails() {
            const selectedOption = document.getElementById('sparepart_id').selectedOptions[0];
            document.getElementById('quantity').value = selectedOption.getAttribute('data-quantity');
            document.getElementById('harga_pokok').value = selectedOption.getAttribute('data-harga-pokok');
            document.getElementById('sparepart_name').value = selectedOption.getAttribute('data-nama');
            document.getElementById('part_number').value = selectedOption.getAttribute('data-number');
        }

        // Fungsi untuk memfilter spare part berdasarkan input pencarian
        function filterSpareparts() {
            const searchInput = document.getElementById('search_sparepart').value.toLowerCase();
            const sparepartSelect = document.getElementById('sparepart_id');
            const options = sparepartSelect.querySelectorAll('option');

            options.forEach(option => {
                const text = option.textContent.toLowerCase();
                option.style.display = text.includes(searchInput) ? 'block' : 'none';
            });
        }
        
    </script>
<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Form</span> Tambah Penjualan</h4>

    <div class="row">
      <div class="col-xxl">
        <div class="card mb-4">
          <div class="card-body">
            <form action="../aksi/jual_aksi.php?aksi=add" method="POST" id="jualForm">
                <!-- Pilihan Nama Customer -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="customer_name">Nama Customer</label>
                <div class="col-sm-10">
                  <select class="form-control" id="customer_name" name="customer_name" required>
                    <option value="" selected disabled>Pilih Customer</option>
                    <?php foreach ($customers as $customer) : ?>
                      <option value="<?= $customer['name'] ?>"><?= htmlspecialchars($customer['name']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
                <!-- Tanggal Datang -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="tgl_transaksi">Tanggal Transaksi</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="tgl_transaksi" name="tgl_transaksi" required>
                    </div>
                </div>
                <!-- Kategori Spare Part -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="category">Kategori Spare Part</label>
                    <div class="col-sm-10">
                      <select class="form-control" id="category" name="category" onchange="fetchSpareparts()" required>
                          <option value="">Pilih Kategori</option>
                          <option value="new">Baru</option>
                          <option value="used">Used</option>
                      </select>
                    </div>
                </div>
                <!-- search sparepart -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="search_sparepart">Cari Spare Part</label>
                    <div class="col-sm-10">
                      <input type="text" id="search_sparepart" placeholder="Cari Spare Part" onkeyup="filterSpareparts()" class="form-control mb-2" />
                    </div>
                </div>
                <!-- Data Spare Part -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="sparepart_id">Spare Part</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="sparepart_id" name="sparepart_id" onchange="updateDetails()" required>
                          <option value="">Pilih Spare Part</option>
                        </select>
                    </div>
                </div>
                <!-- Nama Spare Part -->
                        <input type="hidden" id="sparepart_name" name="sparepart_name" readonly>
                        <input type="hidden" id="part_number" name="part_number" readonly>
                        
                <!-- Stock -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="quantity">Stock</label>
                    <div class="col-sm-10">
                    <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity" readonly>
                    </div>
                </div>
              <!-- Harga Pokok -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="harga_pokok">Harga Pokok</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="harga_pokok" name="harga_pokok" placeholder="Harga" readonly>
                </div>
              </div>
              <!-- Harga jual -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="harga_jual">Harga Jual</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="harga_jual" name="harga_jual" placeholder="Harga Jual" required>
                </div>
              </div>
              <!-- Quantity -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="jumlah">Quantity</label>
                    <div class="col-sm-10">
                    <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="Quantity" min="1" required>
                    </div>
                </div>
              <!-- Total Harga jual -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="total">Total Harga</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="total" name="total" placeholder="Total Harga" readonly>
                </div>
              </div>
              <!-- Jumlah Bayar -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="total_payment">Jumlah Bayar</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="total_payment" name="total_payment" placeholder="Jumlah Bayar" required>
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
              <!-- Keuntungan Bersih -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="profit">Keuntungan Bersih</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="profit" name="profit" placeholder="Keuntungan Bersih" readonly>
                </div>
              </div>
              <!-- Submit and Cancel Buttons -->
              <div class="row justify-content-end">
                <div class="col-sm-10">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <button type="button" class="btn btn-secondary" onclick="window.location.href='../jual.php';">Cancel</button>
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

<!-- Tambahkan jQuery -->
 <script>
 // Fungsi untuk menghitung total harga
function calculateTotalPrice() {
    const hargaJual = parseFloat(document.getElementById("harga_jual").value) || 0;
    const jumlah = parseFloat(document.getElementById("jumlah").value) || 1; // Default quantity ke 1
    const total = hargaJual * jumlah;

    // Set nilai total harga
    document.getElementById("total").value = total.toFixed(0); // Format tanpa desimal

    // Hitung keuntungan bersih
    calculateProfit();
}

// Fungsi untuk menghitung sisa pembayaran dan status pembayaran
function calculateRemainingPaymentAndStatus() {
    const total = parseFloat(document.getElementById("total").value) || 0;
    const totalPayment = parseFloat(document.getElementById("total_payment").value) || 0;
    const remainingPayment = total - totalPayment;

    // Set nilai sisa pembayaran
    document.getElementById("remaining_payment").value = remainingPayment.toFixed(0);

    // Tentukan status pembayaran
    const paymentStatus = remainingPayment <= 0 ? "Lunas" : "Belum Lunas";
    document.getElementById("payment_status").value = paymentStatus;
}

// Fungsi untuk menghitung keuntungan bersih
function calculateProfit() {
    const total = parseFloat(document.getElementById("total").value) || 0;
    const hargaPokok = parseFloat(document.getElementById("harga_pokok").value) || 0;
    const jumlah = parseFloat(document.getElementById("jumlah").value) || 0;
    const profit = total - (hargaPokok * jumlah);

    // Set nilai keuntungan bersih
    document.getElementById("profit").value = profit.toFixed(0); // Format tanpa desimal
}

// Event listener untuk harga jual, jumlah, dan harga pokok
document.getElementById("harga_jual").addEventListener("input", () => {
    calculateTotalPrice();
    calculateRemainingPaymentAndStatus();
});

document.getElementById("jumlah").addEventListener("input", () => {
    calculateTotalPrice();
    calculateRemainingPaymentAndStatus();
});

document.getElementById("harga_pokok").addEventListener("input", calculateProfit);

// Event listener untuk jumlah bayar
document.getElementById("total_payment").addEventListener("input", calculateRemainingPaymentAndStatus);
</script>

