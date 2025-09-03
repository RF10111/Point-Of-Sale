<?php
include('../config_query.php');
$db = new Database();
session_start();

$aksi = $_GET['aksi'];

if ($aksi == "add") {
    $invoice_no = $_POST['invoice_no'];

    // Cek apakah invoice_no sudah ada
    $query = "SELECT COUNT(*) as count FROM tb_invoice WHERE invoice_no = ?";
    $stmt = $db->koneksi->prepare($query);
    $stmt->bind_param("s", $invoice_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        echo "<script>alert('Nomor Invoice sudah ada, silakan gunakan nomor lain!'); window.location.href='../tambah/invoice.php';</script>";
        exit;
    }

    // Data pelanggan
    $customer_name = $_POST['name'];
    $registration_no = $_POST['registration_no'];
    $chassis_no = $_POST['chassis_no'];
    $engine_no = $_POST['engine_no'];
    $phone_no = $_POST['phone_no'];
    $vin_no = $_POST['vin_no'];
    $desc = $_POST['description'];
    $mileage = $_POST['mileage'];
    $received = $_POST['received'];
    $deadline = $_POST['deadline'];

    // Total
    $total_sparepart = $_POST['total_sparepart'];
    $total_labour = $_POST['total_labour'];
    $grand_total = $_POST['grand_total'];

    // Status pembayaran
    $total_payment = $_POST['total_payment'];
    $remaining_payment = $_POST['remaining_payment'];
    $payment_status = $_POST['payment_status'];

    $success = true;
    $values = [];
    $insert_query = "INSERT INTO tb_invoice (
        invoice_no, customer_name, registration_no, chassis_no, 
        engine_no, vin_no, description, phone_no, mileage,
        sparepart_name, part_number, quantity, harga_jual, total_harga, 
        discount, labour_name, labour_cost, total_sparepart, 
        total_labour, grand_total, total_payment, remaining_payment, 
        payment_status, category, received, deadline,
        manual_name, manual_jml
    ) VALUES ";

    $types = "";
    $params = [];

    // Proses sparepart reguler
    if (isset($_POST['sparepart_name']) && !empty($_POST['sparepart_name'][0])) {
        foreach ($_POST['sparepart_name'] as $index => $sparepart_name) {
            if (!empty($sparepart_name)) {
                $category = $_POST['category'][$index];
                $quantity = $_POST['jumlah'][$index];
                
                // Cek dan update stok
                if ($category == 'used') {
                    $currentStock = $db->get_stock_sp_used($sparepart_name, $_POST['part_number'][$index]);
                    if ($currentStock >= $quantity) {
                        $newStock = $currentStock - $quantity;
                        $stockUpdated = $db->update_stock_sp_used($sparepart_name, $_POST['part_number'][$index], $newStock);
                    } else {
                        echo "<script>alert('Stok tidak mencukupi untuk $sparepart_name (Used)');window.location.href='../tambah/invoice.php';</script>";
                        exit;
                    }
                } elseif ($category == 'new') {
                    $currentStock = $db->get_stock_sp_new($sparepart_name, $_POST['part_number'][$index]);
                    if ($currentStock >= $quantity) {
                        $newStock = $currentStock - $quantity;
                        $stockUpdated = $db->update_stock_sp_new($sparepart_name, $_POST['part_number'][$index], $newStock);
                    } else {
                        echo "<script>alert('Stok tidak mencukupi untuk $sparepart_name (New)');window.location.href='../tambah/invoice.php';</script>";
                        exit;
                    }
                }
                
                $values[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $types .= "ssssssssssssdddsdddddsssssss";
                array_push(
                    $params,
                    $invoice_no,
                    $customer_name,
                    $registration_no,
                    $chassis_no,
                    $engine_no,
                    $vin_no,
                    $desc,
                    $phone_no,
                    $mileage,
                    $sparepart_name,
                    $_POST['part_number'][$index],
                    $quantity,
                    $_POST['harga_jual'][$index],
                    $_POST['total_harga'][$index],
                    $_POST['discount'][$index],
                    '', // labour_name
                    0,  // labour_cost
                    $total_sparepart,
                    $total_labour,
                    $grand_total,
                    $total_payment,
                    $remaining_payment,
                    $payment_status,
                    $category,
                    $received,
                    $deadline,
                    '', // spare_name
                    0   // spare_jml
                );
            }
        }
    }

    // Proses manual sparepart
    if (isset($_POST['spare_name']) && !empty($_POST['spare_name'][0])) {
        foreach ($_POST['spare_name'] as $index => $spare_name) {
            if (!empty($spare_name)) {
                $values[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $types .= "sssssssssssddddsdddddsssssss";
                array_push(
                    $params,
                    $invoice_no,
                    $customer_name,
                    $registration_no,
                    $chassis_no,
                    $engine_no,
                    $vin_no,
                    $desc,
                    $phone_no,
                    $mileage,
                    '', // sparepart_name
                    '', // part_number
                    0,  // quantity
                    0,  // price
                    0,  // total_price
                    0,  // discount
                    '', // labour_name
                    0,  // labour_cost
                    $total_sparepart,
                    $total_labour,
                    $grand_total,
                    $total_payment,
                    $remaining_payment,
                    $payment_status,
                    '', // category
                    $received,
                    $deadline,
                    $spare_name,
                    $_POST['spare_jml'][$index]
                );
            }
        }
    }

    // Proses labour
    if (isset($_POST['labour_name']) && !empty($_POST['labour_name'][0])) {
        foreach ($_POST['labour_name'] as $index => $labour_name) {
            if (!empty($labour_name)) {
                $values[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $types .= "sssssssssssddddsdddddsssssss";
                array_push(
                    $params,
                    $invoice_no,
                    $customer_name,
                    $registration_no,
                    $chassis_no,
                    $engine_no,
                    $vin_no,
                    $desc,
                    $phone_no,
                    $mileage,
                    '', // sparepart_name
                    '', // part_number
                    0,  // quantity
                    0,  // price
                    0,  // total_price
                    0,  // discount
                    $labour_name,
                    $_POST['labour_cost'][$index],
                    $total_sparepart,
                    $total_labour,
                    $grand_total,
                    $total_payment,
                    $remaining_payment,
                    $payment_status,
                    '', // category
                    $received,
                    $deadline,
                    '', // spare_name
                    0   // spare_jml
                );
            }
        }
    }

    if (!empty($values)) {
        $insert_query .= implode(',', $values);
        $stmt = $db->koneksi->prepare($insert_query);
        $stmt->bind_param($types, ...$params);
        
        if ($stmt->execute()) {
            echo "<script>alert('Data Berhasil Ditambahkan');window.location.href='../invoice.php';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan data');window.location.href='../tambah/invoice.php';</script>";
        }
    } else {
        echo "<script>alert('Tidak ada data untuk ditambahkan');window.location.href='../tambah/invoice.php';</script>";
    }



}elseif($aksi == 'update') {
    // Menangkap data dari form
    $invoice_no = $_POST['invoice_no']; // Nomor Invoice
    $total_payment = $_POST['total_payment']; // Total Pembayaran
    $remaining_payment = $_POST['remaining_payment']; // Sisa Pembayaran
    $payment_status = $_POST['payment_status']; // Status Pembayaran
    

    // Membuat query update berdasarkan invoice_no dan created_at
    $query = "UPDATE tb_invoice 
              SET total_payment = ?, remaining_payment = ?, payment_status = ? 
              WHERE invoice_no = ?";

    // Menyiapkan dan menjalankan query
    $stmt = $db->koneksi->prepare($query);
    if ($stmt === false) {
        die('MySQL prepare error: ' . $db->koneksi->error);
    }
    $stmt->bind_param("ddss", $total_payment, $remaining_payment, $payment_status, $invoice_no);

    // Mengeksekusi query
    if ($stmt->execute()) {
    // Mengecek berapa baris yang terpengaruh
    $affectedRows = $stmt->affected_rows;
    if ($affectedRows > 0) {
        echo "<script>alert('Data pembayaran berhasil diperbarui!'); window.location.href='../invoice.php';</script>";
    } else {
        echo "<script>alert('Tidak ada perubahan pada data pembayaran.'); window.location.href='../edit/invoice.php?invoice_no={$invoice_no}&created_at={$created_at}';</script>";
    }
} else {
    echo "<script>alert('Gagal memperbarui data pembayaran.'); window.location.href='../edit/invoice.php?invoice_no={$invoice_no}&created_at={$created_at}';</script>";
}

    
    
} elseif ($aksi == "delete") {
    // Data untuk penghapusan
    $invoice_no = $_GET['invoice_no'] ?? '';

    if ($invoice_no) {
        // Ambil semua sparepart terkait berdasarkan invoice_no
        $queryFetch = "SELECT category, sparepart_name, part_number, quantity FROM tb_invoice WHERE invoice_no = ?";
        $stmtFetch = $db->koneksi->prepare($queryFetch);
        $stmtFetch->bind_param("s", $invoice_no);
        $stmtFetch->execute();
        $result = $stmtFetch->get_result();

        if ($result->num_rows > 0) {
            $success = true;
            while ($row = $result->fetch_assoc()) {
                $category = $row['category'];
                $sparepart_name = $row['sparepart_name'];
                $part_number = $row['part_number'];
                $jumlah = $row['quantity'];

                if (!empty($sparepart_name) && !empty($part_number) && $jumlah > 0) {
                    // Kembalikan stok sparepart sesuai kategori
                    if ($category == 'used') {
                        $currentStock = $db->get_stock_sp_used($sparepart_name, $part_number);
                        $newStock = $currentStock + $jumlah;
                        $updateStock = $db->update_stock_sp_used($sparepart_name, $part_number, $newStock);
                    } elseif ($category == 'new') {
                        $currentStock = $db->get_stock_sp_new($sparepart_name, $part_number);
                        $newStock = $currentStock + $jumlah;
                        $updateStock = $db->update_stock_sp_new($sparepart_name, $part_number, $newStock);
                    } else {
                        $updateStock = false;
                    }

                    if (!$updateStock) {
                        $success = false;
                        break; // Hentikan jika gagal mengembalikan stok
                    }
                }
            }

            // Jika semua stok berhasil dikembalikan, hapus data invoice
            if ($success) {
                $queryDelete = "DELETE FROM tb_invoice WHERE invoice_no = ?";
                $stmtDelete = $db->koneksi->prepare($queryDelete);
                $stmtDelete->bind_param("s", $invoice_no);
                $stmtDelete->execute();

                if ($stmtDelete->affected_rows > 0) {
                    echo "<script>alert('Data invoice berhasil dihapus dan stok dikembalikan!'); window.location.href='../invoice.php';</script>";
                } else {
                    echo "<script>alert('Gagal menghapus data invoice. Pastikan invoice ada dalam database.'); window.location.href='../invoice.php';</script>";
                }
            } else {
                echo "<script>alert('Gagal memperbarui stok. Pastikan data sparepart valid.'); window.location.href='../invoice.php';</script>";
            }
        } else {
            echo "<script>alert('Data invoice tidak ditemukan atau sudah dihapus sebelumnya.'); window.location.href='../invoice.php';</script>";
        }
    } else {
        echo "<script>alert('Parameter invoice_no tidak ditemukan.'); window.location.href='../invoice.php';</script>";
    }
}
?>
<!-- belum selesai -->