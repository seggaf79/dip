-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2025 at 03:39 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mip_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `informasi`
--

CREATE TABLE `informasi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `semester` enum('1','2') NOT NULL,
  `tahun` year(4) NOT NULL,
  `jenis_informasi` enum('Informasi Berkala','Informasi Setiap Saat','Informasi Serta Merta') NOT NULL,
  `judul_informasi` varchar(255) NOT NULL,
  `ringkasan` text NOT NULL,
  `pejabat_pengelola` varchar(100) NOT NULL,
  `penanggung_jawab` varchar(100) NOT NULL,
  `tempat_waktu` varchar(100) NOT NULL,
  `bentuk_informasi` set('Cetak','Rekam','Online') NOT NULL,
  `retensi_arsip` varchar(100) DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `opd` varchar(100) NOT NULL,
  `nama_pejabat` varchar(100) NOT NULL,
  `nip` varchar(30) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `opd`, `nama_pejabat`, `nip`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$rE3nmBKU3NckG7FVxKc/Iem1T/boQ9Ysx/nE7qMwnDdXHdSG7Xheq', 'Diskominfo', 'Administrator', '1234567890', 'admin', '2025-06-14 10:50:29'),
(2, 'ppidutama', '$2y$10$5ME7l94mDgchTln7yXk8I.pVPAOtsjb6LCNWLv7TwDcNs7Fp2pvw6', 'PPID Bulungan Utama', 'Pejabat Utama PPID', '000000000000000000', 'admin', '2025-06-14 12:39:23'),
(3, 'dkipbul', '$2y$10$kiaXmay9hDcLvPngH7OMwO5zu/wweBg5kXDXqDegAxjAQcO27.3oC', 'DKIP Bulungan', 'Pejabat DKIP', '111111111111111111', 'user', '2025-06-14 12:39:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `informasi`
--
ALTER TABLE `informasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `informasi`
--
ALTER TABLE `informasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `informasi`
--
ALTER TABLE `informasi`
  ADD CONSTRAINT `informasi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
