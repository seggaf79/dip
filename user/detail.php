<?php
// ===========================
// user/detail.php
// ===========================
session_start();
require_once '../config/config.php';
require_once '../config/auth.php';
require_once '../includes/functions.php';
require_login();

if ($_SESSION['role'] !== 'user' || !isset($_GET['id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM informasi WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);
$data = $stmt->fetch();

if (!$data) {
    echo "Data tidak ditemukan.";
    exit();
}

include '../includes/header.php';
include '../includes/sidebar_user.php';
?>

<h4>Detail Informasi</h4>
<table class="table table-bordered">
    <tr><th>Judul</th><td><?= htmlspecialchars($data['judul_informasi']) ?></td></tr>
    <tr><th>Jenis Informasi</th><td><?= $data['jenis_informasi'] ?></td></tr>
    <tr><th>Tahun</th><td><?= $data['tahun'] ?></td></tr>
    <tr><th>Semester</th><td><?= $data['semester'] ?></td></tr>
    <tr><th>Ringkasan</th><td><?= nl2br(htmlspecialchars($data['ringkasan'])) ?></td></tr>
    <tr><th>Pejabat / Satker</th><td><?= $data['pejabat_pengelola'] ?></td></tr>
    <tr><th>Penanggung Jawab</th><td><?= $data['penanggung_jawab'] ?></td></tr>
    <tr><th>Tempat & Waktu</th><td><?= $data['tempat_waktu'] ?></td></tr>
    <tr><th>Bentuk Informasi</th><td><?= $data['bentuk_informasi'] ?></td></tr>
    <tr><th>Retensi Arsip</th><td><?= $data['retensi_arsip'] ?></td></tr>
    <tr>
        <th>File</th>
        <td>
            <?php if ($data['file_path']): ?>
                <a href="../uploads/<?= $data['file_path'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">Preview File</a>
            <?php else: ?>
                <span class="text-muted">Tidak ada file</span>
            <?php endif; ?>
        </td>
    </tr>
</table>

<a href="dashboard.php" class="btn btn-secondary">← Kembali</a>

<?php include '../includes/footer.php'; ?>
