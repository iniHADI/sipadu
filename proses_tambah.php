<?php
include 'koneksi.php';

$nomor = $_POST['nomor'] ?? '';
$tanggal = $_POST['tanggal'] ?? '';
$pengirim = $_POST['pengirim'] ?? '';
$perihal = $_POST['perihal'] ?? '';
$status = $_POST['status'] ?? '';
$disposisi = $_POST['disposisi'] ?? '';

// upload file
$file_error = $_FILES['file']['error'] ?? UPLOAD_ERR_NO_FILE;
$path = '';

if ($file_error === UPLOAD_ERR_OK) {
    $nama_file = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];
    $tmp = $_FILES['file']['tmp_name'];
    
    if ($size > 0 && $size < 10*1024*1024) { // max 10MB
        $folder = "upload/";
        $path = $folder . time() . "_" . $nama_file;
        
        $allowed = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            if (move_uploaded_file($tmp, $path)) {
                // OK
            } else {
                die("Gagal upload file!");
            }
        } else {
            die("Format tidak diizinkan! (PDF, DOC, DOCX, JPG, PNG)");
        }
    }
}

// Insert ke DB (file optional)
$query = "INSERT INTO surat (nomor_surat, tanggal, pengirim, perihal, file, status, disposisi_ke) 
          VALUES ('$nomor', '$tanggal', '$pengirim', '$perihal', '$path', '$status', '$disposisi')";
if (mysqli_query($conn, $query)) {
    header("Location: index.php?success=1");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>

