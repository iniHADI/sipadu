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

$barang_id = isset($_POST['barang_id']) ? (int)$_POST['barang_id'] : 0;
$jumlah_diminta = isset($_POST['jumlah_diminta']) ? (int)$_POST['jumlah_diminta'] : 0;
$keperluan = isset($_POST['keperluan']) ? trim($_POST['keperluan']) : '';
$tanggal_dibutuhkan = isset($_POST['tanggal_dibutuhkan']) ? $_POST['tanggal_dibutuhkan'] : '';
$user_id = (int)$_SESSION['user_id'];

// Validasi input
$errors = [];

if ($barang_id <= 0) {
    $errors[] = 'Barang tidak valid';
}

if ($jumlah_diminta <= 0) {
    $errors[] = 'Jumlah diminta harus lebih dari 0';
}

if (empty($keperluan)) {
    $errors[] = 'Keperluan harus diisi';
}

if (empty($tanggal_dibutuhkan)) {
    $errors[] = 'Tanggal dibutuhkan harus diisi';
}

// Cek stok barang
if ($barang_id > 0) {
    $stmt = mysqli_prepare($conn, "SELECT jumlah FROM bmn_barang WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $barang_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $barang = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$barang) {
        $errors[] = 'Barang tidak ditemukan';
    } elseif ($jumlah_diminta > $barang['jumlah']) {
        $errors[] = 'Jumlah diminta melebihi stok yang tersedia (' . $barang['jumlah'] . ')';
    }
}

// Jika ada error, tampilkan pesan
if (!empty($errors)) {
    $error_msg = implode('\\n', $errors);
    echo "<script>alert('Error:\\n$error_msg'); window.location='bmn_permintaan.php?id=$barang_id';</script>";
    exit;
}

// Insert dengan prepared statement
$stmt = mysqli_prepare($conn, "INSERT INTO bmn_permintaan (barang_id, user_id, jumlah_diminta, keperluan, tanggal_dibutuhkan, status, tanggal_permintaan) VALUES (?, ?, ?, ?, ?, 'Pending', NOW())");
mysqli_stmt_bind_param($stmt, "iiiss", $barang_id, $user_id, $jumlah_diminta, $keperluan, $tanggal_dibutuhkan);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Permintaan berhasil dikirim!'); window.location='bmn_index.php';</script>";
} else {
    $error = mysqli_error($conn);
    echo "<script>alert('Error: $error'); window.location='bmn_permintaan.php?id=$barang_id';</script>";
}

mysqli_stmt_close($stmt);
?>

