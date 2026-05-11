<?php
$currentPage = $_GET['page'] ?? 'dashboard';
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <h2>🥗 Wanbubu</h2>
        <small>Panel Admin</small>
    </div>
    <nav class="sidebar-nav">
        <a href="/wanbubu/admin.php" class="<?php echo ($currentPage == 'dashboard') ? 'active' : ''; ?>">
            <i class="fas fa-chart-line"></i> <span>Dashboard</span>
        </a>
        <a href="/wanbubu/admin.php?page=products" class="<?php echo ($currentPage == 'products') ? 'active' : ''; ?>">
            <i class="fas fa-box"></i> <span>Produk</span>
        </a>
        <a href="/wanbubu/admin.php?page=categories" class="<?php echo ($currentPage == 'categories') ? 'active' : ''; ?>">
            <i class="fas fa-tags"></i> <span>Kategori</span>
        </a>
        <a href="/wanbubu/admin.php?page=orders" class="<?php echo ($currentPage == 'orders') ? 'active' : ''; ?>">
            <i class="fas fa-shopping-cart"></i> <span>Pesanan</span>
        </a>
        <a href="/wanbubu/admin.php?page=customers" class="<?php echo ($currentPage == 'customers') ? 'active' : ''; ?>">
            <i class="fas fa-users"></i> <span>Pelanggan</span>
        </a>
        <a href="/wanbubu/admin.php?page=reviews" class="<?php echo ($currentPage == 'reviews') ? 'active' : ''; ?>">
            <i class="fas fa-star"></i> <span>Ulasan</span>
        </a>
        <a href="/wanbubu/admin.php?page=settings" class="<?php echo ($currentPage == 'settings') ? 'active' : ''; ?>">
            <i class="fas fa-cog"></i> <span>Pengaturan</span>
        </a>
        <a href="/wanbubu/logout.php" class="logout-link">
            <i class="fas fa-sign-out-alt"></i> <span>Keluar</span>
        </a>
    </nav>
</aside>