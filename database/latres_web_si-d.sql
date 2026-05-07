-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: May 07, 2026 at 05:36 PM
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
-- Database: `latres_web_si-d`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id_asset` int(11) NOT NULL,
  `serial_number` varchar(50) NOT NULL,
  `nama_alat` varchar(100) NOT NULL,
  `merk` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `url_gambar` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id_asset`, `serial_number`, `nama_alat`, `merk`, `status`, `jumlah`, `url_gambar`, `created_at`) VALUES
(1, 'CAM-SONY-A73-01', 'Sony Alpha a7 III Mirrorless', 'Sony', 'Tersedia', 3, 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?q=80&w=1000&auto=format&fit=crop', '2026-04-04 13:47:31'),
(2, 'LNS-CAN-50MM-02', 'Canon EF 50mm f/1.8 STM', 'Canon', 'Dipinjam', 1, 'https://images.unsplash.com/photo-1617005082133-548c4dd27f35?q=80&w=1000&auto=format&fit=crop', '2026-04-04 13:47:31'),
(5, 'CAM-CANON-901', 'Canon 1000D', 'Canon', 'Maintenance', 5, 'https://resersetua.photo.url', '2026-05-07 02:27:23'),
(6, 'CAM-FUJI-005', 'Fujifilm X-T57', 'Fujifilm', 'Tersedia', 2, 'https://sinarphoto.com/prd/l/fujifilm-x-t5-mirrorless-camera-black-08.jpg', '2026-05-07 07:01:44'),
(7, 'LNS-SIGMA-85MM', 'Sigma 85mm f/1.4 Art', 'Sigma', 'Dipinjam', 3, 'https://www.cined.com/content/uploads/2020/08/SIGMA-85mm-f14-DG-DN-Art-comparison.jpg', '2026-05-07 07:02:40'),
(8, 'DRN-AUTEL-12', 'Autel EVO II Series', 'Autel', 'Maintenance', 1, 'https://shop.autelrobotics.com/cdn/shop/files/10_c96a6466-0ef4-43d2-8dc4-7085d405d13c_1100x.jpg?v=1718206908', '2026-05-07 07:05:14'),
(9, 'LNS-CANON-004', 'Canon 200mm f1.8', 'Canon', 'Tersedia', 1, 'https://i.imgur.com/Lmy2iDE.jpeg', '2026-05-07 07:06:14'),
(10, 'CAM-NIKON-011', 'Nikon 1 J1/V1', 'NIKON', 'Dipinjam', 2, 'https://platform.theverge.com/wp-content/uploads/sites/2/chorus/uploads/chorus_asset/file/13062127/Hero.1419962192.jpg?quality=90&strip=all&crop=0,0,100,100', '2026-05-07 07:10:34'),
(11, 'CAM-CANON-801', 'Canon 800D', 'Canon', 'Tersedia', 3, 'https://down-id.img.susercontent.com/file/id-11134207-7r98r-lny5ef6d3l3b2b', '2026-05-07 22:09:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `created_at`) VALUES
(1, '123', '123', '2026-04-02 20:12:10'),
(3, 'mieayam', 'resersemuda', '2026-05-07 01:28:23'),
(4, 'kicaw', 'negaraKORUPSI', '2026-05-07 08:18:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id_asset`),
  ADD UNIQUE KEY `serial_number` (`serial_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id_asset` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
