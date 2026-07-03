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

$id = $_POST['id'] ?? null;
$namaBarang = $_POST['nama_barang'] ?? '';
$kategori = $_POST['kategori'] ?? '';
$jumlahStok = $_POST['jumlah_stok'] ?? null;
$harga = $_POST['harga'] ?? null;
$deskripsi = $_POST['deskripsi'] ?? '';

if (!$id || !is_numeric($id) || empty($namaBarang) || empty($kategori) || $jumlahStok === null || $harga === null) {
    echo json_encode(['success' => false, 'message' => 'Field tidak valid atau tidak lengkap']);
    exit;
}

if (!is_numeric($jumlahStok) || !is_numeric($harga)) {
    echo json_encode(['success' => false, 'message' => 'Nilai stok atau harga tidak valid']);
    exit;
}

$sql = 'UPDATE barang SET nama_barang = :nama_barang, kategori = :kategori, jumlah_stok = :jumlah_stok, harga = :harga, deskripsi = :deskripsi WHERE id = :id';
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'nama_barang' => $namaBarang,
    'kategori' => $kategori,
    'jumlah_stok' => $jumlahStok,
    'harga' => $harga,
    'deskripsi' => $deskripsi,
    'id' => $id,
]);

echo json_encode(['success' => true, 'message' => 'Barang berhasil diubah']);
