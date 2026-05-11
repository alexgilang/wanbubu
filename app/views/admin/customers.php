<?php
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../helpers/Session.php';
Session::start(); Session::requireAdmin();

$userModel = new User();
$users = $userModel->getAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelanggan - Admin Wanbubu</title>
    <link rel="stylesheet" href="/wanbubu/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <?php require_once __DIR__ . '/../layouts/admin-sidebar.php'; ?>
        <main class="main-content">
            <header class="content-header">
                <h1>👥 Kelola Pelanggan</h1>
                <small class="text-muted">Total: <?php echo count($users); ?> pengguna</small>
            </header>
            
            <div class="card">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead><tr><th>Username</th><th>Nama Lengkap</th><th>Email</th><th>Telepon</th><th>Role</th><th>Bergabung</th></tr></thead>
                        <tbody>
                            <?php foreach($users as $u): ?>
                            <tr>
                                <td><strong>@<?php echo htmlspecialchars($u['username']); ?></strong></td>
                                <td><?php echo htmlspecialchars($u['nama_lengkap']); ?></td>
                                <td><?php echo htmlspecialchars($u['email']); ?></td>
                                <td><?php echo htmlspecialchars($u['telepon'] ?? '-'); ?></td>
                                <td><span class="badge <?php echo $u['role'] == 'admin' ? 'badge-primary' : 'badge-success'; ?>"><?php echo $u['role']; ?></span></td>
                                <td><?php echo date('d M Y', strtotime($u['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>