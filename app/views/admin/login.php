<!-- http://localhost/wanbubu/app/views/admin/login.php -->

<?php
require_once __DIR__ . '/../../helpers/Session.php';
require_once __DIR__ . '/../../models/User.php';

Session::start();

if(Session::isAdmin()) {
    header('Location: /wanbubu/admin.php');
    exit;
}

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userModel = new User();
    $user = $userModel->login($_POST['username'], $_POST['password']);
    
    if($user && $user['role'] === 'admin') {
        Session::set('user_id', $user['id']);
        Session::set('username', $user['username']);
        Session::set('nama_lengkap', $user['nama_lengkap']);
        Session::set('role', $user['role']);
        header('Location: /wanbubu/admin.php');
        exit;
    } else {
        $error = 'Username atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Wanbubu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #4CAF50; --primary-dark: #388E3C; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
        }
        
        .login-card {
            background: white; border-radius: 20px; padding: 40px;
            width: 400px; box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            animation: slideUp 0.5s ease;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-logo {
            width: 80px; height: 80px; background: linear-gradient(135deg, #4CAF50, #66BB6A);
            border-radius: 20px; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px; box-shadow: 0 10px 30px rgba(76,175,80,0.3);
        }
        .login-logo i { font-size: 36px; color: white; }
        .login-card h2 { text-align: center; color: #333; margin-bottom: 5px; }
        .login-card p { text-align: center; color: #666; margin-bottom: 25px; font-size: 14px; }
        
        .error {
            background: #fff5f5; color: #c62828; padding: 10px 15px;
            border-radius: 10px; margin-bottom: 20px; font-size: 14px;
            display: flex; align-items: center; gap: 10px; border: 1px solid #ffcdd2;
        }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 14px; }
        .input-icon { position: relative; }
        .input-icon i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999; z-index: 1; }
        .input-icon input {
            width: 100%; padding: 14px 15px 14px 45px;
            border: 2px solid #e0e0e0; border-radius: 12px; font-size: 15px; transition: all 0.3s;
        }
        .input-icon input:focus { outline: none; border-color: #4CAF50; box-shadow: 0 0 0 4px rgba(76,175,80,0.1); }
        
        .btn-login {
            width: 100%; padding: 14px; background: linear-gradient(135deg, #4CAF50, #66BB6A);
            color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600;
            cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(76,175,80,0.3); }
        
        .back-link { display: block; text-align: center; margin-top: 20px; color: #4CAF50; text-decoration: none; font-weight: 600; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo"><i class="fas fa-user-shield"></i></div>
        <h2>Login Admin</h2>
        <p>Masuk ke Panel Admin Wanbubu</p>
        
        <?php if($error): ?>
        <div class="error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <div class="input-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" required placeholder="Masukkan username" autocomplete="off">
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
        
        <a href="/wanbubu/" class="back-link">← Kembali ke Website</a>
    </div>
</body>
</html>