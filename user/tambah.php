<?php
// ===========================
// user/tambah.php
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    $semester = $_POST['semester'];
    $tahun = $_POST['tahun'];
    $jenis = $_POST['jenis_informasi'];
    $judul = clean_input($_POST['judul']);
    $ringkasan = clean_input($_POST['ringkasan']);
    $pengelola = clean_input($_POST['pengelola']);
    $penanggung = clean_input($_POST['penanggung']);
    $tempat_waktu = clean_input($_POST['tempat_waktu']);
    $bentuk = implode(", ", $_POST['bentuk']);
    $retensi = clean_input($_POST['retensi']);

    $upload_dir = create_upload_path($username, $tahun, $semester);
    $file_name = basename($_FILES['file_upload']['name']);
    $target_path = $upload_dir . "/" . $file_name;
    move_uploaded_file($_FILES['file_upload']['tmp_name'], $target_path);

    $rel_path = strtolower(trim(preg_replace('/\s+/', '', $username))) . "/$tahun/$semester/$file_name";

    $stmt = $pdo->prepare("INSERT INTO informasi (user_id, semester, tahun, jenis_informasi, judul_informasi, ringkasan, pejabat_pengelola, penanggung_jawab, tempat_waktu, bentuk_informasi, retensi_arsip, file_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $semester, $tahun, $jenis, $judul, $ringkasan, $pengelola, $penanggung, $tempat_waktu, $bentuk, $retensi, $rel_path]);

    header("Location: dashboard.php");
    exit();
}

include '../includes/header.php';
include '../includes/sidebar_user.php';
?>

<h4>Tambah Informasi Baru</h4>
<form method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">
            <label>Semester</label>
            <select name="semester" class="form-select" required>
                <option value="1">1</option>
                <option value="2">2</option>
            </select>
        </div>
        <div class="col-md-6">
            <label>Tahun</label>
            <input type="number" name="tahun" class="form-control" value="<?= date('Y') ?>" required>
        </div>
    </div>

    <label class="mt-2">Jenis Informasi</label>
    <select name="jenis_informasi" class="form-select" required>
        <option value="Informasi Berkala">Informasi Berkala</option>
        <option value="Informasi Setiap Saat">Informasi Setiap Saat</option>
        <option value="Informasi Serta Merta">Informasi Serta Merta</option>
    </select>

    <label class="mt-2">Judul Informasi</label>
    <input type="text" name="judul" class="form-control" required>

    <label class="mt-2">Ringkasan Isi Informasi</label>
    <textarea name="ringkasan" class="form-control" required></textarea>

    <label class="mt-2">Pejabat / Unit / Satker</label>
    <input type="text" name="pengelola" class="form-control" required>

    <label class="mt-2">Penanggung Jawab Penerbit</label>
    <input type="text" name="penanggung" class="form-control" required>

    <label class="mt-2">Tempat dan Waktu Pembuatan</label>
    <input type="text" name="tempat_waktu" class="form-control" required>

    <label class="mt-2">Bentuk Informasi Tersedia</label><br>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="bentuk[]" value="Cetak">
        <label class="form-check-label">Cetak</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="bentuk[]" value="Rekam">
        <label class="form-check-label">Rekam</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="bentuk[]" value="Online">
        <label class="form-check-label">Online</label>
    </div>
<br>
    <label class="mt-2">Jangka Waktu Penyimpanan / Retensi Arsip</label>
    <input type="text" name="retensi" class="form-control" required>

    <label class="mt-2">Upload File (PDF, DOCX, XLSX)</label>
    <input type="file" name="file_upload" accept=".pdf,.docx,.xlsx" class="form-control" required>

    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
</form>

<?php include '../includes/footer.php'; ?>
