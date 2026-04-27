<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

$search = $_GET['cari'] ?? '';
$where = "WHERE 1=1";
if ($search) $where .= " AND (nama_barang LIKE '%$search%' OR kode_barang LIKE '%$search%')";

// Statistik
$total_barang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bmn_barang"))['total'];
$total_baik = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bmn_barang WHERE kondisi='Baik'"))['total'];
$total_rusak = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bmn_barang WHERE kondisi='Rusak Ringan' OR kondisi='Rusak Berat'"))['total'];
$query_stok = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM bmn_barang");
$total_stok = mysqli_fetch_assoc($query_stok)['total'] ?? 0;

$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM bmn_barang $where");
$total = mysqli_fetch_assoc($total_query)['total'];
$per_page = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $per_page;

$query = mysqli_query($conn, "SELECT * FROM bmn_barang $where ORDER BY kode_barang LIMIT $per_page OFFSET $offset");

$page_title = 'Persediaan Barang';
$title = 'Persediaan - Lapas Kelas IIB Purwakarta';
?>
<?php include 'header.php'; ?>

<div class="page-header">
    <h1><i class="bi bi-box-seam me-2 text-primary"></i>Persediaan Barang</h1>
    <p class="breadcrumb">Dashboard / Persediaan Barang</p>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="stat-info">
                <h3><?= $total_barang ?></h3>
                <p>Total Barang</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3><?= $total_baik ?></h3>
                <p>Kondisi Baik</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="stat-info">
                <h3><?= $total_rusak ?></h3>
                <p>Rusak</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-stack"></i>
            </div>
            <div class="stat-info">
                <h3><?= $total_stok ?></h3>
                <p>Total Stok</p>
            </div>
        </div>
    </div>
</div>

<!-- Search & Table Card -->
<div class="card card-modern">
    <div class="card-header-modern d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-box-seam me-2"></i>Katalog Persediaan Barang</h5>
        <div class="d-flex gap-2">
            <a href="import_persediaan.php" class="btn btn-outline-primary rounded-pill">
                <i class="bi bi-upload me-1"></i>Import
            </a>
            <a href="export_persediaan.php" class="btn btn-outline-success rounded-pill">
                <i class="bi bi-download me-1"></i>Export
            </a>
            <a href="bmn_tambah.php" class="btn btn-gradient">
                <i class="bi bi-plus-lg me-1"></i>Tambah Barang
            </a>
        </div>
    </div>
    <div class="card-body p-4">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="cari" value="<?= $search ?>" class="form-control form-control-modern border-start-0 rounded-end-pill" placeholder="Cari kode atau nama barang...">
                </div>
            </div>
            <div class="col-md-2">
                <button class="btn btn-gradient w-100 rounded-pill">
                    <i class="bi bi-search me-1"></i>Cari
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Spesifikasi</th>
                        <th>Kondisi</th>
                        <th>Jumlah</th>
                        <th>Lokasi</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($barang = mysqli_fetch_assoc($query)) { 
                        $badgeClass = 'bg-secondary';
                        if ($barang['kondisi'] == 'Baik') $badgeClass = 'bg-success';
                        elseif ($barang['kondisi'] == 'Rusak Ringan') $badgeClass = 'bg-warning';
                        elseif ($barang['kondisi'] == 'Rusak Berat') $badgeClass = 'bg-danger';
                    ?>
                    <tr>
                        <td><span class="fw-bold text-primary"><?= $barang['kode_barang'] ?></span></td>
                        <td><?= htmlspecialchars($barang['nama_barang']) ?></td>
                        <td><small class="text-muted"><?= substr($barang['spesifikasi'], 0, 50) ?>...</small></td>
                        <td>
                            <span class="badge <?= $badgeClass ?> bg-opacity-75 rounded-pill px-3 py-2">
                                <?= $barang['kondisi'] ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-info bg-opacity-75 rounded-pill px-3 py-2">
                                <?= $barang['jumlah'] ?> unit
                            </span>
                        </td>
                        <td><i class="bi bi-geo-alt text-muted me-1"></i><?= htmlspecialchars($barang['lokasi'] ?? '-') ?></td>
                        <td><small class="text-muted"><?= date('d/m/Y', strtotime($barang['tanggal_input'])) ?></small></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="bmn_detail.php?id=<?= $barang['id'] ?>" class="btn btn-sm btn-outline-info rounded-pill" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="bmn_edit.php?id=<?= $barang['id'] ?>" class="btn btn-sm btn-outline-warning rounded-pill" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="bmn_hapus.php?id=<?= $barang['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill" title="Hapus" onclick="return confirm('Yakin ingin menghapus barang ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <a href="bmn_permintaan.php?id=<?= $barang['id'] ?>" class="btn btn-sm btn-primary rounded-pill" title="Permintaan">
                                    <i class="bi bi-cart-plus"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <?php if ($total > $per_page) { ?>
        <nav class="mt-4">
            <ul class="pagination pagination-modern justify-content-center">
                <?php for ($i=1; $i <= ceil($total/$per_page); $i++) { ?>
                    <li class="page-item <?= $i==$page ? 'active' : '' ?>">
                        <a class="page-link" href="?cari=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
        <?php } ?>
    </div>
</div>

<?php include 'footer.php'; ?>

