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

if(!$cartId) {
    echo json_encode(['success' => false]);
    exit;
}

$db = (new Database())->connect();
$db->prepare("DELETE FROM cart WHERE id = :id AND user_id = :uid")
   ->execute(['id' => $cartId, 'uid' => $_SESSION['user_id']]);

echo json_encode(['success' => true]);