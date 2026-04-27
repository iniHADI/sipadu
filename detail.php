<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

$id = $_GET['id'] ?? 0;
$query = mysqli_query($conn, "SELECT * FROM surat WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Surat tidak ditemukan'); window.location='index.php';</script>";
    exit;
}

$warna = "secondary";
$icon = "bi-circle";
if ($data['status'] == "Diterima") { $warna = "primary"; $icon = "bi-inbox"; }
if ($data['status'] == "Diproses") { $warna = "warning"; $icon = "bi-hourglass-split"; }
if ($data['status'] == "Selesai") { $warna = "success"; $icon = "bi-check-circle"; }

$page_title = 'Detail Surat';
$title = 'Detail - ' . $data['nomor_surat'];
?>
<?php include 'header.php'; ?>

<div class="page-header">
    <h1><i class="bi bi-file-earmark-text me-2 text-primary"></i>Detail Surat</h1>
    <p class="breadcrumb"><a href="index.php" class="text-decoration-none">Arsip Surat</a> / Detail</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card card-modern mb-4">
            <div class="card-header-modern d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-<?= $warna ?> bg-opacity-75 rounded-pill px-3 py-2 fs-6">
                        <i class="bi <?= $icon ?> me-1"></i><?= $data['status'] ?>
                    </span>
                </div>
                <div class="d-flex gap-2">
                    <a href="edit.php?id=<?= $id ?>" class="btn btn-outline-warning rounded-pill btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <a href="hapus.php?id=<?= $id ?>" class="btn btn-outline-danger rounded-pill btn-sm" onclick="return confirm('Yakin ingin menghapus surat ini?')">
                        <i class="bi bi-trash me-1"></i>Hapus
                    </a>
                    <a href="index.php" class="btn btn-outline-secondary rounded-pill btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td class="text-muted" style="width: 180px;"><i class="bi bi-hash me-2"></i>Nomor Surat</td>
                            <td class="fw-bold"><?= htmlspecialchars($data['nomor_surat']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-calendar me-2"></i>Tanggal</td>
                            <td><?= date('d F Y', strtotime($data['tanggal'])) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-building me-2"></i>Pengirim</td>
                            <td><?= htmlspecialchars($data['pengirim']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-text-left me-2"></i>Perihal</td>
                            <td><?= htmlspecialchars($data['perihal']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-arrow-right-circle me-2"></i>Disposisi Ke</td>
                            <td><?= htmlspecialchars($data['disposisi_ke'] ?: '-') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if (!empty($data['file'])) { ?>
        <div class="card card-modern">
            <div class="card-header-modern">
                <h5 class="mb-0 fw-bold"><i class="bi bi-paperclip me-2"></i>Lampiran File</h5>
            </div>
            <div class="card-body p-4">
                <div class="d-flex gap-3 mb-3">
                    <a href="<?= $data['file'] ?>" target="_blank" class="btn btn-outline-primary rounded-pill">
                        <i class="bi bi-eye me-2"></i>Lihat di Tab Baru
                    </a>
                    <a href="<?= $data['file'] ?>" class="btn btn-outline-success rounded-pill" download>
                        <i class="bi bi-download me-2"></i>Download
                    </a>
                </div>
                
                <?php 
                $ext = strtolower(pathinfo($data['file'], PATHINFO_EXTENSION));
                if (in_array($ext, ['pdf'])) { 
                ?>
                <div class="rounded-4 overflow-hidden" style="border: 1px solid #e0e0e0;">
                    <iframe src="<?= $data['file'] ?>#toolbar=0&navpanes=0" width="100%" height="500px"></iframe>
                </div>
                <?php } elseif (in_array($ext, ['jpg', 'jpeg', 'png'])) { ?>
                <div class="text-center rounded-4 overflow-hidden" style="border: 1px solid #e0e0e0;">
                    <img src="<?= $data['file'] ?>" class="img-fluid" style="max-height: 500px;">
                </div>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
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
                        <span class="text-muted">Status</span>
                        <span class="badge bg-<?= $warna ?> rounded-pill px-3"><?= $data['status'] ?></span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom: 1px solid #f0f0f0;">
                        <span class="text-muted">Tanggal</span>
                        <span class="fw-semibold"><?= date('d/m/Y', strtotime($data['tanggal'])) ?></span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom: 1px solid #f0f0f0;">
                        <span class="text-muted">Pengirim</span>
                        <span class="fw-semibold text-end" style="max-width: 60%;"><?= htmlspecialchars($data['pengirim']) ?></span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Disposisi</span>
                        <span class="fw-semibold"><?= htmlspecialchars($data['disposisi_ke'] ?: '-') ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

