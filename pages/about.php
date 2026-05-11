<?php
require_once __DIR__ . '/../app/helpers/Session.php';
Session::start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang - Wanbubu</title>
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
        
        .container { max-width: 800px; margin: 0 auto; padding: 60px 20px; }
        .hero { text-align: center; margin-bottom: 50px; }
        .hero i { font-size: 80px; color: var(--primary); margin-bottom: 20px; }
        .hero h1 { font-size: 36px; margin-bottom: 15px; }
        .hero p { color: #666; font-size: 18px; line-height: 1.8; }
        
        .card {
            background: white; padding: 30px; border-radius: 15px;
            box-shadow: var(--shadow); margin-bottom: 25px;
        }
        .card h3 { margin-bottom: 15px; color: var(--primary); }
        .card p { line-height: 1.8; color: #555; }
        
        .features { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 40px; }
        .feature { text-align: center; padding: 25px; }
        .feature i { font-size: 40px; color: var(--primary); margin-bottom: 15px; }
        .feature h4 { margin-bottom: 10px; }
        .feature p { color: #666; font-size: 14px; }
        
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
        <div class="hero">
            <i class="fas fa-heart"></i>
            <h1>Tentang Wanbubu</h1>
            <p>Misi kami adalah menyediakan makanan sehat organik berkualitas tinggi untuk gaya hidup yang lebih baik.</p>
        </div>
        
        <div class="card">
            <h3>🌱 Cerita Kami</h3>
            <p>Wanbubu didirikan dengan semangat untuk membuat makanan sehat lebih mudah diakses oleh semua orang. Kami bekerja sama langsung dengan petani lokal untuk memastikan setiap produk yang kami jual segar, organik, dan berkualitas tinggi.</p>
        </div>
        
        <div class="features">
            <div class="feature"><i class="fas fa-leaf"></i><h4>100% Organik</h4><p>Semua produk bersertifikat organik</p></div>
            <div class="feature"><i class="fas fa-truck"></i><h4>Pengiriman Cepat</h4><p>Pesanan sampai dalam 24 jam</p></div>
            <div class="feature"><i class="fas fa-shield-alt"></i><h4>Jaminan Kualitas</h4><p>Garansi uang kembali</p></div>
        </div>
    </div>
    
    <footer><p>&copy; <?php echo date('Y'); ?> Wanbubu - Makanan Sehat</p></footer>
</body>
</html>