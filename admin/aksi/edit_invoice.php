<?php
include("template/header.php");
include("../config_query.php");

// Membuat koneksi ke database menggunakan mysqli
$db = new database();

if (isset($_GET['invoice_no'])) {
    $invoice_no = $_GET['invoice_no'];
    
    // Menyiapkan query untuk mendapatkan data berdasarkan invoice_no dan created_at
    $query = "SELECT invoice_no, grand_total, total_payment, remaining_payment, payment_status FROM tb_invoice WHERE invoice_no = '$invoice_no'";
    
    // Menjalankan query menggunakan koneksi mysqli
    $result = $db->koneksi->query($query);
    
    // Memeriksa apakah query berhasil dan mengambil hasilnya
    if ($result) {
        $invoice = $result->fetch_assoc();
    } else {
        echo "Error: " . $db->koneksi->error;
    }
}
?>


<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Form</span> Edit Invoice</h4>

        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="../aksi/invoice_aksi.php?aksi=update">
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="invoice_no">No Invoice</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="invoice_no" name="invoice_no" value="<?php echo $invoice['invoice_no']; ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="grand_total">Grand Total</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="grand_total" name="grand_total" value="<?php echo $invoice['grand_total']; ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="total_payment">Total Pembayaran</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="total_payment" name="total_payment" value="<?php echo $invoice['total_payment']; ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="remaining_payment">Sisa Bayar</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="remaining_payment" name="remaining_payment" value="<?php echo $invoice['remaining_payment']; ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="payment_status">Status Pembayaran</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="payment_status" name="payment_status" value="<?php echo $invoice['payment_status']; ?>" readonly>
                                </div>
                            </div>

                            <div class="row justify-content-end mt-3">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Update</button>
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


<script>
    document.addEventListener('DOMContentLoaded', function () {
    const totalPaymentInput = document.getElementById('total_payment');
    const grandTotal = parseFloat(document.getElementById('grand_total').value) || 0;

    totalPaymentInput.addEventListener('input', function() {
        const totalPayment = parseFloat(totalPaymentInput.value) || 0;
        const remainingPayment = grandTotal - totalPayment;
        document.getElementById('remaining_payment').value = remainingPayment.toFixed(0);

        const paymentStatus = remainingPayment <= 0 ? "Lunas" : "Belum Lunas";
        document.getElementById('payment_status').value = paymentStatus;
    });
});
</script>

<?php include("template/footer.php"); ?>
