<?php
require_once __DIR__ . '/../app/helpers/Session.php';
require_once __DIR__ . '/../app/models/Database.php';

Session::start();
Session::requireLogin();

$db = (new Database())->connect();
$userId = Session::get('user_id');

// Get cart items
$stmt = $db->prepare("SELECT c.*, p.nama, p.harga, p.gambar, p.slug FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = :uid");
$stmt->execute(['uid' => $userId]);
$cartItems = $stmt->fetchAll();

$total = 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Wanbubu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #4CAF50; --bg: #f5f6fa; --white: #fff; --danger: #ff4757; --shadow: 0 2px 15px rgba(0,0,0,0.08); }
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
        
        .container { max-width: 900px; margin: 0 auto; padding: 40px 20px; }
        .container h2 { margin-bottom: 30px; }
        
        .cart-item {
            background: white; padding: 25px; border-radius: 15px;
            box-shadow: var(--shadow); margin-bottom: 15px;
            display: flex; align-items: center; gap: 20px;
            transition: transform 0.3s;
        }
        .cart-item:hover { transform: translateY(-2px); }
        
        .cart-image {
            width: 100px; height: 100px; background: #f5f5f5;
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
            overflow: hidden; flex-shrink: 0;
        }
        .cart-image img { width: 100%; height: 100%; object-fit: cover; }
        .cart-image .placeholder { font-size: 40px; opacity: 0.5; }
        
        .cart-info { flex: 1; }
        .cart-info .name { font-weight: 600; font-size: 16px; margin-bottom: 5px; }
        .cart-info .name a { color: #333; text-decoration: none; }
        .cart-info .name a:hover { color: var(--primary); }
        .cart-info .price { color: var(--primary); font-weight: 700; font-size: 18px; }
        
        .cart-qty { display: flex; align-items: center; gap: 8px; }
        .cart-qty button {
            width: 35px; height: 35px; border: 1px solid #ddd;
            background: white; cursor: pointer; font-size: 18px; border-radius: 8px;
            transition: all 0.3s;
        }
        .cart-qty button:hover { border-color: var(--primary); }
        .cart-qty span { min-width: 30px; text-align: center; font-weight: 600; }
        
        .cart-subtotal { font-weight: 700; font-size: 16px; min-width: 100px; text-align: right; }
        
        .btn-remove {
            background: var(--danger); color: white; border: none;
            padding: 8px 12px; border-radius: 8px; cursor: pointer;
            transition: background 0.3s;
        }
        .btn-remove:hover { background: #ee3a4a; }
        
        .cart-total {
            background: white; padding: 30px; border-radius: 15px;
            box-shadow: var(--shadow); text-align: right; margin-top: 25px;
        }
        .cart-total h3 { font-size: 28px; color: var(--primary); margin-bottom: 15px; }
        .btn-checkout {
            display: inline-block; padding: 14px 40px;
            background: var(--primary); color: white; border-radius: 10px;
            text-decoration: none; font-size: 18px; font-weight: 600;
            transition: background 0.3s;
        }
        .btn-checkout:hover { background: #388E3C; }
        
        .btn-shop {
            display: inline-block; padding: 12px 30px;
            background: white; color: var(--primary); border: 2px solid var(--primary);
            border-radius: 10px; text-decoration: none; font-weight: 600;
            transition: all 0.3s;
        }
        .btn-shop:hover { background: #e8f5e9; }
        
        .empty-state { text-align: center; padding: 80px 20px; }
        .empty-state i { font-size: 100px; color: #ccc; margin-bottom: 20px; }
        .empty-state h3 { margin-bottom: 10px; }
        .empty-state p { color: #999; margin-bottom: 25px; }
        
        footer { background: #2c3e50; color: white; text-align: center; padding: 30px; margin-top: 60px; }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/wanbubu/" class="logo">🥗 Wanbubu</a>
        <div class="nav-links">
            <a href="/wanbubu/">Beranda</a>
            <a href="/wanbubu/pages/products.php">Produk</a>
            <a href="/wanbubu/pages/orders.php">📋 Pesanan Saya</a>
            <span>👋 <?php echo htmlspecialchars(Session::get('username')); ?></span>
            <a href="/wanbubu/logout.php">Keluar</a>
        </div>
    </nav>
    
    <div class="container">
        <h2>🛒 Keranjang Belanja</h2>
        
        <?php if(count($cartItems) > 0): ?>
            <?php foreach($cartItems as $item): 
                $subtotal = $item['harga'] * $item['quantity'];
                $total += $subtotal;
            ?>
            <div class="cart-item" id="item-<?php echo $item['id']; ?>">
                <div class="cart-image">
                    <?php if(!empty($item['gambar']) && $item['gambar'] != 'default-product.jpg'): ?>
                    <img src="/wanbubu/uploads/products/<?php echo htmlspecialchars($item['gambar']); ?>" alt="">
                    <?php else: ?>
                    <div class="placeholder">🥬</div>
                    <?php endif; ?>
                </div>
                
                <div class="cart-info">
                    <div class="name">
                        <a href="/wanbubu/pages/product-detail.php?slug=<?php echo $item['slug']; ?>">
                            <?php echo htmlspecialchars($item['nama']); ?>
                        </a>
                    </div>
                    <div class="price">Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></div>
                </div>
                
                <div class="cart-qty">
                    <button onclick="updateQty(<?php echo $item['id']; ?>, -1)">−</button>
                    <span id="qty-<?php echo $item['id']; ?>"><?php echo $item['quantity']; ?></span>
                    <button onclick="updateQty(<?php echo $item['id']; ?>, 1)">+</button>
                </div>
                
                <div class="cart-subtotal" id="subtotal-<?php echo $item['id']; ?>">
                    Rp <?php echo number_format($subtotal, 0, ',', '.'); ?>
                </div>
                
                <button class="btn-remove" onclick="removeItem(<?php echo $item['id']; ?>)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <?php endforeach; ?>
            
            <div class="cart-total">
                <h3>Total: Rp <?php echo number_format($total, 0, ',', '.'); ?></h3>
                <a href="/wanbubu/pages/checkout.php" class="btn-checkout">
                    <i class="fas fa-credit-card"></i> Lanjut ke Checkout
                </a>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-shopping-cart"></i>
                <h3>Keranjang kosong</h3>
                <p>Yuk, belanja makanan sehat!</p>
                <a href="/wanbubu/pages/products.php" class="btn-shop">Belanja Sekarang</a>
            </div>
        <?php endif; ?>
    </div>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Wanbubu - Makanan Sehat</p>
    </footer>
    
    <script>
    function updateQty(cartId, change) {
        var el = document.getElementById('qty-' + cartId);
        var newQty = parseInt(el.textContent) + change;
        
        if(newQty < 1) {
            removeItem(cartId);
            return;
        }
        
        fetch('/wanbubu/api/update-cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cart_id: cartId, quantity: newQty })
        })
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if(d.success) location.reload();
        });
    }
    
    function removeItem(cartId) {
        if(!confirm('Hapus item dari keranjang?')) return;
        
        fetch('/wanbubu/api/remove-from-cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cart_id: cartId })
        })
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if(d.success) location.reload();
        });
    }
    </script>
</body>
</html>