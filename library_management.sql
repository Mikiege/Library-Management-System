-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 15, 2025 at 06:41 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `resource_id` int NOT NULL,
  `booking_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `STATUS` enum('active','completed','cancelled') DEFAULT 'active',
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `resource_id`, `booking_time`, `STATUS`, `booking_date`, `start_time`, `end_time`) VALUES
(2, 'br29898', 11, '2025-03-14 13:55:04', 'completed', '2025-03-14', '10:00:00', '10:34:00'),
(3, 'jo10658', 9, '2025-03-14 23:56:00', 'cancelled', '2025-03-14', '19:55:00', '20:55:00'),
(4, 'br29898', 14, '2025-03-15 16:00:41', 'cancelled', '2025-03-15', '12:01:00', '17:00:00'),
(5, 'br29898', 1, '2025-03-15 16:09:10', 'cancelled', '2025-03-15', '17:09:00', '18:09:00'),
(6, 'br29898', 1, '2025-03-15 17:02:20', 'active', '2025-03-15', '13:05:00', '15:02:00'),
(7, 'jo10658', 9, '2025-03-15 17:05:09', 'cancelled', '2025-03-15', '14:05:00', '15:05:00'),
(8, 'jo10658', 1, '2025-03-15 18:40:54', 'cancelled', '2025-03-19', '18:40:00', '19:40:00');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `resource_id` int NOT NULL,
  `resource_name` varchar(50) NOT NULL,
  `resource_type` enum('seat','computer') NOT NULL,
  `is_available` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`resource_id`, `resource_name`, `resource_type`, `is_available`) VALUES
(1, 'Seat 1', 'seat', 1),
(2, 'Seat 2', 'seat', 1),
(3, 'Seat 3', 'seat', 1),
(4, 'Seat 4', 'seat', 1),
(5, 'Seat 5', 'seat', 1),
(6, 'Seat 6', 'seat', 1),
(7, 'Seat 7', 'seat', 1),
(8, 'Seat 8', 'seat', 1),
(9, 'Seat 9', 'seat', 1),
(10, 'Seat 10', 'seat', 1),
(11, 'Seat 11', 'seat', 1),
(12, 'Seat 12', 'seat', 1),
(13, 'Seat 13', 'seat', 1),
(14, 'Seat 14', 'seat', 1),
(15, 'Seat 15', 'seat', 1),
(16, 'Seat 16', 'seat', 1),
(17, 'Seat 17', 'seat', 1),
(18, 'Seat 18', 'seat', 1),
(19, 'Seat 19', 'seat', 1),
(20, 'Seat 20', 'seat', 1),
(21, 'Seat 21', 'seat', 1),
(22, 'Seat 22', 'seat', 1),
(23, 'Seat 23', 'seat', 1),
(24, 'Seat 24', 'seat', 1),
(25, 'Seat 25', 'seat', 1),
(26, 'Seat 26', 'seat', 1),
(27, 'Seat 27', 'seat', 1),
(28, 'Seat 28', 'seat', 1),
(29, 'Seat 29', 'seat', 1),
(30, 'Seat 30', 'seat', 1),
(31, 'Computer 1', 'computer', 1),
(32, 'Computer 2', 'computer', 1),
(33, 'Computer 3', 'computer', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `user_type` enum('student','staff','visitor') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `first_name`, `last_name`, `email`, `user_type`, `created_at`) VALUES
('bd78965', 'bobbyd', '$2y$10$r54DxeYy81NXJl5QPtcYHOpxDb2NdyvkLBsJKNeQ7e.w2zw6fkZ9q', 'Bob', 'Dylan', 'bobby_d@gmail.com', 'student', '2025-03-15 17:26:00'),
('br29898', 'charlieb', '$2y$10$k/8p95LWapzG6.e0K1B7te5.yxKWUSwAmXSCPFFcaUFrft6s5fyva', 'Charlie', 'Brown', 'chbrown@salcc.edu.lc', 'staff', '2025-03-04 02:33:03'),
('jo10658', 'alicej', '$2y$10$IvUn41BvtmDWwkopdanpYOq2GO5//gtEjumpGc1axiHeJF7WRILZK', 'Alice', 'Johnson', 'aljohnson@salcc.edu.lc', 'student', '2025-03-04 02:33:03'),
('pr78797', 'dianap', '$2y$10$la9ooXn2ynvxZf3UKihn5O6k00BDBKBsjx.GCljx0ZWgMyUoGUNWW', 'Diana', 'Prince', 'diprince@salcc.edu.lc', 'student', '2025-03-04 02:33:03'),
('sm42350', 'bobsmith', '$2y$10$YdD0zTM0RXhBYyx.IC650.qMs/GnJ0CWe3SHKssMOHNwdFoFNk29y', 'Bob', 'Smith', 'bosmith@salcc.edu.lc', 'staff', '2025-03-04 02:33:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `resource_id` (`resource_id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`resource_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `resource_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`resource_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
