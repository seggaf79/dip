<?php
require_once '../config/config.php';
require_once '../config/auth.php';
require_once '../includes/functions.php';
require_once '../vendor/autoload.php';

require_login();
is_admin();

use Dompdf\Dompdf;
use Dompdf\Options;

$opd = $_SESSION['opd'] ?? 'Tidak diketahui';
$tahun = $_GET['tahun'] ?? '';
$semester = $_GET['semester'] ?? '';
$jenis = $_GET['jenis'] ?? '';

$query = "SELECT i.*, u.opd FROM informasi i JOIN users u ON i.user_id = u.id WHERE 1=1";
$params = [];

if (!empty($tahun)) {
    $query .= " AND tahun = :tahun";
    $params['tahun'] = $tahun;
}
if (!empty($semester)) {
    $query .= " AND semester = :semester";
    $params['semester'] = $semester;
}
if (!empty($jenis)) {
    $query .= " AND jenis_informasi = :jenis";
    $params['jenis'] = $jenis;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$data = $stmt->fetchAll();

// logo base64
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
                <th>Jenis Informasi</th>
                <th>Judul Informasi</th>
                <th>Ringkasan Informasi</th>
                <th>Pejabat / Unit / Satker Yang Menguasai Informasi</th>
                <th>Penanggung Jawab Pembuatan atau Penerbitan Informasi</th>
                <th>Tempat/Waktu Pembuatan</th>
                <th>Bentuk</th>
                <th>Retensi Arsip</th>
            </tr>
        </thead>
        <tbody>';

$no = 1;
foreach ($data as $row) {
    $html .= '<tr>
        <td>' . $no++ . '</td>
        <td>' . htmlspecialchars($row['jenis_informasi']) . '</td>
        <td>' . htmlspecialchars($row['judul_informasi']) . '</td>
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