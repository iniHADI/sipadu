<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

$id = $_GET['id'] ?? 0;
if ($id == 0) {
    header("Location: bmn_index.php");
    exit;
}

$query = mysqli_query($conn, "DELETE FROM bmn_barang WHERE id = $id");

if ($query) {
    echo "<script>alert('Barang berhasil dihapus!'); window.location='bmn_index.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus barang!'); window.location='bmn_index.php';</script>";
}
?>

