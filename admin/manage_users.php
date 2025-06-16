<?php
require_once '../config/config.php';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

// Ambil data user beserta jumlah file yang diupload
$query = $pdo->query("
    SELECT users.*, 
        (SELECT COUNT(*) FROM informasi WHERE informasi.user_id = users.id) AS jumlah_file 
    FROM users
");

$users = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h4>Manajemen Pengguna</h4>
    <a href="tambah_user.php" class="btn btn-primary mb-3">Tambah Pengguna</a>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>OPD</th>
                    <th>Nama Pejabat</th>
                    <th>NIP</th>
                    <th>Jumlah DIP</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $i => $user): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['opd']) ?></td>
                        <td><?= htmlspecialchars($user['nama_pejabat']) ?></td>
                        <td><?= htmlspecialchars($user['nip']) ?></td>
                        <td><?= $user['jumlah_file'] ?></td>
                        <td><?= ucfirst($user['role']) ?></td>
                        <td>
                            <a href="detail_user.php?id=<?= $user['id'] ?>" class="btn btn-info btn-sm">Detail</a>
                            <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="hapus_user.php?id=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
