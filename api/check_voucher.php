<?php
require_once __DIR__ . '/../inc/config.php'; // koneksi PDO

header("Content-Type: application/json");

$code = strtoupper(trim($_GET["code"] ?? ""));

if ($code === "") {
    echo json_encode(["valid" => false, "msg" => "Kode kosong"]);
    exit;
}

$stmt = $pdo->prepare("SELECT id, code, description, discount_type, discount_value, min_purchase FROM vouchers WHERE code=? LIMIT 1");
$stmt->execute([$code]);
$voucher = $stmt->fetch();

if (!$voucher) {
    echo json_encode(["valid" => false, "msg" => "Kode tidak ditemukan"]);
    exit;
}

echo json_encode([
    "valid" => true,
    "code" => $voucher["code"],
    "description" => $voucher["description"],
    "discount_type" => $voucher["discount_type"],
    "discount_value" => (float) $voucher["discount_value"],
    "min_purchase" => (float) $voucher["min_purchase"]
]);

