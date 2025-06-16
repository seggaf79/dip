<?php
// ===========================
// user/dashboard.php
// ===========================
session_start();
require_once '../config/config.php';
require_once '../config/auth.php';
require_once '../includes/functions.php';
require_login();

if ($_SESSION['role'] !== 'user') {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Filter (jika ada)
$jenis = $_GET['jenis'] ?? '';
$tahun = $_GET['tahun'] ?? '';
$semester = $_GET['semester'] ?? '';

$query = "SELECT * FROM informasi WHERE user_id = ?";
$params = [$user_id];

if ($jenis) {
    $query .= " AND jenis_informasi = ?";
    $params[] = $jenis;
}
if ($tahun) {
    $query .= " AND tahun = ?";
    $params[] = $tahun;
}
if ($semester) {
    $query .= " AND semester = ?";
    $params[] = $semester;
}

$query .= " ORDER BY uploaded_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$files = $stmt->fetchAll();

// Statistik
$total_files = count($files);

include '../includes/header.php';
include '../includes/sidebar_user.php';
?>

<h3>Dashboard Informasi Saya</h3>

<!-- Statistik Ringkas -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5>Total File Diupload</h5>
                <p class="fs-3"><?= $total_files ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Form Filter -->
<form method="GET" class="row g-2 align-items-end mb-4">
    <div class="col-md-3">
        <label>Jenis Informasi</label>
        <select name="jenis" class="form-select">
            <option value="">Semua</option>
            <option value="Informasi Berkala" <?= $jenis === 'Informasi Berkala' ? 'selected' : '' ?>>Informasi Berkala</option>
            <option value="Informasi Setiap Saat" <?= $jenis === 'Informasi Setiap Saat' ? 'selected' : '' ?>>Informasi Setiap Saat</option>
            <option value="Informasi Serta Merta" <?= $jenis === 'Informasi Serta Merta' ? 'selected' : '' ?>>Informasi Serta Merta</option>
        </select>
    </div>
    <div class="col-md-2">
        <label>Tahun</label>
        <input type="number" name="tahun" class="form-control" value="<?= htmlspecialchars($tahun) ?>">
    </div>
    <div class="col-md-2">
        <label>Semester</label>
        <select name="semester" class="form-select">
            <option value="">Semua</option>
            <option value="1" <?= $semester === '1' ? 'selected' : '' ?>>1</option>
            <option value="2" <?= $semester === '2' ? 'selected' : '' ?>>2</option>
        </select>
    </div>
    <div class="col-md-5 d-flex gap-2">
        <button type="submit" class="btn btn-primary">🔍 Cari</button>
        <a href="export_pdf.php?jenis=<?= $jenis ?>&tahun=<?= $tahun ?>&semester=<?= $semester ?>" class="btn btn-danger">Export PDF</a>
        <a href="export_excel.php?jenis=<?= $jenis ?>&tahun=<?= $tahun ?>&semester=<?= $semester ?>" class="btn btn-success">Export Excel</a>
    </div>
</form>

<!-- Tabel Data -->
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Jenis</th>
                <th>Tahun</th>
                <th>Semester</th>
                <th>Diunggah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($files as $i => $row): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($row['judul_informasi']) ?></td>
                <td><?= $row['jenis_informasi'] ?></td>
                <td><?= $row['tahun'] ?></td>
                <td><?= $row['semester'] ?></td>
                <td><?= date('d/m/Y', strtotime($row['uploaded_at'])) ?></td>
                <td>
                    <a href="detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">Detail</a>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
