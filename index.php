<?php
session_start();

// Jika session role sudah ada, langsung redirect sesuai role
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin/dashboard.php');
        exit();
    } elseif ($_SESSION['role'] === 'user') {
        header('Location: user/dashboard.php');
        exit();
    }
}

// Jika belum login, redirect ke halaman login publik
header('Location: public/login.php');
exit();
