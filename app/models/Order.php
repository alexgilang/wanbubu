<?php
require_once __DIR__ . '/Database.php';

class Order {
    private $db;
    
    public function __construct() {
        $this->db = (new Database())->connect();
    }
    
    public function getAll() {
        return $this->db->query("SELECT o.*, u.nama_lengkap, u.username, u.email FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC")->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT o.*, u.nama_lengkap, u.username, u.email, u.telepon FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function getByOrderNumber($orderNumber) {
        $stmt = $this->db->prepare("SELECT o.*, u.nama_lengkap, u.username FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.order_number = :on");
        $stmt->execute(['on' => $orderNumber]);
        return $stmt->fetch();
    }
    
    public function getItems($orderId) {
        $stmt = $this->db->prepare("SELECT oi.*, p.gambar FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = :oid");
        $stmt->execute(['oid' => $orderId]);
        return $stmt->fetchAll();
    }
    
    public function getUserOrders($userId) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id = :uid ORDER BY created_at DESC");
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll();
    }
    
    public function create($data) {
        $orderNumber = 'ORD-' . date('Ymd') . '-' . rand(10000, 99999);
        
        $stmt = $this->db->prepare("INSERT INTO orders (order_number, user_id, total_harga, metode_pembayaran, alamat_pengiriman, status) VALUES (:on, :uid, :total, :metode, :alamat, 'menunggu')");
        $stmt->execute([
            'on' => $orderNumber,
            'uid' => $data['user_id'],
            'total' => $data['total_harga'],
            'metode' => $data['metode_pembayaran'],
            'alamat' => $data['alamat_pengiriman']
        ]);
        
        return [
            'order_id' => $this->db->lastInsertId(),
            'order_number' => $orderNumber
        ];
    }
    
    public function addItem($orderId, $item) {
        $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, nama_produk, quantity, harga_satuan) VALUES (:oid, :pid, :nama, :qty, :harga)");
        return $stmt->execute([
            'oid' => $orderId,
            'pid' => $item['product_id'],
            'nama' => $item['nama_produk'],
            'qty' => $item['quantity'],
            'harga' => $item['harga_satuan']
        ]);
    }
    
    public function updateStatus($id, $status) {
        $allowed = ['menunggu', 'diproses', 'dikirim', 'selesai', 'dibatalkan'];
        if(!in_array($status, $allowed)) return false;
        
        return $this->db->prepare("UPDATE orders SET status = :s WHERE id = :id")->execute(['s' => $status, 'id' => $id]);
    }
    
    public function countAll() {
        return $this->db->query("SELECT COUNT(*) as t FROM orders")->fetch()['t'];
    }
    
    public function totalRevenue() {
        return $this->db->query("SELECT COALESCE(SUM(total_harga), 0) as t FROM orders WHERE status != 'dibatalkan'")->fetch()['t'];
    }
    
    public function getRecent($limit = 5) {
        $stmt = $this->db->prepare("SELECT o.*, u.nama_lengkap FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT :l");
        $stmt->bindValue(':l', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getSalesData($days = 30) {
        $stmt = $this->db->prepare("SELECT DATE(created_at) as tgl, SUM(total_harga) as total FROM orders WHERE status != 'dibatalkan' AND created_at >= DATE_SUB(NOW(), INTERVAL :d DAY) GROUP BY DATE(created_at) ORDER BY tgl ASC");
        $stmt->bindValue(':d', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}