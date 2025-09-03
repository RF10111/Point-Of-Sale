<?php
include('../config_query.php');
$db = new Database();
session_start();
$id_users = $_SESSION['id_users'];
$aksi = $_GET['aksi'];

if ($aksi == "add") {
    $name = $_POST['supplier_name'];
    $alamat = $_POST['alamat'];
    $phone_no = $_POST['phone_no'];

    $insertData = $db->tambah_supplier($name, $alamat, $phone_no);

    if ($insertData) {
        echo "<script>alert('Data Berhasil Ditambahkan');window.location.href='../supplier.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Ditambahkan');window.location.href='../tambah/supplier.php';</script>";
    }
} elseif ($aksi == "edit") {
    $id = $_POST['id_supplier'];
    $name = $_POST['supplier_name'];
    $alamat = $_POST['alamat'];
    $phone_no = $_POST['phone_no'];

    $updateData = $db->update_supplier($id, $name, $alamat, $phone_no);

    if ($updateData) {
        echo "<script>alert('Data Berhasil Diupdate');window.location.href='../supplier.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Diupdate');window.location.href='edit_supplier.php?id=$id';</script>";
    }
} elseif ($aksi == "delete") {
    $id = $_GET['id_supplier'];
    $deleteData = $db->delete_supplier($id);

    if ($deleteData) {
        echo "<script>alert('Data Berhasil Dihapus');window.location.href='../supplier.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Dihapus');window.location.href='../supplier.php';</script>";
    }
} else {
    echo "<script>alert('Operasi tidak diizinkan');window.location.href='../supplier.php';</script>";
}
?>
