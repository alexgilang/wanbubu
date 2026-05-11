<?php
require_once __DIR__ . '/../app/helpers/Session.php';
Session::start();

$orderNumber = $_GET['order'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - Wanbubu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #4CAF50; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f6fa; }
        .navbar { background: white; padding: 15px 30px; box-shadow: 0 2px 15px rgba(0,0,0,0.08); }
        .navbar .logo { font-size: 24px; font-weight: 700; color: var(--primary); text-decoration: none; }
        .container { max-width: 500px; margin: 80px auto; padding: 40px; text-align: center; background: white; border-radius: 20px; box-shadow: 0 5px 30px rgba(0,0,0,0.1); }
        .icon { font-size: 80px; color: var(--primary); margin-bottom: 20px; }
        h2 { margin-bottom: 10px; color: #333; }
        p { color: #666; margin-bottom: 20px; }
        .order-number { background: #e8f5e9; padding: 15px; border-radius: 10px; font-size: 18px; font-weight: 700; color: var(--primary); margin-bottom: 25px; }
        .btn { display: inline-block; padding: 12px 30px; background: var(--primary); color: white; border-radius: 8px; text-decoration: none; font-weight: 600; margin: 5px; }
        .btn-outline { background: white; color: var(--primary); border: 2px solid var(--primary); }
        footer { text-align: center; padding: 30px; color: #999; margin-top: 40px; }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/wanbubu/" class="logo">🥗 Wanbubu</a>
    </nav>
    
    <div class="container">
        <div class="icon"><i class="fas fa-check-circle"></i></div>
        <h2>Pesanan Berhasil! 🎉</h2>
        <p>Terima kasih telah berbelanja di Wanbubu. Pesanan Anda sedang diproses.</p>
        
        <?php if($orderNumber): ?>
        <div class="order-number">No. Pesanan: <?php echo htmlspecialchars($orderNumber); ?></div>
        <?php endif; ?>
        
        <a href="/wanbubu/pages/products.php" class="btn">Lanjut Belanja</a>
        <a href="/wanbubu/" class="btn btn-outline">Ke Beranda</a>
    </div>
    
    <footer>&copy; <?php echo date('Y'); ?> Wanbubu - Makanan Sehat</footer>
</body>
</html>