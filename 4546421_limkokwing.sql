-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: fdb1030.awardspace.net
-- Generation Time: Nov 20, 2024 at 09:45 AM
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
-- Table structure for table `academic_years`
--

CREATE TABLE `academic_years` (
  `id` int NOT NULL,
  `year_name` varchar(20) DEFAULT NULL,
  `is_current` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `academic_years`
--

INSERT INTO `academic_years` (`id`, `year_name`, `is_current`, `status`) VALUES
(1, '2024-2025', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int NOT NULL,
  `student_id` int DEFAULT NULL,
  `module_id` int DEFAULT NULL,
  `class_id` int DEFAULT NULL,
  `lecturer_id` int DEFAULT NULL,
  `status` enum('present','absent') DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `module_id`, `class_id`, `lecturer_id`, `status`, `date`) VALUES
(1, 1, 2, 1, 3, 'present', '2024-11-02'),
(2, 2, 2, 1, 3, 'present', '2024-11-02'),
(5, 1, 3, 1, 6, 'present', '2024-11-03'),
(6, 2, 3, 1, 6, 'present', '2024-11-03'),
(9, 1, 2, 1, 3, 'present', '2024-11-07'),
(10, 2, 2, 1, 3, 'present', '2024-11-07'),
(11, 1, 2, 1, 3, 'present', '2024-11-12'),
(12, 2, 2, 1, 3, 'present', '2024-11-12'),
(13, 1, 1, 1, 4, 'present', '2024-11-19'),
(14, 2, 1, 1, 4, 'present', '2024-11-19');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int NOT NULL,
  `class_name` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `class_name`, `status`) VALUES
