<?php
include('../config_query.php');
$db = new database();
session_start();
$id_users = $_SESSION['id_users'];

$aksi = $_GET['aksi'];

if ($aksi == "add") {
    $supplier_name = $_POST['supplier_name'];
    $sparepart_name = $_POST['sparepart_name'];
    $category = $_POST['category'];
    $arrival_date = $_POST['arrival_date'];
    $part_number = $_POST['part_number'];
    $quantity = $_POST['quantity'];
    $price = (float)$_POST['price'];
    $total_payment = (float)$_POST['total_payment'];
    $harga_pokok = $quantity > 0 ? $price / $quantity : 0;
    $remaining_payment = $price - $total_payment;
    $payment_status = $remaining_payment <= 0 ? 'Lunas' : 'Belum Lunas';

    if ($category == 'used') {
        $sparepart_name .= ' (used)';
    }

    // Normalisasi nama sparepart dan part_number untuk pengecekan
    // Hapus spasi dan ubah ke huruf kecil, hanya menyisakan huruf dan angka
    $normalized_sparepart = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($sparepart_name));
    $normalized_part_number = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($part_number));

    // Cek apakah spare part sudah ada berdasarkan nama dan nomor spare part yang dinormalisasi
    if ($category == 'used') {
        $existingPart = $db->cek_sp_used_normalized($normalized_sparepart, $normalized_part_number);
    } elseif ($category == 'new') {
        $existingPart = $db->cek_sp_new_normalized($normalized_sparepart, $normalized_part_number);
    }

    if ($existingPart) {
        // Spare part sudah ada, tambahkan stoknya saja
        $new_quantity = $existingPart['quantity'] + $quantity;
        if ($category == 'used') {
            $db->update_stock_sp_used_order($existingPart['id'], $new_quantity, $harga_pokok);
        } elseif ($category == 'new') {
            $db->update_stock_sp_new_order($existingPart['id'], $new_quantity, $harga_pokok);
        }
    } else {
        // Spare part belum ada, tambahkan ke tabel kategori
        if ($category == 'used') {
            $db->tambah_sp_used($sparepart_name, $part_number, $quantity, $harga_pokok);
        } elseif ($category == 'new') {
            $db->tambah_sp_new($sparepart_name, $part_number, $quantity, $harga_pokok);
        }
    }



    // Selalu tambahkan ke tb_order (untuk pencatatan transaksi)
    $insertOrder = $db->tambah_order(
        $supplier_name, 
        $sparepart_name, 
        $category, 
        $arrival_date, 
        $part_number, 
        $quantity, 
        $price, 
        $harga_pokok, 
        $total_payment, 
        $remaining_payment, 
        $payment_status
    );

    if ($insertOrder) {
        echo "<script>alert('Order berhasil diproses');window.location.href='../order.php';</script>";
    } else {
        echo "<script>alert('Order gagal diproses');window.location.href='../tambah/order.php';</script>";
    }

} elseif ($aksi == "edit") {
    $id = $_POST['id_order'];
    $total_payment = (float)$_POST['total_payment'];
    $remaining_payment = $_POST['remaining_payment'];
    $payment_status = $_POST['payment_status'];

    $updateData = $db->update_order($id, $total_payment, $remaining_payment, $payment_status);

    if ($updateData) {
        echo "<script>alert('Order berhasil diupdate');window.location.href='../order.php';</script>";
    } else {
        echo "<script>alert('Order gagal diupdate');window.location.href='edit_order.php?id=$id';</script>";
    }
    
} elseif ($aksi == "delete") {
    $id = $_GET['id_order'];

    // Ambil data order sebelum dihapus
    $orderData = $db->get_order_by_id($id);
    if ($orderData) {
        $category = $orderData['category'];
        $sparepart_name = $orderData['sparepart_name'];
        $part_number = $orderData['part_number'];
        $quantity = $orderData['quantity'];

        // Kurangi stok dari tabel sesuai kategori
        if ($category == 'used') {
            $existingPart = $db->cek_sp_used($sparepart_name, $part_number);
            if ($existingPart) {
                $new_quantity = $existingPart['quantity'] - $quantity;
                $new_quantity = max(0, $new_quantity); // Hindari negatif
                $db->update_stock_sp_used_order($existingPart['id'], $new_quantity, $existingPart['harga_pokok']);
            }
        } elseif ($category == 'new') {
            $existingPart = $db->cek_sp_new($sparepart_name, $part_number);
            if ($existingPart) {
                $new_quantity = $existingPart['quantity'] - $quantity;
                $new_quantity = max(0, $new_quantity); // Hindari negatif
                $db->update_stock_sp_new_order($existingPart['id'], $new_quantity, $existingPart['harga_pokok']);
            }
        }

        // Hapus data order setelah stok dikurangi
        $deleteData = $db->delete_order($id);

        if ($deleteData) {
            echo "<script>alert('Order dan stok berhasil dihapus');window.location.href='../order.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus order');window.location.href='../order.php';</script>";
        }
    } else {
        echo "<script>alert('Data order tidak ditemukan');window.location.href='../order.php';</script>";
    }
}
?>