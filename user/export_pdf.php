<?php
require_once '../config/config.php';
require_once '../config/auth.php';
require_once '../includes/functions.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

require_login();
if (!is_admin()) {
    header("Location: ../public/login.php");
    exit;
}

// Ambil parameter filter
$opd = $_GET['opd'] ?? '';
$jenis = $_GET['jenis'] ?? '';
$tahun = $_GET['tahun'] ?? '';
$semester = $_GET['semester'] ?? '';


// Query data
$query = "SELECT i.*, u.opd FROM informasi i JOIN users u ON i.user_id = u.id WHERE 1=1";
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

$query .= " ORDER BY i.uploaded_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$data = $stmt->fetchAll();

// Encode logo ke base64
$logo_path = '../assets/images/kopsurat.jpg';
$logo_base64 = '';
if (file_exists($logo_path)) {
    $logo_data = file_get_contents($logo_path);
    $logo_base64 = base64_encode($logo_data);
}

// HTML template
$html = '
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        h3 { text-align: center; margin: 0; }
    </style>
</head>
<body>
    <img src="data:image/jpeg;base64,' . $logo_base64 . '" style="height: 100px;">
    <h3>DAFTAR INFORMASI PUBLIK<br>
        TAHUN ' . htmlspecialchars($tahun) . ' SEMESTER ' . htmlspecialchars($semester) . '<br>' . strtoupper(htmlspecialchars($opd)) . '</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
            <th>Jenis</th>
            <th>Ringkasan Isi Informasi</th>
            <th>Pejabat/Unit/Satker Yang Menguasai Informasi</th>
            <th>Penanggung Jawab Pembuatan atau Penerbitan Informasi</th>
            <th>Tempat dan Waktu Pembuatan</th>
            <th>Bentuk</th>
            <th>Jangka Waktu Penyimpanan atau Retensi Arsip</th>
            </tr>
        </thead>
        <tbody>';

$no = 1;
foreach ($data as $row) {
    $html .= '<tr>
        <td>' . $no++ . '</td>
            <td>' . htmlspecialchars($row['jenis_informasi']) . '</td>
            <td>' . htmlspecialchars($row['ringkasan']) . '</td>
            <td>' . htmlspecialchars($row['pejabat_pengelola']) . '</td>
            <td>' . htmlspecialchars($row['penanggung_jawab']) . '</td>
            <td>' . htmlspecialchars($row['tempat_waktu']) . '</td>
            <td>' . htmlspecialchars($row['bentuk_informasi']) . '</td>
            <td>' . htmlspecialchars($row['retensi_arsip']) . '</td>
    </tr>';
}

$html .= '</tbody></table></body></html>';

// render PDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->setPaper('A4', 'landscape');
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream("Informasi_Publik_User.pdf", ["Attachment" => false]);
exit;
?>