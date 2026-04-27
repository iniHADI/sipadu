<?php
session_start();
include 'koneksi.php';

$kode = $_POST['kode_barang'];
$nama = $_POST['nama_barang'];
$spek = $_POST['spesifikasi'];
$kondisi = $_POST['kondisi'];
$jumlah = $_POST['jumlah'];
$lokasi = $_POST['lokasi'];
$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "INSERT INTO bmn_barang (kode_barang, nama_barang, spesifikasi, kondisi, jumlah, lokasi, tanggal_input, user_input) VALUES ('$kode', '$nama', '$spek', '$kondisi', $jumlah, '$lokasi', CURDATE(), $user_id)");

if ($query) {
    header("Location: bmn_index.php?success=tambah");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
