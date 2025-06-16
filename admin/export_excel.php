<?php
// ===========================
// admin/export_excel.php
// ===========================
session_start();
require_once '../config/config.php';
require_once '../config/auth.php';
require_once '../includes/functions.php';
require_once '../vendor/autoload.php';
require_login();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

if (!is_admin()) {
    header("Location: ../public/login.php");
    exit();
}

// Ambil filter dari URL
$opd = $_GET['opd'] ?? '';
$jenis = $_GET['jenis'] ?? '';
$tahun = $_GET['tahun'] ?? '';
$semester = $_GET['semester'] ?? '';

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

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Informasi Publik');

// Judul
$judul = "DAFTAR INFORMASI PUBLIK\nTAHUN " . ($tahun ?: '-') . " SEMESTER " . ($semester ?: '-') . "\n" . strtoupper($opd ?: 'SEMUA OPD');
$sheet->mergeCells('A1:H1');
$sheet->setCellValue('A1', $judul);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1')->getAlignment()->setWrapText(true);
$sheet->getRowDimension(1)->setRowHeight(50);

// Header
$headers = ['No', 'Jenis Informasi', 'Ringkasan Isi Informasi', 'Pejabat/Unit/Satker Yang Menguasai Informasi', 'Penanggung Jawab Pembuatan atau Penerbitan Informasi', 'Tempat dan Waktu Pembuatan', 'Bentuk Informasi', 'Jangka Waktu Penyimpanan atau Retensi Arsip'];
$sheet->fromArray($headers, NULL, 'A3');

// Border header
$sheet->getStyle('A3:H3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
$sheet->getStyle('A3:H3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A3:H3')->getFont()->setBold(true);

// Data
$rowIndex = 4;
foreach ($data as $i => $row) {
    $sheet->setCellValue("A$rowIndex", $i + 1);
    $sheet->setCellValue("B$rowIndex", $row['jenis_informasi']);
    $sheet->setCellValue("C$rowIndex", $row['ringkasan']);
    $sheet->setCellValue("D$rowIndex", $row['pejabat_pengelola']);
    $sheet->setCellValue("E$rowIndex", $row['penanggung_jawab']);
    $sheet->setCellValue("F$rowIndex", $row['tempat_waktu']);
    $sheet->setCellValue("G$rowIndex", $row['bentuk_informasi']);
    $sheet->setCellValue("H$rowIndex", $row['retensi_arsip']);
    $rowIndex++;
}

// Border all
$sheet->getStyle("A4:H" . ($rowIndex - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
$sheet->getStyle("A4:H" . ($rowIndex - 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

// Autosize
foreach (range('A', 'H') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Output Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="informasi_publik.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
