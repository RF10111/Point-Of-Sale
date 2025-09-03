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
    $vin_no = $_POST['vin_no'];
    $desc = $_POST['description'];
    $mileage = $_POST['mileage'];
    $desc = $_POST['description'];
    

    // Data labour
    $labour_names = $_POST['labour_name'];
    $labour_kets = $_POST['labour_ket'];

    foreach ($labour_names as $index => $labour_name) {
        $labour_ket = isset($labour_kets[$index]) ? $labour_kets[$index] : null;

        $insertData = $db->tambah_wo(
            $customer_name,
            $registration_no, 
            $chassis_no,
            $engine_no,
            $phone_no,
            $vin_no,
            $mileage,
            $desc,
            $labour_name,
            $labour_ket
        );
    }

    if ($insertData) {
        echo "<script>alert('Data Berhasil Ditambahkan');window.location.href='../wo.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Ditambahkan');window.location.href='../tambah/wo.php';</script>";
    }


} elseif ($aksi == "delete") {
    // Data untuk penghapusan
    $created_at = $_GET['created_at'] ?? '';
    $registration_no = $_GET['registration_no'] ?? '';

    if ($created_at && $registration_no) {
        // Query untuk menghapus data
        $query = "DELETE FROM tb_wo WHERE created_at = ? AND registration_no = ?";
        $stmt = $db->koneksi->prepare($query);
        $stmt->bind_param("ss", $created_at, $registration_no);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Data wo berhasil dihapus!'); window.location.href='../wo.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data wo.'); window.location.href='../wo.php';</script>";
        }
    } else {
        echo "<script>alert('Parameter tidak lengkap.'); window.location.href='../wo.php';</script>";
    }
} else {
    echo "<script>alert('Operasi tidak valid.'); window.location.href='../wo.php';</script>";
}
?>
