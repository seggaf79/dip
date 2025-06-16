<?php
// config/auth.php

// Fungsi untuk memeriksa apakah user sudah login
function is_logged_in() {
    return isset($_SESSION['username']);
}

// Fungsi untuk memeriksa apakah user adalah admin
function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Fungsi untuk redirect jika belum login
function require_login() {
    if (!is_logged_in()) {
        header('Location: ' . BASE_URL . 'public/login.php');
        exit();
    }
}

// Fungsi logout
function logout() {
    session_unset();
    session_destroy();
    header('Location: ' . BASE_URL . 'public/login.php');
    exit();
}
