<?php
require_once __DIR__ . '/../app/helpers/Session.php';
require_once __DIR__ . '/../app/models/Product.php';

Session::start();

$productModel = new Product();
$products = $productModel->getAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Produk - Wanbubu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #4CAF50; --bg: #f5f6fa; --white: #fff; --shadow: 0 2px 15px rgba(0,0,0,0.08); }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--bg); color: #333; }
        
        /* Navbar */
        .navbar {
            background: var(--white); padding: 15px 30px;
            box-shadow: var(--shadow); display: flex;
            justify-content: space-between; align-items: center;
            position: sticky; top: 0; z-index: 100;
        }
        .navbar .logo { font-size: 24px; font-weight: 700; color: var(--primary); text-decoration: none; }
        .navbar .nav-links { display: flex; gap: 20px; align-items: center; }
        .navbar .nav-links a { text-decoration: none; color: #333; font-weight: 500; transition: color 0.3s; }
        .navbar .nav-links a:hover { color: var(--primary); }
        .btn-primary {
            padding: 10px 20px; background: var(--primary); color: white !important;
            border-radius: 8px; font-weight: 600; transition: background 0.3s;
        }
        .btn-primary:hover { background: #388E3C; }
        
        /* Container */
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        .container h2 { text-align: center; font-size: 32px; margin-bottom: 10px; }
        .container .subtitle { text-align: center; color: #666; margin-bottom: 40px; }
        
        /* Products Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 25px;
        }
        
        .product-card {
            background: white; border-radius: 15px; overflow: hidden;
            box-shadow: var(--shadow); transition: transform 0.3s, box-shadow 0.3s;
        }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 5px 25px rgba(0,0,0,0.12); }
        
        .product-image {
            height: 250px; background: #f0f0f0;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }
        .product-image img { width: 100%; height: 100%; object-fit: cover; }
        .product-image .placeholder { font-size: 80px; opacity: 0.5; }
        
        .product-info { padding: 20px; }
        .product-info .category { font-size: 12px; color: #999; text-transform: uppercase; margin-bottom: 5px; }
        .product-info h3 { font-size: 18px; margin-bottom: 8px; }
        .product-info h3 a { text-decoration: none; color: #333; }
        .product-info h3 a:hover { color: var(--primary); }
        .product-info .price { font-size: 22px; font-weight: 700; color: var(--primary); margin-bottom: 5px; }
        .product-info .rating { color: #ffc107; margin-bottom: 12px; font-size: 14px; }
        .product-info .stock { font-size: 13px; margin-bottom: 12px; }
        .product-info .stock.available { color: #4CAF50; }
        .product-info .stock.empty { color: #ff4757; }
        
        .btn-cart {
            display: block; width: 100%; padding: 12px;
            background: var(--primary); color: white; border: none;
            border-radius: 8px; font-size: 14px; font-weight: 600;
            cursor: pointer; transition: background 0.3s;
        }
        .btn-cart:hover { background: #388E3C; }
        
        .btn-detail {
            display: block; width: 100%; padding: 12px;
            background: white; color: var(--primary); border: 2px solid var(--primary);
            border-radius: 8px; font-size: 14px; font-weight: 600; text-align: center;
            text-decoration: none; transition: all 0.3s;
        }
        .btn-detail:hover { background: #e8f5e9; }
        
        /* Footer */
        footer {
            background: #2c3e50; color: white; text-align: center;
            padding: 30px; margin-top: 60px; font-size: 14px;
        }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 60px; }
        .empty-state i { font-size: 80px; color: #ccc; margin-bottom: 20px; }
        .empty-state h3 { margin-bottom: 10px; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar { padding: 15px; }
            .navbar .nav-links { gap: 10px; font-size: 14px; }
            .products-grid { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; }
        }
    </style>
</head>
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
                <a href="/wanbubu/pages/cart.php">🛒 Keranjang</a>
                <span>👋 <?php echo htmlspecialchars(Session::get('username')); ?></span>
                <a href="/wanbubu/logout.php">Keluar</a>
            <?php else: ?>
                <a href="/wanbubu/login.php">Login</a>
                <a href="/wanbubu/register.php" class="btn-primary">Daftar</a>
            <?php endif; ?>
        </div>
    </nav>
    
    <!-- Products -->
    <div class="container">
        <h2>🛍️ Semua Produk</h2>
        <p class="subtitle">Temukan makanan sehat pilihan untuk Anda</p>
        
        <?php if(count($products) > 0): ?>
        <div class="products-grid">
            <?php foreach($products as $p): ?>
            <div class="product-card">
                <div class="product-image">
                    <?php if(!empty($p['gambar']) && $p['gambar'] != 'default-product.jpg'): ?>
                    <img src="/wanbubu/uploads/products/<?php echo htmlspecialchars($p['gambar']); ?>" alt="<?php echo htmlspecialchars($p['nama']); ?>">
                    <?php else: ?>
                    <div class="placeholder">🥬</div>
                    <?php endif; ?>
                </div>
                <div class="product-info">
                    <div class="category"><?php echo htmlspecialchars($p['kategori_nama'] ?? 'Umum'); ?></div>
                    <h3>
                        <a href="/wanbubu/pages/product-detail.php?slug=<?php echo $p['slug']; ?>">
                            <?php echo htmlspecialchars($p['nama']); ?>
                        </a>
                    </h3>
                    <div class="price">Rp <?php echo number_format($p['harga'], 0, ',', '.'); ?></div>
                    
                    <?php if($p['rating'] > 0): ?>
                    <div class="rating">⭐ <?php echo $p['rating']; ?> (<?php echo $p['total_rating']; ?>)</div>
                    <?php endif; ?>
                    
                    <div class="stock <?php echo $p['stok'] > 0 ? 'available' : 'empty'; ?>">
                        <?php echo $p['stok'] > 0 ? '✅ Stok: ' . $p['stok'] : '❌ Stok habis'; ?>
                    </div>
                    
                    <?php if($p['stok'] > 0): ?>
                    <button class="btn-cart" onclick="addToCart(<?php echo $p['id']; ?>)">
                        <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                    </button>
                    <?php else: ?>
                    <a href="/wanbubu/pages/product-detail.php?slug=<?php echo $p['slug']; ?>" class="btn-detail">Lihat Detail</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <h3>Belum ada produk</h3>
            <p>Produk akan segera hadir!</p>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Wanbubu - Makanan Sehat. All rights reserved.</p>
    </footer>
    
    <script>
    function addToCart(productId) {
        <?php if(!Session::isLoggedIn()): ?>
        alert('Silakan login terlebih dahulu!');
        window.location.href = '/wanbubu/login.php';
        return;
        <?php endif; ?>
        
        fetch('/wanbubu/api/add-to-cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        })
        .then(r => r.json())
        .then(data => {
            if(data.success) {
                alert('✅ Produk ditambahkan ke keranjang!');
            } else {
                alert(data.message || 'Gagal menambahkan');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan');
        });
    }
    </script>
</body>
</html>