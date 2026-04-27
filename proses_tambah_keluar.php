<?php
include 'koneksi.php';

$nomor = $_POST['nomor'] ?? '';
$tanggal = $_POST['tanggal'] ?? '';
$penerima = $_POST['penerima'] ?? '';
$perihal = $_POST['perihal'] ?? '';
$no_resp = $_POST['no_resp'] ?? '';
$status = $_POST['status'] ?? '';

$file_error = $_FILES['file']['error'] ?? UPLOAD_ERR_NO_FILE;
$path = '';

if ($file_error === UPLOAD_ERR_OK) {
    $nama_file = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];
    $tmp = $_FILES['file']['tmp_name'];
    
    if ($size > 0 && $size < 10*1024*1024) {
        $folder = "upload/";
        $path = $folder . time() . "_keluar_" . $nama_file;
        
        $allowed = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed) && move_uploaded_file($tmp, $path)) {
            // OK
        } else {
            die("Format file tidak valid!");
        }
    }
}

// Insert - perlu kolom tambahan di tabel surat atau tabel terpisah
// Untuk sederhana, tambah type='keluar'
mysqli_query($conn, "INSERT INTO surat (nomor_surat, tanggal, pengirim, perihal, file, status, disposisi_ke, penerima, no_resp, type) 
VALUES ('$nomor', '$tanggal', '$penerima', '$perihal', '$path', '$status', '', '$penerima', '$no_resp', 'keluar')");

header("Location: index.php?keluar=1");
?>

