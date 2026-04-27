<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$page_title = 'Import Persediaan Barang';
$title = 'Import Persediaan';
include 'header.php';
?>

<div class="page-header">
    <h1><i class="bi bi-upload me-2 text-primary"></i>Import Persediaan Barang</h1>
    <p class="breadcrumb"><a href="bmn_index.php" class="text-decoration-none">Persediaan Barang</a> / Import</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-modern">
            <div class="card-header-modern d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-arrow-up me-2"></i>Upload File CSV</h5>
                <a href="bmn_index.php" class="btn btn-outline-secondary btn-sm rounded-pill">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info d-flex align-items-start rounded-4 mb-4" style="background: #e7f3ff; border-color: #b8daff;">
                    <i class="bi bi-info-circle-fill text-primary me-3 mt-1 fs-4"></i>
                    <div>
                        <h6 class="fw-bold text-primary mb-2">Petunjuk Import Data</h6>
                        <ol class="mb-0 small text-dark">
                            <li>Siapkan file dalam format <strong>CSV</strong> (dari Excel: Save As → CSV UTF-8)</li>
                            <li>Baris pertama harus berisi header: <code>kode_barang,nama_barang,spesifikasi,kondisi,jumlah,lokasi</code></li>
                            <li>Kolom <strong>kode_barang, nama_barang, kondisi, jumlah</strong> wajib diisi</li>
                            <li>Nilai <strong>kondisi</strong> harus: Baik / Rusak Ringan / Rusak Berat</li>
                            <li>Nilai <strong>jumlah</strong> harus angka lebih dari 0</li>
                        </ol>
                    </div>
                </div>

                <form action="proses_import_persediaan.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Pilih File CSV</label>
                        <div class="input-group">
                            <input type="file" name="file_csv" class="form-control form-control-modern" accept=".csv" required id="fileInput">
                        </div>
                        <small class="text-muted">Format yang diterima: .csv (maksimal 2MB)</small>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="template_persediaan.csv" class="btn btn-outline-primary rounded-pill">
                            <i class="bi bi-download me-2"></i>Download Template
                        </a>
                        <button type="submit" class="btn btn-gradient rounded-pill px-4">
                            <i class="bi bi-rocket me-2"></i>Import Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

