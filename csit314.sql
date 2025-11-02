-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2025 at 07:43 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `csit314`
--

-- --------------------------------------------------------

--
-- Table structure for table `csr_shortlist`
--

CREATE TABLE `csr_shortlist` (
  `id` int(11) NOT NULL,
  `csr_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `csr_shortlist`
--

INSERT INTO `csr_shortlist` (`id`, `csr_id`, `request_id`, `created_at`) VALUES
(1, 3, 1, '2025-10-29 07:43:02'),
(2, 3, 2, '2025-10-29 07:43:02'),
(3, 3, 3, '2025-10-29 07:43:02'),
(4, 3, 18, '2025-10-29 16:04:17'),
(5, 3, 14, '2025-10-29 16:04:17'),
(6, 3, 13, '2025-10-29 16:04:25'),
(7, 3, 17, '2025-10-30 14:03:21'),
(8, 3, 16, '2025-10-30 18:26:00'),
(9, 3, 12, '2025-10-30 18:26:13'),
(10, 6, 24, '2025-10-30 21:11:22'),
(11, 6, 21, '2025-10-30 21:11:22'),
(12, 6, 20, '2025-10-30 21:11:23'),
(13, 6, 19, '2025-10-30 21:11:23'),
(14, 6, 18, '2025-10-30 21:11:24');

-- --------------------------------------------------------

--
-- Table structure for table `pin_history`
--

CREATE TABLE `pin_history` (
  `history_id` int(11) UNSIGNED NOT NULL,
  `request_id` int(11) NOT NULL,
  `volunteer_id` int(11) DEFAULT NULL,
  `status` enum('completed','cancelled') DEFAULT 'completed',
  `completed_at` datetime DEFAULT current_timestamp(),
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `pin_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pin_history`
--

INSERT INTO `pin_history` (`history_id`, `request_id`, `volunteer_id`, `status`, `completed_at`, `title`, `description`, `pin_id`) VALUES
(1, 14, 1, 'completed', '2025-10-15 10:30:00', 'Grocery Shopping Help', 'Require help to buy groceries and household items once a week', 2),
(2, 16, 2, 'completed', '2025-10-20 14:00:00', 'Gardening Help', 'Need volunteer to help trim plants and tidy garden area', 2),
(3, 12, 3, 'completed', '2025-10-25 16:00:00', 'Transport to market', 'Need transport to nearby market for weekly grocery shopping', 2);

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(80) NOT NULL,
  `content` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `status` enum('open','in_progress','closed') NOT NULL DEFAULT 'open',
  `view_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `shortlist_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `closed_at` datetime DEFAULT NULL,
  `category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`request_id`, `user_id`, `category_id`, `title`, `content`, `location`, `status`, `view_count`, `shortlist_count`, `created_at`, `closed_at`, `category`) VALUES
(12, 2, 0, 'Transport to market', 'Need transport to nearby market for weekly grocery.', 'Blk 123', 'closed', 4, 1, '2025-10-28 14:47:53', NULL, ''),
(13, 2, 0, 'Smartphone Setup Help', 'Need volunteer to help set up mobile phone and WhatsApp.', 'Blk 222', 'open', 1, 1, '2025-10-29 02:02:54', NULL, ''),
(14, 2, 0, 'Grocery Shopping Help', 'Require help to buy groceries and household items.', 'Blk 111', 'closed', 1, 1, '2025-10-29 06:12:42', NULL, ''),
(16, 2, 1, 'Gardening Help', 'Need volunteer to help trim plants and tidy garden.', '98, abc road', 'closed', 2, 2, '2025-10-29 06:15:48', NULL, ''),
(17, 2, 1, 'Home Cleaning Support', 'Need assistance in cleaning my home as I have limited mobility.', '21, jalan abc', 'open', 6, 2, '2025-10-29 16:20:29', NULL, ''),
(18, 4, 5, 'afsfs', 'afsaf', 'amk ave 10 401', 'open', 2, 1, '2025-10-30 18:38:53', NULL, ''),
(19, 4, 6, 'fasdfa', 'afsdfa', 'fasdfadsfasdfa', 'open', 0, 1, '2025-10-30 18:56:01', NULL, ''),
(20, 4, 5, 'asfaf', 'fasfasdfasdfa', 'asfafas', 'open', 0, 1, '2025-10-30 18:57:51', NULL, ''),
(21, 4, 5, 'hello', 'afdasfafa', 'amk ave 10 401ffa', 'open', 0, 1, '2025-10-30 18:58:07', NULL, ''),
(24, 4, 6, 'aircon repair', 'asfas', 'amk ave 10 4011', 'open', 0, 1, '2025-10-30 19:21:40', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`category_id`, `category_name`, `created_at`, `updated_at`) VALUES
(2, 'Medical & Health-Related Assistance', '2025-10-30 00:04:39', '2025-10-30 00:04:39'),
(3, 'Transportation Assistance', '2025-10-30 00:04:51', '2025-10-30 00:04:51'),
(4, 'Social & Emotional Support', '2025-10-30 00:05:07', '2025-10-30 00:05:07'),
(5, 'Learning & Digital Assistance', '2025-10-30 00:05:18', '2025-10-30 00:05:18'),
(6, 'Home Repair=', '2025-10-30 00:05:34', '2025-10-31 05:31:56'),
(7, 'moneyy', '2025-10-31 04:37:38', '2025-10-31 04:37:38'),
(8, 'moneyy', '2025-10-31 04:37:42', '2025-10-31 04:37:42');

