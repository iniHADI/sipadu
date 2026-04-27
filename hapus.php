<?php
include 'koneksi.php';

$id = $_GET['id'] ?? 0;
if ($id == 0) {
    header("Location: index.php");
    exit;
}

$query = mysqli_query($conn, "SELECT file FROM surat WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if ($data) {
    // Hapus file fisik jika ada
    if (!empty($data['file']) && file_exists($data['file'])) {
        unlink($data['file']);
    }
    
    // Hapus DB
    mysqli_query($conn, "DELETE FROM surat WHERE id = $id");
}

header("Location: index.php?deleted=1");
?>

