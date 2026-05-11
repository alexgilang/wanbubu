<?php
session_start();
require_once __DIR__ . '/../app/models/Database.php';

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['count' => 0]);
    exit;
}

$db = (new Database())->connect();
$stmt = $db->prepare("SELECT COALESCE(SUM(quantity), 0) as count FROM cart WHERE user_id = :uid");
$stmt->execute(['uid' => $_SESSION['user_id']]);
$result = $stmt->fetch();

echo json_encode(['count' => (int)$result['count']]);