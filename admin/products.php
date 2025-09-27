<?php
session_start();
require_once "../inc/config.php";

// proteksi login
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

// Ambil semua kategori (buat dropdown)
$catStmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $catStmt->fetchAll();

// --- Tambah Produk ---
if (isset($_POST["add"])) {
    $name = trim($_POST["name"]);
    $desc = trim($_POST["description"]);
    $price = (int) $_POST["price"];
    $stock = (int) $_POST["stock"];
    $category_id = (int) $_POST["category_id"];
    $imagePath = null;

    // upload gambar
    if (!empty($_FILES["image"]["name"])) {
        $targetDir = "../uploads/products/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = time() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $imagePath = "uploads/products/" . $fileName;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO products (category_id, name, description, price, stock) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$category_id, $name, $desc, $price, $stock]);
    $productId = $pdo->lastInsertId();

    // simpan path gambar ke DB kalau ada
    if ($imagePath) {
        $pdo->prepare("UPDATE products SET description = CONCAT(IFNULL(description,''), '\n<img src=\"$imagePath\">') WHERE id=?")
            ->execute([$productId]);
    }

    header("Location: products.php");
    exit;
}

// --- Hapus Produk ---
if (isset($_GET["delete"])) {
    $id = (int) $_GET["delete"];
    $pdo->prepare("DELETE FROM products WHERE id=?")->execute([$id]);
    header("Location: products.php");
    exit;
}

// --- Ambil semua produk ---
$stmt = $pdo->query("SELECT p.*, c.name AS category FROM products p LEFT JOIN categories c ON p.category_id=c.id ORDER BY p.id DESC");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Produk</title>
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
    img.thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; }
  </style>
</head>
<body>
  <h1>Manajemen Produk</h1>
  <div class="nav">
    <a href="dashboard.php">Dashboard</a>
    <a href="categories.php">Kategori</a>
    <a href="products.php">Produk</a>
    <a href="outlets.php">Outlet</a>
    <a href="vouchers.php">Voucher</a>
    <a href="logout.php">Logout</a>
  </div>

  <!-- Form tambah produk -->
  <div class="form-box">
    <h2>Tambah Produk</h2>
    <form method="POST" enctype="multipart/form-data">
      <label>Nama Produk</label><br>
      <input type="text" name="name" required><br><br>

      <label>Kategori</label><br>
      <select name="category_id" required>
        <option value="">-- Pilih Kategori --</option>
        <?php foreach ($categories as $c): ?>
          <option value="<?= $c["id"] ?>"><?= htmlspecialchars($c["name"]) ?></option>
        <?php endforeach; ?>
      </select><br><br>

      <label>Deskripsi</label><br>
      <textarea name="description" rows="3"></textarea><br><br>

      <label>Harga</label><br>
      <input type="number" name="price" required><br><br>

      <label>Stok</label><br>
      <input type="number" name="stock" value="0" required><br><br>

      <label>Gambar Produk</label><br>
      <input type="file" name="image" accept="image/*"><br><br>

      <button type="submit" name="add" class="btn btn-add">Tambah Produk</button>
    </form>
  </div>

  <!-- Daftar produk -->
  <table class="table">
    <tr>
      <th>ID</th>
      <th>Nama</th>
      <th>Kategori</th>
      <th>Harga</th>
      <th>Stok</th>
      <th>Aksi</th>
    </tr>
    <?php foreach ($products as $p): ?>
    <tr>
      <td><?= $p["id"] ?></td>
      <td><?= htmlspecialchars($p["name"]) ?></td>
      <td><?= $p["category"] ?></td>
      <td>Rp<?= number_format($p["price"],0,",",".") ?></td>
      <td><?= $p["stock"] ?></td>
      <td>
        <a href="products.php?delete=<?= $p["id"] ?>" onclick="return confirm('Hapus produk ini?')">
          <button class="btn btn-delete">Hapus</button>
        </a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
