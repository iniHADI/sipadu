<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

// Ambil semua data persediaan barang
$query = mysqli_query($conn, "SELECT * FROM bmn_barang ORDER BY kode_barang");

// Nama file export
$filename = 'Data_Persediaan_Barang_' . date('Ymd_His') . '.csv';

// Set header untuk download CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Output BOM untuk UTF-8 (agar karakter Indonesia tampil benar di Excel)
echo "\xEF\xBB\xBF";

// Buka output stream
$output = fopen('php://output', 'w');

// Tulis header kolom
fputcsv($output, ['No', 'Kode Barang', 'Nama Barang', 'Spesifikasi', 'Kondisi', 'Jumlah', 'Lokasi', 'Tanggal Input']);

// Tulis data baris per baris
$no = 1;
while ($row = mysqli_fetch_assoc($query)) {
    fputcsv($output, [
        $no++,
        $row['kode_barang'],
        $row['nama_barang'],
        $row['spesifikasi'],
        $row['kondisi'],
        $row['jumlah'],
        $row['lokasi'],
        $row['tanggal_input']
    ]);
}

fclose($output);
exit;
?>

