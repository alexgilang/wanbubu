<?php
require_once __DIR__ . '/../app/helpers/Session.php';
require_once __DIR__ . '/../app/models/Database.php';

Session::start();
Session::requireLogin();

$db = (new Database())->connect();
$userId = Session::get('user_id');

$stmt = $db->prepare("SELECT c.*, p.nama, p.harga FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = :uid");
$stmt->execute(['uid' => $userId]);
$cartItems = $stmt->fetchAll();

if(count($cartItems) == 0) {
    header('Location: /wanbubu/pages/cart.php');
    exit;
}

$total = 0;
foreach($cartItems as $item) {
    $total += $item['harga'] * $item['quantity'];
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metode = $_POST['metode_pembayaran'] ?? 'transfer';
    $alamat = trim($_POST['alamat'] ?? '');
    
    if(empty($alamat)) {
        $error = 'Alamat pengiriman harus diisi!';
    } else {
        $orderNumber = 'ORD-' . date('Ymd') . '-' . rand(10000, 99999);
        
        $stmt = $db->prepare("INSERT INTO orders (order_number, user_id, total_harga, metode_pembayaran, alamat_pengiriman, status) VALUES (:on, :uid, :total, :metode, :alamat, 'menunggu')");
        $stmt->execute([
            'on' => $orderNumber,
            'uid' => $userId,
            'total' => $total,
            'metode' => $metode,
            'alamat' => $alamat
        ]);
        
        $orderId = $db->lastInsertId();
        
        foreach($cartItems as $item) {
            $stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, nama_produk, quantity, harga_satuan) VALUES (:oid, :pid, :nama, :qty, :harga)");
            $stmt->execute([
                'oid' => $orderId,
                'pid' => $item['product_id'],
                'nama' => $item['nama'],
                'qty' => $item['quantity'],
                'harga' => $item['harga']
            ]);
        }
        
        $db->prepare("DELETE FROM cart WHERE user_id = :uid")->execute(['uid' => $userId]);
        
        header('Location: /wanbubu/pages/order-success.php?order=' . $orderNumber);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Wanbubu</title>
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
        .navbar .nav-links a { text-decoration: none; color: #333; font-weight: 500; margin-left: 20px; }
        
        .container { max-width: 800px; margin: 0 auto; padding: 40px 20px; }
        .container h2 { margin-bottom: 30px; }
        
        .checkout-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
        
        .card {
            background: white; padding: 30px; border-radius: 15px;
            box-shadow: var(--shadow);
        }
        .card h3 { margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #f0f0f0; }
        
        .summary-item {
            display: flex; justify-content: space-between; margin-bottom: 15px;
            padding-bottom: 15px; border-bottom: 1px solid #f5f5f5;
        }
        .summary-total {
            display: flex; justify-content: space-between; font-size: 22px;
            font-weight: 700; color: var(--primary); margin-top: 15px; padding-top: 15px;
            border-top: 2px solid #e0e0e0;
        }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; }
        .form-group textarea, .form-group select {
            width: 100%; padding: 12px; border: 2px solid #e0e0e0;
            border-radius: 10px; font-size: 15px; font-family: inherit;
            transition: border-color 0.3s;
        }
        .form-group textarea:focus, .form-group select:focus {
            outline: none; border-color: var(--primary);
        }
        .form-group textarea { resize: vertical; min-height: 120px; }
        
        .alert {
            padding: 12px 15px; border-radius: 10px; margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px;
        }
        .alert-error { background: #fff5f5; color: #c62828; border: 1px solid #ffcdd2; }
        
        .btn-submit {
            width: 100%; padding: 16px; background: var(--primary); color: white;
            border: none; border-radius: 12px; font-size: 18px; font-weight: 600;
            cursor: pointer; transition: background 0.3s; display: flex;
            align-items: center; justify-content: center; gap: 10px;
        }
        .btn-submit:hover { background: #388E3C; }
        
        footer { background: #2c3e50; color: white; text-align: center; padding: 30px; margin-top: 60px; }
        
        @media (max-width: 768px) {
            .checkout-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/wanbubu/" class="logo">🥗 Wanbubu</a>
        <div class="nav-links">
            <a href="/wanbubu/pages/cart.php">← Keranjang</a>
        </div>
    </nav>
    
    <div class="container">
        <h2>📦 Checkout</h2>
        
        <?php if($error): ?>
        <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="checkout-grid">
            <!-- Ringkasan -->
            <div class="card">
                <h3>🛒 Ringkasan Pesanan</h3>
                <?php foreach($cartItems as $item): 
                    $sub = $item['harga'] * $item['quantity'];
                ?>
                <div class="summary-item">
                    <span><?php echo htmlspecialchars($item['nama']); ?> x<?php echo $item['quantity']; ?></span>
                    <span>Rp <?php echo number_format($sub, 0, ',', '.'); ?></span>
                </div>
                <?php endforeach; ?>
                <div class="summary-total">
                    <span>Total</span>
                    <span>Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                </div>
            </div>
            
            <!-- Form -->
            <div class="card">
                <h3>📝 Detail Pengiriman</h3>
                <form method="POST">
                    <div class="form-group">
                        <label>Alamat Lengkap *</label>
                        <textarea name="alamat" required placeholder="Masukkan alamat lengkap pengiriman..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <select name="metode_pembayaran">
                            <option value="transfer">Transfer Bank</option>
                            <option value="cod">Cash on Delivery (COD)</option>
                            <option value="ewallet">E-Wallet</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-check-circle"></i> Buat Pesanan
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Wanbubu - Makanan Sehat</p>
    </footer>
</body>
</html>