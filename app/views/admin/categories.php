<?php
require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../../helpers/Session.php';
Session::start(); Session::requireAdmin();

$categoryModel = new Category();
$categories = $categoryModel->getAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori - Admin Wanbubu</title>
    <link rel="stylesheet" href="/wanbubu/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <?php require_once __DIR__ . '/../layouts/admin-sidebar.php'; ?>
        <main class="main-content">
            <header class="content-header"><h1>🏷️ Kelola Kategori</h1></header>
            
            <div class="card">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead><tr><th>ID</th><th>Nama Kategori</th><th>Slug</th><th>Deskripsi</th></tr></thead>
                        <tbody>
                            <?php foreach($categories as $cat): ?>
                            <tr>
                                <td><?php echo $cat['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($cat['nama']); ?></strong></td>
                                <td><code><?php echo htmlspecialchars($cat['slug']); ?></code></td>
                                <td><?php echo htmlspecialchars($cat['deskripsi'] ?? '-'); ?></td>
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