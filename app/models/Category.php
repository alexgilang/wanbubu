<?php
require_once __DIR__ . '/Database.php';

class Category {
    private $db;
    
    public function __construct() {
        $this->db = (new Database())->connect();
    }
    
    public function getAll() {
        return $this->db->query("SELECT * FROM categories ORDER BY nama ASC")->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function getBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE slug = :s");
        $stmt->execute(['s' => $slug]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO categories (nama, slug, deskripsi) VALUES (:n, :s, :d)");
        return $stmt->execute([
            'n' => $data['nama'],
            's' => $data['slug'],
            'd' => $data['deskripsi'] ?? null
        ]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE categories SET nama=:n, slug=:s, deskripsi=:d WHERE id=:id");
        return $stmt->execute([
            'n' => $data['nama'],
            's' => $data['slug'],
            'd' => $data['deskripsi'] ?? null,
            'id' => $id
        ]);
    }
    
    public function delete($id) {
        return $this->db->prepare("DELETE FROM categories WHERE id = :id")->execute(['id' => $id]);
    }
}