(1, 'BSCBIT Y3S1', 1),
(2, 'DBIT Y3S1', 1),
(3, 'DIT Y3S1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lecturer_assignments`
--

CREATE TABLE `lecturer_assignments` (
  `id` int NOT NULL,
  `lecturer_id` int DEFAULT NULL,
  `module_id` int DEFAULT NULL,
  `class_id` int DEFAULT NULL,
  `semester_id` int DEFAULT NULL,
  `academic_year_id` int DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lecturer_assignments`
--

INSERT INTO `lecturer_assignments` (`id`, `lecturer_id`, `module_id`, `class_id`, `semester_id`, `academic_year_id`, `status`) VALUES
(1, 4, 1, 1, 1, 1, 1),
(2, 3, 2, 1, 1, 1, 1),
(3, 6, 3, 1, 1, 1, 1),
(4, 6, 4, 2, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `action`, `timestamp`) VALUES
(1, 1, 'User logged in', '2024-11-02 16:21:23'),
(2, 1, 'User logged out', '2024-11-02 16:47:46'),
(3, 2, 'User logged in', '2024-11-02 16:48:39'),
(4, 2, 'User logged out', '2024-11-02 16:50:48'),
(5, 3, 'User logged in', '2024-11-02 16:51:41'),
(6, 2, 'User logged in', '2024-11-02 17:12:55'),
(7, 2, 'User logged out', '2024-11-02 17:13:16'),
(8, 3, 'User logged out', '2024-11-02 17:20:34'),
(9, 3, 'User logged in', '2024-11-02 17:36:27'),
(10, 3, 'User logged in', '2024-11-02 17:48:16'),
(11, 3, 'Submitted weekly report for module ID: 2', '2024-11-02 17:49:23'),
(12, 3, 'User logged out', '2024-11-02 17:49:53'),
(13, 2, 'User logged in', '2024-11-02 17:50:23'),
(14, 1, 'User logged in', '2024-11-02 17:52:54'),
(15, 1, 'User logged out', '2024-11-02 17:53:02'),
(16, 3, 'User logged in', '2024-11-02 17:53:42'),
(17, 2, 'User logged in', '2024-11-02 17:59:18'),
(18, 1, 'User logged in', '2024-11-03 15:22:17'),
(19, 1, 'User logged in', '2024-11-03 15:30:01'),
(20, 1, 'User logged out', '2024-11-03 15:36:57'),
(21, 6, 'User logged in', '2024-11-03 15:37:23'),
(22, 6, 'User logged out', '2024-11-03 15:39:56'),
(23, 2, 'User logged in', '2024-11-03 15:40:14'),
(24, 1, 'User logged in', '2024-11-03 20:00:05'),
(25, 1, 'User logged out', '2024-11-03 20:01:58'),
(26, 4, 'User logged in', '2024-11-03 20:02:20'),
(27, 4, 'User logged out', '2024-11-03 20:03:19'),
(28, 1, 'User logged in', '2024-11-07 11:08:53'),
(29, 1, 'User logged out', '2024-11-07 11:09:35'),
(30, 2, 'User logged in', '2024-11-07 11:13:02'),
(31, 2, 'User logged out', '2024-11-07 11:14:38'),
(32, 3, 'User logged in', '2024-11-07 11:14:59'),
(33, 3, 'User logged out', '2024-11-07 12:12:15'),
(34, 1, 'User logged in', '2024-11-07 12:12:44'),
(35, 1, 'User logged in', '2024-11-12 09:54:44'),
(36, 1, 'User logged out', '2024-11-12 10:09:35'),
(37, 3, 'User logged in', '2024-11-12 10:18:27'),
(38, 7, 'User logged in', '2024-11-12 10:30:22'),
(39, 3, 'User logged in', '2024-11-12 10:44:18'),
(40, 3, 'User logged out', '2024-11-12 10:55:10'),
(41, 1, 'User logged in', '2024-11-12 11:05:07'),
(42, 1, 'User logged in', '2024-11-12 11:19:11'),
(43, 1, 'User logged out', '2024-11-12 11:26:01'),
(44, 1, 'User logged in', '2024-11-12 11:30:00'),
(45, 1, 'User logged out', '2024-11-12 11:30:20'),
(46, 1, 'User logged out', '2024-11-12 11:31:11'),
(47, 6, 'User logged in', '2024-11-12 11:31:29'),
(48, 4, 'User logged in', '2024-11-12 11:36:05'),
(49, 4, 'Submitted weekly report for module ID: 1', '2024-11-12 11:39:35'),
(50, 4, 'User logged out', '2024-11-12 11:42:09'),
(51, 2, 'User logged in', '2024-11-12 11:43:55'),
(52, 2, 'User logged out', '2024-11-12 11:46:02'),
(53, 1, 'User logged in', '2024-11-17 12:20:09'),
(54, 1, 'User logged out', '2024-11-17 12:27:29'),
(55, 4, 'User logged in', '2024-11-19 18:45:56'),
(56, 4, 'User logged out', '2024-11-19 18:47:17'),
(57, 4, 'User logged in', '2024-11-20 02:25:25'),
(58, 4, 'User logged out', '2024-11-20 02:26:29'),
(59, 2, 'User logged in', '2024-11-20 02:26:54'),
(60, 2, 'User logged out', '2024-11-20 02:27:49'),
(61, 7, 'User logged in', '2024-11-20 02:32:17'),
(62, 7, 'User logged out', '2024-11-20 02:33:09'),
(63, 7, 'User logged in', '2024-11-20 06:46:16'),
(64, 7, 'User logged out', '2024-11-20 06:50:37'),
(65, 4, 'User logged in', '2024-11-20 06:51:15'),
(66, 4, 'User logged out', '2024-11-20 06:56:05'),
(67, 7, 'User logged in', '2024-11-20 06:56:42'),
(68, 7, 'User logged out', '2024-11-20 06:57:44'),
(69, 7, 'User logged in', '2024-11-20 07:43:51'),
(70, 4, 'User logged in', '2024-11-20 08:17:06'),
(71, 4, 'User logged out', '2024-11-20 08:17:26'),
(72, 2, 'User logged in', '2024-11-20 08:18:13'),
(73, 2, 'User logged out', '2024-11-20 08:26:32'),
(74, 4, 'User logged in', '2024-11-20 08:26:48'),
(75, 7, 'User logged in', '2024-11-20 08:36:49'),
(76, 4, 'User logged in', '2024-11-20 09:23:02'),
(77, 4, 'User logged out', '2024-11-20 09:23:27'),
(78, 7, 'User logged in', '2024-11-20 09:24:47'),
(79, 7, 'User logged out', '2024-11-20 09:25:28'),
(80, 1, 'User logged in', '2024-11-20 09:26:24'),
(81, 1, 'User logged out', '2024-11-20 09:27:12'),
(82, 2, 'User logged in', '2024-11-20 09:27:28'),
(83, 2, 'User logged out', '2024-11-20 09:28:45'),
(84, 7, 'User logged out', '2024-11-20 09:28:56'),
(85, 4, 'User logged in', '2024-11-20 09:29:27'),
(86, 4, 'User logged in', '2024-11-20 09:29:46'),
(87, 4, 'User logged out', '2024-11-20 09:30:44'),
(88, 2, 'User logged in', '2024-11-20 09:31:01'),
(89, 2, 'User logged out', '2024-11-20 09:33:35');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int NOT NULL,
  `module_code` varchar(20) DEFAULT NULL,
  `module_name` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `module_code`, `module_name`, `status`) VALUES
(1, 'DICS3112', 'COMPUTER SYSTEM SUPPORT', 1),
(2, 'DBLA 313', 'LEGAL ASPECTS OF BUSINESS', 1),
(3, 'AIIS313', 'INFORMATION SECURITY MANAGEMENT', 1),
(4, 'ABMK313', 'MOBILE COMMERCE', 1);

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `id` int NOT NULL,
  `semester_name` varchar(50) DEFAULT NULL,
  `academic_year_id` int DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_current` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`id`, `semester_name`, `academic_year_id`, `start_date`, `end_date`, `is_current`, `status`) VALUES
(1, 'semester 1', 1, '2024-08-11', '2025-05-30', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int NOT NULL,
  `student_number` varchar(9) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `class_id` int DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_number`, `full_name`, `contact`, `class_id`, `status`) VALUES
(1, '901016480', 'Mpiti Liete', NULL, 1, 1),
(2, '901016747', 'Rosina Mosebetsane', NULL, 1, 1),
(3, '901016500', 'Karabo Joel', NULL, 1, 1),
(4, '901016745', 'Rethabile Mothobi', NULL, 2, 1);

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

-- --------------------------------------------------------

--
-- Table structure for table `weekly_reports`
--

CREATE TABLE `weekly_reports` (
  `id` int NOT NULL,
  `lecturer_id` int DEFAULT NULL,
  `module_id` int DEFAULT NULL,
  `class_id` int DEFAULT NULL,
  `chapter_covered` varchar(200) DEFAULT NULL,
  `learning_outcomes` text,
  `mode_of_delivery` varchar(50) DEFAULT NULL,
  `student_attendance` int DEFAULT NULL,
  `challenges` text,
  `recommendations` text,
  `malpractice_instances` text,
  `report_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `weekly_reports`
--

INSERT INTO `weekly_reports` (`id`, `lecturer_id`, `module_id`, `class_id`, `chapter_covered`, `learning_outcomes`, `mode_of_delivery`, `student_attendance`, `challenges`, `recommendations`, `malpractice_instances`, `report_date`, `created_at`) VALUES
(1, 3, 2, 1, 'Law of agreement ', 'None', 'Face-to-Face', 20, 'None', 'None', '', '2024-11-01', '2024-11-02 17:49:23'),
(2, 4, 1, 1, 'CHAPTER 9', 'DEFINITION OF PHYSICAL SECURITY\r\nIMPORTANCE OF PHYSICAL SECURITY', 'Face-to-Face', 6, 'LOW INTERNET', 'TO INCREASE WIFI', 'FOR', '2024-11-12', '2024-11-12 11:39:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_years`
--
ALTER TABLE `academic_years`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `module_id` (`module_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `lecturer_id` (`lecturer_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lecturer_assignments`
--
ALTER TABLE `lecturer_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lecturer_id` (`lecturer_id`),
  ADD KEY `module_id` (`module_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `semester_id` (`semester_id`),
  ADD KEY `academic_year_id` (`academic_year_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `academic_year_id` (`academic_year_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_number` (`student_number`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_number` (`employee_number`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `weekly_reports`
--
ALTER TABLE `weekly_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lecturer_id` (`lecturer_id`),
  ADD KEY `module_id` (`module_id`),
  ADD KEY `class_id` (`class_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_years`
--
ALTER TABLE `academic_years`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lecturer_assignments`
--
ALTER TABLE `lecturer_assignments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `weekly_reports`
--
ALTER TABLE `weekly_reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`),
  ADD CONSTRAINT `attendance_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `attendance_ibfk_4` FOREIGN KEY (`lecturer_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `lecturer_assignments`
--
ALTER TABLE `lecturer_assignments`
  ADD CONSTRAINT `lecturer_assignments_ibfk_1` FOREIGN KEY (`lecturer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `lecturer_assignments_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`),
  ADD CONSTRAINT `lecturer_assignments_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `lecturer_assignments_ibfk_4` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`),
  ADD CONSTRAINT `lecturer_assignments_ibfk_5` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`);

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `semesters`
--
ALTER TABLE `semesters`
  ADD CONSTRAINT `semesters_ibfk_1` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`);

--
-- Constraints for table `weekly_reports`
--
ALTER TABLE `weekly_reports`
  ADD CONSTRAINT `weekly_reports_ibfk_1` FOREIGN KEY (`lecturer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `weekly_reports_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`),
  ADD CONSTRAINT `weekly_reports_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
