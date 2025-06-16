<!-- includes/sidebar.php -->
<aside class="bg-light border-end" style="width: 250px; height: 100vh; position: fixed; top: 70px; left: 0;">
    <div class="list-group list-group-flush">
        <a href="<?= BASE_URL ?>admin/dashboard.php" class="list-group-item list-group-item-action">🏠 Dashboard</a>
        <a href="<?= BASE_URL ?>admin/manage_users.php" class="list-group-item list-group-item-action">👥 Manajemen User</a>
        <a href="<?= BASE_URL ?>admin/files.php" class="list-group-item list-group-item-action">📁 Daftar Informasi</a>
        <a href="<?= BASE_URL ?>public/logout.php" class="list-group-item list-group-item-action text-danger">🚪 Logout</a>
    </div>
</aside>

<!-- Offset main content (untuk dipakai di file utama) -->
<div style="margin-left: 250px; padding: 20px;">
