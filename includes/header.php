<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= APP_NAME ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- includes/header.php -->
<header class="bg-dark text-white py-3 px-4 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center">
        <img src="<?= BASE_URL ?>assets/images/ppid_logo.png" alt="Logo PPID" height="35" class="me-3">
        <h5 class="mb-0">MANAJEMEN INFORMASI PUBLIK BULUNGAN</h5>
    </div>
    <div class="text-white">
        Selamat Datang, <b><?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Tamu' ?></b>
    </div>
</header>