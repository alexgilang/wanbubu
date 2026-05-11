<?php
session_start();
require_once __DIR__ . '/../app/models/Database.php';

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$userId = $_SESSION['user_id'];
$productId = $data['product_id'] ?? 0;
$quantity = $data['quantity'] ?? 1;

if(!$productId) {
    echo json_encode(['success' => false, 'message' => 'Produk tidak valid']);
    exit;
}

$db = (new Database())->connect();

// Cek stok produk
$stmt = $db->prepare("SELECT id, stok FROM products WHERE id = :id AND is_active = 1");
$stmt->execute(['id' => $productId]);
$product = $stmt->fetch();

if(!$product) {
    echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan']);
    exit;
}

if($product['stok'] < 1) {
    echo json_encode(['success' => false, 'message' => 'Stok produk habis']);
    exit;
}

// Cek existing cart
$stmt = $db->prepare("SELECT id, quantity FROM cart WHERE user_id = :uid AND product_id = :pid");
$stmt->execute(['uid' => $userId, 'pid' => $productId]);
$existing = $stmt->fetch();

if($existing) {
    $newQty = $existing['quantity'] + $quantity;
    if($newQty > $product['stok']) {
        echo json_encode(['success' => false, 'message' => 'Stok tidak mencukupi']);
        exit;
    }
    $db->prepare("UPDATE cart SET quantity = quantity + :qty WHERE id = :id")
       ->execute(['qty' => $quantity, 'id' => $existing['id']]);
} else {
    $db->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:uid, :pid, :qty)")
       ->execute(['uid' => $userId, 'pid' => $productId, 'qty' => $quantity]);
}

echo json_encode(['success' => true, 'message' => 'Produk ditambahkan ke keranjang']);