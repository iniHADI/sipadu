<?php
include 'koneksi.php';

$id = $_POST['id'] ?? 0;
$nomor = $_POST['nomor'] ?? '';
$tanggal = $_POST['tanggal'] ?? '';
$pengirim = $_POST['pengirim'] ?? '';
$perihal = $_POST['perihal'] ?? '';
$status = $_POST['status'] ?? '';
$disposisi = $_POST['disposisi'] ?? '';

if ($id == 0) {
    header("Location: index.php");
    exit;
}

// Ambil file lama
$query = mysqli_query($conn, "SELECT file FROM surat WHERE id = $id");
$data = mysqli_fetch_assoc($query);
$old_file = $data['file'] ?? '';

$new_path = $old_file; // keep old by default

// Cek upload file baru
$file_error = $_FILES['file']['error'] ?? UPLOAD_ERR_NO_FILE;
if ($file_error === UPLOAD_ERR_OK) {
    $nama_file = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];
    $tmp = $_FILES['file']['tmp_name'];
    
    if ($size > 0 && $size < 10*1024*1024) {
        $folder = "upload/";
        $new_path = $folder . time() . "_" . $nama_file;
        
        $allowed = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed) && move_uploaded_file($tmp, $new_path)) {
            // Hapus file lama jika berbeda
            if (!empty($old_file) && $old_file != $new_path && file_exists($old_file)) {
                unlink($old_file);
            }
        } else {
            die("Format file tidak valid!");
        }
    }
}

// Update DB
$query = "UPDATE surat SET 
    nomor_surat='$nomor', 
    tanggal='$tanggal', 
    pengirim='$pengirim', 
    perihal='$perihal', 
    file='$new_path', 
    status='$status', 
    disposisi_ke='$disposisi' 
    WHERE id = $id";

if (mysqli_query($conn, $query)) {
    header("Location: index.php?edited=1");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>

