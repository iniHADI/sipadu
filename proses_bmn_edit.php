<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: bmn_index.php");
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$kode = mysqli_real_escape_string($conn, $_POST['kode_barang']);
$nama = mysqli_real_escape_string($conn, $_POST['nama_barang']);
$spek = mysqli_real_escape_string($conn, $_POST['spesifikasi']);
$kondisi = mysqli_real_escape_string($conn, $_POST['kondisi']);
$jumlah = (int)$_POST['jumlah'];
$lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);

if ($id <= 0) {
    echo "<script>alert('ID barang tidak valid!'); window.location='bmn_index.php';</script>";
    exit;
}

$query = mysqli_query($conn, "UPDATE bmn_barang SET 
    kode_barang = '$kode',
    nama_barang = '$nama',
    spesifikasi = '$spek',
    kondisi = '$kondisi',
    jumlah = $jumlah,
    lokasi = '$lokasi'
    WHERE id = $id");

if ($query) {
    echo "<script>alert('Barang berhasil diupdate!'); window.location='bmn_index.php';</script>";
} else {
    echo "<script>alert('Gagal mengupdate barang: " . mysqli_error($conn) . "'); window.location='bmn_edit.php?id=$id';</script>";
}
?>

