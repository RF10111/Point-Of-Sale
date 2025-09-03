<?php
include('../config_query.php');
$db = new Database();
session_start();

$aksi = $_GET['aksi'];

if ($aksi == "add") {
    // Data pelanggan
    $customer_name = $_POST['name'];
    $registration_no = $_POST['registration_no'];
    $chassis_no = $_POST['chassis_no'];
    $engine_no = $_POST['engine_no'];
    $phone_no = $_POST['phone_no'];
    $mileage = $_POST['mileage'];

    // Total
    $total_sparepart = $_POST['total_sparepart'];
    $total_labour = $_POST['total_labour'];
    $grand_total = $_POST['grand_total'];

    $success = true;
    $values = [];
    $insert_query = "INSERT INTO tb_estimasi (
        customer_name, registration_no, chassis_no, engine_no, phone_no, mileage,
        sparepart_name, part_number, quantity, harga_jual, total_harga, 
        labour_name, labour_cost, total_sparepart, total_labour, grand_total
    ) VALUES ";

    $types = "";
    $params = [];

    // Proses sparepart
    if (isset($_POST['sparepart_name']) && !empty($_POST['sparepart_name'][0])) {
        foreach ($_POST['sparepart_name'] as $index => $sparepart_name) {
            if (!empty($sparepart_name)) {
                $values[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $types .= "ssssssssdddsdddd";
                array_push(
                    $params,
                    $customer_name,
                    $registration_no,
                    $chassis_no,
                    $engine_no,
                    $phone_no,
                    $mileage,
                    $sparepart_name,
                    $_POST['part_number'][$index],
                    $_POST['jumlah'][$index],
                    $_POST['harga_jual'][$index],
                    $_POST['total_harga'][$index],
                    '', // labour_name
                    0,  // labour_cost
                    $total_sparepart,
                    $total_labour,
                    $grand_total
                );
            }
        }
    }

    // Proses labour
    if (isset($_POST['labour_name']) && !empty($_POST['labour_name'][0])) {
        foreach ($_POST['labour_name'] as $index => $labour_name) {
            if (!empty($labour_name)) {
                $values[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $types .= "ssssssssdddsdddd";
                array_push(
                    $params,
                    $customer_name,
                    $registration_no,
                    $chassis_no,
                    $engine_no,
                    $phone_no,
                    $mileage,
                    '', // sparepart_name
                    '', // part_number
                    0,  // quantity
                    0,  // harga_jual
                    0,  // total_harga
                    $labour_name,
                    $_POST['labour_cost'][$index],
                    $total_sparepart,
                    $total_labour,
                    $grand_total
                );
            }
        }
    }

    if (!empty($values)) {
        $insert_query .= implode(',', $values);
        
        // Debugging: Cek query sebelum dieksekusi
        // echo $insert_query;
        // print_r($params);
        // exit;

        $stmt = $db->koneksi->prepare($insert_query);
        
        // Pastikan jumlah parameter sesuai dengan jumlah placeholder `?`
        if (count(str_split($types)) !== count($params)) {
            die("Error: Jumlah tipe data tidak sesuai dengan jumlah parameter.");
        }

        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            echo "<script>alert('Data Berhasil Ditambahkan');window.location.href='../estimasi.php';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan data');window.location.href='../tambah/estimasi.php';</script>";
        }
    } else {
        echo "<script>alert('Tidak ada data untuk ditambahkan');window.location.href='../tambah/estimasi.php';</script>";
    }


} elseif ($aksi == "delete") {
    // Data untuk penghapusan
    $created_at = $_GET['created_at'] ?? '';
    $registration_no = $_GET['registration_no'] ?? '';

    if ($created_at && $registration_no) {
        // Query untuk menghapus data
        $query = "DELETE FROM tb_estimasi WHERE created_at = ? AND registration_no = ?";
        $stmt = $db->koneksi->prepare($query);
        $stmt->bind_param("ss", $created_at, $registration_no);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Data estimasi berhasil dihapus!'); window.location.href='../estimasi.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data estimasi.'); window.location.href='../estimasi.php';</script>";
        }
    } else {
        echo "<script>alert('Parameter tidak lengkap.'); window.location.href='../estimasi.php';</script>";
    }
} else {
    echo "<script>alert('Operasi tidak valid.'); window.location.href='../estimasi.php';</script>";
}
?>
<!-- belum selesai -->