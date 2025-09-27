<?php
require_once __DIR__ . '/../inc/config.php';
header('Content-Type: application/json');

$stmt = $pdo->query('SELECT p.id, p.name, p.description, p.price, p.stock, p.category_id, c.name as category FROM products p LEFT JOIN categories c ON p.category_id=c.id ORDER BY p.id DESC');
$rows = $stmt->fetchAll();

$results = [];
foreach ($rows as $r) {
    $image = null;
    if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $r['description'], $m)) {
        $image = $m[1];
    }
    $results[] = [
        'id' => (int)$r['id'],
        'name' => $r['name'],
        'description' => $r['description'],
        'price' => (int)$r['price'],
        'stock' => (int)$r['stock'],
        'category_id' => $r['category_id'],
        'category' => $r['category'],
        'image' => $image
    ];
}
echo json_encode($results);
