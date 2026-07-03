<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Max-Age: 86400');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

require_once 'db.php';

$userId = $_GET['user_id'] ?? null;

if (!$userId || !is_numeric($userId)) {
    echo json_encode(['success' => false, 'message' => 'User ID tidak valid']);
    exit;
}

$sql = 'SELECT id, nama_barang, kategori, jumlah_stok, harga, deskripsi, user_id, created_at, updated_at FROM barang WHERE user_id = :user_id ORDER BY created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $userId]);
$items = $stmt->fetchAll();

echo json_encode(['success' => true, 'data' => $items]);
