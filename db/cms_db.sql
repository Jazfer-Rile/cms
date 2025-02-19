-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 19, 2025 at 01:11 PM
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
-- Database: `cms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `action_type` varchar(255) NOT NULL,
  `action_description` text NOT NULL,
  `assigned_by` int(11) NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `action_type`, `action_description`, `assigned_by`, `assigned_to`, `timestamp`) VALUES
(1, 'Competency', 'Assigned Competency \'Test Test\' to Employee ID 12', 4, 12, '2025-02-19 10:20:46'),
(2, 'Training', 'Assigned Training \'qd\' to Employee ID 12', 4, 12, '2025-02-19 10:22:23'),
(3, 'Training', 'Assigned Training \'trainings1\' to Employee ID 10', 4, 10, '2025-02-19 10:22:26'),
(4, 'Competency', 'Assigned Competency \'Analytical Thinking\' to Employee ID 1002', 1001, 1002, '2025-02-19 11:59:41'),
(5, 'Training', 'Assigned Training \'Elevating Customer Experience\' to Employee ID 1002', 1001, 1002, '2025-02-19 11:59:47'),
(6, 'Training', 'Assigned Training \'Leading with Impact\' to Employee ID 1003', 1001, 1003, '2025-02-19 11:59:56'),
(7, 'Training', 'Assigned Training \'Mastering Effective Communication\' to Employee ID 1004', 1001, 1004, '2025-02-19 12:00:08'),
(8, 'Competency', 'Assigned Competency \'Effective Communication\' to Employee ID 1002', 1001, 1002, '2025-02-19 12:02:07'),
(9, 'Competency', 'Assigned Competency \'Customer-Centric Service\' to Employee ID 1002', 1001, 1002, '2025-02-19 12:02:11'),
(10, 'Training', 'Assigned Training \'Mastering Effective Communication\' to Employee ID 1002', 1001, 1002, '2025-02-19 12:02:23'),
(11, 'Training', 'Assigned Training \'Building High-Performing Teams\' to Employee ID 1002', 1001, 1002, '2025-02-19 12:02:27'),
(12, 'Training', 'Assigned Training \'Leading with Impact\' to Employee ID 1002', 1001, 1002, '2025-02-19 12:02:31');

-- --------------------------------------------------------

--
-- Table structure for table `competencies`
--

CREATE TABLE `competencies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `competencies`
--

INSERT INTO `competencies` (`id`, `title`, `description`, `created_at`) VALUES
(1, 'Effective Communication', 'The skill to clearly and efficiently convey information, both verbally and in writing. This involves active listening, providing constructive feedback, and tailoring messages to suit various audiences.', '2025-02-17 19:11:48'),
(2, 'Collaborative Teamwork', 'The capability to work harmoniously with others towards a common goal. This includes building strong relationships, resolving conflicts amicably, and contributing to a positive team environment.', '2025-02-19 02:19:50'),
(3, 'Analytical Thinking', 'The proficiency to analyze complex issues and develop innovative solutions. This includes critical thinking, evaluating options, and implementing effective problem-solving strategies.', '2025-02-19 05:13:35'),
(4, 'Customer-Centric Service', 'The dedication to meeting and exceeding customer expectations. This involves understanding customer needs, providing exceptional service, and continuously seeking ways to enhance the customer experience.', '2025-02-19 05:13:41');

-- --------------------------------------------------------

--
-- Table structure for table `training_programs`
--

