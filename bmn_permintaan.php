<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

$id_barang = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_barang <= 0) {
    echo "<script>alert('ID barang tidak valid!'); window.location='bmn_index.php';</script>";
    exit;
}

$stmt = mysqli_prepare($conn, "SELECT * FROM bmn_barang WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id_barang);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$barang = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$barang) {
    echo "<script>alert('Barang tidak ditemukan!'); window.location='bmn_index.php';</script>";
    exit;
}

if ($barang['jumlah'] <= 0) {
    echo "<script>alert('Stok barang habis!'); window.location='bmn_index.php';</script>";
    exit;
}

$page_title = 'Permintaan Persediaan Barang';
$title = 'Permintaan - ' . htmlspecialchars($barang['nama_barang']);
?>
<?php include 'header.php'; ?>

<div class="page-header">
    <h1><i class="bi bi-cart-plus me-2 text-primary"></i>Permintaan Barang</h1>
    <p class="breadcrumb"><a href="bmn_index.php" class="text-decoration-none">Persediaan Barang</a> / Permintaan</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card card-modern">
            <div class="card-header-modern d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-clipboard-check me-2"></i>Form Permintaan Barang</h5>
                <a href="bmn_index.php" class="btn btn-outline-secondary btn-sm rounded-pill">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <!-- Info Barang -->
                <div class="p-4 rounded-4 mb-4" style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%); border: 1px solid #667eea30;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-shrink-0">
                            <div class="d-flex align-items-center justify-content-center rounded-4" style="width: 60px; height: 60px; background: var(--primary-gradient);">
                                <i class="bi bi-box-seam text-white fs-3"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Barang yang Diminta</h6>
                            <h5 class="fw-bold mb-1"><?= htmlspecialchars($barang['kode_barang'] . ' - ' . $barang['nama_barang']) ?></h5>
                            <div class="d-flex gap-3 flex-wrap">
                                <span class="badge bg-info bg-opacity-75 rounded-pill px-3">
                                    <i class="bi bi-gear me-1"></i><?= htmlspecialchars($barang['kondisi']) ?>
                                </span>
                                <span class="badge bg-primary bg-opacity-75 rounded-pill px-3">
                                    <i class="bi bi-stack me-1"></i>Stok: <?= (int)$barang['jumlah'] ?> unit
                                </span>
                                <span class="badge bg-secondary bg-opacity-75 rounded-pill px-3">
                                    <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($barang['lokasi'] ?? '-') ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="proses_bmn_permintaan.php" method="POST">
                    <input type="hidden" name="barang_id" value="<?= $id_barang ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Diminta <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 rounded-start-pill">
                                <i class="bi bi-123 text-muted"></i>
                            </span>
                            <input type="number" name="jumlah_diminta" class="form-control form-control-modern border-start-0 rounded-end-pill" min="1" max="<?= (int)$barang['jumlah'] ?>" required placeholder="Masukkan jumlah...">
                        </div>
                        <small class="text-muted">Maksimal <?= (int)$barang['jumlah'] ?> unit</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keperluan <span class="text-danger">*</span></label>
                        <textarea name="keperluan" class="form-control form-control-modern" rows="3" required placeholder="Jelaskan keperluan peminjaman barang..."></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Tanggal Dibutuhkan <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_dibutuhkan" class="form-control form-control-modern" required>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <a href="bmn_index.php" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-x-lg me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-gradient rounded-pill px-4">
                            <i class="bi bi-send me-2"></i>Kirim Permintaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Sidebar Info -->
    <div class="col-md-4">
        <div class="card card-modern">
            <div class="card-header-modern">
                <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2"></i>Informasi</h6>
            </div>
            <div class="card-body p-4">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                        <span class="small">Pastikan jumlah yang diminta tidak melebihi stok tersedia.</span>
                    </li>
                    <li class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                        <span class="small">Isi keperluan dengan jelas agar permintaan cepat diproses.</span>
                    </li>
                    <li class="d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                        <span class="small">Status permintaan akan menjadi <strong>Pending</strong> hingga disetujui admin.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

