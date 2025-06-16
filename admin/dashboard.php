<?php
session_start();
require_once '../config/config.php';
require_once '../config/auth.php';
require_once '../includes/functions.php';
require_login();

if (!is_admin()) {
    header("Location: ../public/login.php");
    exit();


}


$stmt = $pdo->query("SELECT COUNT(*) FROM informasi");
$totalFiles = $stmt->fetchColumn();

// Hitung total user
$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'");
$totalUsers = $stmt->fetchColumn();

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - MIP Bulungan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="container-fluid mt-4">
    <h3 class="mb-4">Dashboard Admin</h3>

    <!-- Statistik Ringkas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total File</h5>
                    <p class="fs-3"><?= $totalFiles ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>User Aktif</h5>
                    <p class="fs-3"><?= $totalUsers ?></p>
                </div>
            </div>
        </div>
    </div>

 <div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Grafik Tren Upload File Per OPD</h5>
    </div>
    <div class="card-body">
        <canvas id="uploadChart" height="100"></canvas>
    </div>
</div>
</main>

<?php
// Ambil data tren upload per OPD dari database
$stmt = $pdo->query("
    SELECT u.opd, i.semester, COUNT(*) as jumlah
    FROM informasi i
    JOIN users u ON i.user_id = u.id
    GROUP BY u.opd, i.semester
");


$opdData = [];
foreach ($stmt as $row) {
    $opd = $row['opd'];
    $semester = 'semester_' . $row['semester'];
    $opdData[$opd][$semester] = (int)$row['jumlah'];
}

// Siapkan data untuk Chart.js
$labels = json_encode(array_keys($opdData));
$semester1 = json_encode(array_map(function($v) {
    return $v['semester_1'] ?? 0;
}, $opdData));
$semester2 = json_encode(array_map(function($v) {
    return $v['semester_2'] ?? 0;
}, $opdData));
?>

<?php include '../includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('uploadChart').getContext('2d');
    const uploadChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= $labels ?>,
            datasets: [
                {
                    label: 'Semester 1',
                    data: <?= $semester1 ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)'
                },
                {
                    label: 'Semester 2',
                    data: <?= $semester2 ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.7)'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>


</body>
</html>
