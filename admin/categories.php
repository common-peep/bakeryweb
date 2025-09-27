<?php
session_start();
require_once "../inc/config.php";

// proteksi login
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

// --- Tambah Kategori ---
if (isset($_POST["add"])) {
    $name = trim($_POST["name"]);
    $slug = strtolower(str_replace(" ", "-", $name));

    if ($name !== "") {
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
        $stmt->execute([$name, $slug]);
        header("Location: categories.php");
        exit;
    }
}

// --- Hapus Kategori ---
if (isset($_GET["delete"])) {
    $id = (int) $_GET["delete"];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: categories.php");
    exit;
}

// --- Ambil Semua Kategori ---
$stmt = $pdo->query("SELECT * FROM categories ORDER BY id DESC");
$categories = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Kategori</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f4f4f4; }
    .nav { margin-bottom: 20px; }
    .nav a { margin-right: 15px; text-decoration: none; }
    .form-box { background: white; padding: 20px; border-radius: 6px; margin-bottom: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.2);}
    .table { width: 100%; border-collapse: collapse; background: white; border-radius: 6px; overflow: hidden; }
    .table th, .table td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
    .table th { background: #333; color: #fff; }
    .btn { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; }
    .btn-delete { background: #e74c3c; color: white; }
    .btn-add { background: #2ecc71; color: white; }
  </style>
</head>
<body>
  <h1>Manajemen Kategori</h1>
  <div class="nav">
    <a href="dashboard.php">Dashboard</a>
    <a href="categories.php">Kategori</a>
    <a href="products.php">Produk</a>
    <a href="outlets.php">Outlet</a>
    <a href="vouchers.php">Voucher</a>
    <a href="logout.php">Logout</a>
  </div>

  <!-- Form tambah kategori -->
  <div class="form-box">
    <h2>Tambah Kategori</h2>
    <form method="POST">
      <input type="text" name="name" placeholder="Nama kategori" required>
      <button type="submit" name="add" class="btn btn-add">Tambah</button>
    </form>
  </div>

  <!-- Daftar kategori -->
  <table class="table">
    <tr>
      <th>ID</th>
      <th>Nama</th>
      <th>Slug</th>
      <th>Aksi</th>
    </tr>
    <?php foreach ($categories as $c): ?>
    <tr>
      <td><?= $c["id"] ?></td>
      <td><?= htmlspecialchars($c["name"]) ?></td>
      <td><?= $c["slug"] ?></td>
      <td>
        <a href="categories.php?delete=<?= $c["id"] ?>" onclick="return confirm('Yakin hapus kategori ini?')">
          <button class="btn btn-delete">Hapus</button>
        </a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
