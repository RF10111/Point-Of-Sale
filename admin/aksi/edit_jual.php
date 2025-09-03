<?php
include("template/header.php");
include("../config_query.php");

$db = new database();
$id = $_GET['id_jual'];
$customer = $db->tampil_customers(); // Ambil data supplier
// Fetch jual data by ID
$jual = $db->get_jual_by_id($id);
if (!$jual) {
    echo "<script>alert('Data tidak ditemukan');window.location.href='../jual.php';</script>";
    exit;
}
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
    </script>
<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Form</span> Edit Penjualan</h4>

    <div class="row">
      <div class="col-xxl">
        <div class="card mb-4">
          <div class="card-body">
            <form action="../aksi/jual_aksi.php?aksi=edit" method="POST" id="jualForm">
                <input type="hidden" name="id_jual" value="<?php echo $jual['id_jual']; ?>">
                <!-- Pilihan Nama Customer -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="customer_name">Nama Customer</label>
                <div class="col-sm-10">
                  <input class="form-control" type="text" name="customer_name" id="customer_name" value="<?php echo $jual['customer_name'];?>" readonly>
                </div>
              </div>
                <!-- Tanggal Datang -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="tgl_transaksi">Tanggal Transaksi</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="tgl_transaksi" name="tgl_transaksi" value="<?php echo $jual['tgl_transaksi'];?>" readonly>
                    </div>
                </div>
              <!-- Harga Pokok -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="harga_pokok">Harga Pokok</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="harga_pokok" name="harga_pokok" placeholder="Harga" value="<?php echo $jual['harga_pokok'];?>" readonly>
                </div>
              </div>
              <!-- Harga jual -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="harga_jual">Harga Jual</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="harga_jual" name="harga_jual" placeholder="Harga Jual" value="<?php echo $jual['harga_jual'];?>" readonly>
                </div>
              </div>
              <!-- Quantity -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="jumlah">Quantity</label>
                    <div class="col-sm-10">
                    <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="Quantity" min="1" value="<?php echo $jual['jumlah'];?>" readonly>
                    </div>
                </div>
              <!-- Total Harga jual -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="total">Total Harga</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="total" name="total" placeholder="Total Harga" value="<?php echo $jual['total'];?>" readonly>
                </div>
              </div>
              <!-- Jumlah Bayar -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="total_payment">Jumlah Bayar</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="total_payment" name="total_payment" placeholder="Jumlah Bayar" value="<?php echo $jual['total_payment'];?>"required>
                </div>
              </div>
              <!-- Sisa Bayar -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="remaining_payment">Sisa Bayar</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="remaining_payment" name="remaining_payment" placeholder="Sisa Bayar" value="<?php echo $jual['remaining_payment'];?>" readonly>
                </div>
              </div>
              <!-- Keterangan -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="payment_status">Keterangan</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="payment_status" name="payment_status" placeholder="Status Pembayaran" value="<?php echo $jual['payment_status'];?>" readonly>
                </div>
              </div>
              <!-- Keuntungan Bersih -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="profit">Keuntungan Bersih</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="profit" name="profit" placeholder="Keuntungan Bersih" value="<?php echo $jual['profit'];?>" readonly>
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

