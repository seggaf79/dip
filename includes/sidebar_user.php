<!-- includes/sidebar_user.php -->
<aside class="bg-light border-end" style="width: 250px; height: 100vh; position: fixed; top: 70px; left: 0;">
    <div class="list-group list-group-flush">
        <a href="<?= BASE_URL ?>user/dashboard.php" class="list-group-item list-group-item-action">🏠 Dashboard</a>
        <a href="<?= BASE_URL ?>user/files.php" class="list-group-item list-group-item-action">📁 Informasi Saya</a>
        <a href="<?= BASE_URL ?>user/tambah.php" class="list-group-item list-group-item-action">➕ Tambah Informasi</a>
        <a href="<?= BASE_URL ?>public/logout.php" class="list-group-item list-group-item-action text-danger">🚪 Logout</a>
    </div>
</aside>

<!-- Offset main content -->
<div style="margin-left: 250px; padding: 20px;">
