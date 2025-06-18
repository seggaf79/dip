<?php
require_once '../config/config.php';
require_once '../config/auth.php';
require_once '../includes/functions.php';
require_login();

if (!is_user()) {
    header('Location: ../admin/dashboard.php');
    exit;
}

include '../includes/header.php';
include '../includes/sidebar_user.php';

// Ambil filter
$jenis_filter = $_GET['jenis'] ?? '';
$tahun_filter = $_GET['tahun'] ?? '';
$semester_filter = $_GET['semester'] ?? '';

$username = $_SESSION['username'];

$sql = "SELECT * FROM informasi WHERE user_id = :user_id";
$params = ['user_id' => $_SESSION['user_id']];

if ($jenis_filter !== '') {
    $sql .= " AND jenis_informasi = :jenis";
    $params['jenis'] = $jenis_filter;
}
if ($tahun_filter !== '') {
    $sql .= " AND tahun = :tahun";
    $params['tahun'] = $tahun_filter;
}
if ($semester_filter !== '') {
    $sql .= " AND semester = :semester";
    $params['semester'] = $semester_filter;
}

$sql .= " ORDER BY tahun DESC, semester DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h4>Daftar File Saya</h4>

    <form method="get" class="row g-3 mb-3">
        <div class="col-md-3">
            <select name="jenis" class="form-select">
                <option value="">-- Semua Jenis Informasi --</option>
                <option value="Informasi Berkala" <?= $jenis_filter === 'Informasi Berkala' ? 'selected' : '' ?>>Informasi Berkala</option>
                <option value="Informasi Setiap Saat" <?= $jenis_filter === 'Informasi Setiap Saat' ? 'selected' : '' ?>>Informasi Setiap Saat</option>
                <option value="Informasi Serta Merta" <?= $jenis_filter === 'Informasi Serta Merta' ? 'selected' : '' ?>>Informasi Serta Merta</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="tahun" class="form-select">
                <option value="">-- Semua Tahun --</option>
                <?php for ($i = date('Y'); $i >= 2020; $i--): ?>
                    <option value="<?= $i ?>" <?= $tahun_filter == $i ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="semester" class="form-select">
                <option value="">-- Semua Semester --</option>
                <option value="1" <?= $semester_filter == '1' ? 'selected' : '' ?>>Semester 1</option>
                <option value="2" <?= $semester_filter == '2' ? 'selected' : '' ?>>Semester 2</option>
            </select>
        </div>
        <div class="col-md-5 text-end">
            <button class="btn btn-primary" type="submit">Cari</button>
            <a href="export_pdf.php?jenis=<?= $jenis_filter ?>&tahun=<?= $tahun_filter ?>&semester=<?= $semester_filter ?>" class="btn btn-danger">Export PDF</a>
            <a href="export_excel.php?jenis=<?= $jenis_filter ?>&tahun=<?= $tahun_filter ?>&semester=<?= $semester_filter ?>" class="btn btn-success">Export Excel</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Jenis Informasi</th>
                    <th>Ringkasan</th>
                    <th>Tahun</th>
                    <th>Semester</th>
                    <th>File</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($data) > 0): $no = 1; ?>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['jenis_informasi']) ?></td>
                            <td><?= htmlspecialchars($row['ringkasan']) ?></td>
                            <td><?= $row['tahun'] ?></td>
                            <td><?= $row['semester'] ?></td>
                            <td>
  <?php if (!empty($row['file_path'])): ?>
    <a href="../uploads/<?= htmlspecialchars($row['file_path']) ?>" target="_blank">
      <?= htmlspecialchars(basename($row['file_path'])) ?>
    </a>
  <?php else: ?>
    <span class="text-muted">Tidak ada file</span>
  <?php endif; ?>
</td>
                            <td>
                                <a href="detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">Detail</a>
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus file ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">Data tidak ditemukan</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; 
?>