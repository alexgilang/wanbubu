<?php
require_once __DIR__ . '/app/helpers/Session.php';
require_once __DIR__ . '/app/models/User.php';

Session::start();

if(Session::isLoggedIn()) {
    header('Location: /wanbubu/');
    exit;
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $nama = trim($_POST['nama_lengkap'] ?? '');
    
    if(empty($username) || empty($email) || empty($password) || empty($nama)) {
        $error = 'Semua field harus diisi!';
    } elseif($password !== $confirm) {
        $error = 'Password tidak cocok!';
    } elseif(strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } else {
        $userModel = new User();
        $result = $userModel->register([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'nama_lengkap' => $nama
        ]);
        
        if($result['success']) {
            $success = 'Pendaftaran berhasil! Silakan login.';
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Wanbubu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #4CAF50; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 20px;
        }
        
        .register-card {
            background: white; border-radius: 20px; padding: 40px;
            width: 420px; box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        }
        
        .logo {
            width: 70px; height: 70px;
            background: linear-gradient(135deg, #4CAF50, #66BB6A);
            border-radius: 15px; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px; font-size: 30px; color: white;
        }
        
        .register-card h2 { text-align: center; color: #333; margin-bottom: 5px; }
        .register-card .subtitle { text-align: center; color: #666; margin-bottom: 25px; font-size: 14px; }
        
        .alert { padding: 12px 15px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 10px; }
        .alert-error { background: #fff5f5; color: #c62828; border: 1px solid #ffcdd2; }
        .alert-success { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 14px; color: #333; }
        .form-group input {
            width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0;
            border-radius: 10px; font-size: 14px; transition: all 0.3s;
        }
        .form-group input:focus { outline: none; border-color: #4CAF50; }
        
        .btn-register {
            width: 100%; padding: 14px; background: #4CAF50; color: white;
            border: none; border-radius: 12px; font-size: 16px; font-weight: 600;
            cursor: pointer; transition: background 0.3s; margin-top: 10px;
        }
        .btn-register:hover { background: #388E3C; }
        
        .links { text-align: center; margin-top: 20px; }
        .links a { color: #4CAF50; text-decoration: none; font-weight: 600; }
        .links a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="logo">🥗</div>
        <h2>Daftar Akun</h2>
        <p class="subtitle">Bergabung dengan Wanbubu</p>
        
        <?php if($error): ?>
        <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            <br><a href="/wanbubu/login.php" style="color:#2e7d32;font-weight:600;">Klik di sini untuk login</a>
        </div>
        <?php endif; ?>
        
        <?php if(!$success): ?>
        <form method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" required placeholder="Masukkan nama lengkap">
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="Masukkan username">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="Masukkan email">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter">
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="confirm_password" required placeholder="Ulangi password">
            </div>
            <button type="submit" class="btn-register">Daftar</button>
        </form>
        <?php endif; ?>
        
        <div class="links">
            <p style="color:#666;">Sudah punya akun? <a href="/wanbubu/login.php">Login di sini</a></p>
            <p><a href="/wanbubu/">← Kembali ke Beranda</a></p>
        </div>
    </div>
</body>
</html>