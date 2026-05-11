<?php
require_once __DIR__ . '/Database.php';

class Product {
    private $db;
    
    public function __construct() {
        $this->db = (new Database())->connect();
    }
    
    public function getAll() {
        return $this->db->query("SELECT p.*, c.nama as kategori_nama FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_active = 1 ORDER BY p.id DESC")->fetchAll();
    }
    
    public function getFeatured($limit = 8) {
        $stmt = $this->db->prepare("SELECT p.*, c.nama as kategori_nama FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_featured = 1 AND p.is_active = 1 LIMIT :l");
        $stmt->bindValue(':l', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT p.*, c.nama as kategori_nama FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function getBySlug($slug) {
        $stmt = $this->db->prepare("SELECT p.*, c.nama as kategori_nama FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.slug = :s");
        $stmt->execute(['s' => $slug]);
        return $stmt->fetch();
    }
    
    public function getByCategory($categorySlug) {
        $stmt = $this->db->prepare("SELECT p.* FROM products p JOIN categories c ON p.category_id = c.id WHERE c.slug = :s AND p.is_active = 1");
        $stmt->execute(['s' => $categorySlug]);
        return $stmt->fetchAll();
    }
    
    public function search($keyword) {
        $stmt = $this->db->prepare("SELECT p.*, c.nama as kategori_nama FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_active = 1 AND (p.nama LIKE :k OR p.deskripsi LIKE :k2)");
        $kw = "%{$keyword}%";
        $stmt->execute(['k' => $kw, 'k2' => $kw]);
        return $stmt->fetchAll();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO products (category_id, nama, slug, deskripsi, harga, stok, gambar, is_featured) VALUES (:c, :n, :s, :d, :h, :st, :g, :f)");
        return $stmt->execute([
            'c' => $data['category_id'],
            'n' => $data['nama'],
            's' => $data['slug'],
            'd' => $data['deskripsi'],
            'h' => $data['harga'],
            'st' => $data['stok'],
            'g' => $data['gambar'],
            'f' => $data['is_featured']
        ]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE products SET category_id=:c, nama=:n, slug=:s, deskripsi=:d, harga=:h, stok=:st, gambar=:g, is_featured=:f WHERE id=:id");
        return $stmt->execute([
            'c' => $data['category_id'],
            'n' => $data['nama'],
            's' => $data['slug'],
            'd' => $data['deskripsi'],
            'h' => $data['harga'],
            'st' => $data['stok'],
            'g' => $data['gambar'],
            'f' => $data['is_featured'],
            'id' => $id
        ]);
    }
    
    public function delete($id) {
        return $this->db->prepare("DELETE FROM products WHERE id = :id")->execute(['id' => $id]);
    }
    
    public function toggleFeatured($id) {
        return $this->db->prepare("UPDATE products SET is_featured = NOT is_featured WHERE id = :id")->execute(['id' => $id]);
    }
    
    public function updateRating($id) {
        $stmt = $this->db->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM reviews WHERE product_id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        
        if($data) {
            $this->db->prepare("UPDATE products SET rating = :r, total_rating = :t WHERE id = :id")->execute([
                'r' => round($data['avg_rating'], 1),
                't' => $data['total'],
                'id' => $id
            ]);
        }
    }
    
    public function countAll() {
        return $this->db->query("SELECT COUNT(*) as t FROM products")->fetch()['t'];
    }
}