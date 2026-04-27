<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['file_csv'])) {
    header("Location: import_persediaan.php");
    exit;
}

$file = $_FILES['file_csv'];

// Validasi file
if ($file['error'] !== UPLOAD_ERR_OK) {
    echo "<script>alert('Gagal upload file!'); window.location='import_persediaan.php';</script>";
    exit;
}

$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if ($ext !== 'csv') {
    echo "<script>alert('File harus berformat CSV!'); window.location='import_persediaan.php';</script>";
    exit;
}

// Buka file CSV
$handle = fopen($file['tmp_name'], 'r');
if (!$handle) {
    echo "<script>alert('Gagal membaca file!'); window.location='import_persediaan.php';</script>";
    exit;
}

// Deteksi delimiter (koma atau titik koma)
$firstLine = fgets($handle);
rewind($handle);
$delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';

// Baca header
$header = fgetcsv($handle, 0, $delimiter);
if (!$header) {
    echo "<script>alert('File CSV kosong atau format tidak valid!'); window.location='import_persediaan.php';</script>";
    exit;
}

// Normalisasi header (lowercase, trim)
$header = array_map(function($h) {
    return strtolower(trim($h));
}, $header);

// Mapping kolom yang diharapkan
$requiredCols = ['kode_barang', 'nama_barang', 'kondisi', 'jumlah'];
$missingCols = array_diff($requiredCols, $header);

if (!empty($missingCols)) {
    $missing = implode(', ', $missingCols);
    echo "<script>alert('Kolom wajib tidak ditemukan: $missing'); window.location='import_persediaan.php';</script>";
    exit;
}

// Index kolom
$idxKode = array_search('kode_barang', $header);
$idxNama = array_search('nama_barang', $header);
$idxSpek = array_search('spesifikasi', $header);
$idxKondisi = array_search('kondisi', $header);
$idxJumlah = array_search('jumlah', $header);
$idxLokasi = array_search('lokasi', $header);

$success = 0;
$failed = 0;
$errors = [];
$rowNum = 1; // header = baris 1, data mulai baris 2

$user_id = (int)$_SESSION['user_id'];

while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
    $rowNum++;

    // Skip baris kosong
    if (count($data) < count($header) || empty(array_filter($data))) {
        continue;
    }

    $kode = isset($data[$idxKode]) ? trim($data[$idxKode]) : '';
    $nama = isset($data[$idxNama]) ? trim($data[$idxNama]) : '';
    $spek = ($idxSpek !== false && isset($data[$idxSpek])) ? trim($data[$idxSpek]) : '';
    $kondisi = isset($data[$idxKondisi]) ? trim($data[$idxKondisi]) : '';
    $jumlah = isset($data[$idxJumlah]) ? (int)$data[$idxJumlah] : 0;
    $lokasi = ($idxLokasi !== false && isset($data[$idxLokasi])) ? trim($data[$idxLokasi]) : '';

    // Validasi per baris
    $rowErrors = [];
    if (empty($kode)) $rowErrors[] = 'kode_barang kosong';
    if (empty($nama)) $rowErrors[] = 'nama_barang kosong';
    if (!in_array($kondisi, ['Baik', 'Rusak Ringan', 'Rusak Berat'])) {
        $rowErrors[] = "kondisi tidak valid ($kondisi)";
    }
    if ($jumlah <= 0) $rowErrors[] = 'jumlah harus > 0';

    if (!empty($rowErrors)) {
        $failed++;
        $errors[] = "Baris $rowNum: " . implode(', ', $rowErrors);
        continue;
    }

    // Cek duplikat kode_barang
    $checkStmt = mysqli_prepare($conn, "SELECT id FROM bmn_barang WHERE kode_barang = ?");
    mysqli_stmt_bind_param($checkStmt, "s", $kode);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) > 0) {
        mysqli_stmt_close($checkStmt);
        $failed++;
        $errors[] = "Baris $rowNum: kode_barang '$kode' sudah ada";
        continue;
    }
    mysqli_stmt_close($checkStmt);

    // Insert ke database
    $insertStmt = mysqli_prepare($conn, "INSERT INTO bmn_barang (kode_barang, nama_barang, spesifikasi, kondisi, jumlah, lokasi, tanggal_input, user_input) VALUES (?, ?, ?, ?, ?, ?, CURDATE(), ?)");
    mysqli_stmt_bind_param($insertStmt, "ssssisi", $kode, $nama, $spek, $kondisi, $jumlah, $lokasi, $user_id);

    if (mysqli_stmt_execute($insertStmt)) {
        $success++;
    } else {
        $failed++;
        $errors[] = "Baris $rowNum: " . mysqli_error($conn);
    }
    mysqli_stmt_close($insertStmt);
}

fclose($handle);

// Tampilkan hasil
if ($failed > 0) {
    $errorList = implode("\\n", array_slice($errors, 0, 10));
    $more = count($errors) > 10 ? "\\n...dan " . (count($errors) - 10) . " error lainnya" : "";
    echo "<script>alert('Import selesai!\\nBerhasil: $success baris\\nGagal: $failed baris\\n\\nDetail error:\\n$errorList$more'); window.location='bmn_index.php';</script>";
} else {
    echo "<script>alert('Import berhasil! $success baris data masuk ke Persediaan Barang.'); window.location='bmn_index.php';</script>";
}
?>

