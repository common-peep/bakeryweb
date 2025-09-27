<?php
require_once __DIR__ . '/../inc/config.php';
header('Content-Type: application/json');
$stmt = $pdo->query('SELECT id, name, address, phone FROM outlets ORDER BY id DESC');
$out = $stmt->fetchAll();
echo json_encode($out);
