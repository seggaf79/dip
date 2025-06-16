<?php
session_start();
require_once '../config/config.php';
require_once '../config/auth.php';
require_once '../includes/functions.php';
require_login();

if (!is_admin()) {
    header("Location: ../public/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    echo "User tidak ditemukan.";
    exit();
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<main class="container mt-4">
    <h4>Detail User: <?= htmlspecialchars($user['username']) ?></h4>
    <ul class="list-group mt-3">
        <li class="list-group-item"><strong>OPD:</strong> <?= $user['opd'] ?></li>
        <li class="list-group-item"><strong>Nama Pejabat:</strong> <?= $user['nama_pejabat'] ?></li>
        <li class="list-group-item"><strong>NIP:</strong> <?= $user['nip'] ?></li>
        <li class="list-group-item"><strong>Role:</strong> <?= $user['role'] ?></li>
        <li class="list-group-item"><strong>Dibuat pada:</strong> <?= $user['created_at'] ?></li>
    </ul>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
