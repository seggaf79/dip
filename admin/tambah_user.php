<?php
require_once '../config/config.php';
require_once '../config/auth.php';
require_once '../includes/functions.php';

require_login();

if (!is_admin()) {
    header("Location: ../user/dashboard.php");
    exit;
}

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $opd = trim($_POST['opd']);
    $nama_pejabat = trim($_POST['nama_pejabat']);
    $nip = trim($_POST['nip']);
    $role = $_POST['role'];

    // Cek apakah username sudah ada
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->rowCount() > 0) {
        $error = "Username sudah digunakan.";
    } else {
        // Simpan user ke database
        $stmt = $pdo->prepare("INSERT INTO users (username, password, opd, nama_pejabat, nip, role) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$username, $password, $opd, $nama_pejabat, $nip, $role])) {
            // Buat folder user di uploads/
            $user_folder = "../uploads/" . $username;
            if (!file_exists($user_folder)) {
                mkdir($user_folder, 0777, true);
            }

            $_SESSION['success'] = "User berhasil ditambahkan.";
            header("Location: manage_users.php");
            exit;
        } else {
            $error = "Gagal menambahkan user.";
        }
    }
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="container mt-4">
    <h4>Tambah User Baru</h4>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>OPD</label>
            <input type="text" name="opd" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Nama Pejabat PPID Pembantu</label>
            <input type="text" name="nama_pejabat" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>NIP</label>
            <input type="text" name="nip" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-select" required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Tambah User</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
