<?php
session_start();
require_once '../config/config.php';
require_once '../config/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['id'];

        if ($user['role'] === 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../user/dashboard.php");
        }
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - MIP Bulungan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f6fa;
        }
        .logo-container {
            background-color: #1e1e2f;
            padding: 30px;
            border-top-left-radius: .5rem;
            border-top-right-radius: .5rem;
            text-align: center;
        }
        .logo-container img {
            max-height: 80px;
        }
        .login-box {
            max-width: 420px;
            margin: 100px auto;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        footer {
            text-align: center;
            margin-top: 60px;
            color: #888;
        }
    </style>
</head>
<body>

<div class="login-box bg-white rounded">
    <div class="logo-container">
        <img src="../assets/images/ppid_logo.png" alt="Logo PPID">
    </div>
    <div class="p-4">
        <h4 class="mb-4 text-center">Login MIP Bulungan</h4>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Masuk</button>
        </form>
    </div>
</div>

<footer>
    © 2025 - Manajemen Informasi Publik Bulungan by PPID Bulungan
</footer>

</body>
</html>
