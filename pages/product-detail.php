<?php
require_once __DIR__ . '/../app/helpers/Session.php';
require_once __DIR__ . '/../app/models/Product.php';

Session::start();

$slug = $_GET['slug'] ?? '';
$productModel = new Product();
$product = $productModel->getBySlug($slug);

if(!$product) {
    header('Location: /wanbubu/pages/products.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['nama']); ?> - Wanbubu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #4CAF50; --bg: #f5f6fa; --white: #fff; --shadow: 0 2px 15px rgba(0,0,0,0.08); }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--bg); color: #333; }
        
        .navbar {
            background: var(--white); padding: 15px 30px;
            box-shadow: var(--shadow); display: flex;
            justify-content: space-between; align-items: center;
        }
        .navbar .logo { font-size: 24px; font-weight: 700; color: var(--primary); text-decoration: none; }
        .navbar .nav-links { display: flex; gap: 20px; align-items: center; }
        .navbar .nav-links a { text-decoration: none; color: #333; font-weight: 500; }
        
        .container { max-width: 1100px; margin: 0 auto; padding: 40px 20px; }
        .breadcrumb { margin-bottom: 25px; }
        .breadcrumb a { color: var(--primary); text-decoration: none; }
        .breadcrumb span { color: #999; }
        
        .detail-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 50px; background: white; padding: 40px;
            border-radius: 20px; box-shadow: var(--shadow);
        }
        
        .detail-image {
            display: flex; align-items: center; justify-content: center;
            background: #f5f5f5; border-radius: 15px; min-height: 450px;
            overflow: hidden;
        }
        .detail-image img { max-width: 100%; max-height: 450px; object-fit: cover; border-radius: 15px; }
        .detail-image .placeholder { font-size: 150px; opacity: 0.3; }
        
        .detail-info .category {
            display: inline-block; background: #e8f5e9; color: var(--primary);
            padding: 5px 15px; border-radius: 20px; font-size: 13px; font-weight: 600;
            margin-bottom: 15px;
        }
        .detail-info h1 { font-size: 30px; margin-bottom: 10px; line-height: 1.3; }
        .detail-info .rating { color: #ffc107; margin-bottom: 15px; font-size: 16px; }
        .detail-info .price { font-size: 36px; font-weight: 700; color: var(--primary); margin-bottom: 15px; }
        .detail-info .description { color: #555; line-height: 1.8; margin-bottom: 25px; }
        .detail-info .stock { margin-bottom: 20px; font-size: 15px; }
        .detail-info .stock.available { color: #4CAF50; }
        .detail-info .stock.empty { color: #ff4757; }
        
        .qty-selector { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
        .qty-selector button {
            width: 45px; height: 45px; border: 2px solid #e0e0e0;
            background: white; cursor: pointer; font-size: 22px; border-radius: 10px;
            transition: all 0.3s;
        }
        .qty-selector button:hover { border-color: var(--primary); color: var(--primary); }
        .qty-selector input {
            width: 70px; height: 45px; text-align: center;
            border: 2px solid #e0e0e0; border-radius: 10px; font-size: 18px; font-weight: 600;
        }
        
        .btn-add-cart {
            width: 100%; padding: 16px; background: var(--primary); color: white;
            border: none; border-radius: 12px; font-size: 18px; font-weight: 600;
            cursor: pointer; transition: background 0.3s; display: flex;
            align-items: center; justify-content: center; gap: 10px;
        }
        .btn-add-cart:hover { background: #388E3C; }
        .btn-add-cart:disabled { background: #ccc; cursor: not-allowed; }
        
        footer { background: #2c3e50; color: white; text-align: center; padding: 30px; margin-top: 60px; }
        
        @media (max-width: 768px) {
            .detail-grid { grid-template-columns: 1fr; gap: 30px; padding: 25px; }
            .detail-image { min-height: 300px; }
            .detail-info h1 { font-size: 24px; }
            .detail-info .price { font-size: 28px; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/wanbubu/" class="logo">🥗 Wanbubu</a>
        <div class="nav-links">
            <a href="/wanbubu/">Beranda</a>
            <a href="/wanbubu/pages/products.php">Produk</a>
            <?php if(Session::isLoggedIn()): ?>
                <a href="/wanbubu/pages/cart.php">🛒 Keranjang</a>
                <a href="/wanbubu/pages/orders.php">📋 Pesanan Saya</a>
                <span>👋 <?php echo htmlspecialchars(Session::get('username')); ?></span>
                <a href="/wanbubu/logout.php">Keluar</a>
            <?php else: ?>
                <a href="/wanbubu/login.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>
    
    <div class="container">
        <div class="breadcrumb">
            <a href="/wanbubu/">Beranda</a> &raquo;
            <a href="/wanbubu/pages/products.php">Produk</a> &raquo;
            <span><?php echo htmlspecialchars($product['nama']); ?></span>
        </div>
        
        <div class="detail-grid">
            <div class="detail-image">
                <?php if(!empty($product['gambar']) && $product['gambar'] != 'default-product.jpg'): ?>
                <img src="/wanbubu/uploads/products/<?php echo htmlspecialchars($product['gambar']); ?>" alt="<?php echo htmlspecialchars($product['nama']); ?>">
                <?php else: ?>
                <div class="placeholder">🥬</div>
                <?php endif; ?>
            </div>
            
            <div class="detail-info">
                <div class="category"><?php echo htmlspecialchars($product['kategori_nama'] ?? 'Umum'); ?></div>
                <h1><?php echo htmlspecialchars($product['nama']); ?></h1>
                
                <?php if($product['rating'] > 0): ?>
                <div class="rating">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star" style="color: <?php echo $i <= $product['rating'] ? '#ffc107' : '#e0e0e0'; ?>;"></i>
                    <?php endfor; ?>
                    <?php echo $product['rating']; ?> (<?php echo $product['total_rating']; ?> ulasan)
                </div>
                <?php endif; ?>
                
                <div class="price">Rp <?php echo number_format($product['harga'], 0, ',', '.'); ?></div>
                
                <div class="description">
                    <?php echo nl2br(htmlspecialchars($product['deskripsi'] ?? 'Tidak ada deskripsi')); ?>
                </div>
                
                <div class="stock <?php echo $product['stok'] > 0 ? 'available' : 'empty'; ?>">
                    <?php if($product['stok'] > 0): ?>
                    <i class="fas fa-check-circle"></i> Stok tersedia (<?php echo $product['stok']; ?> tersedia)
                    <?php else: ?>
                    <i class="fas fa-times-circle"></i> Stok habis
                    <?php endif; ?>
                </div>
                
                <?php if($product['stok'] > 0): ?>
                <div class="qty-selector">
                    <button onclick="changeQty(-1)">−</button>
                    <input type="number" id="qty" value="1" min="1" max="<?php echo $product['stok']; ?>" readonly>
                    <button onclick="changeQty(1)">+</button>
                </div>
                
                <button class="btn-add-cart" onclick="addToCart(<?php echo $product['id']; ?>)">
                    <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Wanbubu - Makanan Sehat</p>
    </footer>
    
    <script>
    function changeQty(amount) {
        var input = document.getElementById('qty');
        var val = parseInt(input.value) + amount;
        if(val >= 1 && val <= <?php echo $product['stok']; ?>) {
            input.value = val;
        }
    }
    
    function addToCart(productId) {
        <?php if(!Session::isLoggedIn()): ?>
        alert('Silakan login terlebih dahulu!');
        window.location.href = '/wanbubu/login.php';
        return;
        <?php endif; ?>
        
        var qty = document.getElementById('qty').value;
        
        fetch('/wanbubu/api/add-to-cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId, quantity: parseInt(qty) })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if(data.success) {
                alert('✅ Produk ditambahkan ke keranjang!');
            } else {
                alert(data.message || 'Gagal menambahkan');
            }
        })
        .catch(function(err) {
            console.error(err);
            alert('Terjadi kesalahan');
        });
    }
    </script>
</body>
</html>