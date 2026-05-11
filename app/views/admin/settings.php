<?php
require_once __DIR__ . '/../../helpers/Session.php';
Session::start(); Session::requireAdmin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - Admin Wanbubu</title>
    <link rel="stylesheet" href="/wanbubu/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <?php require_once __DIR__ . '/../layouts/admin-sidebar.php'; ?>
        <main class="main-content">
            <header class="content-header"><h1>⚙️ Pengaturan</h1></header>
            
            <div class="card">
                <h3>Informasi Toko</h3>
                <div class="form-group"><label>Nama Toko</label><input type="text" value="Wanbubu" class="form-control" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;"></div>
                <div class="form-group"><label>Email</label><input type="email" value="halo@wanbubu.com" class="form-control" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;"></div>
                <div class="form-group"><label>Telepon</label><input type="text" value="0812-3456-7890" class="form-control" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;"></div>
                <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </main>
    </div>
</body>
</html>