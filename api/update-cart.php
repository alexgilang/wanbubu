<?php
session_start();
require_once __DIR__ . '/../app/models/Database.php';

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$cartId = $data['cart_id'] ?? 0;
$quantity = $data['quantity'] ?? 1;

if(!$cartId) {
    echo json_encode(['success' => false]);
    exit;
}

$db = (new Database())->connect();

if($quantity <= 0) {
    $db->prepare("DELETE FROM cart WHERE id = :id AND user_id = :uid")
       ->execute(['id' => $cartId, 'uid' => $_SESSION['user_id']]);
} else {
    // Cek stok
    $stmt = $db->prepare("SELECT p.stok FROM cart c JOIN products p ON c.product_id = p.id WHERE c.id = :id");
    $stmt->execute(['id' => $cartId]);
    $item = $stmt->fetch();
    
    if($item && $quantity > $item['stok']) {
        echo json_encode(['success' => false, 'message' => 'Stok tidak mencukupi']);
        exit;
    }
    
    $db->prepare("UPDATE cart SET quantity = :qty WHERE id = :id AND user_id = :uid")
       ->execute(['qty' => $quantity, 'id' => $cartId, 'uid' => $_SESSION['user_id']]);
}

echo json_encode(['success' => true]);