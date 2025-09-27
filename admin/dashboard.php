<?php
session_start();
require_once "../inc/config.php";

// proteksi login
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

// ambil data ringkasan
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalCategories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$totalOutlets = $pdo->query("SELECT COUNT(*) FROM outlets")->fetchColumn();
$totalVouchers = $pdo->query("SELECT COUNT(*) FROM vouchers")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f4f4f4; }
    .nav { margin-bottom: 20px; }
    .nav a { margin-right: 15px; text-decoration: none; }
    .card { display: inline-block; background: white; padding: 20px; margin: 10px; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.2); }
  </style>
</head>
<body>
  <h1>Dashboard Admin</h1>
  <div class="nav">
    <a href="dashboard.php">Dashboard</a>
    <a href="categories.php">Kategori</a>
    <a href="products.php">Produk</a>
    <a href="outlets.php">Outlet</a>
    <a href="vouchers.php">Voucher</a>
    <a href="logout.php">Logout</a>
  </div>

  <div class="card">
    <h3>Total Produk</h3>
    <p><?= $totalProducts ?></p>
  </div>
  <div class="card">
    <h3>Total Kategori</h3>
    <p><?= $totalCategories ?></p>
  </div>
  <div class="card">
    <h3>Total Outlet</h3>
    <p><?= $totalOutlets ?></p>
  </div>
  <div class="card">
    <h3>Total Voucher</h3>
    <p><?= $totalVouchers ?></p>
  </div>
</body>
</html>
