<?php
include('../config_query.php');
$db = new database(); // Pastikan class database tersedia dan sudah di-instantiate

$category = $_GET['category'];
$table = ($category === 'new') ? 'tb_sp_new' : 'tb_sp_used';

// Hindari SQL Injection dengan memverifikasi nilai category
if (!in_array($category, ['new', 'used'])) {
    echo json_encode(['error' => 'Invalid category']);
    exit;
}

$query = "SELECT id, sparepart_name AS name, part_number, quantity, harga_pokok FROM $table";
$query .= " ORDER BY sparepart_name ASC";
$result = $db->koneksi->query($query);

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>
