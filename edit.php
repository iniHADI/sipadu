<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

$id = $_GET['id'] ?? 0;
if ($id == 0) {
    header("Location: index.php");
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM surat WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan'); window.location='index.php';</script>";
    exit;
}

$page_title = 'Edit Surat';
$title = 'Edit - ' . $data['nomor_surat'];
?>
<?php include 'header.php'; ?>

<div class="page-header">
    <h1><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Surat</h1>
    <p class="breadcrumb"><a href="index.php" class="text-decoration-none">Arsip Surat</a> / Edit</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-modern">
            <div class="card-header-modern">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pen me-2"></i>Form Edit Surat</h5>
            </div>
            <div class="card-body p-4">
                <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nomor Surat <span class="text-danger">*</span></label>
                            <input type="text" name="nomor" class="form-control form-control-modern" value="<?= htmlspecialchars($data['nomor_surat']) ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control form-control-modern" value="<?= $data['tanggal'] ?>" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Pengirim <span class="text-danger">*</span></label>
                            <input type="text" name="pengirim" class="form-control form-control-modern" value="<?= htmlspecialchars($data['pengirim']) ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-modern">
                                <option value="Diterima" <?= $data['status']=='Diterima'?'selected':'' ?>>📥 Diterima</option>
                                <option value="Diproses" <?= $data['status']=='Diproses'?'selected':'' ?>>⏳ Diproses</option>
                                <option value="Selesai" <?= $data['status']=='Selesai'?'selected':'' ?>>✅ Selesai</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Perihal <span class="text-danger">*</span></label>
                        <input type="text" name="perihal" class="form-control form-control-modern" value="<?= htmlspecialchars($data['perihal']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Disposisi Ke</label>
                        <input type="text" name="disposisi" class="form-control form-control-modern" value="<?= htmlspecialchars($data['disposisi_ke']) ?>" placeholder="Contoh: Kepala Seksi...">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Saat Ini</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-modern" value="<?= basename($data['file'] ?? '-') ?>" readonly>
                            <?php if (!empty($data['file'])) { ?>
                                <a href="<?= $data['file'] ?>" target="_blank" class="btn btn-outline-success rounded-end-pill">
                                    <i class="bi bi-eye"></i>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Ganti File <small class="text-muted">(kosongkan jika tidak mau ganti)</small></label>
                        <input type="file" name="file" class="form-control form-control-modern">
                        <small class="text-muted">Format: PDF, DOC, JPG, PNG (maksimal 10MB)</small>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <a href="index.php" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-gradient rounded-pill px-4">
                            <i class="bi bi-check-lg me-2"></i>Update Surat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

