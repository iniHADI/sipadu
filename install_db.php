<?php
include 'koneksi.php';

$sql = file_get_contents('update_users.sql');

if (mysqli_multi_query($conn, $sql)) {
    echo '<div class="alert alert-success">✅ DATABASE UPDATED! Kolom nama, posisi, foto ditambah. Admin diupdate.</div>';
    do {
        if ($result = mysqli_store_result($conn)) {
            mysqli_free_result($result);
        }
    } while (mysqli_more_results($conn) && mysqli_next_result($conn));
} else {
    echo '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
}

echo '<a href="index.php" class="btn btn-primary">→ Ke Dashboard</a>';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Install DB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
</body>
</html>
