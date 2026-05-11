<?php
require_once __DIR__ . '/app/helpers/Session.php';
require_once __DIR__ . '/app/models/Product.php';

Session::start();
$productModel = new Product();
$featuredProducts = $productModel->getFeatured(8);
?>
<!DOCTYPE html>
<html lang="id">
<<i class="fa fa-header" aria-hidden="true"></i>>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wanbubu - Makanan Sehat & Organik</title>
    <link rel="stylesheet" href="/wanbubu/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</header>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="/wanbubu/" class="logo">🥗 Wanbubu</a>
        <div class="nav-links">
            <a href="/wanbubu/">Beranda</a>
            <a href="/wanbubu/pages/products.php">Produk</a>
            <a href="/wanbubu/pages/about.php">Tentang</a>
            <a href="/wanbubu/pages/contact.php">Kontak</a>
            <?php if(Session::isLoggedIn()): ?>
                <a href="/wanbubu/pages/cart.php">
                    🛒 Keranjang
                    <span class="cart-count">0</span>
                </a>
                <span>👋 <?php echo htmlspecialchars(Session::get('username')); ?></span>
                <?php if(Session::isAdmin()): ?>
                    <a href="/wanbubu/admin.php">⚙️ Admin</a>
                <?php endif; ?>
                <a href="/wanbubu/logout.php">Keluar</a>
            <?php else: ?>
                <a href="/wanbubu/login.php">Login</a>
                <a href="/wanbubu/register.php" class="btn btn-primary">Daftar</a>
            <?php endif; ?>
        </div>
    </nav>
    
    <!-- Hero -->
    <section class="hero">
        <h1>🥗 Makan Sehat, Hidup Lebih Baik</h1>
        <p>Temukan pilihan makanan organik dan sehat untuk gaya hidup Anda</p>
        <a href="/wanbubu/pages/products.php" class="btn btn-primary" style="font-size:18px;padding:15px 40px;">
            Belanja Sekarang
        </a>
    </section>
    
    <!-- Featured Products -->
    <section style="padding:60px 20px;max-width:1200px;margin:0 auto;">
        <h2 style="text-align:center;font-size:32px;margin-bottom:40px;">🌟 Produk Unggulan</h2>
        
        <?php if(count($featuredProducts) > 0): ?>
        <div class="products-grid">
            <?php foreach($featuredProducts as $p): ?>
            <div class="product-card">
                <div class="product-image">
                    <?php if(!empty($p['gambar']) && $p['gambar'] != 'default-product.jpg'): ?>
                    <img src="/wanbubu/uploads/products/<?php echo htmlspecialchars($p['gambar']); ?>" 
                         alt="<?php echo htmlspecialchars($p['nama']); ?>">
                    <?php else: ?>
                    <div style="font-size:80px;opacity:0.3;">🥬</div>
                    <?php endif; ?>
                </div>
                <div class="product-info">
                    <h3><?php echo htmlspecialchars($p['nama']); ?></h3>
                    <div class="price">Rp <?php echo number_format($p['harga'], 0, ',', '.'); ?></div>
                    <?php if($p['rating'] > 0): ?>
                    <div class="rating">⭐ <?php echo $p['rating']; ?> (<?php echo $p['total_rating']; ?>)</div>
                    <?php endif; ?>
                    <a href="/wanbubu/pages/product-detail.php?slug=<?php echo $p['slug']; ?>" 
                       class="btn btn-primary" style="display:block;text-align:center;">
                        Lihat Detail
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p style="text-align:center;color:#999;">Belum ada produk unggulan.</p>
        <?php endif; ?>
    </section>
    
    <!-- Why Us -->
    <section style="background:white;padding:60px 20px;margin-top:40px;">
        <div style="max-width:1200px;margin:0 auto;text-align:center;">
            <h2 style="font-size:32px;margin-bottom:40px;">Mengapa Wanbubu?</h2>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:30px;">
                <div style="padding:30px;">
                    <i class="fas fa-leaf" style="font-size:48px;color:#4CAF50;margin-bottom:15px;"></i>
                    <h3>100% Organik</h3>
                    <p style="color:#666;">Semua produk bersertifikat organik</p>
                </div>
                <div style="padding:30px;">
                    <i class="fas fa-truck" style="font-size:48px;color:#4CAF50;margin-bottom:15px;"></i>
                    <h3>Gratis Ongkir</h3>
                    <p style="color:#666;">Bebas ongkir untuk pembelian di atas Rp 100.000</p>
                </div>
                <div style="padding:30px;">
                    <i class="fas fa-shield-alt" style="font-size:48px;color:#4CAF50;margin-bottom:15px;"></i>
                    <h3>Jaminan Mutu</h3>
                    <p style="color:#666;">Garansi uang kembali jika tidak puas</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Wanbubu - Makanan Sehat. All rights reserved.</p>
    </footer>
    
    <script src="/wanbubu/assets/js/main.js"></script>
</body>
</html>