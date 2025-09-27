<?php
session_start();
require_once "../inc/config.php";

// proteksi login
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

// --- Tambah Voucher ---
if (isset($_POST["add"])) {
    $code = strtoupper(trim($_POST["code"]));
    $description = trim($_POST["description"]);
    $discount_type = $_POST["discount_type"]; // percent / fixed
    $discount_value = (float) $_POST["discount_value"];
    $min_order = (int) $_POST["min_order"];

    if ($code !== "" && $discount_value > 0) {
        $stmt = $pdo->prepare("
            INSERT INTO vouchers (code, description, discount_type, discount_value, min_order, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$code, $description, $discount_type, $discount_value, $min_order]);
        header("Location: vouchers.php");
        exit;
    }
}

// --- Hapus Voucher ---
if (isset($_GET["delete"])) {
    $id = (int) $_GET["delete"];
    $pdo->prepare("DELETE FROM vouchers WHERE id=?")->execute([$id]);
    header("Location: vouchers.php");
    exit;
}

// --- Ambil semua voucher ---
$stmt = $pdo->query("SELECT * FROM vouchers ORDER BY id DESC");
$vouchers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Voucher</title>
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
  <h1>Manajemen Voucher</h1>
  <div class="nav">
    <a href="dashboard.php">Dashboard</a>
    <a href="categories.php">Kategori</a>
    <a href="products.php">Produk</a>
    <a href="outlets.php">Outlet</a>
    <a href="vouchers.php">Voucher</a>
    <a href="logout.php">Logout</a>
  </div>

  <!-- Form tambah voucher -->
  <div class="form-box">
    <h2>Tambah Voucher</h2>
    <form method="POST">
      <label>Kode Voucher *</label><br>
      <input type="text" name="code" required><br><br>

      <label>Deskripsi</label><br>
      <input type="text" name="description" placeholder="Contoh: Diskon Tahun Baru"><br><br>

      <label>Jenis Diskon</label><br>
      <select name="discount_type">
        <option value="percent">Persentase (%)</option>
        <option value="fixed">Nominal (Rp)</option>
      </select><br><br>

      <label>Nilai Diskon *</label><br>
      <input type="number" name="discount_value" required><br><br>

      <label>Minimal Pembelian (Rp)</label><br>
      <input type="number" name="min_order" value="0"><br><br>

      <button type="submit" name="add" class="btn btn-add">Tambah Voucher</button>
    </form>
  </div>

  <!-- Daftar voucher -->
  <table class="table">
    <tr>
      <th>ID</th>
      <th>Kode</th>
      <th>Deskripsi</th>
      <th>Diskon</th>
      <th>Minimal Belanja</th>
      <th>Aksi</th>
    </tr>
    <?php foreach ($vouchers as $v): ?>
    <tr>
      <td><?= $v["id"] ?></td>
      <td><b><?= htmlspecialchars($v["code"]) ?></b></td>
      <td><?= htmlspecialchars($v["description"]) ?></td>
      <td>
        <?php if ($v["discount_type"] === "percent"): ?>
          <?= $v["discount_value"] ?>%
        <?php else: ?>
          Rp<?= number_format($v["discount_value"],0,",",".") ?>
        <?php endif; ?>
      </td>
      <td>Rp<?= number_format($v["min_order"],0,",",".") ?></td>
      <td>
        <a href="vouchers.php?delete=<?= $v["id"] ?>" onclick="return confirm('Hapus voucher ini?')">
          <button class="btn btn-delete">Hapus</button>
        </a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
