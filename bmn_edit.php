<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

$id = $_GET['id'] ?? 0;
if ($id == 0) {
    header("Location: bmn_index.php");
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM bmn_barang WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan'); window.location='bmn_index.php';</script>";
    exit;
}

$page_title = 'Edit Barang';
$title = 'Edit - ' . $data['nama_barang'];
?>
<?php include 'header.php'; ?>

<div class="page-header">
    <h1><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Barang</h1>
    <p class="breadcrumb"><a href="bmn_index.php" class="text-decoration-none">Persediaan Barang</a> / Edit</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-modern">
            <div class="card-header-modern">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pen me-2"></i>Form Edit Barang</h5>
            </div>
            <div class="card-body p-4">
                <form action="proses_bmn_edit.php" method="POST">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kode Barang <span class="text-danger">*</span></label>
                            <input type="text" name="kode_barang" class="form-control form-control-modern" value="<?= htmlspecialchars($data['kode_barang']) ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" name="nama_barang" class="form-control form-control-modern" value="<?= htmlspecialchars($data['nama_barang']) ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Spesifikasi</label>
                        <textarea name="spesifikasi" class="form-control form-control-modern" rows="3"><?= htmlspecialchars($data['spesifikasi'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Kondisi <span class="text-danger">*</span></label>
                            <select name="kondisi" class="form-select form-select-modern" required>
                                <option value="Baik" <?= $data['kondisi']=='Baik'?'selected':'' ?>>✅ Baik</option>
                                <option value="Rusak Ringan" <?= $data['kondisi']=='Rusak Ringan'?'selected':'' ?>>⚠️ Rusak Ringan</option>
                                <option value="Rusak Berat" <?= $data['kondisi']=='Rusak Berat'?'selected':'' ?>>❌ Rusak Berat</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Jumlah Stok <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah" class="form-control form-control-modern" value="<?= $data['jumlah'] ?>" required min="0">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Lokasi Penyimpanan</label>
                            <input type="text" name="lokasi" class="form-control form-control-modern" value="<?= htmlspecialchars($data['lokasi'] ?? '') ?>" placeholder="Contoh: Gudang A">
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3 mt-4">
                        <a href="bmn_index.php" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-gradient rounded-pill px-4">
                            <i class="bi bi-check-lg me-2"></i>Update Barang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

