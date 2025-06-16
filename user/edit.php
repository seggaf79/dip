<?php
require_once '../config/config.php';
require_once '../config/auth.php';
require_once '../includes/functions.php';

require_login();

if (!is_user()) {
    header("Location: ../admin/dashboard.php");
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: files.php");
    exit;
}

// Ambil data lama
$stmt = $pdo->prepare("SELECT * FROM informasi WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    $_SESSION['error'] = "Data tidak ditemukan.";
    header("Location: files.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $semester = $_POST['semester'];
    $tahun = $_POST['tahun'];
    $jenis_informasi = $_POST['jenis_informasi'];
    $judul_informasi = $_POST['judul_informasi'];
    $ringkasan = $_POST['ringkasan'];
    $pejabat_pengelola = $_POST['pejabat_pengelola'];
    $penanggung_jawab = $_POST['penanggung_jawab'];
    $tempat_waktu = $_POST['tempat_waktu'];
    $bentuk = isset($_POST['bentuk']) ? implode(',', $_POST['bentuk']) : '';
    $retensi_arsip = $_POST['retensi_arsip'];
    $file_path = $data['file_path']; // default

    if (!empty($_FILES['file']['name'])) {
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $allowed = ['pdf', 'docx', 'xlsx'];

        if (in_array(strtolower($ext), $allowed)) {
            $username = $_SESSION['username'];
            $targetDir = "../uploads/$username/$tahun/$semester";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            if (file_exists("../uploads/" . $data['file_path'])) {
                unlink("../uploads/" . $data['file_path']);
            }

            $newFileName = basename($_FILES['file']['name']);
            $targetFile = "$targetDir/$newFileName";
            move_uploaded_file($_FILES['file']['tmp_name'], $targetFile);
            $file_path = "$username/$tahun/$semester/$newFileName";
        }
    }

    $stmt = $pdo->prepare("UPDATE informasi SET 
        semester=?, tahun=?, jenis_informasi=?, judul_informasi=?, ringkasan=?, 
        pejabat_pengelola=?, penanggung_jawab=?, tempat_waktu=?, 
        bentuk_informasi=?, retensi_arsip=?, file_path=? WHERE id=?");

    $stmt->execute([
        $semester, $tahun, $jenis_informasi, $judul_informasi, $ringkasan,
        $pejabat_pengelola, $penanggung_jawab, $tempat_waktu,
        $bentuk, $retensi_arsip, $file_path, $id
    ]);

    $_SESSION['success'] = "Data berhasil diperbarui.";
    header("Location: files.php");
    exit;
}

include '../includes/header.php';
include '../includes/sidebar_user.php';
?>

<h4>Edit Informasi</h4>
<form method="POST">
    <div class="row">
        <div class="col-md-6">
            <label>Semester</label>
            <select name="semester" class="form-select" required>
                <option value="1" <?= $data['semester'] == '1' ? 'selected' : '' ?>>1</option>
                <option value="2" <?= $data['semester'] == '2' ? 'selected' : '' ?>>2</option>
            </select>
        </div>
        <div class="col-md-6">
            <label>Tahun</label>
            <input type="number" name="tahun" class="form-control" value="<?= $data['tahun'] ?>" required>
        </div>
    </div>

    <label class="mt-2">Jenis Informasi</label>
    <select name="jenis_informasi" class="form-select" required>
        <option value="Informasi Berkala" <?= $data['jenis_informasi'] == 'Informasi Berkala' ? 'selected' : '' ?>>Informasi Berkala</option>
        <option value="Informasi Setiap Saat" <?= $data['jenis_informasi'] == 'Informasi Setiap Saat' ? 'selected' : '' ?>>Informasi Setiap Saat</option>
        <option value="Informasi Serta Merta" <?= $data['jenis_informasi'] == 'Informasi Serta Merta' ? 'selected' : '' ?>>Informasi Serta Merta</option>
    </select>

    <label class="mt-2">Judul Informasi</label>
    <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($data['judul_informasi']) ?>" required>

    <label class="mt-2">Ringkasan Isi Informasi</label>
    <textarea name="ringkasan" class="form-control" required><?= htmlspecialchars($data['ringkasan']) ?></textarea>

    <label class="mt-2">Pejabat / Unit / Satker</label>
    <input type="text" name="pengelola" class="form-control" value="<?= $data['pejabat_pengelola'] ?>" required>

    <label class="mt-2">Penanggung Jawab Penerbit</label>
    <input type="text" name="penanggung" class="form-control" value="<?= $data['penanggung_jawab'] ?>" required>

    <label class="mt-2">Tempat dan Waktu Pembuatan</label>
    <input type="text" name="tempat_waktu" class="form-control" value="<?= $data['tempat_waktu'] ?>" required>

    <label class="mt-2">Bentuk Informasi Tersedia</label><br>
    <?php $bentuk_array = explode(", ", $data['bentuk_informasi']); ?>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="bentuk[]" value="Cetak" <?= in_array('Cetak', $bentuk_array) ? 'checked' : '' ?>>
        <label class="form-check-label">Cetak</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="bentuk[]" value="Rekam" <?= in_array('Rekam', $bentuk_array) ? 'checked' : '' ?>>
        <label class="form-check-label">Rekam</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="bentuk[]" value="Online" <?= in_array('Online', $bentuk_array) ? 'checked' : '' ?>>
        <label class="form-check-label">Online</label>
    </div>

    <label class="mt-2">Jangka Waktu Penyimpanan / Retensi Arsip</label>
    <input type="text" name="retensi" class="form-control" value="<?= $data['retensi_arsip'] ?>" required>
    <div class="mb-3">
  <label for="file" class="form-label">Ganti File (PDF, DOCX, XLSX)</label>
  <input type="file" name="file" class="form-control">
  <div class="form-text">Kosongkan jika tidak ingin mengganti file.</div>
</div>


    <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
</form>

<?php include '../includes/footer.php'; ?>
