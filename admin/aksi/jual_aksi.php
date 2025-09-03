<?php
include('../config_query.php');
$db = new database();
session_start();
$id_users = $_SESSION['id_users'];

$aksi = $_GET['aksi'];

if ($aksi == "add") {
    $customer_name = $_POST['customer_name'];
    $tgl_transaksi = $_POST['tgl_transaksi'];
    $sparepart_name = $_POST['sparepart_name'];
    $category = $_POST['category'];
    $part_number = $_POST['part_number'];
    $quantity = $_POST['quantity'];
    $jumlah = $_POST['jumlah'];
    $harga_pokok = (float)$_POST['harga_pokok'];
    $harga_jual = (float)$_POST['harga_jual'];
    $total = (float)$_POST['total'];
    $total_payment = (float)$_POST['total_payment'];
    $remaining_payment = $total - $total_payment;
    $payment_status = $remaining_payment <= 0 ? 'Lunas' : 'Belum Lunas';
    $profit = (float)$_POST['profit'];

    // Cek stok terlebih dahulu sebelum melakukan transaksi
    $currentStock = 0;
    if ($category == 'used') {
        $currentStock = $db->get_stock_sp_used($sparepart_name, $part_number);
    } elseif ($category == 'new') {
        $currentStock = $db->get_stock_sp_new($sparepart_name, $part_number);
    }

    // Validasi apakah stok mencukupi
    if ($currentStock < $jumlah) {
        echo "<script>alert('Stok tidak mencukupi! Stok tersedia: $currentStock, Jumlah yang diminta: $jumlah');window.location.href='../tambah/jual.php';</script>";
        exit; // Keluar dari proses jika stok tidak mencukupi
    }

    // Jika stok mencukupi, lanjutkan proses
    // Tambah data ke tb_jual
    $insertData = $db->tambah_jual(
        $customer_name, 
        $tgl_transaksi, 
        $sparepart_name, 
        $category, 
        $part_number, 
        $quantity, 
        $jumlah, 
        $harga_pokok, 
        $harga_jual, 
        $total, 
        $total_payment, 
        $remaining_payment, 
        $payment_status, 
        $profit
    );

    // Kurangi quantity di tabel sparepart sesuai kategori
    $newStock = $currentStock - $jumlah;
    if ($category == 'used') {
        $updateStock = $db->update_stock_sp_used($sparepart_name, $part_number, $newStock);
    } elseif ($category == 'new') {
        $updateStock = $db->update_stock_sp_new($sparepart_name, $part_number, $newStock);
    }

    if ($insertData && $updateStock) {
        echo "<script>alert('Penjualan berhasil ditambahkan');window.location.href='../jual.php';</script>";
    } else {
        echo "<script>alert('Penjualan gagal ditambahkan');window.location.href='../tambah/jual.php';</script>";
    }
    
} elseif ($aksi == "edit") {
    $id_jual = $_POST['id_jual'];
    $total_payment = $_POST['total_payment'];
    $remaining_payment = $_POST['remaining_payment'];
    $payment_status = $_POST['payment_status'];
    
    // Update data di tb_jual
    $updateData = $db->update_jual(
        $id_jual,
        $total_payment, 
        $remaining_payment, 
        $payment_status, 
    );

    if ($updateData) {
        echo "<script>alert('Penjualan berhasil diupdate');window.location.href='../jual.php';</script>";
    } else {
        echo "<script>alert('Penjualan gagal diupdate');window.location.href='edit_jual.php?id=$id_jual';</script>";
    }
    
} elseif ($aksi == "delete") {
    $id_jual = $_GET['id_jual'];
    
    // Ambil data penjualan yang akan dihapus
    $deletedSaleData = $db->get_jual_by_id($id_jual);

    // Hapus data dari tb_jual
    $deleteData = $db->delete_jual($id_jual);

    // Kembalikan stok ke tabel sparepart
    if ($deletedSaleData['category'] == 'used') {
        $currentStock = $db->get_stock_sp_used($deletedSaleData['sparepart_name'], $deletedSaleData['part_number']);
        $restoreStock = $currentStock + $deletedSaleData['jumlah'];
        $updateStock = $db->update_stock_sp_used($deletedSaleData['sparepart_name'], $deletedSaleData['part_number'], $restoreStock);
    } elseif ($deletedSaleData['category'] == 'new') {
        $currentStock = $db->get_stock_sp_new($deletedSaleData['sparepart_name'], $deletedSaleData['part_number']);
        $restoreStock = $currentStock + $deletedSaleData['jumlah'];
        $updateStock = $db->update_stock_sp_new($deletedSaleData['sparepart_name'], $deletedSaleData['part_number'], $restoreStock);
    }

    if ($deleteData && $updateStock) {
        echo "<script>alert('Penjualan berhasil dihapus');window.location.href='../jual.php';</script>";
    } else {
        echo "<script>alert('Penjualan gagal dihapus');window.location.href='../jual.php';</script>";
    }
} else {
    echo "<script>alert('Operasi tidak valid');window.location.href='../jual.php';</script>";
}
?>