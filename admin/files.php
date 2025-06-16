<?php
// ===========================
// admin/files.php
// ===========================
session_start();
require_once '../config/config.php';
require_once '../config/auth.php';
require_once '../includes/functions.php';
require_login();

if (!is_admin()) {
    header("Location: ../public/login.php");
    exit();
}

// Filter
$opd = $_GET['opd'] ?? '';
$jenis = $_GET['jenis'] ?? '';
$tahun = $_GET['tahun'] ?? '';
$semester = $_GET['semester'] ?? '';

$perPage = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

$query = "FROM informasi i JOIN users u ON i.user_id = u.id WHERE 1=1";
$params = [];

if ($opd) {
    $query .= " AND u.opd LIKE ?";
    $params[] = "%$opd%";
}
if ($jenis) {
    $query .= " AND i.jenis_informasi = ?";
    $params[] = $jenis;
}
if ($tahun) {
    $query .= " AND i.tahun = ?";
    $params[] = $tahun;
}
if ($semester) {
    $query .= " AND i.semester = ?";
    $params[] = $semester;
}

// Total count
$countQuery = "SELECT COUNT(*) $query";
$stmt = $pdo->prepare($countQuery);
$stmt->execute($params);
$totalRows = $stmt->fetchColumn();
$totalPages = ceil($totalRows / $perPage);

// Fetch data
$dataQuery = "SELECT i.*, u.opd, u.username $query ORDER BY i.uploaded_at DESC LIMIT $perPage OFFSET $offset";
$stmt = $pdo->prepare($dataQuery);
$stmt->execute($params);
$informasi = $stmt->fetchAll();

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<main class="container mt-4">
    <h4 class="mb-4">Daftar Informasi Publik</h4>

    <form method="GET" class="row g-3 mb-3">
        <div class="col-md-3">
            <input type="text" name="opd" class="form-control" placeholder="Cari OPD" value="<?= htmlspecialchars($opd) ?>">
        </div>
        <div class="col-md-2">
            <select name="jenis" class="form-select">
                <option value="">Jenis Informasi</option>
                <option value="Informasi Berkala" <?= $jenis == 'Informasi Berkala' ? 'selected' : '' ?>>Informasi Berkala</option>
                <option value="Informasi Setiap Saat" <?= $jenis == 'Informasi Setiap Saat' ? 'selected' : '' ?>>Informasi Setiap Saat</option>
                <option value="Informasi Serta Merta" <?= $jenis == 'Informasi Serta Merta' ? 'selected' : '' ?>>Informasi Serta Merta</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="text" name="tahun" class="form-control" placeholder="Tahun" value="<?= htmlspecialchars($tahun) ?>">
        </div>
        <div class="col-md-2">
            <select name="semester" class="form-select">
                <option value="">Semester</option>
                <option value="1" <?= $semester == '1' ? 'selected' : '' ?>>1</option>
                <option value="2" <?= $semester == '2' ? 'selected' : '' ?>>2</option>
            </select>
        </div>
        <div class="col-md-3 text-end">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="export_pdf.php?opd=<?= urlencode($opd) ?>&jenis=<?= urlencode($jenis) ?>&tahun=<?= urlencode($tahun) ?>&semester=<?= urlencode($semester) ?>" class="btn btn-danger">Export PDF</a>
            <a href="export_excel.php?opd=<?= urlencode($opd) ?>&jenis=<?= urlencode($jenis) ?>&tahun=<?= urlencode($tahun) ?>&semester=<?= urlencode($semester) ?>" class="btn btn-success">Export Excel</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Jenis Informasi</th>
                    <th>Ringkasan Isi Informasi</th>
                    <th>Pejabat / Unit / Satker Yang Menguasai Informasi</th>
                    <th>Penanggung Jawab Pembuatan atau Penerbitan Informasi</th>
                    <th>Tempat dan Waktu Pembuatan</th>
                    <th>Tahun</th>
                    <th>Semester</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($informasi) > 0): ?>
                    <?php $no = $offset + 1; ?>
                    <?php foreach ($informasi as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['jenis_informasi']) ?></td>
                            <td><?= htmlspecialchars($row['ringkasan']) ?></td>
                            <td><?= htmlspecialchars($row['pejabat_pengelola']) ?></td>
                            <td><?= htmlspecialchars($row['penanggung_jawab']) ?></td>
                            <td><?= htmlspecialchars($row['tempat_waktu']) ?></td>
                            <td><?= htmlspecialchars($row['tahun']) ?></td>
                            <td><?= htmlspecialchars($row['semester']) ?></td>
                            <td>
                                <?php if (!empty($row['file_path'])): ?>
                                    <a href="../uploads/<?= $row['file_path'] ?>" target="_blank"><?= basename($row['file_path']) ?></a>
                                <?php else: ?>
                                    <em>Tidak ada file</em>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">Data tidak ditemukan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <nav>
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?opd=<?= urlencode($opd) ?>&jenis=<?= urlencode($jenis) ?>&tahun=<?= urlencode($tahun) ?>&semester=<?= urlencode($semester) ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>
