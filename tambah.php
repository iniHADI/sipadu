<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$page_title = 'Tambah Surat';
$title = 'Tambah Surat';
?>
<?php include 'header.php'; ?>

<div class="page-header">
    <h1><i class="bi bi-file-earmark-plus me-2 text-primary"></i>Tambah Surat Masuk</h1>
    <p class="breadcrumb"><a href="index.php" class="text-decoration-none">Arsip Surat</a> / Tambah Surat</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-modern">
            <div class="card-header-modern">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Form Tambah Surat Baru</h5>
            </div>
            <div class="card-body p-4">
                <form action="proses_tambah.php" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nomor Surat <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 rounded-start-pill">
                                    <i class="bi bi-hash text-muted"></i>
                                </span>
                                <input type="text" name="nomor" class="form-control form-control-modern border-start-0 rounded-end-pill" required placeholder="Contoh: 001/SK/2024">
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control form-control-modern" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Pengirim <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 rounded-start-pill">
                                    <i class="bi bi-building text-muted"></i>
                                </span>
                                <input type="text" name="pengirim" class="form-control form-control-modern border-start-0 rounded-end-pill" required placeholder="Nama instansi/pengirim">
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-modern">
                                <option value="Diterima">📥 Diterima</option>
                                <option value="Diproses">⏳ Diproses</option>
                                <option value="Selesai">✅ Selesai</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Perihal <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 rounded-start-pill">
                                <i class="bi bi-text-left text-muted"></i>
                            </span>
                            <input type="text" name="perihal" class="form-control form-control-modern border-start-0 rounded-end-pill" required placeholder="Perihal surat...">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Disposisi Ke</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 rounded-start-pill">
                                <i class="bi bi-arrow-right-circle text-muted"></i>
                            </span>
                            <input type="text" name="disposisi" class="form-control form-control-modern border-start-0 rounded-end-pill" placeholder="Contoh: Kepala Seksi...">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Upload Arsip Digital</label>
                        <input type="file" name="file" class="form-control form-control-modern">
                        <small class="text-muted">Format: PDF, JPG, PNG (maksimal 5MB)</small>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <a href="index.php" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-gradient rounded-pill px-4">
                            <i class="bi bi-check-lg me-2"></i>Simpan Surat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

