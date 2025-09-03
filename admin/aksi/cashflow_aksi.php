<?php
include('../config_query.php');
$db = new Database();
session_start();
$id_users = $_SESSION['id_users'];
$aksi = $_GET['aksi'];

if ($aksi == "add") {
    $tanggal = $_POST['tanggal'];
    $jumlah = $_POST['jumlah'];
    $ket = $_POST['ket'];

    $insertData = $db->tambah_cashflow($tanggal, $jumlah, $ket);

    if ($insertData) {
        echo "<script>alert('Data Berhasil Ditambahkan');window.location.href='../cashflow.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Ditambahkan');window.location.href='../tambah/cashflow.php';</script>";
    }
} elseif ($aksi == "edit") {
    $id = $_POST['id_cashflow'];
    $tanggal = $_POST['tanggal'];
    $jumlah = $_POST['jumlah'];
    $ket = $_POST['ket'];

    $updateData = $db->update_cashflow($id, $tanggal, $jumlah, $ket);

    if ($updateData) {
        echo "<script>alert('Data Berhasil Diupdate');window.location.href='../cashflow.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Diupdate');window.location.href='edit_cashflow.php?id=$id';</script>";
    }
} elseif ($aksi == "delete") {
    $id = $_GET['id_cashflow'];
    $deleteData = $db->delete_cashflow($id);

    if ($deleteData) {
        echo "<script>alert('Data Berhasil Dihapus');window.location.href='../cashflow.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Dihapus');window.location.href='../cashflow.php';</script>";
    }
} else {
    echo "<script>alert('Operasi tidak diizinkan');window.location.href='../cashflow.php';</script>";
}
?>
