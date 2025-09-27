<?php
session_start();
require_once "../inc/config.php";

// proteksi login
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

// --- Tambah Outlet ---
if (isset($_POST["add"])) {
    $name = trim($_POST["name"]);
    $address = trim($_POST["address"]);
    $phone = trim($_POST["phone"]);
    $gmap = trim($_POST["gmap"]);

    if ($name !== "" && $address !== "") {
        $stmt = $pdo->prepare("INSERT INTO outlets (name, address, phone, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$name, $address, $phone]);

        // simpan link google map jika ada
        if ($gmap) {
            $pdo->prepare("UPDATE outlets SET address = CONCAT(address, '\nGoogle Maps: $gmap') WHERE id=?")
                ->execute([$pdo->lastInsertId()]);
        }

        header("Location: outlets.php");
        exit;
    }
}

// --- Hapus Outlet ---
if (isset($_GET["delete"])) {
    $id = (int) $_GET["delete"];
    $pdo->prepare("DELETE FROM outlets WHERE id=?")->execute([$id]);
    header("Location: outlets.php");
    exit;
}

// --- Ambil semua outlet ---
$stmt = $pdo->query("SELECT * FROM outlets ORDER BY id DESC");
$outlets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Outlet</title>
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
  <h1>Manajemen Outlet</h1>
  <div class="nav">
    <a href="dashboard.php">Dashboard</a>
    <a href="categories.php">Kategori</a>
    <a href="products.php">Produk</a>
    <a href="outlets.php">Outlet</a>
    <a href="vouchers.php">Voucher</a>
    <a href="logout.php">Logout</a>
  </div>

  <!-- Form tambah outlet -->
  <div class="form-box">
    <h2>Tambah Outlet</h2>
    <form method="POST">
      <label>Nama Outlet</label><br>
      <input type="text" name="name" required><br><br>

      <label>Alamat</label><br>
      <textarea name="address" rows="2" required></textarea><br><br>

      <label>No. Telepon</label><br>
      <input type="text" name="phone"><br><br>

      <label>Link Google Maps (opsional)</label><br>
      <input type="url" name="gmap" placeholder="https://goo.gl/maps/..."><br><br>

      <button type="submit" name="add" class="btn btn-add">Tambah Outlet</button>
    </form>
  </div>

  <!-- Daftar outlet -->
  <table class="table">
    <tr>
      <th>ID</th>
      <th>Nama</th>
      <th>Alamat</th>
      <th>Telepon</th>
      <th>Aksi</th>
    </tr>
    <?php foreach ($outlets as $o): ?>
    <tr>
      <td><?= $o["id"] ?></td>
      <td><?= htmlspecialchars($o["name"]) ?></td>
      <td><?= nl2br(htmlspecialchars($o["address"])) ?></td>
      <td><?= $o["phone"] ?></td>
      <td>
        <a href="outlets.php?delete=<?= $o["id"] ?>" onclick="return confirm('Hapus outlet ini?')">
          <button class="btn btn-delete">Hapus</button>
        </a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
