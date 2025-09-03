<?php
include('../config_query.php'); // Pastikan file konfigurasi sudah tersedia
$db = new database(); // Inisialisasi class database

// Query untuk mengambil data Work Order
$query = "SELECT DISTINCT registration_no, mileage, created_at, customer_name, 
                 registration_no, chassis_no, engine_no, phone_no, vin_no, desk 
          FROM tb_wo 
          ORDER BY created_at DESC";
 // Sesuaikan dengan tabel dan kolom yang ada
$result = $db->koneksi->query($query);

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data); // Mengembalikan data dalam format JSON
?>