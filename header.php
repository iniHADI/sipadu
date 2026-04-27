<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

$user_query = mysqli_query($conn, "SELECT nama, foto FROM users WHERE id = " . $_SESSION['user_id']);
$user = mysqli_fetch_assoc($user_query);
$foto = $user['foto'] ?? 'default.jpg';
$nama = $user['nama'] ?? $_SESSION['username'];
$role = $_SESSION['role'] ?? 'user';

$page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SIPADU - Lapas Kelas IIB Purwakarta' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-color: #667eea;
            --sidebar-width: 260px;
        }
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f0f2f5;
            overflow-x: hidden;
        }
        
        /* Navbar */
        .top-navbar {
            background: var(--primary-gradient);
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
            padding: 0.8rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        
        .top-navbar .navbar-brand {
            font-weight: 700;
            font-size: 1.1rem;
            color: white !important;
        }
        
        .top-navbar .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .top-navbar .user-info img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.5);
        }
        
        .top-navbar .user-info .user-text {
            line-height: 1.2;
        }
        
        .top-navbar .user-info .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: white;
        }
        
        .top-navbar .user-info .user-role {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.8);
        }
        
        .top-navbar .btn-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            background: rgba(255,255,255,0.1);
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .top-navbar .btn-icon:hover {
            background: rgba(255,255,255,0.25);
            color: white;
        }
        
        /* Sidebar */
        .sidebar-wrapper {
            width: var(--sidebar-width);
            min-height: calc(100vh - 65px);
            background: white;
            box-shadow: 4px 0 20px rgba(0,0,0,0.05);
            position: fixed;
            left: 0;
            top: 65px;
            z-index: 1020;
            padding: 1.5rem 0;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0 1rem;
        }
        
        .sidebar-menu .menu-header {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #adb5bd;
            padding: 0.5rem 1rem;
            margin-bottom: 0.5rem;
        }
        
        .sidebar-menu li {
            margin-bottom: 0.4rem;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            color: #6c757d;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        
        .sidebar-menu a i {
            font-size: 1.2rem;
            margin-right: 12px;
            width: 24px;
            text-align: center;
        }
        
        .sidebar-menu a:hover {
            background: #f8f9fa;
            color: var(--primary-color);
        }
        
        .sidebar-menu a.active {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: calc(100vh - 65px);
        }
        
        .page-header {
            margin-bottom: 2rem;
        }
        
        .page-header h1 {
            font-weight: 700;
            color: #2d3748;
            font-size: 1.8rem;
        }
        
        .page-header .breadcrumb {
            color: #adb5bd;
            font-size: 0.85rem;
            margin-bottom: 0;
        }
        
        /* Cards */
        .card-modern {
            background: white;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }
        
        .card-header-modern {
            background: transparent;
            border-bottom: 1px solid #f0f0f0;
            padding: 1.25rem 1.5rem;
        }
        
        /* Stat Cards */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .stat-card .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .stat-card .stat-icon.purple {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .stat-card .stat-icon.blue {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }
        
        .stat-card .stat-icon.green {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
        }
        
        .stat-card .stat-icon.orange {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
        }
        
        .stat-card .stat-info h3 {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 2px;
            color: #2d3748;
        }
        
        .stat-card .stat-info p {
            color: #adb5bd;
            font-size: 0.85rem;
            margin: 0;
        }
        
        /* Tables */
        .table-modern {
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .table-modern thead th {
            background: #f8f9fa;
            border: none;
            padding: 1rem 1.25rem;
            font-weight: 600;
            color: #495057;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .table-modern tbody td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
            font-size: 0.9rem;
        }
        
        .table-modern tbody tr:hover {
            background: #f8f9fa;
        }
        
        /* Buttons */
        .btn-gradient {
            background: var(--primary-gradient);
            border: none;
            color: white;
            font-weight: 500;
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            transition: all 0.3s;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        /* Form Controls */
        .form-control-modern, .form-select-modern {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        
        .form-control-modern:focus, .form-select-modern:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        /* Pagination */
        .pagination-modern .page-link {
            border: none;
            border-radius: 10px;
            margin: 0 3px;
            color: #6c757d;
            font-weight: 500;
            padding: 0.5rem 0.9rem;
        }
        
        .pagination-modern .page-item.active .page-link {
            background: var(--primary-gradient);
            color: white;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar-wrapper {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>

<!-- Navbar Top -->
<nav class="navbar top-navbar">
    <div class="container-fluid">
        <a class="navbar-brand">
            <i class="bi bi-shield-lock me-2"></i>SIPADU
        </a>
        <div class="d-flex align-items-center gap-3">
            <div class="user-info d-none d-sm-flex">
                <img src="<?= $foto ?>" alt="<?= $nama ?>">
                <div class="user-text">
                    <div class="user-name"><?= htmlspecialchars($nama) ?></div>
                    <div class="user-role"><?= ucfirst($role) ?></div>
                </div>
            </div>
            <a href="profile.php" class="btn-icon" title="Profil">
                <i class="bi bi-person"></i>
            </a>
            <a href="logout.php" class="btn-icon" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</nav>

<!-- Sidebar -->
<div class="sidebar-wrapper d-none d-md-block">
    <ul class="sidebar-menu">
        <div class="menu-header">Menu Utama</div>
        <li>
            <a href="index.php" class="<?= $page=='index.php' ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-text"></i>
                <span>Arsip Surat</span>
            </a>
        </li>
        <li>
            <a href="bmn_index.php" class="<?= strpos($page, 'bmn')!==false ? 'active' : '' ?>">
                <i class="bi bi-box-seam"></i>
                <span>Persediaan Barang</span>
            </a>
        </li>
        <div class="menu-header mt-4">Pengaturan</div>
        <li>
            <a href="profile.php" class="<?= $page=='profile.php' ? 'active' : '' ?>">
                <i class="bi bi-person-circle"></i>
                <span>Profil Saya</span>
            </a>
        </li>
    </ul>
</div>

<!-- Main Content -->
<main class="main-content">