CREATE TABLE `training_programs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `training_programs`
--

INSERT INTO `training_programs` (`id`, `title`, `description`, `created_at`) VALUES
(1, 'Mastering Effective Communication', 'This course aims to enhance participants\' verbal and written communication skills. Topics include active listening, persuasive communication, and tailoring messages to different audiences to ensure clarity and impact.', '2025-02-19 03:21:32'),
(2, 'Building High-Performing Teams', 'This training covers strategies for fostering teamwork and collaboration. Participants will explore techniques for conflict resolution, building trust, and creating a positive team culture that boosts productivity and morale.', '2025-02-19 03:22:29'),
(3, 'Elevating Customer Experience', 'This program focuses on delivering exceptional customer service. Participants will gain insights into customer needs, learn strategies for exceeding expectations, and explore ways to enhance overall customer satisfaction.', '2025-02-19 05:13:20'),
(4, 'Leading with Impact', 'This training program focuses on developing leadership skills, including strategic planning, effective decision-making, and inspiring team members. Participants will learn to navigate complex challenges and drive organizational success.', '2025-02-19 11:44:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Manager','Employee') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1000, 'Admin', 'admin@gmail.com', '$2y$10$wJBnRGnJwzNN0r5VQ192v.fuqRe.8Uza1zTJf3ZM8IBe.lAEeGdS6', 'Admin', '2025-02-19 11:35:49'),
(1001, 'MANAGER', 'manager@gmail.com', '$2y$10$JfBYgF.efNnRX1/fYfgz6ezDF32yrylNAVoViyAsZ4ugwfwiNrDnC', 'Manager', '2025-02-19 11:38:11'),
(1002, 'Jazfer', 'rilejazfer@gmail.com', '$2y$10$EM6Cwyq1yEx7.txm6EzDHucKDy8FA0u8eeRJgCXhlibRvVZZXB8M2', 'Employee', '2025-02-19 11:39:59'),
(1003, 'Maricris', 'maricris@gmail.com', '$2y$10$7ZHrVldApltY2u7pYeBiEOl3RIDsm6vVqaiIyWLcAFtSnoFFDL2x2', 'Employee', '2025-02-19 11:57:50'),
(1004, 'Kaye', 'kaye@gmail.com', '$2y$10$fTjY7Cy9z8mQ/dpfPJBrJeUtQSqn9L44PVLlKYfuhRLtr.OC5U6ti', 'Employee', '2025-02-19 11:58:27');

-- --------------------------------------------------------

--
-- Table structure for table `user_competencies`
--

CREATE TABLE `user_competencies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `competency_id` int(11) DEFAULT NULL,
  `assigned_by` int(11) NOT NULL,
  `status` enum('Assigned','In Progress','Completed') DEFAULT 'Assigned',
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_competencies`
--

INSERT INTO `user_competencies` (`id`, `user_id`, `competency_id`, `assigned_by`, `status`, `assigned_at`) VALUES
(8, 1002, 3, 1001, 'Assigned', '2025-02-19 11:59:41'),
(9, 1002, 1, 1001, 'Assigned', '2025-02-19 12:02:07'),
(10, 1002, 4, 1001, 'Assigned', '2025-02-19 12:02:11');

-- --------------------------------------------------------

--
-- Table structure for table `user_training`
--

CREATE TABLE `user_training` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `assigned_by` int(11) NOT NULL,
  `training_id` int(11) DEFAULT NULL,
  `status` enum('Assigned','In Progress','Completed') DEFAULT 'Assigned',
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_training`
--

INSERT INTO `user_training` (`id`, `user_id`, `assigned_by`, `training_id`, `status`, `assigned_at`) VALUES
(10, 1002, 1001, 3, 'In Progress', '2025-02-19 11:59:47'),
(11, 1003, 1001, 4, '', '2025-02-19 11:59:55'),
(12, 1004, 1001, 1, '', '2025-02-19 12:00:08'),
(13, 1002, 1001, 1, 'Completed', '2025-02-19 12:02:23'),
(14, 1002, 1001, 2, 'In Progress', '2025-02-19 12:02:27'),
(15, 1002, 1001, 4, 'In Progress', '2025-02-19 12:02:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `competencies`
--
ALTER TABLE `competencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `training_programs`
--
ALTER TABLE `training_programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_competencies`
--
ALTER TABLE `user_competencies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `competency_id` (`competency_id`);

--
-- Indexes for table `user_training`
--
ALTER TABLE `user_training`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `training_id` (`training_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `competencies`
--
ALTER TABLE `competencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `training_programs`
--
ALTER TABLE `training_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1005;

--
-- AUTO_INCREMENT for table `user_competencies`
--
ALTER TABLE `user_competencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_training`
--
ALTER TABLE `user_training`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_competencies`
--
ALTER TABLE `user_competencies`
  ADD CONSTRAINT `user_competencies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_competencies_ibfk_2` FOREIGN KEY (`competency_id`) REFERENCES `competencies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_training`
--
ALTER TABLE `user_training`
  ADD CONSTRAINT `user_training_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_training_ibfk_2` FOREIGN KEY (`training_id`) REFERENCES `training_programs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
