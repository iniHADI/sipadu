<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

$page_title = 'Tambah Persediaan Barang';
$title = 'Tambah Persediaan Barang';
?>
<?php include 'header.php'; ?>

<div class="page-header">
    <h1><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Persediaan Barang</h1>
    <p class="breadcrumb"><a href="bmn_index.php" class="text-decoration-none">Persediaan Barang</a> / Tambah Barang</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-modern">
            <div class="card-header-modern">
                <h5 class="mb-0 fw-bold"><i class="bi bi-box-seam me-2"></i>Form Tambah Barang Baru</h5>
            </div>
            <div class="card-body p-4">
                <form action="proses_bmn_tambah.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kode Barang <span class="text-danger">*</span></label>
                            <input type="text" name="kode_barang" class="form-control form-control-modern" required maxlength="50" placeholder="Contoh: BRG001">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" name="nama_barang" class="form-control form-control-modern" required placeholder="Nama barang...">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Spesifikasi</label>
                        <textarea name="spesifikasi" class="form-control form-control-modern" rows="3" placeholder="Deskripsi spesifikasi barang..."></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Kondisi <span class="text-danger">*</span></label>
                            <select name="kondisi" class="form-select form-select-modern" required>
                                <option value="Baik">✅ Baik</option>
                                <option value="Rusak Ringan">⚠️ Rusak Ringan</option>
                                <option value="Rusak Berat">❌ Rusak Berat</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Jumlah Stok <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah" class="form-control form-control-modern" required min="1" value="1">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Lokasi Penyimpanan</label>
                            <input type="text" name="lokasi" class="form-control form-control-modern" placeholder="Contoh: Gudang A">
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3 mt-4">
                        <a href="bmn_index.php" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-gradient rounded-pill px-4">
                            <i class="bi bi-check-lg me-2"></i>Simpan Barang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

