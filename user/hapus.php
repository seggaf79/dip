<?php
// ===========================
// user/hapus.php
// ===========================
session_start();
require_once '../config/config.php';
require_once '../config/auth.php';
require_once '../includes/functions.php';
require_login();

if ($_SESSION['role'] !== 'user' || !isset($_GET['id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$id = $_GET['id'];

// Cek apakah file milik user
$stmt = $pdo->prepare("SELECT file_path FROM informasi WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);
$data = $stmt->fetch();

if (!$data) {
    echo "Data tidak ditemukan.";
    exit();
}

// Hapus file dari sistem jika ada
$path = '../uploads/' . $data['file_path'];
if (file_exists($path)) {
    unlink($path);
}

// Hapus dari database
$stmt = $pdo->prepare("DELETE FROM informasi WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);

header("Location: dashboard.php");
exit();
