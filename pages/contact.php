<?php
require_once __DIR__ . '/../app/helpers/Session.php';
Session::start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak - Wanbubu</title>
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
        .navbar .nav-links { display: flex; gap: 20px; }
        .navbar .nav-links a { text-decoration: none; color: #333; font-weight: 500; }
        
        .container { max-width: 700px; margin: 0 auto; padding: 60px 20px; }
        .container h2 { margin-bottom: 30px; }
        
        .card {
            background: white; padding: 30px; border-radius: 15px;
            box-shadow: var(--shadow); margin-bottom: 20px;
            display: flex; align-items: center; gap: 20px;
        }
        .card i { font-size: 30px; color: var(--primary); width: 50px; text-align: center; }
        .card h4 { margin-bottom: 5px; }
        .card p { color: #666; }
        
        footer { background: #2c3e50; color: white; text-align: center; padding: 30px; margin-top: 60px; }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/wanbubu/" class="logo">🥗 Wanbubu</a>
        <div class="nav-links">
            <a href="/wanbubu/">Beranda</a>
            <a href="/wanbubu/pages/products.php">Produk</a>
            <a href="/wanbubu/pages/about.php">Tentang</a>
            <a href="/wanbubu/pages/contact.php">Kontak</a>
            <a href="/wanbubu/pages/orders.php">📋 Pesanan Saya</a>
        </div>
    </nav>
    
    <div class="container">
        <h2>📞 Hubungi Kami</h2>
        
        <div class="card">
            <i class="fas fa-envelope"></i>
            <div><h4>Email</h4><p>halo@wanbubu.com</p></div>
        </div>
        <div class="card">
            <i class="fas fa-phone"></i>
            <div><h4>Telepon</h4><p>0812-3456-7890</p></div>
        </div>
        <div class="card">
            <i class="fas fa-map-marker-alt"></i>
            <div><h4>Alamat</h4><p>Jl. Sehat Bahagia No. 123, Jakarta Selatan</p></div>
        </div>
        <div class="card">
            <i class="fas fa-clock"></i>
            <div><h4>Jam Operasional</h4><p>Senin - Sabtu: 08:00 - 20:00 WIB</p></div>
        </div>
    </div>
    
    <footer><p>&copy; <?php echo date('Y'); ?> Wanbubu - Makanan Sehat</p></footer>
</body>
</html>