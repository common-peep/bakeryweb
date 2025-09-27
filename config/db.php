<?php
$host = "localhost";
$user = "root";       // default XAMPP biasanya "root"
$pass = "";           // default XAMPP kosong (tanpa password)
$db   = "bakery_db";  // pastikan nama sesuai database kamu

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>
