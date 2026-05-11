CREATE DATABASE IF NOT EXISTS wanbubu_db;
USE wanbubu_db;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    telepon VARCHAR(20),
    alamat TEXT,
    role ENUM('user','admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    nama VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    deskripsi TEXT,
    harga INT NOT NULL,
    stok INT DEFAULT 0,
    gambar VARCHAR(255) DEFAULT 'default-product.jpg',
    rating DECIMAL(2,1) DEFAULT 0,
    total_rating INT DEFAULT 0,
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    user_id INT,
    total_harga INT NOT NULL,
    metode_pembayaran VARCHAR(50) NOT NULL,
    alamat_pengiriman TEXT NOT NULL,
    status ENUM('menunggu','diproses','dikirim','selesai','dibatalkan') DEFAULT 'menunggu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT,
    nama_produk VARCHAR(200) NOT NULL,
    quantity INT NOT NULL,
    harga_satuan INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    rating TINYINT CHECK (rating >= 1 AND rating <= 5),
    komentar TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Admin: admin / admin123
INSERT INTO users (username, email, password, nama_lengkap, role) VALUES
('admin', 'admin@wanbubu.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin Wanbubu', 'admin'),
('budi', 'budi@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Budi Santoso', 'user');

INSERT INTO categories (nama, slug, deskripsi) VALUES
('Sayuran Organik', 'sayuran-organik', 'Sayuran segar organik langsung dari petani lokal'),
('Buah Segar', 'buah-segar', 'Buah-buahan segar kaya vitamin'),
('Makanan Sehat', 'makanan-sehat', 'Cemilan dan makanan sehat tanpa pengawet'),
('Superfood', 'superfood', 'Makanan super kaya nutrisi'),
('Minuman', 'minuman', 'Minuman segar dan sehat');

INSERT INTO products (category_id, nama, slug, deskripsi, harga, stok, rating, total_rating, is_featured) VALUES
(1, 'Bayam Organik Segar', 'bayam-organik-segar', 'Bayam organik segar kaya zat besi. Dipanen langsung dari kebun organik bersertifikat.', 15000, 100, 4.5, 128, 1),
(1, 'Kale Organik', 'kale-organik', 'Kale organik superfood kaya nutrisi. Sempurna untuk salad sehat.', 25000, 75, 4.3, 95, 1),
(2, 'Alpukat Mentega', 'alpukat-mentega', 'Alpukat premium tekstur lembut. Kaya lemak sehat.', 8000, 200, 4.8, 256, 1),
(2, 'Pisang Cavendish', 'pisang-cavendish', 'Pisang manis alami kaya kalium. Cocok untuk smoothie.', 5000, 300, 4.6, 189, 0),
(3, 'Granola Bar Madu', 'granola-bar-madu', 'Granola bar sehat dengan madu murni dan almond panggang.', 15000, 250, 4.2, 89, 1),
(4, 'Biji Chia Organik', 'biji-chia-organik', 'Biji chia kaya omega-3, serat, dan protein.', 35000, 100, 4.7, 312, 1),
(5, 'Kombucha Original', 'kombucha-original', 'Kombucha fermentasi alami kaya probiotik.', 18000, 70, 4.6, 210, 1),
(5, 'Smoothie Hijau Detox', 'smoothie-hijau-detox', 'Smoothie segar dari bayam, apel, lemon, dan jahe.', 25000, 40, 4.8, 145, 1);

INSERT INTO reviews (user_id, product_id, rating, komentar) VALUES
(2, 1, 5, 'Bayamnya segar banget! Pengiriman cepat.'),
(2, 3, 4, 'Alpukatnya enak dan matang sempurna.');