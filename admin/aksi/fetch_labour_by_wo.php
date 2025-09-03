<?php
include('../config_query.php'); 
$db = new database();

$registration_no = $_GET['registration_no'] ?? '';
$mileage = $_GET['mileage'] ?? '';
$created_at = $_GET['created_at'] ?? '';

if (empty($registration_no) || empty($mileage) || empty($created_at)) {
    echo json_encode([]);
    exit;
}

// Query untuk mengambil Labour berdasarkan Work Order yang dipilih dari tb_wo
$query = "SELECT DISTINCT labour_name, customer_name, registration_no, chassis_no, engine_no, phone_no, vin_no, desk FROM tb_wo
          WHERE registration_no = ? AND mileage = ? AND created_at = ?";
$stmt = $db->koneksi->prepare($query);
$stmt->bind_param("sss", $registration_no, $mileage, $created_at);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row['labour_name'];
}

echo json_encode($data);
?>
