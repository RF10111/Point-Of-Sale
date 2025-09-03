<?php
include('../config_query.php');
$db = new Database();
session_start();
$id_users = $_SESSION['id_users'];
$aksi = $_GET['aksi'];

if ($aksi == "edit") {
    $id = $_POST['id'];
    $sparepart_name = $_POST['sparepart_name'];
    $part_number = $_POST['part_number'];
    $stock = $_POST['quantity'];
    $harga_pokok = $_POST['unit_price'];
    $tempat = $_POST['tempat'];

    $updateData = $db->update_sp_used($id, $sparepart_name, $part_number, $stock, $harga_pokok, $tempat);

    if ($updateData) {
        echo "<script>alert('Data Berhasil Diupdate');window.location.href='../sp_used.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Diupdate');window.location.href='edit_sp_used.php?id=$id';</script>";
    }
} elseif ($aksi == "delete") {
    $id = $_GET['id'];
    $deleteData = $db->delete_sp_used($id);

    if ($deleteData) {
        echo "<script>alert('Data Berhasil Dihapus');window.location.href='../sp_used.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Dihapus');window.location.href='../sp_used.php';</script>";
    }
} else {
    echo "<script>alert('Operasi tidak diizinkan');window.location.href='../sp_used.php';</script>";
}
?>
