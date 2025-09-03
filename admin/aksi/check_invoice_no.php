<?php
include("../config_query.php");
$db = new database();

$invoice_no = $_GET['invoice_no'] ?? '';

// Cek apakah invoice_no sudah ada
$query = "SELECT COUNT(*) as count FROM tb_invoice WHERE invoice_no = ?";
$result = $db->query($query, [$invoice_no])->fetch();

header('Content-Type: application/json');
echo json_encode(['exists' => $result['count'] > 0]);
