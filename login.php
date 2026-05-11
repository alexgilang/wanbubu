<?php
require_once __DIR__ . '/app/helpers/Session.php';
require_once __DIR__ . '/app/models/User.php';

Session::start();

// Kalau sudah login, redirect
if(Session::isLoggedIn()) {
    if(Session::isAdmin()) {
        header('Location: /wanbubu/admin.php');
    } else {
        header('Location: /wanbubu/');
    }
    exit;
}

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if(empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi!';
    } else {
        $userModel = new User();
        $user = $userModel->login($username, $password);
        
        if($user) {
            Session::set('user_id', $user['id']);
            Session::set('username', $user['username']);
            Session::set('nama_lengkap', $user['nama_lengkap']);
            Session::set('role', $user['role']);
            
            if($user['role'] === 'admin') {
                header('Location: /wanbubu/admin.php');
            } else {
                header('Location: /wanbubu/');
            }
            exit;
        } else {
            $error = 'Username atau password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Wanbubu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #4CAF50; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        
        .login-card {
            background: white; border-radius: 20px; padding: 40px;
            width: 400px; box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        }
        
        .logo {
            width: 70px; height: 70px;
            background: linear-gradient(135deg, #4CAF50, #66BB6A);
            border-radius: 15px; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px; font-size: 30px; color: white;
        }
        
        .login-card h2 { text-align: center; color: #333; margin-bottom: 5px; }
        .login-card .subtitle { text-align: center; color: #666; margin-bottom: 25px; font-size: 14px; }
        
        .error {
            background: #fff5f5; color: #c62828; padding: 12px 15px;
            border-radius: 10px; margin-bottom: 20px; font-size: 14px;
            display: flex; align-items: center; gap: 10px; border: 1px solid #ffcdd2;
        }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 14px; color: #333; }
        .input-icon { position: relative; }
        .input-icon i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999; }
        .input-icon input {
            width: 100%; padding: 14px 15px 14px 45px;
            border: 2px solid #e0e0e0; border-radius: 12px;
            font-size: 15px; transition: all 0.3s;
        }
        .input-icon input:focus { outline: none; border-color: #4CAF50; }
        
        .btn-login {
            width: 100%; padding: 14px; background: #4CAF50; color: white;
            border: none; border-radius: 12px; font-size: 16px; font-weight: 600;
            cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: background 0.3s;
        }
        .btn-login:hover { background: #388E3C; }
        
        .links { text-align: center; margin-top: 20px; }
        .links a { color: #4CAF50; text-decoration: none; font-weight: 600; }
        .links a:hover { text-decoration: underline; }
        .links p { color: #666; margin-top: 10px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo">🥗</div>
        <h2>Login</h2>
        <p class="subtitle">Masuk ke akun Wanbubu Anda</p>
        
        <?php if($error): ?>
        <div class="error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Username atau Email</label>
                <div class="input-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" required placeholder="Masukkan username atau email">
                </div>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" required placeholder="Masukkan password">
                </div>
            </div>
            <button type="submit" class="btn-login"><i class="fas fa-sign-in-alt"></i> Masuk</button>
        </form>
        
        <div class="links">
            <p>Belum punya akun? <a href="/wanbubu/register.php">Daftar di sini</a></p>
            <p><a href="/wanbubu/">← Kembali ke Beranda</a></p>
        </div>
    </div>
</body>
</html>