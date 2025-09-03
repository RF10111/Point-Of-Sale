<?php
include('../config_query.php');
$db = new Database();
session_start();
$id_users = $_SESSION['id_users'];
$aksi = $_GET['aksi'];

if ($aksi == "add") {
    $name = $_POST['customer_name'];
    $registration_no = $_POST['registration_no'];
    $chassis_no = $_POST['chassis_no'];
    $engine_no = $_POST['engine_no'];
    $vin_no = $_POST['vin_no'];
    $desc = $_POST['desc'];
    $phone_no = $_POST['phone_no'];

    $insertData = $db->tambah_customer($name, $registration_no, $chassis_no, $phone_no, $engine_no, $vin_no, $desc);

    if ($insertData) {
        echo "<script>alert('Data Berhasil Ditambahkan');window.location.href='../index.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Ditambahkan');window.location.href='../tambah/customer.php';</script>";
    }
} elseif ($aksi == "edit") {
    $id = $_POST['id_customer'];
    $name = $_POST['customer_name'];
    $registration_no = $_POST['registration_no'];
    $chassis_no = $_POST['chassis_no'];
    $engine_no = $_POST['engine_no'];
    $vin_no = $_POST['vin_no'];
    $desc = $_POST['desc'];
    $phone_no = $_POST['phone_no'];

    $updateData = $db->update_customer($id, $name, $registration_no, $chassis_no, $phone_no, $engine_no, $vin_no, $desc);

    if ($updateData) {
        echo "<script>alert('Data Berhasil Diupdate');window.location.href='../index.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Diupdate');window.location.href='edit_customer.php?id=$id';</script>";
    }
} elseif ($aksi == "delete") {
    $id = $_GET['id_customer'];
    $deleteData = $db->delete_customer($id);

    if ($deleteData) {
        echo "<script>alert('Data Berhasil Dihapus');window.location.href='../index.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Dihapus');window.location.href='../index.php';</script>";
    }
} else {
    echo "<script>alert('Operasi tidak diizinkan');window.location.href='../index.php';</script>";
}
?>
