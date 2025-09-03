<?php
include('../config_query.php');
$db = new Database();
session_start();

$aksi = $_GET['aksi'];

if ($aksi == "add") {
    $mekanik_name = $_POST['mekanik_name'];
    $spareparts = $_POST['sparepart_name'];
    $part_numbers = $_POST['part_number'];
    $quantities = $_POST['jumlah'];
    $categories = $_POST['category'];
    $customers = $_POST['customer_regist'];

    foreach ($spareparts as $index => $sparepart_name) {
        $part_number = $part_numbers[$index];
        $quantity = $quantities[$index];
        $category = $categories[$index];

        // Simpan ke database
        $db->tambah_reservasi($mekanik_name, $sparepart_name, $part_number, $quantity, $category, $customers);
    }

    echo "<script>alert('Reservasi berhasil ditambahkan!'); window.location.href='../reservasi.php';</script>";
} elseif ($aksi == "edit") {
    $id = $_POST['id'];
    $mekanik_name = $_POST['mekanik_name'];
    $sparepart_name = $_POST['sparepart_name'];
    $part_number = $_POST['part_number'];
    $quantity = $_POST['jumlah'];
    $categories = $_POST['category'];
    $customers = $_POST['customer_regist'];

    $updateData = $db->update_reservasi($id, $mekanik_name, $sparepart_name, $part_number, $quantity, $category, $customers);

    if ($updateData) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='../reservasi.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data.'); window.location.href='../edit_reservasi.php?id=$id';</script>";
    }
    
} elseif ($aksi == "delete") {
    $created_at = $_GET['created_at'] ?? '';
    $mekanik_name = $_GET['mekanik_name'] ?? '';
    $customers = $_GET['customer'] ?? '';

    if ($created_at && $mekanik_name && $customers) {
        // Query untuk menghapus data
        $query = "DELETE FROM tb_reservasi WHERE created_at = ? AND mekanik_name = ? AND customer = ?";
        $stmt = $db->koneksi->prepare($query);
        $stmt->bind_param("sss", $created_at, $mekanik_name, $customers);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Reservasi berhasil dihapus!'); window.location.href='../reservasi.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus reservasi.'); window.location.href='../reservasi.php';</script>";
        }
    } else {
        echo "<script>alert('Parameter tidak lengkap.'); window.location.href='../reservasi.php';</script>";
    }
} else {
    echo "<script>alert('Operasi tidak valid.'); window.location.href='../reservasi.php';</script>";
}
?>