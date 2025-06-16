<?php
// Membersihkan input dari karakter berbahaya
function clean_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function is_user() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'user';
}

// Membuat folder berdasarkan struktur: uploads/[username]/[tahun]/[semester]
function create_upload_path($username, $tahun, $semester) {
    $folder = strtolower(trim(preg_replace('/\s+/', '', $username)));
    $path = "../uploads/{$folder}/{$tahun}/{$semester}";
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
    return $path;
}

// Mendapatkan tahun dan semester sekarang
function get_current_semester() {
    $bulan = date('n');
    return ($bulan <= 6) ? 1 : 2;
}
