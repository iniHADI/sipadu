<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($query);

// Update profile
$message = '';
if ($_POST) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $posisi = mysqli_real_escape_string($conn, $_POST['posisi']);
    
    $foto_path = $user['foto'];
    
    // Upload foto
    $file_error = $_FILES['foto']['error'] ?? UPLOAD_ERR_NO_FILE;
    if ($file_error === UPLOAD_ERR_OK) {
        $nama_foto = $_FILES['foto']['name'];
        $size = $_FILES['foto']['size'];
        $tmp = $_FILES['foto']['tmp_name'];
        
        if ($size > 0 && $size < 2*1024*1024) { // 2MB
            $folder = "profile/";
            @mkdir($folder, 0777, true);
            $foto_path = $folder . time() . "_" . basename($nama_foto);
            
            $allowed = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($nama_foto, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed) && move_uploaded_file($tmp, $foto_path)) {
                // Hapus foto lama
                if ($user['foto'] != 'default.jpg' && file_exists($user['foto'])) {
                    unlink($user['foto']);
                }
            }
        }
    }
    
    $query = mysqli_query($conn, "UPDATE users SET nama = '$nama', posisi = '$posisi', foto = '$foto_path' WHERE id = $user_id");
    
    if ($query) {
        $message = '<div class="alert alert-success rounded-4 d-flex align-items-center"><i class="bi bi-check-circle-fill me-2"></i>Profil berhasil diupdate!</div>';
        $_SESSION['nama'] = $nama;
        // Refresh data
        $query = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
        $user = mysqli_fetch_assoc($query);
    } else {
        $message = '<div class="alert alert-danger rounded-4 d-flex align-items-center"><i class="bi bi-exclamation-circle-fill me-2"></i>Error update: ' . mysqli_error($conn) . '</div>';
    }
}

$page_title = 'Edit Profil';
$title = 'Profil - Lapas Kelas IIB Purwakarta';
?>
<?php include 'header.php'; ?>

<div class="page-header">
    <h1><i class="bi bi-person-circle me-2 text-primary"></i>Profil Saya</h1>
    <p class="breadcrumb">Dashboard / Profil</p>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card card-modern text-center">
            <div class="card-body p-4">
                <div class="position-relative d-inline-block mb-3">
                    <img src="<?= $user['foto'] ?? 'default.jpg' ?>" class="rounded-circle" style="width:130px;height:130px;object-fit:cover;border:4px solid #f0f0f0;">
                    <div class="position-absolute bottom-0 end-0 bg-success rounded-circle p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-check-lg text-white" style="font-size: 14px;"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($user['nama'] ?? $_SESSION['username']) ?></h5>
                <span class="badge bg-primary bg-opacity-75 rounded-pill px-3 py-2 mb-2"><?= ucfirst($_SESSION['role'] ?? 'user') ?></span>
                <p class="text-muted small mb-3"><i class="bi bi-briefcase me-1"></i><?= htmlspecialchars($user['posisi'] ?? 'Belum diatur') ?></p>
                <div class="d-grid gap-2">
                    <a href="index.php" class="btn btn-gradient rounded-pill">
                        <i class="bi bi-house me-2"></i>Dashboard
                    </a>
                    <a href="logout.php" class="btn btn-outline-danger rounded-pill">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-modern">
            <div class="card-header-modern">
                <h5 class="mb-0 fw-bold"><i class="bi bi-person-gear me-2"></i>Edit Profil</h5>
            </div>
            <div class="card-body p-4">
                <?= $message ?>
                
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 rounded-start-pill">
                                <i class="bi bi-person text-muted"></i>
                            </span>
                            <input type="text" name="nama" class="form-control form-control-modern border-start-0 rounded-end-pill" value="<?= htmlspecialchars($user['nama'] ?? '') ?>" required placeholder="Nama lengkap...">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Posisi / Jabatan</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 rounded-start-pill">
                                <i class="bi bi-briefcase text-muted"></i>
                            </span>
                            <input type="text" name="posisi" class="form-control form-control-modern border-start-0 rounded-end-pill" value="<?= htmlspecialchars($user['posisi'] ?? '') ?>" placeholder="Contoh: Staff Administrasi">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Foto Profil</label>
                        <input type="file" name="foto" class="form-control form-control-modern" accept="image/jpeg,image/png">
                        <small class="text-muted">Format: JPG/PNG, maksimal 2MB. Kosongkan jika tidak ingin mengganti foto.</small>
                        <?php if ($user['foto'] && $user['foto'] != 'default.jpg') { ?>
                            <div class="mt-2">
                                <small>Foto saat ini: <a href="<?= $user['foto'] ?>" target="_blank" class="text-decoration-none">Lihat foto</a></small>
                            </div>
                        <?php } ?>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-gradient rounded-pill px-4">
                            <i class="bi bi-check-lg me-2"></i>Update Profil
                        </button>
                        <a href="index.php" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-x-lg me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

