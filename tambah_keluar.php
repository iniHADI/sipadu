<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>

<html>
<head>
    <title>Tambah Surat Keluar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5" style="max-width:600px;">
    <div class="card shadow p-4">

        <h3 class="text-center mb-4">📤 Tambah Surat Keluar</h3>

        <form action="proses_tambah_keluar.php" method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label">Nomor Surat</label>
                <input type="text" name="nomor" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Penerima</label>
                <input type="text" name="penerima" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Perihal</label>
                <input type="text" name="perihal" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">No Respons (opsional)</label>
                <input type="text" name="no_resp" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option>Dikirim</option>
                    <option>Terbalas</option>
                    <option>Selesai</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Upload Lampiran</label>
                <input type="file" name="file" class="form-control">
            </div>

            <div class="d-flex justify-content-between">
                <a href="index.php" class="btn btn-secondary">← Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>

        </form>

    </div>
</div>

</body>
</html>
