<?php
include('../config_query.php');
$db = new Database();
session_start();
$id_users = $_SESSION['id_users'];
$aksi = $_GET['aksi'];

if ($aksi == "add") {
    $name = $_POST['mekanik_name'];
    $alamat = $_POST['address'];
    $phone_no = $_POST['phone_no'];

    $insertData = $db->tambah_mekanik($name, $alamat, $phone_no);

    if ($insertData) {
        echo "<script>alert('Data Berhasil Ditambahkan');window.location.href='../mekanik.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Ditambahkan');window.location.href='../tambah/mekanik.php';</script>";
    }
} elseif ($aksi == "edit") {
    $id = $_POST['id_mekanik'];
    $name = $_POST['mekanik_name'];
    $alamat = $_POST['address'];
    $phone_no = $_POST['phone_no'];

    $updateData = $db->update_mekanik($id, $name, $alamat, $phone_no);

    if ($updateData) {
        echo "<script>alert('Data Berhasil Diupdate');window.location.href='../mekanik.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Diupdate');window.location.href='edit_mekanik.php?id=$id';</script>";
    }
} elseif ($aksi == "delete") {
    $id = $_GET['id_mekanik'];
    $deleteData = $db->delete_mekanik($id);

    if ($deleteData) {
        echo "<script>alert('Data Berhasil Dihapus');window.location.href='../mekanik.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Dihapus');window.location.href='../mekanik.php';</script>";
    }
} else {
    echo "<script>alert('Operasi tidak diizinkan');window.location.href='../mekanik.php';</script>";
}
?>
