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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $opd = $_POST['opd'];
    $nama_pejabat = $_POST['nama_pejabat'];
    $nip = $_POST['nip'];
    $role = $_POST['role'];

    $updateStmt = $pdo->prepare("UPDATE users SET opd=?, nama_pejabat=?, nip=?, role=? WHERE id=?");
    $updateStmt->execute([$opd, $nama_pejabat, $nip, $role, $id]);

    header("Location: manage_users.php");
    exit();
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<main class="container mt-4">
    <h4>Edit User: <?= htmlspecialchars($user['username']) ?></h4>

    <form method="POST" class="mt-3">
        <div class="mb-2">
            <label>OPD</label>
            <input type="text" name="opd" class="form-control" value="<?= $user['opd'] ?>" required>
        </div>
        <div class="mb-2">
            <label>Nama Pejabat</label>
            <input type="text" name="nama_pejabat" class="form-control" value="<?= $user['nama_pejabat'] ?>" required>
        </div>
        <div class="mb-2">
            <label>NIP</label>
            <input type="text" name="nip" class="form-control" value="<?= $user['nip'] ?>" required>
        </div>
        <div class="mb-2">
            <label>Role</label>
            <select name="role" class="form-select">
                <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    </form>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
