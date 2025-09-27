<?php

// get_outlets.php
header('Content-Type: application/json');
// gunakan path yang benar ke db.php
require __DIR__ . '/../config/db.php'; // __DIR__ = folder api/, jadi ../ = bakery/


$result = $conn->query("SELECT id, name, address FROM outlets ORDER BY name ASC");
$outlets = [];

while ($row = $result->fetch_assoc()) {
    $outlets[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'address' => $row['address']
    ];
}

echo json_encode($outlets);
