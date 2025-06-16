<?php
// config/config.php

// Pastikan session belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    ini_set('session.gc_maxlifetime', 3600);
    ini_set('session.cookie_lifetime', 3600);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
}

// Definisi global
define('BASE_URL', 'http://localhost/mip/');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('APP_NAME', 'Manajemen Informasi Publik Bulungan');

// Koneksi database
$host = 'localhost';
$db   = 'mip_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