-- --------------------------------------------------------

--
-- Table structure for table `service_history`
--

CREATE TABLE `service_history` (
  `service_id` int(11) NOT NULL,
  `csr_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `volunteer_id` int(11) DEFAULT NULL,
  `status` enum('completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'completed',
  `completed_at` datetime DEFAULT current_timestamp(),
  `hours_served` decimal(5,2) DEFAULT NULL,
  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_history`
--

INSERT INTO `service_history` (`service_id`, `csr_id`, `request_id`, `volunteer_id`, `status`, `completed_at`, `hours_served`, `remarks`) VALUES
(1, 26, 4, 1, 'completed', '2025-08-05 10:00:00', 2.00, 'Delivered groceries to senior home'),
(2, 26, 5, 2, 'completed', '2025-08-12 15:00:00', 3.50, 'Tutoring session for primary students'),
(3, 26, 6, 3, 'completed', '2025-08-28 09:30:00', 4.00, 'Community garden maintenance'),
(4, 26, 7, 1, 'completed', '2025-09-02 11:00:00', 2.50, 'Meal delivery for elderly'),
(5, 26, 8, 2, 'completed', '2025-09-14 13:00:00', 3.00, 'Assisted in medication pickup'),
(6, 26, 9, 3, 'completed', '2025-09-25 10:30:00', 5.00, 'Helped organize community event'),
(7, 26, 10, 1, 'completed', '2025-10-05 09:00:00', 2.50, 'Transportation to clinic'),
(8, 26, 11, 2, 'completed', '2025-10-15 10:30:00', 3.00, 'Tutoring session at community centre'),
(9, 26, 12, 3, 'completed', '2025-10-22 16:00:00', 4.00, 'Beach cleanup and logistics support'),
(10, 26, 13, 3, 'completed', '2025-10-30 14:00:00', 3.50, 'Helped distribute supplies at event');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `profile_type` varchar(50) NOT NULL,
  `status` enum('active','suspended') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password_hash`, `profile_type`, `status`, `created_at`) VALUES
(1, 'admi@gmail.com', '$2y$10$N6J0X9NJxPKyWJSiZ2F1O.kKI7mn1WcvwGdhijXfcMjpTvyp.n/RG', 'admin', 'suspended', '2025-10-29 07:23:40'),
(2, 'pm@gmail.com', '$2y$10$qHh49t.MsJ3JECRcZRkoyOXklyuzxTxVE3Ic2cnxA6rLuPZKg07Fu', 'platform', 'active', '2025-10-29 07:23:44'),
(3, 'csr@gmail.com', '$2y$10$duD5Zl0xKIwX5FjwkTBajepf4fSLIx7vAuq4o8AK8DrsVsiTxxFtu', 'csr', 'active', '2025-10-29 07:23:49'),
(4, 'pin@gmail.com', '$2y$10$7NB2wX1KwAFvgng4HzV2cOuNRF5RipotcSN6i.OlE/G4qQyVQeq/y', 'pin', 'active', '2025-10-29 07:23:55'),
(5, 'a@gmail.com', '$2y$10$JY6Clcxxcl2WDur716jTUOY2tOZ159XDNKrhngjKSLToQZE79yk.u', 'admin', 'active', '2025-10-29 07:24:43'),
(6, 'csr2@gmail.com', '$2y$10$hlq9kVt5TJrT4XJyGO8CCuh5PCkyuvi.6L/EHW4fAllRC7EVc9cMS', 'csr', 'active', '2025-10-30 18:42:00'),
(7, 'ddd@gmail.com', '$2y$10$Spq8ZWcBpB92Jyk2nuK0lOhAp63q.7gUAHpzksQ9zeM80tk3GPfQG', 'nothin', 'suspended', '2025-10-30 21:35:53'),
(8, 'csr3@gmail.com', '$2y$10$qpSjv8uErJsWs5I2pY6sk.1zob6b/mdF9VJLXIteJYnXx4xID6ehG', 'csr', 'active', '2025-10-31 15:40:08');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL,
  `profile_type` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','suspended') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `profile_type`, `created_at`, `status`) VALUES
(1, 'admin', '2025-10-29 07:17:13', 'active'),
(2, 'csr', '2025-10-29 07:17:13', 'active'),
(3, 'pin', '2025-10-29 07:17:13', 'active'),
(4, 'platform', '2025-10-29 07:17:13', 'active'),
(5, 'nothin', '2025-10-30 21:25:51', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `volunteers`
--

CREATE TABLE `volunteers` (
  `volunteer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `skills` varchar(255) DEFAULT NULL,
  `availability` enum('Full-Time','Part-Time','Occasional') DEFAULT 'Occasional',
  `joined_date` date DEFAULT curdate(),
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `volunteers`
--

INSERT INTO `volunteers` (`volunteer_id`, `name`, `email`, `phone`, `gender`, `skills`, `availability`, `joined_date`, `status`) VALUES
(1, 'Alice Tan', 'alice.tan@example.com', '91234567', 'Female', 'First Aid, Elderly Care', 'Part-Time', '2025-10-29', 'Active'),
(2, 'Ben Ong', 'ben.ong@example.com', '98765432', 'Male', 'Tutoring, Event Support', 'Full-Time', '2025-10-29', 'Active'),
(3, 'Cindy Lee', 'cindy.lee@example.com', '96543218', 'Female', 'Logistics, Public Speaking', 'Occasional', '2025-10-29', 'Active'),
(4, 'David Lim', 'david.lim@example.com', '92345678', 'Male', 'Cooking, Outreach', 'Part-Time', '2025-10-29', 'Inactive');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `csr_shortlist`
--
ALTER TABLE `csr_shortlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `csr_id` (`csr_id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `pin_history`
--
ALTER TABLE `pin_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `fk_pin_history_request_id` (`request_id`),
  ADD KEY `fk_pin_history_volunteer_id` (`volunteer_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `fk_requests_category_id` (`category_id`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `category_name` (`category_name`);

--
-- Indexes for table `service_history`
--
ALTER TABLE `service_history`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `fk_csr_id` (`csr_id`),
  ADD KEY `fk_request_id` (`request_id`),
  ADD KEY `fk_volunteer_id` (`volunteer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `fk_user_profile` (`profile_type`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `profile_type` (`profile_type`);

--
-- Indexes for table `volunteers`
--
ALTER TABLE `volunteers`
  ADD PRIMARY KEY (`volunteer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `csr_shortlist`
--
ALTER TABLE `csr_shortlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `pin_history`
--
ALTER TABLE `pin_history`
  MODIFY `history_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `service_history`
--
ALTER TABLE `service_history`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `volunteers`
--
ALTER TABLE `volunteers`
  MODIFY `volunteer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pin_history`
--
ALTER TABLE `pin_history`
  ADD CONSTRAINT `fk_pin_history_request_id` FOREIGN KEY (`request_id`) REFERENCES `requests` (`request_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pin_history_volunteer_id` FOREIGN KEY (`volunteer_id`) REFERENCES `volunteers` (`volunteer_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_profile` FOREIGN KEY (`profile_type`) REFERENCES `user_profiles` (`profile_type`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
