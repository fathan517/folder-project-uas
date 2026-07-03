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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$namaLengkap = $_POST['nama_lengkap'] ?? '';

if (empty($username) || empty($password) || empty($namaLengkap)) {
    echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi']);
    exit;
}

if (strlen($username) < 3 || strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Username atau password tidak memenuhi syarat']);
    exit;
}

$sql = 'SELECT COUNT(*) FROM users WHERE username = :username';
$stmt = $pdo->prepare($sql);
$stmt->execute(['username' => $username]);
$exists = $stmt->fetchColumn();

if ($exists > 0) {
    echo json_encode(['success' => false, 'message' => 'Username sudah terdaftar']);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$sql = 'INSERT INTO users (username, password, nama_lengkap) VALUES (:username, :password, :nama_lengkap)';
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'username' => $username,
    'password' => $hashedPassword,
    'nama_lengkap' => $namaLengkap,
]);

$userId = $pdo->lastInsertId();

if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'Gagal membuat akun']);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'Registrasi berhasil',
    'data' => [
        'id' => (int)$userId,
        'username' => $username,
        'nama_lengkap' => $namaLengkap,
    ],
]);
