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

$namaBarang = $_POST['nama_barang'] ?? '';
$kategori = $_POST['kategori'] ?? '';
$jumlahStok = $_POST['jumlah_stok'] ?? null;
$harga = $_POST['harga'] ?? null;
$deskripsi = $_POST['deskripsi'] ?? '';
$userId = $_POST['user_id'] ?? null;

if (empty($namaBarang) || empty($kategori) || $jumlahStok === null || $harga === null || $userId === null) {
    echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi']);
    exit;
}

if (!is_numeric($jumlahStok) || !is_numeric($harga) || !is_numeric($userId)) {
    echo json_encode(['success' => false, 'message' => 'Nilai stok, harga, atau user_id tidak valid']);
    exit;
}

$sql = 'INSERT INTO barang (nama_barang, kategori, jumlah_stok, harga, deskripsi, user_id) VALUES (:nama_barang, :kategori, :jumlah_stok, :harga, :deskripsi, :user_id)';
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'nama_barang' => $namaBarang,
    'kategori' => $kategori,
    'jumlah_stok' => $jumlahStok,
    'harga' => $harga,
    'deskripsi' => $deskripsi,
    'user_id' => $userId,
]);

echo json_encode(['success' => true, 'message' => 'Barang berhasil ditambahkan']);
