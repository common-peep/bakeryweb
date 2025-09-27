<?php
require_once __DIR__ . '/../inc/config.php';
header('Content-Type: application/json');

$stmt = $pdo->query('SELECT id, code, description, discount_type, discount_value, min_order FROM vouchers ORDER BY id DESC');
$out = $stmt->fetchAll();
echo json_encode($out);
