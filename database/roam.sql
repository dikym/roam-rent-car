-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql204.epizy.com
-- Generation Time: May 17, 2023 at 08:59 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `epiz_33712473_roam`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL,
  `level` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `name`, `username`, `password`, `created_date`, `level`) VALUES
(1, 'Diky', 'maul', 'que', '2023-02-10 19:23:27', 'admin'),
(2, 'Maul', 'test', 'admin', '2023-02-10 19:23:27', 'admin'),
(37, 'Nia', 'nia', 'user', '2023-02-10 19:23:27', 'user'),
(39, 'Rummy', 'rum', 'user', '2023-02-10 19:23:27', 'user'),
(40, 'Corleone', 'Leo', 'user', '2023-02-10 19:23:27', 'user'),
(49, 'hah', 'hah', 'hah', '2023-02-21 21:57:13', 'admin'),
(51, 'diky', 'diky', 'maul', '2023-02-22 08:24:57', 'admin'),
(55, 'hehe', 'hehe', '1', '2023-03-01 08:53:14', 'user'),
(56, 'Ulul Arham', 'ulul', '123', '2023-03-03 07:48:08', 'user'),
(57, 'Bahrul', 'bahrul', 'BU', '2023-03-05 15:39:20', 'user'),
(59, 'Tvan Galang', 'galang', '26', '2023-03-06 07:43:45', 'user'),
(60, 'Ferdy', 'Ferdy', 'eka123', '2023-03-06 07:59:06', 'user'),
(61, 'adam', 'Adam', '2', '2023-03-06 08:02:43', 'user'),
(62, 'azmi', 'arab', 'arabganteng', '2023-03-06 12:50:05', 'user'),
(64, 'Ouda', 'ouda', 'bro', '2023-03-07 16:50:38', 'user'),
(65, 'Diky', 'diky', '17', '2023-03-08 09:26:21', 'user'),
(66, 'Ahmad', 'ahmad', '100', '2023-03-08 09:28:06', 'user'),
(67, 'Nur Alfianti', 'alfi', '16', '2023-03-08 16:45:49', 'user'),
(73, 'rudy', 'alip', '1', '2023-03-13 14:33:24', 'user'),
(74, 'Jo', 'Jo', '32', '2023-03-14 12:25:54', 'user'),
(76, 'joo', 'joko', '1', '2023-03-15 20:23:54', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `diskon`
--

CREATE TABLE `diskon` (
  `id_diskon` int(255) NOT NULL,
  `nama_diskon` varchar(255) NOT NULL,
  `total_diskon` int(2) NOT NULL,
  `mulai` date NOT NULL,
  `selesai` date NOT NULL,
  `lama_diskon` varchar(50) NOT NULL,
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mobil`
--

CREATE TABLE `mobil` (
  `id_mobil` int(255) NOT NULL,
  `plat_mobil` varchar(100) NOT NULL,
  `nama_mobil` varchar(255) NOT NULL,
  `tarif_per_hari` int(255) NOT NULL,
  `status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mobil`
--

INSERT INTO `mobil` (`id_mobil`, `plat_mobil`, `nama_mobil`, `tarif_per_hari`, `status`) VALUES
(1, 'N 805', 'Volkswagen Scirocco', 850000, 'unavailable'),
(2, 'N 87 GLE', 'Mercedes-Benz GLE 53 AMG', 1500000, 'unavailable'),
(3, 'B 99 RR', 'Rolls-Royce Ghost', 2500000, 'unavailable'),
(7, 'AE 86 GUA', 'Toyota AE 86', 1750000, 'unavailable'),
(35, 'N 8', 'Toyota Innova Reborn', 500000, 'unavailable'),
(38, 'M 3 BMW', 'BMW M3 2022', 1600000, 'unavailable'),
(39, 'B 99 TSL', 'Tesla Model S', 1350000, 'unavailable'),
(41, 'N 1396 AAA', 'Mercedes-Benz SLR 200', 750000, 'unavailable'),
(42, 'N 1937 FG', 'Mitsubishi Lancer EX', 900000, 'available'),
(43, 'N 1913 ABG', 'Toyota Passo', 350000, 'available'),
(44, 'B 1174 SPW', 'Hyundai IONIQ Electric Sedan', 1000000, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_sewa` int(255) NOT NULL,
  `tgl_pembayaran` datetime NOT NULL,
  `total` int(255) NOT NULL,
  `diskon` int(255) NOT NULL,
  `uang_user` int(255) NOT NULL,
  `status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_sewa`, `tgl_pembayaran`, `total`, `diskon`, `uang_user`, `status`) VALUES
(29, 34, '2023-03-01 07:50:12', 3867500, 35, 4000000, 'lunas'),
(31, 41, '2023-03-01 08:09:55', 2275000, 35, 2275000, 'lunas'),
(34, 43, '2023-03-01 21:33:02', 8400000, 30, 6000000, 'belum lunas'),
(58, 70, '2023-03-06 13:28:58', 325000, 35, 200000, 'belum lunas'),
(59, 71, '2023-03-07 12:59:06', 4160000, 35, 10000000, 'lunas'),
(61, 73, '2023-03-08 09:29:17', 2250000, 25, 2500000, 'lunas'),
(63, 75, '2023-03-08 16:47:51', 4387500, 35, 3000000, 'belum lunas'),
(67, 79, '2023-05-01 06:33:21', 7500000, 0, 10, 'belum lunas');

-- --------------------------------------------------------

--
-- Table structure for table `sewa`
--

CREATE TABLE `sewa` (
  `id_sewa` int(255) NOT NULL,
  `tgl_pemesanan` datetime NOT NULL,
  `id_user` int(255) NOT NULL,
  `plat_mobil` varchar(100) NOT NULL,
  `mulai` date NOT NULL,
  `selesai` date NOT NULL,
  `lama_sewa` varchar(255) NOT NULL,
  `total_pembayaran` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sewa`
--

INSERT INTO `sewa` (`id_sewa`, `tgl_pemesanan`, `id_user`, `plat_mobil`, `mulai`, `selesai`, `lama_sewa`, `total_pembayaran`) VALUES
(34, '2023-02-18 11:18:10', 1, 'N 805', '2023-02-18', '2023-02-25', '7 hari', 5950000),
(41, '2023-02-26 20:09:37', 39, 'AE 86 GUA', '2023-03-09', '2023-03-11', '2 hari', 3500000),
(43, '2023-03-01 21:31:57', 40, 'N 87 GLE', '2023-03-01', '2023-03-09', '8 hari', 12000000),
(70, '2023-03-06 13:28:58', 62, 'N 8', '2023-03-06', '2023-03-07', '1 hari', 500000),
(71, '2023-03-07 12:59:06', 57, 'M 3 BMW', '2023-03-09', '2023-03-13', '4 hari', 6400000),
(73, '2023-03-08 09:29:17', 66, 'N 1396 AAA', '2023-03-08', '2023-03-12', '4 hari', 3000000),
(75, '2023-03-08 16:47:51', 67, 'B 99 TSL', '2023-03-04', '2023-03-09', '5 hari', 6750000),
(79, '2023-05-01 06:33:21', 39, 'B 99 RR', '2023-05-01', '2023-05-04', '3 hari', 7500000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diskon`
--
ALTER TABLE `diskon`
  ADD PRIMARY KEY (`id_diskon`);

--
-- Indexes for table `mobil`
--
ALTER TABLE `mobil`
  ADD PRIMARY KEY (`id_mobil`),
  ADD UNIQUE KEY `plat_mobil` (`plat_mobil`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `pembayaran_ibfk_1` (`id_sewa`);

--
-- Indexes for table `sewa`
--
ALTER TABLE `sewa`
  ADD PRIMARY KEY (`id_sewa`),
  ADD KEY `sewa_ibfk_1` (`id_user`),
  ADD KEY `sewa_ibfk_2` (`plat_mobil`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `diskon`
--
ALTER TABLE `diskon`
  MODIFY `id_diskon` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `mobil`
--
ALTER TABLE `mobil`
  MODIFY `id_mobil` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `sewa`
--
ALTER TABLE `sewa`
  MODIFY `id_sewa` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_sewa`) REFERENCES `sewa` (`id_sewa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sewa`
--
ALTER TABLE `sewa`
  ADD CONSTRAINT `sewa_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sewa_ibfk_2` FOREIGN KEY (`plat_mobil`) REFERENCES `mobil` (`plat_mobil`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
