-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: fdb1030.awardspace.net
-- Generation Time: Nov 20, 2024 at 09:42 AM
-- Server version: 8.0.32
-- PHP Version: 8.1.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `4546421_limkokwing`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `employee_number` varchar(20) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `role` enum('admin','lecturer','prl') DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_number`, `full_name`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(1, '123456', 'Limpho Mothobi', 'mothobilimpho16@gmail.com', '123456', 'admin', 1, '2024-11-02 16:19:48'),
(2, '20241', 'Hape Borotho', 'borothohape17@gmail.com', '20241', 'prl', 1, '2024-11-02 16:25:41'),
(3, '20242', 'Lerato Mothobi', 'lerato@gmail.com', '20242', 'lecturer', 1, '2024-11-02 16:27:59'),
(4, '20243', 'Amohelang Mosebetsane', 'mosebetsaneamohelang19@gmail.com', '20243', 'lecturer', 1, '2024-11-02 16:28:54'),
(5, 'admin', 'Karabelo Matala', 'karabelomatala@gmail.com', '123456', 'admin', 1, '2024-11-02 17:35:24'),
(6, '20244', 'Nthabiseng Peete', 'olgamothobi@gmail.com', '20244', 'lecturer', 1, '2024-11-03 15:32:01'),
(7, '202400', 'Belina Matala', 'limkokwing@edu.com', '202400', 'admin', 1, '2024-11-12 10:30:03'),
(8, '20245', 'Mitchel Peete', 'mitchel@gmail.com', '20245', 'lecturer', 1, '2024-11-20 08:15:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_number` (`employee_number`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
