<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

// Hitung statistik
$total_surat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM surat"))['total'];
$total_diterima = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM surat WHERE status='Diterima'"))['total'];
$total_diproses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM surat WHERE status='Diproses'"))['total'];
$total_selesai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM surat WHERE status='Selesai'"))['total'];

$page_title = 'Arsip Surat';
$title = 'Arsip Surat - Lapas Kelas IIB Purwakarta';
?>
<?php include 'header.php'; ?>

<div class="page-header">
    <h1><i class="bi bi-file-earmark-text me-2 text-primary"></i>Arsip Surat</h1>
    <p class="breadcrumb">Dashboard / Arsip Surat</p>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="bi bi-envelope-fill"></i>
            </div>
            <div class="stat-info">
                <h3><?= $total_surat ?></h3>
                <p>Total Surat</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-inbox-fill"></i>
            </div>
            <div class="stat-info">
                <h3><?= $total_diterima ?></h3>
                <p>Diterima</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-info">
                <h3><?= $total_diproses ?></h3>
                <p>Diproses</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stat-info">
                <h3><?= $total_selesai ?></h3>
                <p>Selesai</p>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Table Card -->
<div class="card card-modern">
    <div class="card-header-modern d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-funnel me-2"></i>Filter Data</h5>
        <a href="tambah.php" class="btn btn-gradient">
            <i class="bi bi-plus-lg me-1"></i>Tambah Surat
        </a>
    </div>
    <div class="card-body p-4">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <input type="text" name="cari" value="<?= $_GET['cari'] ?? '' ?>" class="form-control form-control-modern" placeholder="🔍 Cari perihal...">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-modern">
                    <option value="">Semua Status</option>
                    <option value="Diterima" <?= ($_GET['status']??'')=='Diterima'?'selected':'' ?>>Diterima</option>
                    <option value="Diproses" <?= ($_GET['status']??'')=='Diproses'?'selected':'' ?>>Diproses</option>
                    <option value="Selesai" <?= ($_GET['status']??'')=='Selesai'?'selected':'' ?>>Selesai</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="tanggal_dari" value="<?= $_GET['tanggal_dari'] ?? '' ?>" class="form-control form-control-modern" placeholder="Dari">
            </div>
            <div class="col-md-2">
                <input type="date" name="tanggal_sampai" value="<?= $_GET['tanggal_sampai'] ?? '' ?>" class="form-control form-control-modern" placeholder="Sampai">
            </div>
            <div class="col-md-3">
                <button class="btn btn-gradient w-100">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Surat</th>
                        <th>Pengirim</th>
                        <th>Perihal</th>
                        <th>Status</th>
                        <th>Disposisi</th>
                        <th>File</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $cari = $_GET['cari'] ?? '';
                    $status_filter = $_GET['status'] ?? '';
                    $tgl_dari = $_GET['tanggal_dari'] ?? '';
                    $tgl_sampai = $_GET['tanggal_sampai'] ?? '';
                    
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $limit = 10;
                    $offset = ($page - 1) * $limit;
                    
                    $where = "WHERE 1=1";
                    if ($cari) $where .= " AND perihal LIKE '%$cari%'";
                    if ($status_filter) $where .= " AND status = '$status_filter'";
                    if ($tgl_dari) $where .= " AND tanggal >= '$tgl_dari'";
                    if ($tgl_sampai) $where .= " AND tanggal <= '$tgl_sampai'";
                    
                    $query_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM surat $where");
                    $total_rows = mysqli_fetch_assoc($query_count)['total'];
                    $total_pages = ceil($total_rows / $limit);
                    
                    $query = mysqli_query($conn, "SELECT * FROM surat $where ORDER BY tanggal DESC LIMIT $limit OFFSET $offset");

                    $no = $offset + 1;
                    while ($data = mysqli_fetch_assoc($query)) {
                        $warna = "secondary";
                        $icon = "bi-circle";
                        if ($data['status'] == "Diterima") { $warna = "primary"; $icon = "bi-inbox"; }
                        if ($data['status'] == "Diproses") { $warna = "warning"; $icon = "bi-hourglass-split"; }
                        if ($data['status'] == "Selesai") { $warna = "success"; $icon = "bi-check-circle"; }
                    ?>
                    <tr>
                        <td><span class="fw-semibold text-muted"><?= $no++ ?></span></td>
                        <td><span class="fw-semibold"><?= $data['nomor_surat'] ?></span></td>
                        <td><?= htmlspecialchars($data['pengirim']) ?></td>
                        <td><?= htmlspecialchars($data['perihal']) ?></td>
                        <td>
                            <span class="badge bg-<?= $warna ?> bg-opacity-75 text-white px-3 py-2 rounded-pill">
                                <i class="bi <?= $icon ?> me-1"></i><?= $data['status'] ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($data['disposisi_ke'] ?? '-') ?></td>
                        <td>
                            <?php if (!empty($data['file'])) { ?>
                                <a href="<?= $data['file'] ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                    <i class="bi bi-file-earmark-text me-1"></i>Lihat
                                </a>
                            <?php } else { ?>
                                <span class="text-muted">-</span>
                            <?php } ?>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="detail.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-outline-info rounded-pill" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="edit.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-outline-warning rounded-pill" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="hapus.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill" title="Hapus" onclick="return confirm('Yakin ingin menghapus surat ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_pages > 1) { ?>
        <nav class="mt-4">
            <ul class="pagination pagination-modern justify-content-center">
                <?php for($i=1; $i<=$total_pages; $i++) { ?>
                    <li class="page-item <?= $i==$page ? 'active' : '' ?>">
                        <a class="page-link" href="?<?= http_build_query($_GET, '', '&') ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
        <?php } ?>
    </div>
</div>

<?php include 'footer.php'; ?>

