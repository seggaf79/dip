<?php
session_start();
require_once '../config/config.php';
require_once '../config/auth.php';
require_once '../includes/functions.php';
require_login();

if (!is_admin() || !isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$id = $_GET['id'];

// Cek user yang akan dihapus
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if ($user) {
    $folder = strtolower(trim(preg_replace('/\s+/', '', $user['username'])));
    $uploadPath = "../uploads/$folder";

    // Hapus dari database
    $delete = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $delete->execute([$id]);

    // Hapus folder upload (jika ada)
    if (is_dir($uploadPath)) {
        array_map('unlink', glob("$uploadPath/*"));
        rmdir($uploadPath);
    }
}

header("Location: manage_users.php");
exit();
