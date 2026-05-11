<?php
require_once __DIR__ . '/../app/helpers/Session.php';
require_once __DIR__ . '/../app/models/Order.php';

Session::start();
Session::requireLogin();

$orderModel = new Order();
$userId = Session::get('user_id');
$orders = $orderModel->getUserOrders($userId);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Wanbubu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f6fa; color: #333; }
        
        .navbar { background: white; padding: 15px 30px; box-shadow: 0 2px 15px rgba(0,0,0,0.08); display: flex; justify-content: space-between; align-items: center; }
        .navbar .logo { font-size: 24px; font-weight: 700; color: #4CAF50; text-decoration: none; }
        .navbar .nav-links { display: flex; gap: 20px; align-items: center; }
        .navbar .nav-links a { text-decoration: none; color: #333; font-weight: 500; }
        
        .container { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        .container h2 { margin-bottom: 30px; }
        
        .order-card {
            background: white; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px; overflow: hidden;
        }
        .order-header {
            padding: 20px; background: #f8f9fa; display: flex;
            justify-content: space-between; align-items: center;
            border-bottom: 1px solid #e0e0e0;
        }
        .order-header .order-number { font-weight: 700; font-size: 16px; }
        .order-header .order-date { color: #666; font-size: 13px; }
        
        .order-body { padding: 20px; }
        .order-info { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px; }
        .order-info div strong { display: block; font-size: 12px; color: #999; text-transform: uppercase; margin-bottom: 3px; }
        .order-info div span { font-size: 15px; }
        
        .status-badge {
            display: inline-block; padding: 6px 14px; border-radius: 20px;
            font-size: 12px; font-weight: 600; text-transform: uppercase;
        }
        .status-menunggu { background: #fff3cd; color: #856404; }
        .status-diproses { background: #cce5ff; color: #004085; }
        .status-dikirim { background: #d4edda; color: #155724; }
        .status-selesai { background: #d1ecf1; color: #0c5460; }
        .status-dibatalkan { background: #f8d7da; color: #721c24; }
        
        .btn { display: inline-block; padding: 8px 15px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 13px; }
        .btn-primary { background: #4CAF50; color: white; }
        .btn-outline { background: white; color: #4CAF50; border: 2px solid #4CAF50; }
        
        .empty-state { text-align: center; padding: 60px; }
        .empty-state i { font-size: 80px; color: #ccc; margin-bottom: 20px; }
        .empty-state h3 { margin-bottom: 10px; }
        .empty-state p { color: #999; margin-bottom: 20px; }
        
        footer { background: #2c3e50; color: white; text-align: center; padding: 30px; margin-top: 60px; }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="/wanbubu/" class="logo">🥗 Wanbubu</a>
    <div class="nav-links">
        <a href="/wanbubu/">Beranda</a>
        <a href="/wanbubu/pages/products.php">Produk</a>
        <a href="/wanbubu/pages/cart.php">🛒 Keranjang</a>
        <a href="/wanbubu/pages/orders.php" style="color:#4CAF50;font-weight:700;">📋 Pesanan Saya</a>
        <span>👋 <?php echo htmlspecialchars(Session::get('username')); ?></span>
        <a href="/wanbubu/logout.php">Keluar</a>
    </div>
</nav>

<div class="container">
    <h2>📋 Riwayat Pesanan</h2>
    
    <?php if(count($orders) > 0): ?>
        <?php foreach($orders as $order): 
            $statusClass = 'status-' . $order['status'];
            $statusLabels = [
                'menunggu' => 'Menunggu',
                'diproses' => 'Diproses',
                'dikirim' => 'Dikirim',
                'selesai' => 'Selesai',
                'dibatalkan' => 'Dibatalkan'
            ];
            $statusLabel = $statusLabels[$order['status']] ?? $order['status'];
        ?>
        <div class="order-card">
            <div class="order-header">
                <div>
                    <div class="order-number">📦 <?php echo htmlspecialchars($order['order_number']); ?></div>
                    <div class="order-date"><?php echo date('d M Y H:i', strtotime($order['created_at'])); ?></div>
                </div>
                <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span>
            </div>
            <div class="order-body">
                <div class="order-info">
                    <div>
                        <strong>Total</strong>
                        <span style="color:#4CAF50;font-weight:700;">Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?></span>
                    </div>
                    <div>
                        <strong>Pembayaran</strong>
                        <span><?php echo ucfirst($order['metode_pembayaran']); ?></span>
                    </div>
                    <div>
                        <strong>Alamat</strong>
                        <span style="font-size:13px;"><?php echo htmlspecialchars(substr($order['alamat_pengiriman'], 0, 50)); ?>...</span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-receipt"></i>
            <h3>Belum ada pesanan</h3>
            <p>Yuk, mulai belanja makanan sehat!</p>
            <a href="/wanbubu/pages/products.php" class="btn btn-primary">Belanja Sekarang</a>
        </div>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; <?php echo date('Y'); ?> Wanbubu - Makanan Sehat</p>
</footer>

</body>
</html>