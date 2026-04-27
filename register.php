<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = $success = '';
if ($_POST) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (strlen($username) < 3) {
        $error = "Username min 3 karakter";
    } elseif (strlen($password) < 6) {
        $error = "Password min 6 karakter";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $query = mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$username', '$hash')");
        
        if (mysqli_insert_id($conn)) {
            $success = "Akun berhasil dibuat! <a href='login.php'>Login sekarang</a>";
        } else {
            $error = "Username sudah ada!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - SIPADU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }
        
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }
        
        .shape {
            position: absolute;
            filter: blur(50px);
            z-index: 0;
            animation: float 20s infinite;
        }
        
        .shape-1 {
            top: -10%; left: -10%;
            width: 500px; height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        
        .shape-2 {
            bottom: -10%; right: -10%;
            width: 400px; height: 400px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            animation-delay: -5s;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }
        
        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 1;
        }
        
        .form-control-modern {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.8rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        
        .form-control-modern:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.8rem;
            border-radius: 12px;
            transition: all 0.3s;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .icon-circle {
            width: 70px; height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .icon-circle i {
            font-size: 32px; color: white;
        }
    </style>
</head>
<body>
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    
    <div class="register-card">
        <div class="text-center mb-4">
            <div class="icon-circle">
                <i class="bi bi-person-plus"></i>
            </div>
            <h3 class="fw-bold">Daftar Akun</h3>
            <p class="text-muted small">Buat akun baru untuk mengakses SIPADU</p>
        </div>
        
        <?php if ($error) { ?>
        <div class="alert alert-danger rounded-4 d-flex align-items-center">
            <i class="bi bi-exclamation-circle-fill me-2"></i><?= $error ?>
        </div>
        <?php } ?>
        
        <?php if ($success) { ?>
        <div class="alert alert-success rounded-4 d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2"></i><?= $success ?>
        </div>
        <?php } else { ?>
        
        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 rounded-start-pill">
                        <i class="bi bi-person text-muted"></i>
                    </span>
                    <input type="text" name="username" class="form-control form-control-modern border-start-0 rounded-end-pill" value="<?= $_POST['username'] ?? '' ?>" required placeholder="Minimal 3 karakter">
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 rounded-start-pill">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input type="password" name="password" class="form-control form-control-modern border-start-0 rounded-end-pill" required placeholder="Minimal 6 karakter">
                </div>
            </div>
            
            <button type="submit" class="btn btn-gradient w-100">
                <i class="bi bi-person-plus me-2"></i>Daftar
            </button>
        </form>
        
        <?php } ?>
        
        <div class="text-center mt-4">
            <p class="text-muted mb-0">Sudah punya akun? <a href="login.php" class="text-decoration-none fw-semibold" style="color: #667eea;">Masuk di sini</a></p>
        </div>
    </div>
</body>
</html>

