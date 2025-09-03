<?php
include('../config_query.php'); // Pastikan file konfigurasi sudah tersedia
$db = new database(); // Inisialisasi class database

$query = "SELECT id_customer, name, registration_no, chassis_no, phone_no, engine_no, vin_no, description FROM tb_customer";
$result = $db->koneksi->query($query);

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>
