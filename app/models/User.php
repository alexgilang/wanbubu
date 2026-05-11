<?php
require_once __DIR__ . '/Database.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = (new Database())->connect();
    }
    
    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :u OR email = :e");
        $stmt->execute(['u' => $username, 'e' => $username]);
        $user = $stmt->fetch();
        
        if($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
    
    public function register($data) {
        if(empty($data['username']) || empty($data['email']) || empty($data['password']) || empty($data['nama_lengkap'])) {
            return ['success' => false, 'message' => 'Semua field harus diisi!'];
        }
        
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = :u OR email = :e");
        $stmt->execute(['u' => $data['username'], 'e' => $data['email']]);
        
        if($stmt->fetch()) {
            return ['success' => false, 'message' => 'Username atau email sudah terdaftar!'];
        }
        
        if(strlen($data['password']) < 6) {
            return ['success' => false, 'message' => 'Password minimal 6 karakter!'];
        }
        
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, nama_lengkap, telepon) VALUES (:u, :e, :p, :n, :t)");
        $result = $stmt->execute([
            'u' => $data['username'],
            'e' => $data['email'],
            'p' => $hash,
            'n' => $data['nama_lengkap'],
            't' => $data['telepon'] ?? null
        ]);
        
        if($result) {
            return ['success' => true, 'message' => 'Pendaftaran berhasil! Silakan login.'];
        }
        return ['success' => false, 'message' => 'Gagal mendaftar, coba lagi.'];
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function updateProfile($id, $data) {
        $stmt = $this->db->prepare("UPDATE users SET nama_lengkap=:n, telepon=:t, alamat=:a WHERE id=:id");
        return $stmt->execute([
            'n' => $data['nama_lengkap'],
            't' => $data['telepon'] ?? null,
            'a' => $data['alamat'] ?? null,
            'id' => $id
        ]);
    }
    
    public function getAll() {
        return $this->db->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
    }
    
    public function countUsers() {
        return $this->db->query("SELECT COUNT(*) as t FROM users WHERE role = 'user'")->fetch()['t'];
    }
    
    public function delete($id) {
        return $this->db->prepare("DELETE FROM users WHERE id = :id AND role != 'admin'")->execute(['id' => $id]);
    }
}