<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

$id = $_GET['id'] ?? 0;
$query = mysqli_query($conn, "SELECT * FROM bmn_barang WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Barang tidak ditemukan'); window.location='bmn_index.php';</script>";
    exit;
}

$badgeClass = 'bg-secondary';
if ($data['kondisi'] == 'Baik') $badgeClass = 'bg-success';
elseif ($data['kondisi'] == 'Rusak Ringan') $badgeClass = 'bg-warning';
elseif ($data['kondisi'] == 'Rusak Berat') $badgeClass = 'bg-danger';

$page_title = 'Detail Barang';
$title = 'Detail - ' . $data['nama_barang'];
?>
<?php include 'header.php'; ?>

<div class="page-header">
    <h1><i class="bi bi-box-seam me-2 text-primary"></i>Detail Barang</h1>
    <p class="breadcrumb"><a href="bmn_index.php" class="text-decoration-none">Persediaan Barang</a> / Detail</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card card-modern mb-4">
            <div class="card-header-modern d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge <?= $badgeClass ?> bg-opacity-75 rounded-pill px-3 py-2 fs-6">
                        <?= $data['kondisi'] ?>
                    </span>
                </div>
                <div class="d-flex gap-2">
                    <a href="bmn_edit.php?id=<?= $id ?>" class="btn btn-outline-warning rounded-pill btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <a href="bmn_hapus.php?id=<?= $id ?>" class="btn btn-outline-danger rounded-pill btn-sm" onclick="return confirm('Yakin ingin menghapus barang ini?')">
                        <i class="bi bi-trash me-1"></i>Hapus
                    </a>
                    <a href="bmn_index.php" class="btn btn-outline-secondary rounded-pill btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td class="text-muted" style="width: 180px;"><i class="bi bi-hash me-2"></i>Kode Barang</td>
                            <td class="fw-bold"><?= htmlspecialchars($data['kode_barang']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-box me-2"></i>Nama Barang</td>
                            <td><?= htmlspecialchars($data['nama_barang']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-card-text me-2"></i>Spesifikasi</td>
                            <td><?= nl2br(htmlspecialchars($data['spesifikasi'] ?? '-')) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-gear me-2"></i>Kondisi</td>
                            <td>
                                <span class="badge <?= $badgeClass ?> rounded-pill px-3"><?= $data['kondisi'] ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-stack me-2"></i>Jumlah Stok</td>
                            <td><span class="fw-bold"><?= $data['jumlah'] ?> unit</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-geo-alt me-2"></i>Lokasi</td>
                            <td><?= htmlspecialchars($data['lokasi'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-calendar me-2"></i>Tanggal Input</td>
                            <td><?= date('d F Y', strtotime($data['tanggal_input'])) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Info Panel -->
    <div class="col-md-4">
        <div class="card card-modern">
            <div class="card-header-modern">
                <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2"></i>Ringkasan</h6>
            </div>
            <div class="card-body p-4">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom: 1px solid #f0f0f0;">
                        <span class="text-muted">Kondisi</span>
                        <span class="badge <?= $badgeClass ?> rounded-pill px-3"><?= $data['kondisi'] ?></span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom: 1px solid #f0f0f0;">
                        <span class="text-muted">Jumlah Stok</span>
                        <span class="fw-semibold"><?= $data['jumlah'] ?> unit</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom: 1px solid #f0f0f0;">
                        <span class="text-muted">Lokasi</span>
                        <span class="fw-semibold"><?= htmlspecialchars($data['lokasi'] ?? '-') ?></span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Tanggal Input</span>
                        <span class="fw-semibold"><?= date('d/m/Y', strtotime($data['tanggal_input'])) ?></span>
                    </li>
                </ul>
                
                <div class="d-grid gap-2 mt-4">
                    <a href="bmn_permintaan.php?id=<?= $id ?>" class="btn btn-gradient rounded-pill">
                        <i class="bi bi-cart-plus me-2"></i>Ajukan Permintaan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

