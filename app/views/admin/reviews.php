<?php
require_once __DIR__ . '/../../helpers/Session.php';
Session::start(); Session::requireAdmin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulasan - Admin Wanbubu</title>
    <link rel="stylesheet" href="/wanbubu/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <?php require_once __DIR__ . '/../layouts/admin-sidebar.php'; ?>
        <main class="main-content">
            <header class="content-header"><h1>⭐ Kelola Ulasan</h1></header>
            <div class="card">
                <div class="empty-state">
                    <i class="fas fa-star" style="font-size:48px;color:#ffc107;"></i>
                    <h4>Fitur Ulasan</h4>
                    <p>Fitur manajemen ulasan akan segera hadir</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>