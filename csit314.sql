-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2025 at 03:22 PM
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
(1, 3, 1, '2025-10-27 14:16:10'),
(2, 3, 2, '2025-10-27 14:16:15'),
(3, 3, 3, '2025-10-27 14:16:16'),
(4, 3, 12, '2025-10-29 01:39:19'),
(5, 3, 10, '2025-10-29 01:42:02'),
(6, 3, 13, '2025-10-29 03:26:43'),
(7, 3, 16, '2025-10-29 06:16:31'),
(8, 3, 14, '2025-10-29 06:16:33'),
(9, 3, 4, '2025-10-29 15:23:01'),
(10, 3, 5, '2025-10-29 15:24:26'),
(11, 3, 17, '2025-10-29 16:42:30'),
(12, 3, 18, '2025-10-30 14:17:04'),
(13, 3, 19, '2025-10-30 16:53:04'),
(14, 5, 19, '2025-10-31 09:25:31'),
(15, 5, 20, '2025-10-31 09:30:25'),
(16, 5, 22, '2025-11-03 15:06:07'),
(17, 5, 21, '2025-11-03 15:06:13'),
(18, 5, 23, '2025-11-03 15:56:30'),
(19, 5, 24, '2025-11-06 08:51:35'),
(20, 7, 24, '2025-11-06 08:52:55'),
(21, 7, 23, '2025-11-06 09:00:29'),
(22, 7, 20, '2025-11-06 09:00:35'),
(23, 7, 18, '2025-11-06 09:30:23'),
(24, 7, 13, '2025-11-06 13:49:26'),
(25, 5, 25, '2025-11-06 15:12:49'),
(26, 7, 25, '2025-11-06 15:37:58'),
(27, 5, 26, '2025-11-07 07:55:13'),
(28, 5, 27, '2025-11-07 08:05:25'),
(29, 5, 61, '2025-11-13 13:59:13'),
(30, 5, 13, '2025-11-13 13:59:28');

-- --------------------------------------------------------

--
-- Table structure for table `pin_history`
--

CREATE TABLE `pin_history` (
  `history_id` int(11) UNSIGNED NOT NULL,
  `request_id` int(11) UNSIGNED NOT NULL,
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
(3, 12, 3, 'completed', '2025-10-25 16:00:00', 'Transport to market', 'Need transport to nearby market for weekly grocery shopping', 2),
(198, 1, 14, 'completed', '2025-10-27 10:00:00', 'Home Assistance', 'Assisted elderly with general household tasks.', 3),
(199, 2, 15, 'completed', '2025-10-27 11:00:00', 'Shopping Help', 'Accompanied elderly for grocery shopping.', 3),
(200, 3, 16, 'completed', '2025-10-27 12:00:00', 'Medical Assistance', 'Helped elderly with medication delivery.', 3),
(201, 4, 17, 'completed', '2025-10-27 13:00:00', 'Tutoring Session', 'Helped child with homework and learning tasks.', 3),
(202, 5, 18, 'completed', '2025-10-28 09:00:00', 'Food Delivery', 'Delivered food to elderly home.', 3),
(203, 6, 19, 'completed', '2025-10-28 10:00:00', 'Gardening', 'Helped maintain community garden.', 3),
(204, 7, 20, 'completed', '2025-10-28 11:00:00', 'Transport Assistance', 'Transported elderly to clinic for check-up.', 3),
(205, 8, 21, 'completed', '2025-10-28 12:00:00', 'Tutoring', 'Tutored child for primary school math.', 3),
(206, 9, 22, 'completed', '2025-10-29 09:30:00', 'Community Event', 'Participated in local community outreach program.', 3),
(207, 10, 23, 'completed', '2025-10-29 10:00:00', 'Pet Care', 'Took care of pet while owner was away.', 3),
(228, 1, 1, 'completed', '2025-10-15 10:30:00', 'Grocery Shopping Help', 'Require help to buy groceries and household items for elderly user.', 2),
(229, 2, 2, 'completed', '2025-10-20 14:00:00', 'Gardening Help', 'Need volunteer to help trim plants and tidy garden space.', 2),
(230, 3, 3, 'completed', '2025-10-25 16:00:00', 'Transport to market', 'Need transport to nearby market for weekly grocery shopping.', 2),
(231, 4, 4, 'completed', '2025-10-27 10:00:00', 'Home Assistance', 'Assisted elderly with general household tasks.', 2),
(232, 5, 5, 'completed', '2025-10-27 11:00:00', 'Shopping Help', 'Accompanied elderly for grocery shopping.', 3),
(233, 6, 6, 'completed', '2025-10-27 12:00:00', 'Medical Assistance', 'Helped elderly with medication delivery.', 2),
(234, 7, 7, 'completed', '2025-10-27 13:00:00', 'Tutoring', 'Helped child with homework and school lessons.', 2),
(235, 8, 8, 'completed', '2025-10-28 09:00:00', 'Food Delivery', 'Delivered food to elderly home.', 3),
(236, 9, 9, 'completed', '2025-10-28 10:00:00', 'Gardening', 'Helped maintain community garden.', 3),
(237, 10, 10, 'completed', '2025-10-28 11:00:00', 'Transport Assistance', 'Transported elderly to clinic for check-up.', 3),
(293, 1, 24, 'completed', '2025-10-29 12:00:00', 'Grocery Shopping', 'Helped elderly with weekly grocery shopping.', 3),
(294, 2, 25, 'completed', '2025-10-29 13:00:00', 'House Cleaning', 'Assisted elderly with house cleaning tasks.', 3),
(295, 3, 26, 'completed', '2025-10-29 14:00:00', 'Transportation to Hospital', 'Transported elderly for medical checkup.', 3),
(296, 4, 27, 'completed', '2025-10-29 15:00:00', 'Tutoring', 'Helped child with homework and school lessons.', 3),
(297, 5, 28, 'completed', '2025-10-29 16:00:00', 'Pet Care', 'Took care of pet while owner was away.', 3),
(298, 6, 29, 'completed', '2025-10-29 17:00:00', 'Gardening Help', 'Assisted with trimming plants and maintaining garden.', 3),
(299, 7, 30, 'completed', '2025-10-29 18:00:00', 'Meal Delivery', 'Delivered meals to elderly home.', 3),
(300, 8, 31, 'completed', '2025-10-29 19:00:00', 'Transport Assistance', 'Transported elderly to medical appointment.', 3),
(301, 9, 32, 'completed', '2025-10-29 20:00:00', 'Community Event', 'Helped organize and set up for a community event.', 3),
(302, 10, 33, 'completed', '2025-10-29 21:00:00', 'Shopping Help', 'Accompanied elderly for grocery shopping and errands.', 3);

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `request_id` int(11) UNSIGNED NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(80) NOT NULL,
  `content` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `status` enum('open','in_progress','closed') NOT NULL DEFAULT 'open',
  `view_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `shortlist_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`request_id`, `category_id`, `user_id`, `title`, `content`, `location`, `status`, `view_count`, `shortlist_count`, `created_at`) VALUES
(1, NULL, 25, 'Wheelchair Assistance', 'Need help pushing wheelchair to community centre', 'Blk 123 Jurong West Ave 4', 'open', 1, 0, '2025-10-27 13:50:46'),
(2, NULL, 26, 'Grocery Delivery', 'Elderly needs groceries delivered weekly', 'Tampines Street 91', 'open', 0, 0, '2025-10-27 13:50:46'),
(3, NULL, 27, 'IT Support', 'Help setting up laptop for online learning', 'Yishun Ring Road', 'in_progress', 0, 0, '2025-10-27 13:50:46'),
(4, NULL, 28, 'Tutoring', 'Looking for volunteer to teach Primary 3 Maths', 'Hougang Ave 2', 'open', 0, 1, '2025-10-27 13:50:46'),
(5, NULL, 29, 'Gardening', 'Help needed to maintain community garden plot', 'Pasir Ris Drive 10', 'closed', 0, 1, '2025-10-27 13:50:46'),
(6, NULL, 30, 'House Cleaning', 'Single mother needs assistance cleaning flat', 'Bukit Batok Street 22', 'open', 0, 0, '2025-10-27 13:50:46'),
(7, NULL, 31, 'Pet Care', 'Temporary foster care for elderly cat', 'Sengkang East Ave', 'open', 0, 0, '2025-10-27 13:50:46'),
(8, NULL, 32, 'Meal Delivery', 'Assist in delivering meals to senior residents', 'Woodlands Ave 9', 'in_progress', 0, 0, '2025-10-27 13:50:46'),
(9, NULL, 33, 'Medication Pickup', 'Pickup and deliver medicine from polyclinic', 'Serangoon North Ave 1', 'open', 0, 0, '2025-10-27 13:50:46'),
(10, NULL, 34, 'Repair Work', 'Help repair broken cabinet door', 'Clementi Ave 5', 'closed', 0, 0, '2025-10-27 13:50:46'),
(12, NULL, 2, 'Transport to market', 'Need transport to nearby market for weekly grocery shopping.', 'Blk 123', 'closed', 4, 0, '2025-10-28 14:47:53'),
(13, NULL, 2, 'Smartphone Setup Help', 'Need volunteer to help set up mobile phone and WhatsApp.', 'Blk 222', 'open', 2, 3, '2025-10-29 02:02:54'),
(14, NULL, 2, 'Grocery Shopping Help', 'Require help to buy groceries and household items once a week.', 'Blk 111', 'closed', 1, 1, '2025-10-29 06:12:42'),
(16, 1, 2, 'Gardening Help', 'Need volunteer to help trim plants and tidy garden area.', '98, abc road', 'closed', 1, 1, '2025-10-29 06:15:48'),
(17, 1, 2, 'Home Cleaning Support', 'Need assistance in cleaning my home as I have limited mobility.', '21, jalan abc', 'open', 2, 1, '2025-10-29 16:20:29'),
(18, 5, 2, 'Basic Computer Lessons', 'Want to learn how to use email and Zoom for online communication.', '88, abc st', 'open', 1, 2, '2025-10-30 14:16:31'),
(19, 6, 2, 'Install Safety Grab Bars', 'Need volunteer to assist in installing grab bars in toilet for safety.', '123', 'open', 2, 2, '2025-10-30 16:52:34'),
(20, 3, 2, 'Transportation to hospital', 'Need help to transport me to hospital for check up.', '323', 'open', 4, 2, '2025-10-31 09:28:36'),
(21, 6, 6, 'Install safety handle', 'Need volunteer to help me to install safety bar around the house.', '12, abc', 'open', 1, 1, '2025-11-03 15:03:20'),
(22, 3, 6, 'Transport to market', 'Need someone to drive me to market.', '123', 'open', 2, 1, '2025-11-03 15:05:11'),
(23, 5, 2, 'Chatgpt lesson', 'Need someone to teach me how to use chatgpt.', 'amoy street', 'open', 4, 2, '2025-11-03 15:55:35'),
(26, 2, 2, 'Physiotherapy Support', 'Looking for someone to accompany me during my physiotherapy sessions twice a week', 'street x', 'open', 1, 1, '2025-11-07 07:53:40'),
(27, 2, 6, 'Wheelchair Donation', 'Need a wheelchair due to mobility issues after surgery', '1, street 2', 'open', 2, 1, '2025-11-07 08:03:53'),
(29, 4, 2, 'Reading Companion', 'Need someone to read newspapers and letters to me due to poor eyesight', 'Jurong East', 'open', 0, 0, '2025-11-09 16:27:39'),
(50, 5, 30, 'Basic Computer Lessons', 'Want to learn how to use email and Zoom for online meetings', '88, abc st', 'open', 1, 2, '2025-10-30 14:16:31'),
(51, 6, 31, 'Install Safety Grab Bars', 'Need volunteer to assist in installing grab bars in bathroom', '123', 'open', 2, 2, '2025-10-30 16:52:34'),
(52, 3, 32, 'Transportation to hospital', 'Need help to transport me to hospital for check up', '323', 'open', 4, 2, '2025-10-31 09:28:36'),
(53, 6, 33, 'Install safety handle', 'Need volunteer to help me to install safety bar at home', '12, abc', 'open', 1, 1, '2025-11-03 15:03:20'),
(54, 3, 34, 'Transport to market', 'Need someone to drive me to market', '123', 'open', 2, 1, '2025-11-03 15:05:11'),
(55, 5, 35, 'Chatgpt lesson', 'Need someone to teach me how to use chatgpt', 'amoy street', 'open', 4, 2, '2025-11-03 15:55:35'),
(56, 2, 36, 'Physiotherapy Support', 'Looking for someone to accompany me during my physiotherapy sessions', 'street x', 'open', 1, 1, '2025-11-07 07:53:40'),
(57, 6, 37, 'Wheelchair Donation', 'Need a wheelchair due to mobility issues after surgery', '1, street 2', 'open', 2, 1, '2025-11-07 08:03:53'),
(58, 4, 38, 'Reading Companion', 'Need someone to read newspapers and letters to me', 'Jurong East', 'open', 0, 0, '2025-11-09 16:27:39'),
(59, 2, 39, 'Food Delivery', 'Need food delivered to elderly parents living in Bukit Panjang', 'Bukit Panjang St 21', 'open', 0, 0, '2025-11-10 05:30:00'),
(60, 5, 40, 'Basic Computer Lessons', 'Looking for someone to teach me basic computer skills', 'Serangoon Road', 'open', 2, 0, '2025-11-10 05:40:00'),
(61, 1, 41, 'Wheelchair Assistance', 'Need help getting to the park with my wheelchair', 'Bedok North', 'open', 1, 3, '2025-11-10 06:10:00'),
(62, 3, 42, 'Transport to Hospital', 'Need help to transport my child to hospital for a checkup', 'Ang Mo Kio Ave 3', 'open', 0, 0, '2025-11-11 01:20:00'),
(63, 4, 43, 'Tutoring', 'Looking for a tutor for my 7-year-old in English and Math', 'Jurong West Ave 5', 'open', 1, 1, '2025-11-11 01:45:00'),
(64, 6, 44, 'Home Repair', 'Help needed with fixing a broken faucet in the kitchen', 'Queen Street', 'in_progress', 0, 0, '2025-11-11 02:00:00'),
(65, 2, 45, 'Medical Assistance', 'Looking for someone to help with medication reminders', 'Tampines Ave 8', 'open', 0, 0, '2025-11-11 02:30:00'),
(66, 5, 46, 'Digital Literacy', 'Need assistance to set up a digital library account', 'Bukit Timah Road', 'open', 1, 1, '2025-11-12 03:15:00'),
(67, 3, 47, 'Transport for Elderly', 'Transport my elderly mother for medical appointments', 'Yishun Ring Road', 'open', 0, 0, '2025-11-12 04:00:00'),
(68, 6, 48, 'Install Safety Features', 'Need help installing grab bars and safety handles in the bathroom', 'Clementi Road', 'open', 1, 1, '2025-11-12 05:30:00'),
(69, 4, 49, 'Cooking Lessons', 'I am looking for someone to teach me basic cooking skills', 'Pasir Ris Drive 3', 'open', 0, 0, '2025-11-12 06:00:00'),
(70, 2, 50, 'Medical Escort', 'Seeking someone to escort my father to his medical appointments', 'Kallang Road', 'open', 1, 0, '2025-11-13 02:15:00'),
(71, 1, 51, 'Elderly Care', 'Need assistance with caring for my elderly mother', 'Novena Rise', 'in_progress', 0, 1, '2025-11-13 02:30:00'),
(72, 6, 52, 'Home Renovation', 'Help needed with minor home renovation tasks', 'Thomson Road', 'open', 0, 0, '2025-11-13 03:45:00'),
(73, 4, 53, 'Dance Lessons', 'Looking for dance lessons for a 10-year-old child', 'Tiong Bahru Road', 'open', 0, 0, '2025-11-13 04:00:00'),
(74, 2, 54, 'Health Screening', 'Help needed for health screening for elderly', 'Changi Road', 'closed', 0, 2, '2025-11-13 06:00:00'),
(75, 5, 55, 'Job Search Assistance', 'Need help creating a resume and applying for jobs online', 'Hougang Street 21', 'open', 1, 1, '2025-11-14 01:00:00'),
(76, 3, 56, 'Elderly Transportation', 'Need transportation for my elderly mother to doctor’s appointment', 'Bukit Batok Street 25', 'open', 0, 0, '2025-11-14 02:00:00'),
(77, 1, 57, 'Wheelchair Assistance', 'Assistance required to get in and out of wheelchair', 'Bedok Reservoir', 'open', 1, 0, '2025-11-14 02:30:00'),
(78, 6, 58, 'Window Repair', 'Assistance with repairing a broken window', 'Yishun Ave 7', 'open', 2, 1, '2025-11-14 03:15:00'),
(79, 4, 59, 'Education Assistance', 'Looking for volunteer to tutor a child in science and math', 'Bukit Panjang St 12', 'in_progress', 0, 0, '2025-11-14 03:45:00'),
(80, 2, 60, 'Elderly Home Care', 'Looking for assistance with my elderly father', 'Serangoon Ave 3', 'closed', 0, 0, '2025-11-14 04:00:00'),
(81, 1, 61, 'Community Support', 'Looking for volunteer to assist with home repairs for seniors', 'Tampines North Drive', 'open', 0, 0, '2025-11-15 05:00:00'),
(82, 5, 62, 'Online Learning', 'Seeking assistance with learning Microsoft Office online', 'Bedok South', 'open', 1, 1, '2025-11-15 06:30:00'),
(83, 3, 63, 'Grocery Shopping', 'Need help with weekly grocery shopping', 'East Coast Road', 'closed', 0, 2, '2025-11-15 08:00:00'),
(84, 6, 64, 'Home Repair', 'Assistance required to fix the leaking roof', 'Jalan Bukit Merah', 'in_progress', 1, 0, '2025-11-15 08:30:00'),
(85, 2, 65, 'Health Assistance', 'Looking for someone to help with medical transportation and care', 'Sembawang Road', 'open', 2, 0, '2025-11-16 01:30:00'),
(86, 5, 66, 'Child Care Assistance', 'Looking for babysitter to care for child in the evenings', 'Woodlands Ave 8', 'closed', 1, 0, '2025-11-16 02:00:00'),
(87, 3, 67, 'Transportation Help', 'Need assistance with transportation to and from the airport', 'Tuas', 'in_progress', 0, 1, '2025-11-16 02:30:00'),
(88, 4, 68, 'Gardening', 'Help needed for gardening and lawn care in my home', 'Ang Mo Kio Ave 5', 'open', 1, 0, '2025-11-16 03:00:00'),
(89, 2, 69, 'Assisted Living', 'Looking for assisted living arrangements for elderly relative', 'Paya Lebar Crescent', 'open', 0, 1, '2025-11-16 04:00:00'),
(90, 5, 70, 'Education Assistance', 'Looking for a tutor for my son in English and math', 'Pasir Ris St 21', 'in_progress', 0, 0, '2025-11-16 04:45:00'),
(91, 1, 71, 'Wheelchair Assistance', 'Assistance needed to go to a wedding for elderly relative', 'Toa Payoh West', 'open', 2, 0, '2025-11-16 05:00:00'),
(308, 94, 6, 'help me', 'test', 'block 21', 'open', 0, 0, '2025-11-13 13:57:29');

-- --------------------------------------------------------

--
-- Table structure for table `request_shortlists`
--

CREATE TABLE `request_shortlists` (
  `request_id` int(10) UNSIGNED NOT NULL,
  `csr_id` int(10) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`category_id`, `category_name`, `created_at`, `updated_at`) VALUES
(1, 'Daily Living Assistance', '2025-10-30 00:04:25', '2025-10-30 00:04:25'),
(2, 'Medical & Health-Related Assistance', '2025-10-30 00:04:39', '2025-10-30 00:04:39'),
(3, 'Transportation Assistance', '2025-10-30 00:04:51', '2025-10-30 00:04:51'),
(4, 'Social & Emotional Support', '2025-10-30 00:05:07', '2025-10-30 00:05:07'),
(5, 'Learning & Digital Assistance', '2025-10-30 00:05:18', '2025-10-30 00:05:18'),
(6, 'Home Repair / Safety', '2025-10-30 00:05:34', '2025-10-30 00:05:34'),
(11, 'others', '2025-11-09 15:48:29', '2025-11-09 15:48:29'),
(12, 'Childcare & Education', '2025-11-10 12:00:00', '2025-11-10 12:00:00'),
(13, 'Elderly Assistance', '2025-11-10 12:15:00', '2025-11-10 12:15:00'),
(14, 'Environmental Sustainability', '2025-11-10 12:30:00', '2025-11-10 12:30:00'),
(15, 'Animal Care & Welfare', '2025-11-10 12:45:00', '2025-11-10 12:45:00'),
(16, 'Technology & Digital Literacy', '2025-11-10 13:00:00', '2025-11-10 13:00:00'),
(17, 'Mental Health & Well-being', '2025-11-10 13:15:00', '2025-11-10 13:15:00'),
(18, 'Community Outreach & Engagement', '2025-11-10 13:30:00', '2025-11-10 13:30:00'),
(19, 'Transportation & Mobility', '2025-11-10 13:45:00', '2025-11-10 13:45:00'),
(20, 'Food Assistance', '2025-11-10 14:00:00', '2025-11-10 14:00:00'),
(21, 'Housing & Shelter', '2025-11-10 14:15:00', '2025-11-10 14:15:00'),
(22, 'Disability Support', '2025-11-10 14:30:00', '2025-11-10 14:30:00'),
(23, 'Workplace & Career Assistance', '2025-11-10 14:45:00', '2025-11-10 14:45:00'),
(24, 'Legal Aid & Advice', '2025-11-10 15:00:00', '2025-11-10 15:00:00'),
(25, 'Crisis Management & Counseling', '2025-11-10 15:15:00', '2025-11-10 15:15:00'),
(26, 'Educational Assistance', '2025-11-10 15:30:00', '2025-11-10 15:30:00'),
(27, 'Youth Development', '2025-11-10 15:45:00', '2025-11-10 15:45:00'),
(28, 'Sports & Recreation', '2025-11-10 16:00:00', '2025-11-10 16:00:00'),
(29, 'Community Building & Support', '2025-11-10 16:15:00', '2025-11-10 16:15:00'),
(30, 'Public Health & Safety', '2025-11-10 16:30:00', '2025-11-10 16:30:00'),
(31, 'Cultural & Arts Support', '2025-11-10 16:45:00', '2025-11-10 16:45:00'),
(32, 'Disaster Relief & Recovery', '2025-11-10 17:00:00', '2025-11-10 17:00:00'),
(33, 'Social Advocacy & Rights', '2025-11-10 17:15:00', '2025-11-10 17:15:00'),
(34, 'Rehabilitation & Reintegration', '2025-11-10 17:30:00', '2025-11-10 17:30:00'),
(35, 'Financial Literacy & Support', '2025-11-10 17:45:00', '2025-11-10 17:45:00'),
(36, 'Family Support & Counseling', '2025-11-10 18:00:00', '2025-11-10 18:00:00'),
(37, 'Environmental Conservation', '2025-11-10 18:15:00', '2025-11-10 18:15:00'),
(38, 'Veteran Support & Assistance', '2025-11-10 18:30:00', '2025-11-10 18:30:00'),
(39, 'Multicultural Services', '2025-11-10 18:45:00', '2025-11-10 18:45:00'),
(40, 'Refugee & Immigrant Support', '2025-11-10 19:00:00', '2025-11-10 19:00:00'),
(41, 'Public Transportation & Infrastructure', '2025-11-10 19:15:00', '2025-11-10 19:15:00'),
(42, 'Civic Engagement & Volunteering', '2025-11-10 19:30:00', '2025-11-10 19:30:00'),
(43, 'Elderly Mobility & Assistance', '2025-11-10 19:45:00', '2025-11-10 19:45:00'),
(44, 'Technology for Social Good', '2025-11-10 20:00:00', '2025-11-10 20:00:00'),
(45, 'Affordable Housing Solutions', '2025-11-10 20:15:00', '2025-11-10 20:15:00'),
(46, 'Support for the Disabled', '2025-11-10 20:30:00', '2025-11-10 20:30:00'),
(47, 'Sustainable Energy Initiatives', '2025-11-10 20:45:00', '2025-11-10 20:45:00'),
(48, 'Neighborhood Watch & Security', '2025-11-10 21:00:00', '2025-11-10 21:00:00'),
(49, 'Recycling & Waste Management', '2025-11-10 21:15:00', '2025-11-10 21:15:00'),
(50, 'Community Health Initiatives', '2025-11-10 21:30:00', '2025-11-10 21:30:00'),
(51, 'Community Support Programs', '2025-11-10 21:45:00', '2025-11-10 21:45:00'),
(52, 'Recreational Activities for Youth', '2025-11-10 22:00:00', '2025-11-10 22:00:00'),
(53, 'Senior Citizen Housing Support', '2025-11-10 22:15:00', '2025-11-10 22:15:00'),
(54, 'Access to Financial Assistance', '2025-11-10 22:30:00', '2025-11-10 22:30:00'),
(55, 'Water Conservation Programs', '2025-11-10 22:45:00', '2025-11-10 22:45:00'),
(56, 'Employment Support for Disabled', '2025-11-10 23:00:00', '2025-11-10 23:00:00'),
(57, 'Child Abuse Prevention & Support', '2025-11-10 23:15:00', '2025-11-10 23:15:00'),
(58, 'Youth Empowerment Initiatives', '2025-11-10 23:30:00', '2025-11-10 23:30:00'),
(59, 'Language Learning & Development', '2025-11-10 23:45:00', '2025-11-10 23:45:00'),
(60, 'Mental Health First Aid', '2025-11-11 00:00:00', '2025-11-11 00:00:00'),
(61, 'Disaster Preparedness', '2025-11-11 00:15:00', '2025-11-11 00:15:00'),
(62, 'Community Center Activities', '2025-11-11 00:30:00', '2025-11-11 00:30:00'),
(63, 'Sustainable Living Practices', '2025-11-11 00:45:00', '2025-11-11 00:45:00'),
(64, 'Emergency Response Services', '2025-11-11 01:00:00', '2025-11-11 01:00:00'),
(65, 'Elderly Care Assistance', '2025-11-11 01:15:00', '2025-11-11 01:15:00'),
(66, 'Entrepreneurship Programs', '2025-11-11 01:30:00', '2025-11-11 01:30:00'),
(67, 'Health & Fitness Programs', '2025-11-11 01:45:00', '2025-11-11 01:45:00'),
(68, 'Social Inclusion Projects', '2025-11-11 02:00:00', '2025-11-11 02:00:00'),
(69, 'Volunteering & Service Opportunities', '2025-11-11 02:15:00', '2025-11-11 02:15:00'),
(70, 'Crisis Hotline & Counseling', '2025-11-11 02:30:00', '2025-11-11 02:30:00'),
(71, 'Technology Access & Education', '2025-11-11 02:45:00', '2025-11-11 02:45:00'),
(72, 'Financial Literacy & Counseling', '2025-11-11 03:00:00', '2025-11-11 03:00:00'),
(73, 'Disaster Relief & Community Support', '2025-11-11 03:15:00', '2025-11-11 03:15:00'),
(74, 'Public Safety Initiatives', '2025-11-11 03:30:00', '2025-11-11 03:30:00'),
(75, 'Environmental Education & Awareness', '2025-11-11 03:45:00', '2025-11-11 03:45:00'),
(76, 'Rehabilitation Programs for Ex-offenders', '2025-11-11 04:00:00', '2025-11-11 04:00:00'),
(77, 'Homeless Support & Outreach', '2025-11-11 04:15:00', '2025-11-11 04:15:00'),
(78, 'Employment Programs for Disadvantaged Groups', '2025-11-11 04:30:00', '2025-11-11 04:30:00'),
(79, 'Cultural Exchange Programs', '2025-11-11 04:45:00', '2025-11-11 04:45:00'),
(80, 'Public Transportation Accessibility', '2025-11-11 05:00:00', '2025-11-11 05:00:00'),
(81, 'Senior Workforce Employment', '2025-11-11 05:15:00', '2025-11-11 05:15:00'),
(82, 'LGBTQ+ Support Services', '2025-11-11 05:30:00', '2025-11-11 05:30:00'),
(83, 'Student Assistance & Mentoring', '2025-11-11 05:45:00', '2025-11-11 05:45:00'),
(84, 'Women’s Empowerment Programs', '2025-11-11 06:00:00', '2025-11-11 06:00:00'),
(85, 'Senior Social Engagement', '2025-11-11 06:15:00', '2025-11-11 06:15:00'),
(86, 'Family Preservation & Support', '2025-11-11 06:30:00', '2025-11-11 06:30:00'),
(87, 'Reproductive Health Support', '2025-11-11 06:45:00', '2025-11-11 06:45:00'),
(88, 'Affordable Education for All', '2025-11-11 07:00:00', '2025-11-11 07:00:00'),
(89, 'Intergenerational Programs', '2025-11-11 07:15:00', '2025-11-11 07:15:00'),
(90, 'Legal Protection for Minorities', '2025-11-11 07:30:00', '2025-11-11 07:30:00'),
(91, 'Refugee & Asylum Seeker Support', '2025-11-11 07:45:00', '2025-11-11 07:45:00'),
(92, 'Sustainable Agriculture & Food Security', '2025-11-11 08:00:00', '2025-11-11 08:00:00'),
(93, 'Civic Rights Education', '2025-11-11 08:15:00', '2025-11-11 08:15:00'),
(94, 'Affordable Childcare Services', '2025-11-11 08:30:00', '2025-11-11 08:30:00'),
(95, 'International Aid & Development', '2025-11-11 08:45:00', '2025-11-11 08:45:00'),
(96, 'Emergency Housing Assistance', '2025-11-11 09:00:00', '2025-11-11 09:00:00'),
(97, 'Youth Sports & Mentorship', '2025-11-11 09:15:00', '2025-11-11 09:15:00'),
(98, 'Immigrant Integration & Support', '2025-11-11 09:30:00', '2025-11-11 09:30:00'),
(99, 'Affordable Housing Programs', '2025-11-11 09:45:00', '2025-11-11 09:45:00'),
(100, 'Technology Solutions for Seniors', '2025-11-11 10:00:00', '2025-11-11 10:00:00'),
(101, 'Emergency Relief & Assistance', '2025-11-11 10:15:00', '2025-11-11 10:15:00'),
(102, 'Public Health Education', '2025-11-11 10:30:00', '2025-11-11 10:30:00'),
(103, 'Elderly Wellness & Healthcare', '2025-11-11 10:45:00', '2025-11-11 10:45:00'),
(104, 'Disability Employment Support', '2025-11-11 11:00:00', '2025-11-11 11:00:00'),
(105, 'Community Economic Development', '2025-11-11 11:15:00', '2025-11-11 11:15:00'),
(106, 'Daily Living Assistance', '2025-11-13 22:03:00', '2025-11-13 22:03:00'),
(107, 'Test 2', '2025-11-13 22:03:38', '2025-11-13 22:04:28');

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
(1, 26, 1, 1, 'completed', '2025-10-15 10:30:00', 2.50, 'Helped with elderly distribution'),
(2, 26, 2, 2, 'completed', '2025-10-20 14:00:00', 3.00, 'Tutoring session at community centre'),
(3, 26, 3, 3, 'completed', '2025-10-25 16:00:00', 4.00, 'Beach cleanup and logistics support'),
(4, 26, 4, 1, 'completed', '2025-08-05 10:00:00', 2.00, 'Delivered groceries to senior home'),
(5, 26, 5, 2, 'completed', '2025-08-12 15:00:00', 3.50, 'Tutoring session for primary students'),
(6, 26, 6, 3, 'completed', '2025-08-28 09:30:00', 4.00, 'Community garden maintenance'),
(7, 26, 7, 1, 'completed', '2025-09-02 11:00:00', 2.50, 'Meal delivery for elderly'),
(8, 26, 8, 2, 'completed', '2025-09-14 13:00:00', 3.00, 'Assisted in medication pickup'),
(9, 26, 9, 3, 'completed', '2025-09-25 10:30:00', 5.00, 'Helped organize community event'),
(10, 26, 10, 1, 'completed', '2025-10-05 09:00:00', 2.50, 'Transportation to clinic'),
(11, 26, 11, 2, 'completed', '2025-10-15 10:30:00', 3.00, 'Tutoring session at community centre'),
(12, 26, 12, 3, 'completed', '2025-10-22 16:00:00', 4.00, 'Beach cleanup and logistics support'),
(13, 26, 13, 3, 'completed', '2025-10-30 14:00:00', 3.50, 'Helped distribute supplies at event'),
(14, 26, 1, 1, 'completed', '2025-10-15 10:30:00', 2.50, 'Helped with elderly distribution'),
(15, 26, 2, 2, 'completed', '2025-10-20 14:00:00', 3.00, 'Tutoring session at community centre'),
(16, 26, 3, 3, 'completed', '2025-10-25 16:00:00', 4.00, 'Beach cleanup and logistics support'),
(17, 26, 4, 4, 'completed', '2025-08-05 10:00:00', 2.00, 'Delivered groceries to senior home'),
(18, 26, 5, 5, 'completed', '2025-08-12 15:00:00', 3.50, 'Tutoring session for primary students'),
(19, 26, 6, 6, 'completed', '2025-08-28 09:30:00', 4.00, 'Community garden maintenance'),
(20, 26, 7, 7, 'completed', '2025-09-02 11:00:00', 2.50, 'Meal delivery for elderly'),
(21, 26, 8, 8, 'completed', '2025-09-14 13:00:00', 3.00, 'Assisted in medication pickup'),
(22, 26, 9, 9, 'completed', '2025-09-25 10:30:00', 5.00, 'Helped organize community event'),
(23, 26, 10, 10, 'completed', '2025-10-05 09:00:00', 2.50, 'Transportation to clinic'),
(24, 26, 11, 11, 'completed', '2025-10-15 10:30:00', 3.00, 'Tutoring session at community centre'),
(25, 26, 12, 12, 'completed', '2025-10-22 16:00:00', 4.00, 'Beach cleanup and logistics support'),
(26, 26, 13, 13, 'completed', '2025-10-30 14:00:00', 3.50, 'Helped distribute supplies at event'),
(27, 26, 14, 14, 'completed', '2025-11-05 09:30:00', 3.00, 'Tutoring session for primary school'),
(28, 26, 15, 15, 'completed', '2025-11-06 13:00:00', 2.50, 'Assisted with community fundraiser'),
(29, 26, 16, 16, 'completed', '2025-11-07 10:30:00', 4.00, 'Environment cleaning and waste management'),
(30, 26, 17, 17, 'completed', '2025-11-08 15:00:00', 3.50, 'Youth mentorship session'),
(31, 26, 18, 18, 'completed', '2025-11-09 14:00:00', 3.00, 'Tutoring for senior citizen education'),
(32, 26, 19, 19, 'completed', '2025-11-10 11:00:00', 4.00, 'Mental health awareness session'),
(33, 26, 20, 20, 'completed', '2025-11-11 12:00:00', 2.50, 'Distribute food packages to low-income families'),
(34, 26, 21, 21, 'completed', '2025-11-12 13:00:00', 4.00, 'Health check-up for elderly'),
(35, 26, 22, 22, 'completed', '2025-11-13 10:30:00', 3.50, 'Assisted with setting up community event'),
(36, 26, 23, 23, 'completed', '2025-11-14 15:00:00', 2.00, 'Mentoring session for troubled youth'),
(37, 26, 24, 24, 'completed', '2025-11-15 11:30:00', 5.00, 'Community outreach and social assistance'),
(38, 26, 25, 25, 'completed', '2025-11-16 09:00:00', 4.00, 'Helped with job placement for underprivileged'),
(39, 26, 26, 26, 'completed', '2025-11-17 13:00:00', 3.00, 'Tutoring session for struggling students'),
(40, 26, 27, 27, 'completed', '2025-11-18 14:30:00', 4.50, 'Youth sports event coordination'),
(41, 26, 28, 28, 'completed', '2025-11-19 16:00:00', 3.50, 'Rehabilitation program for ex-offenders'),
(42, 26, 29, 29, 'completed', '2025-11-20 12:00:00', 4.00, 'Public health awareness campaign'),
(43, 26, 30, 30, 'completed', '2025-11-21 14:30:00', 3.00, 'Food drive for homeless shelters'),
(44, 26, 31, 31, 'completed', '2025-11-22 11:30:00', 5.00, 'Youth volunteer program for social work'),
(45, 26, 32, 32, 'completed', '2025-11-23 10:30:00', 2.50, 'Assisted with organizing charity marathon'),
(46, 26, 33, 33, 'completed', '2025-11-24 13:00:00', 3.00, 'Taught financial literacy to community members'),
(47, 26, 34, 34, 'completed', '2025-11-25 09:00:00', 4.00, 'Assisted elderly with mobility support'),
(48, 26, 35, 35, 'completed', '2025-11-26 12:00:00', 4.50, 'Elderly care and transportation support'),
(49, 26, 36, 36, 'completed', '2025-11-27 10:30:00', 2.50, 'Assisted with packing food supplies'),
(50, 26, 37, 37, 'completed', '2025-11-28 11:00:00', 4.00, 'Conducted health screening for community members'),
(51, 26, 38, 38, 'completed', '2025-11-29 14:00:00', 3.50, 'Organized youth mentorship event'),
(52, 26, 39, 39, 'completed', '2025-11-30 13:00:00', 3.00, 'Assisted in disaster relief efforts'),
(53, 26, 40, 40, 'completed', '2025-12-01 12:30:00', 5.00, 'Coordinated community clean-up event'),
(54, 26, 41, 41, 'completed', '2025-12-02 10:00:00', 2.50, 'Distributed hygiene kits to local shelters'),
(55, 26, 42, 42, 'completed', '2025-12-03 14:00:00', 3.00, 'Tutoring session for disadvantaged students'),
(56, 26, 43, 43, 'completed', '2025-12-04 15:30:00', 4.50, 'Helped set up community outreach booth'),
(57, 26, 44, 44, 'completed', '2025-12-05 10:00:00', 3.00, 'Assisted with elderly care and mobility support'),
(58, 26, 45, 45, 'completed', '2025-12-06 12:30:00', 4.00, 'Helped organize charity run event'),
(59, 26, 46, 46, 'completed', '2025-12-07 11:00:00', 2.50, 'Set up job placement assistance booth'),
(60, 26, 47, 47, 'completed', '2025-12-08 10:30:00', 3.00, 'Taught digital literacy to senior citizens'),
(61, 26, 48, 48, 'completed', '2025-12-09 14:00:00', 4.00, 'Helped distribute clothes at donation drive'),
(62, 26, 49, 49, 'completed', '2025-12-10 15:30:00', 3.50, 'Organized youth development workshop'),
(63, 26, 50, 50, 'completed', '2025-12-11 10:00:00', 2.00, 'Managed registration for community event'),
(64, 26, 51, 51, 'completed', '2025-12-12 13:00:00', 4.00, 'Distributed flyers for community outreach'),
(65, 26, 52, 52, 'completed', '2025-12-13 10:00:00', 3.50, 'Helped organize virtual workshop for teens'),
(66, 26, 53, 53, 'completed', '2025-12-14 14:00:00', 2.50, 'Prepared meals for low-income families'),
(67, 26, 54, 54, 'completed', '2025-12-15 16:00:00', 3.00, 'Provided transportation for senior citizens'),
(68, 26, 55, 55, 'completed', '2025-12-16 13:00:00', 4.00, 'Assisted with setting up health awareness booth'),
(69, 26, 56, 56, 'completed', '2025-12-17 12:30:00', 2.50, 'Led outdoor clean-up initiative'),
(70, 26, 57, 57, 'completed', '2025-12-18 10:30:00', 3.00, 'Helped organize food distribution event'),
(71, 26, 58, 58, 'completed', '2025-12-19 14:00:00', 5.00, 'Mentored underprivileged youth at community centre'),
(72, 26, 59, 59, 'completed', '2025-12-20 11:00:00', 3.50, 'Assisted in community healthcare event'),
(73, 26, 60, 60, 'completed', '2025-12-21 10:00:00', 4.00, 'Distributed essential items for low-income families'),
(74, 26, 61, 61, 'completed', '2025-12-22 13:00:00', 3.00, 'Organized volunteer orientation for new recruits'),
(75, 26, 62, 62, 'completed', '2025-12-23 14:30:00', 2.50, 'Provided mentoring session for school children'),
(76, 26, 63, 63, 'completed', '2025-12-24 10:30:00', 4.00, 'Helped facilitate group therapy session for youth'),
(77, 26, 64, 64, 'completed', '2025-12-25 12:00:00', 3.50, 'Supported medical assistance for senior citizens'),
(78, 26, 65, 65, 'completed', '2025-12-26 11:30:00', 2.50, 'Distributed Christmas gifts to underprivileged children'),
(79, 26, 66, 66, 'completed', '2025-12-27 10:00:00', 3.00, 'Supported community event for social awareness'),
(80, 26, 67, 67, 'completed', '2025-12-28 13:00:00', 4.50, 'Helped with outreach programs for youth in need'),
(81, 26, 68, 68, 'completed', '2025-12-29 10:30:00', 2.50, 'Tutoring session for primary students'),
(82, 26, 69, 69, 'completed', '2025-12-30 11:00:00', 3.00, 'Community outreach for elderly care'),
(83, 26, 70, 70, 'completed', '2025-12-31 12:30:00', 4.00, 'Organized year-end volunteer appreciation event'),
(84, 26, 71, 71, 'completed', '2026-01-01 14:00:00', 3.50, 'Led a team for a new year community clean-up'),
(85, 26, 72, 72, 'completed', '2026-01-02 11:00:00', 2.50, 'Helped with food distribution at local shelter'),
(86, 26, 73, 73, 'completed', '2026-01-03 12:30:00', 3.00, 'Tutored students in math and science'),
(87, 26, 74, 74, 'completed', '2026-01-04 10:30:00', 4.00, 'Community health check-up and assistance'),
(88, 26, 75, 75, 'completed', '2026-01-05 14:00:00', 3.50, 'Youth mentorship session at community center'),
(89, 26, 76, 76, 'completed', '2026-01-06 13:00:00', 4.00, 'Tutored elderly citizens in basic computing'),
(90, 26, 77, 77, 'completed', '2026-01-07 15:00:00', 3.00, 'Delivered groceries to families in need'),
(91, 26, 78, 78, 'completed', '2026-01-08 16:00:00', 2.50, 'Assisted in setting up community event space'),
(92, 26, 79, 79, 'completed', '2026-01-09 14:30:00', 4.00, 'Prepared and served meals at local shelter'),
(93, 26, 80, 80, 'completed', '2026-01-10 11:30:00', 3.50, 'Community recycling program participation'),
(94, 26, 81, 81, 'completed', '2026-01-11 13:00:00', 4.00, 'Provided transportation for elderly to health clinic'),
(95, 26, 82, 82, 'completed', '2026-01-12 14:30:00', 3.00, 'Assisted with educational resources distribution'),
(96, 26, 83, 83, 'completed', '2026-01-13 10:00:00', 4.50, 'Community event organization and coordination'),
(97, 26, 84, 84, 'completed', '2026-01-14 11:00:00', 2.50, 'Assisted elderly with mobility and shopping'),
(98, 26, 85, 85, 'completed', '2026-01-15 12:30:00', 3.00, 'Youth sports mentoring session'),
(99, 26, 86, 86, 'completed', '2026-01-16 10:30:00', 3.50, 'Taught cooking classes for seniors'),
(100, 26, 87, 87, 'completed', '2026-01-17 11:30:00', 4.00, 'Provided career advice and job placement support'),
(101, 26, 88, 88, 'completed', '2026-01-18 12:00:00', 3.00, 'Youth environmental sustainability project'),
(102, 26, 89, 89, 'completed', '2026-01-19 14:00:00', 3.50, 'Organized health and wellness workshops for seniors'),
(103, 26, 90, 90, 'completed', '2026-01-20 15:00:00', 2.50, 'Helped with administrative tasks for community center'),
(104, 26, 91, 91, 'completed', '2026-01-21 16:30:00', 4.00, 'Facilitated youth leadership program'),
(105, 26, 92, 92, 'completed', '2026-01-22 14:00:00', 3.00, 'Assisted with social media outreach for volunteer programs'),
(106, 26, 93, 93, 'completed', '2026-01-23 10:30:00', 3.50, 'Helped organize local fundraiser for charity'),
(107, 26, 94, 94, 'completed', '2026-01-24 12:00:00', 2.50, 'Community outreach for food security issues'),
(108, 26, 95, 95, 'completed', '2026-01-25 15:00:00', 4.00, 'Assisted in preparing community kitchen for events'),
(109, 26, 96, 96, 'completed', '2026-01-26 16:00:00', 3.00, 'Youth education and mentorship on financial literacy'),
(110, 26, 97, 97, 'completed', '2026-01-27 12:30:00', 2.50, 'Assisted with donation drive at community center'),
(111, 26, 98, 98, 'completed', '2026-01-28 10:00:00', 4.00, 'Community health education and outreach'),
(112, 26, 99, 99, 'completed', '2026-01-29 14:30:00', 3.50, 'Coordinated community wellness event'),
(113, 26, 100, 100, 'completed', '2026-01-30 15:30:00', 2.50, 'Distributed hygiene kits to low-income communities');

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
(1, 'test', '$2y$10$JVC64sdnTApJOjoK2ORjieojVNSa4AvRxJEIXFjkv.QlDp5M6bOg6', 'admin', 'active', '2025-10-18 15:12:09'),
(2, 'test1', '$2y$10$tDKvKNbCe8HX0LXnvpEX1uBagTUzPvQiIWyoNSNhGLyajf.GyIz9S', 'pin', 'suspended', '2025-10-21 16:41:28'),
(3, 'test3', '$2y$10$v6r4uiFnc/LuIVxcArs4..SE.CDybGy.8vzRHWbMPpZuH5u1UoLOO', 'csr', 'suspended', '2025-10-24 14:52:16'),
(4, 'platform manager', '$2y$10$ST1625vLp3QLPbjptuZP1OtV9ZG0gHpI3SQ13P1SmyYjHUAbQo7Wy', 'platform', 'active', '2025-10-29 12:38:19'),
(5, 'CSR', '$2y$10$j9c6abbp7tlgfN/Slo0JU.2x8PV5N51lfQcHFsX/mtEP0R4ZTM.Wq', 'csr', 'active', '2025-10-31 09:25:10'),
(6, 'PIN', '$2y$10$fUJmFwcOdRhYa8yh4m0QG.8kRhYRgWmCgtmlMuUQdgmnPYoqto0Le', 'pin', 'active', '2025-10-31 17:36:53'),
(7, 'CSR1', '$2y$10$qlXZO2p.eB3BGhx1PiIixOdKNJBfYsrZRGpul/PPTJ4R.GvkcZyq.', 'csr', 'active', '2025-11-06 08:52:42'),
(8, 'test5', '$2y$10$lLnIDRbDThRDpBDsOAg7xu5w0I57YEUKjHw.9pib0tgkF3SWhDr4i', 'admin', 'suspended', '2025-11-12 17:21:54'),
(9, 'Keenan Ganaesan', '$2y$10$SxTvjhMMk5DpQIDDHkGx9uR1YcQqbHXmYjBLFvwa6RFvvE0F12hjK', 'admin', 'active', '2025-11-12 20:14:34'),
(10, 'test9', '$2y$10$/7lCK5antoGW8wOSaB54nOtj0BuYG/lmWYoxPDnaq5msHE5exzZnq', 'admin', 'suspended', '2025-11-13 09:36:02'),
(12, 'test2', '$2y$10$HsnaKKxN6PvvQg/V.5Jivu19Vd4b.ttVs3v.gYP7jztkQz/RQzvXS', 'admin', 'active', '2025-11-13 09:38:42'),
(13, 'John Doe', '$2y$10$JV64sdnTAPjOjoK2ORijEojVNSa4AvRxJEIXFjx.vQ3my7tTRIZi.', 'admin', 'active', '2025-10-18 15:12:09'),
(14, 'Jane Smith', '$2y$10$KxthCe8Hk0lLxvnpEX1uBagTUzPvQilWyoNSNhGlyF7tqdrdpJto5', 'pin', 'suspended', '2025-10-21 16:41:28'),
(15, 'Mark Johnson', '$2y$10$GzXK94fuiFnc/LulVxCrs4r4.SE.CDybGy.8vzRHWbMppZb1KwO.', 'csr', 'active', '2025-10-24 14:52:16'),
(16, 'Sarah Lee', '$2y$10$3CTj1625Lv3QLPjbtZP10tV9ZG0JgHplJ3SQ13P1SmyYdA6ReLApE', 'platform', 'active', '2025-10-29 12:38:19'),
(17, 'David Brown', '$2y$10$J6a6bap7tgfN1jSIoUU.2x8PVS5f1lFqCkHF5x/XmtEjjEoiAwGz.', 'csr', 'active', '2025-10-31 09:25:10'),
(18, 'Emily White', '$2y$10$uFJmuFCoRhRYa8y4hm0GQ.8kHRyQYgWmCtgmlMuUqdmFu1Qdgmc5', 'csr', 'active', '2025-10-31 17:36:53'),
(19, 'Michael Green', '$2y$10$Jd/Fh1i1KIq0pHJp1hKKTeKZ/gFZud7FZb3ytknQn2jbYo1fJHfRu', 'csr', 'suspended', '2025-11-03 08:51:00'),
(20, 'Sophia Adams', '$2y$10$13bRiIdTI7UX6Op73bZf5tCmljm0oJ9cfyW1iiWzA2S/zwqlIVjx5', 'admin', 'active', '2025-11-04 11:03:15'),
(21, 'Oliver Clark', '$2y$10$0sKk1zSLC5uZ8JgrR4JkiqfnXHdsJ1bc2bIff.cMTb1TQwv2dfeqS', 'csr', 'active', '2025-11-05 01:23:46'),
(22, 'Liam Harris', '$2y$10$NXLlJr2U3nTfGpGc9z3V6kl7o/7lh6F4bmcKbbQ95yFEys27o/FVd', 'csr', 'suspended', '2025-11-06 09:52:22'),
(23, 'Charlotte Young', '$2y$10$ys1j/7Fh7RfqjL2BLaIvLOqXYg78ITuEPu/OcuFHpH77j3zqzdVuW', 'admin', 'active', '2025-11-08 06:35:22'),
(24, 'James Allen', '$2y$10$12bFHjAIbcthXtJz1KT7oZ5wIs9vRfZWFgpx4kXYAsl.R4Sgqei2', 'csr', 'active', '2025-11-09 08:10:30'),
(25, 'Chloe Scott', '$2y$10$h5mNN0Htb1sm5AlMkrt3jgt1bd5LdyYtxg85frEdJ77DcfIMok9.m', 'csr', 'active', '2025-11-10 01:42:55'),
(26, 'Benjamin Martinez', '$2y$10$KSt5Pb39x2E3oP2tYATNY/0kUwtZrpx2cJ8d.fnnqOHV6O2v7uVLa', 'csr', 'active', '2025-11-11 02:50:40'),
(27, 'Ava Turner', '$2y$10$z5o0owzwSY2GCBb3TKFfyyuDpRrbjtXKvDdrFQJYdGvwnbNS.lhn8', 'admin', 'active', '2025-11-12 04:30:10'),
(28, 'Matthew Lewis', '$2y$10$mh1aB36lR8D8m5.JZ2dwcB7X0XQTf0y6lWpqfsaOH7eZvR5rhqn3', 'csr', 'active', '2025-11-13 06:20:35'),
(29, 'Amelia Robinson', '$2y$10$hF9hj2xyFj0.BFmX59oxh2FzRMrHahhhlk2XtBdAKxk9n5g0rtB9A', 'csr', 'suspended', '2025-11-14 05:02:58'),
(30, 'Lucas Walker', '$2y$10$z/qz5qwsOgm8gd6dlcw3pGSz0tfpqYwXbOjp27ECANj9Hk5kmFSZQ', 'csr', 'active', '2025-11-15 02:35:11'),
(31, 'Grace King', '$2y$10$HlKyg9Psg/B02h8txFnDRvXzRZsR8l.3S/rrwqYYt5mSi.sZR7MG', 'admin', 'active', '2025-11-16 04:20:14'),
(32, 'Daniel Moore', '$2y$10$D9d9rxJ6eKnF6cpaMRaNzJOpDNwxbTLl7C2Gxihp6Z1.xJdKYVVyv', 'csr', 'active', '2025-11-17 06:15:32'),
(33, 'Hannah Lee', '$2y$10$slwJX9lWmFzB0.QV3zqzzMch0HVR2ho0VfV6P91Yn0Xc1yBq7Lx34', 'csr', 'active', '2025-11-18 01:05:49'),
(34, 'User100', '$2y$10$QIqlb9gJv9Dlh16s6TzznhXfNTzU8lgygzYt4coT.4FAksA7qjGMu', 'admin', 'active', '2026-01-01 02:00:00'),
(132, 'Tan Wei Ling', '$2y$10$KxthCe8Hk0lLxvnpEX1uBagTUzPvQilWyoNSNhGlyF7tqdrdpJto5', 'pin', 'suspended', '2025-10-21 16:41:28'),
(133, 'Lian Chia Yong', '$2y$10$GzXK94fuiFnc/LulVxCrs4r4.SE.CDybGy.8vzRHWbMppZb1KwO.', 'csr', 'active', '2025-10-24 14:52:16'),
(134, 'Khoo Boon Hweee', '$2y$10$3CTj1625Lv3QLPjbtZP10tV9ZG0JgHplJ3SQ13P1SmyYdA6ReLApE', 'platform', 'active', '2025-10-29 12:38:19'),
(135, 'Siti Aisyah', '$2y$10$J6a6bap7tgfN1jSIoUU.2x8PVS5f1lFqCkHF5x/XmtEjjEoiAwGz.', 'csr', 'active', '2025-10-31 09:25:10'),
(136, 'Rajesh Kumar', '$2y$10$uFJmuFCoRhRYa8y4hm0GQ.8kHRyQYgWmCtgmlMuUqdmFu1Qdgmc5', 'csr', 'active', '2025-10-31 17:36:53'),
(137, 'Chia Wei Ling', '$2y$10$Jd/Fh1i1KIq0pHJp1hKKTeKZ/gFZud7FZb3ytknQn2jbYo1fJHfRu', 'csr', 'suspended', '2025-11-03 08:51:00'),
(138, 'Fong Mei Ling', '$2y$10$13bRiIdTI7UX6Op73bZf5tCmljm0oJ9cfyW1iiWzA2S/zwqlIVjx5', 'admin', 'active', '2025-11-04 11:03:15'),
(139, 'Ng Wei Ping', '$2y$10$0sKk1zSLC5uZ8JgrR4JkiqfnXHdsJ1bc2bIff.cMTb1TQwv2dfeqS', 'csr', 'active', '2025-11-05 01:23:46'),
(140, 'Lee Hwee Leng', '$2y$10$NXLlJr2U3nTfGpGc9z3V6kl7o/7lh6F4bmcKbbQ95yFEys27o/FVd', 'csr', 'active', '2025-11-06 09:52:22'),
(141, 'Ong Swee Lian', '$2y$10$ys1j/7Fh7RfqjL2BLaIvLOqXYg78ITuEPu/OcuFHpH77j3zqzdVuW', 'admin', 'active', '2025-11-08 06:35:22'),
(142, 'Tan Chong Beng', '$2y$10$12bFHjAIbcthXtJz1KT7oZ5wIs9vRfZWFgpx4kXYAsl.R4Sgqei2', 'csr', 'active', '2025-11-09 08:10:30'),
(143, 'Lim Kian Yuen', '$2y$10$h5mNN0Htb1sm5AlMkrt3jgt1bd5LdyYtxg85frEdJ77DcfIMok9.m', 'csr', 'active', '2025-11-10 01:42:55'),
(144, 'Tay Hui Ling', '$2y$10$KSt5Pb39x2E3oP2tYATNY/0kUwtZrpx2cJ8d.fnnqOHV6O2v7uVLa', 'csr', 'active', '2025-11-11 02:50:40'),
(145, 'Goh Mei Hua', '$2y$10$z5o0owzwSY2GCBb3TKFfyyuDpRrbjtXKvDdrFQJYdGvwnbNS.lhn8', 'admin', 'active', '2025-11-12 04:30:10'),
(146, 'Chong Wei Liang', '$2y$10$mh1aB36lR8D8m5.JZ2dwcB7X0XQTf0y6lWpqfsaOH7eZvR5rhqn3', 'csr', 'active', '2025-11-13 06:20:35'),
(147, 'Tan Xiang Lian', '$2y$10$slwJX9lWmFzB0.QV3zqzzMch0HVR2ho0VfV6P91Yn0Xc1yBq7Lx34', 'csr', 'suspended', '2025-11-14 05:02:58'),
(148, 'Zhang Wei Zhi', '$2y$10$z/qz5qwsOgm8gd6dlcw3pGSz0tfpqYwXbOjp27ECANj9Hk5kmFSZQ', 'csr', 'active', '2025-11-15 02:35:11'),
(149, 'Koh Li Ying', '$2y$10$HlKyg9Psg/B02h8txFnDRvXzRZsR8l.3S/rrwqYYt5mSi.sZR7MG', 'admin', 'active', '2025-11-16 04:20:14'),
(150, 'Wong Boon Teck', '$2y$10$D9d9rxJ6eKnF6cpaMRaNzJOpDNwxbTLl7C2Gxihp6Z1.xJdKYVVyv', 'csr', 'active', '2025-11-17 06:15:32'),
(151, 'Sim Hwee Choon', '$2y$10$slwJX9lWmFzB0.QV3zqzzMch0HVR2ho0VfV6P91Yn0Xc1yBq7Lx34', 'csr', 'active', '2025-11-18 01:05:49'),
(152, 'Tan Cheng Han', '$2y$10$wh2MZsLg9kX5g6gUt9Bqs9pkrTRoK.v8pWjVx6fFvRVeqZxGVbsIS', 'admin', 'active', '2025-11-19 00:32:10'),
(153, 'Yong Cheng Wei', '$2y$10$0hf7loIMdfPbzA7f/NV4WopT5jr9zp9RhpO9yHjlLio29zxS8D35V', 'csr', 'active', '2025-11-20 06:45:50'),
(154, 'Chia Li Ying', '$2y$10$Jl4aOihz7Oer5wWi7CVJrcD0aLP3Qj5ABEKFplcPmaYuFccUt9yqm', 'csr', 'active', '2025-11-21 05:15:03'),
(155, 'Wong Zhi Wei', '$2y$10$MyZ6QxzNCFZnE4At2bp9pz3PQGVLOJl5RmCk7X0gCw2HujpWB6zUq', 'admin', 'active', '2025-11-22 08:20:10'),
(156, 'Tan Wei Heng', '$2y$10$IvHqcjAsgdrPq0cXfG9i6z27vM8khX9g.GZgxZT0b21O5D2S3cpaI', 'csr', 'suspended', '2025-11-23 01:25:13'),
(157, 'Chong Mei Qian', '$2y$10$58roaOqz3vcw2ddcmDL5gKZjsPzbrDBvNB82HvLTFzFwIR3uPfDZo', 'csr', 'active', '2025-11-24 00:40:10'),
(158, 'Chua Ji Hao', '$2y$10$RIffJStGqD0jy.r2IgD1mjwcvH3gQ57V5q/PLapcbkT4lBoPqx59q', 'admin', 'active', '2025-11-25 06:05:30'),
(159, 'Tan Xin Yi', '$2y$10$BQKtPzTmy1uIV42QnCzq.c07RGzZwp8TzUPpff.Ep/Xzn9kl8yYP6u', 'csr', 'active', '2025-11-26 08:10:59'),
(160, 'Leong Zhi Hui', '$2y$10$4q9NreB8dEorIqsz9UqZJmTKfb5bxgqfKlcOCswHrHM7nd69h11gg', 'csr', 'active', '2025-11-27 05:20:35'),
(161, 'Halimah Binte Yusof', '$2y$10$lhz/f72q2ne/.L3lfI7FlV6rD3hh0sdpQz5w5rwxr0OGZmjiFZ8C6u', 'csr', 'active', '2025-11-28 06:00:21'),
(162, 'Chong Keat Ming', '$2y$10$QIqlb9gJv9Dlh16s6TzznhXfNTzU8lgygzYt4coT.4FAksA7qjGMu', 'admin', 'active', '2026-01-01 02:00:00'),
(163, 'Ahmad Bin Abdullah', '$2y$10$JV64sdnTAPjOjoK2ORijEojVNSa4AvRxJEIXFjx.vQ3my7tTRIZi.', 'admin', 'active', '2025-10-18 15:12:09'),
(164, 'Fatimah Sulaiman', '$2y$10$KxthCe8Hk0lLxvnpEX1uBagTUzPvQilWyoNSNhGlyF7tqdrdpJto5', 'pin', 'suspended', '2025-10-21 16:41:28'),
(165, 'Lee Chong Wei', '$2y$10$GzXK94fuiFnc/LulVxCrs4r4.SE.CDybGy.8vzRHWbMppZb1KwO.', 'csr', 'active', '2025-10-24 14:52:16'),
(166, 'Zhang Wei Ming', '$2y$10$3CTj1625Lv3QLPjbtZP10tV9ZG0JgHplJ3SQ13P1SmyYdA6ReLApE', 'platform', 'active', '2025-10-29 12:38:19'),
(167, 'Mohammad Ali', '$2y$10$J6a6bap7tgfN1jSIoUU.2x8PVS5f1lFqCkHF5x/XmtEjjEoiAwGz.', 'csr', 'active', '2025-10-31 09:25:10'),
(168, 'Angeline Tan', '$2y$10$uFJmuFCoRhRYa8y4hm0GQ.8kHRyQYgWmCtgmlMuUqdmFu1Qdgmc5', 'csr', 'active', '2025-10-31 17:36:53'),
(169, 'Jasmine Ho', '$2y$10$Jd/Fh1i1KIq0pHJp1hKKTeKZ/gFZud7FZb3ytknQn2jbYo1fJHfRu', 'csr', 'suspended', '2025-11-03 08:51:00'),
(170, 'Kenneth Lim', '$2y$10$13bRiIdTI7UX6Op73bZf5tCmljm0oJ9cfyW1iiWzA2S/zwqlIVjx5', 'admin', 'active', '2025-11-04 11:03:15'),
(171, 'Adeline Ng', '$2y$10$0sKk1zSLC5uZ8JgrR4JkiqfnXHdsJ1bc2bIff.cMTb1TQwv2dfeqS', 'csr', 'active', '2025-11-05 01:23:46'),
(172, 'Jin Wei Jun', '$2y$10$NXLlJr2U3nTfGpGc9z3V6kl7o/7lh6F4bmcKbbQ95yFEys27o/FVd', 'csr', 'active', '2025-11-06 09:52:22'),
(173, 'Yeo Jin Hui', '$2y$10$ys1j/7Fh7RfqjL2BLaIvLOqXYg78ITuEPu/OcuFHpH77j3zqzdVuW', 'admin', 'active', '2025-11-08 06:35:22'),
(174, 'Lee Shi Min', '$2y$10$12bFHjAIbcthXtJz1KT7oZ5wIs9vRfZWFgpx4kXYAsl.R4Sgqei2', 'csr', 'active', '2025-11-09 08:10:30'),
(175, 'Cheryl Tan', '$2y$10$h5mNN0Htb1sm5AlMkrt3jgt1bd5LdyYtxg85frEdJ77DcfIMok9.m', 'csr', 'active', '2025-11-10 01:42:55'),
(176, 'Muhammad Firdaus', '$2y$10$KSt5Pb39x2E3oP2tYATNY/0kUwtZrpx2cJ8d.fnnqOHV6O2v7uVLa', 'csr', 'active', '2025-11-11 02:50:40'),
(177, 'Hui Min Tan', '$2y$10$z5o0owzwSY2GCBb3TKFfyyuDpRrbjtXKvDdrFQJYdGvwnbNS.lhn8', 'admin', 'active', '2025-11-12 04:30:10'),
(178, 'Lim Zhen Wei', '$2y$10$mh1aB36lR8D8m5.JZ2dwcB7X0XQTf0y6lWpqfsaOH7eZvR5rhqn3', 'csr', 'active', '2025-11-13 06:20:35'),
(179, 'Mok Xiu Ling', '$2y$10$slwJX9lWmFzB0.QV3zqzzMch0HVR2ho0VfV6P91Yn0Xc1yBq7Lx34', 'csr', 'suspended', '2025-11-14 05:02:58'),
(180, 'Lim Zhi Wei', '$2y$10$z/qz5qwsOgm8gd6dlcw3pGSz0tfpqYwXbOjp27ECANj9Hk5kmFSZQ', 'csr', 'active', '2025-11-15 02:35:11'),
(181, 'Martha Tan', '$2y$10$HlKyg9Psg/B02h8txFnDRvXzRZsR8l.3S/rrwqYYt5mSi.sZR7MG', 'admin', 'active', '2025-11-16 04:20:14'),
(182, 'Farhan Hussain', '$2y$10$D9d9rxJ6eKnF6cpaMRaNzJOpDNwxbTLl7C2Gxihp6Z1.xJdKYVVyv', 'csr', 'active', '2025-11-17 06:15:32'),
(183, 'Ravi Kumar', '$2y$10$slwJX9lWmFzB0.QV3zqzzMch0HVR2ho0VfV6P91Yn0Xc1yBq7Lx34', 'csr', 'active', '2025-11-18 01:05:49'),
(184, 'Rohani Binte Ibrahim', '$2y$10$uFJmuFCoRhRYa8y4hm0GQ.8kHRyQYgWmCtgmlMuUqdmFu1Qdgmc5', 'csr', 'active', '2025-10-31 17:36:53'),
(185, 'Faris Nasir', '$2y$10$Jd/Fh1i1KIq0pHJp1hKKTeKZ/gFZud7FZb3ytknQn2jbYo1fJHfRu', 'csr', 'suspended', '2025-11-03 08:51:00'),
(186, 'Tan Yong Kang', '$2y$10$13bRiIdTI7UX6Op73bZf5tCmljm0oJ9cfyW1iiWzA2S/zwqlIVjx5', 'admin', 'active', '2025-11-04 11:03:15'),
(187, 'Siti Zahrah', '$2y$10$0sKk1zSLC5uZ8JgrR4JkiqfnXHdsJ1bc2bIff.cMTb1TQwv2dfeqS', 'csr', 'active', '2025-11-05 01:23:46'),
(188, 'Azman Mohamed', '$2y$10$NXLlJr2U3nTfGpGc9z3V6kl7o/7lh6F4bmcKbbQ95yFEys27o/FVd', 'csr', 'active', '2025-11-06 09:52:22'),
(189, 'Fayruz Jamilah', '$2y$10$ys1j/7Fh7RfqjL2BLaIvLOqXYg78ITuEPu/OcuFHpH77j3zqzdVuW', 'admin', 'active', '2025-11-08 06:35:22'),
(190, 'Tasha Tan', '$2y$10$12bFHjAIbcthXtJz1KT7oZ5wIs9vRfZWFgpx4kXYAsl.R4Sgqei2', 'csr', 'active', '2025-11-09 08:10:30'),
(191, 'Ishmael Lee', '$2y$10$h5mNN0Htb1sm5AlMkrt3jgt1bd5LdyYtxg85frEdJ77DcfIMok9.m', 'csr', 'active', '2025-11-10 01:42:55'),
(192, 'Mei Yee', '$2y$10$KSt5Pb39x2E3oP2tYATNY/0kUwtZrpx2cJ8d.fnnqOHV6O2v7uVLa', 'csr', 'active', '2025-11-11 02:50:40'),
(193, 'Zoe Lim', '$2y$10$z5o0owzwSY2GCBb3TKFfyyuDpRrbjtXKvDdrFQJYdGvwnbNS.lhn8', 'admin', 'active', '2025-11-12 04:30:10'),
(194, 'Teddy Tan', '$2y$10$mh1aB36lR8D8m5.JZ2dwcB7X0XQTf0y6lWpqfsaOH7eZvR5rhqn3', 'csr', 'active', '2025-11-13 06:20:35'),
(195, 'Rani Gopal', '$2y$10$slwJX9lWmFzB0.QV3zqzzMch0HVR2ho0VfV6P91Yn0Xc1yBq7Lx34', 'csr', 'suspended', '2025-11-14 05:02:58'),
(196, 'Tan Khim Sin', '$2y$10$z/qz5qwsOgm8gd6dlcw3pGSz0tfpqYwXbOjp27ECANj9Hk5kmFSZQ', 'csr', 'active', '2025-11-15 02:35:11'),
(197, 'Adelina Tan', '$2y$10$HlKyg9Psg/B02h8txFnDRvXzRZsR8l.3S/rrwqYYt5mSi.sZR7MG', 'admin', 'active', '2025-11-16 04:20:14'),
(198, 'Benjamin Lee', '$2y$10$D9d9rxJ6eKnF6cpaMRaNzJOpDNwxbTLl7C2Gxihp6Z1.xJdKYVVyv', 'csr', 'active', '2025-11-17 06:15:32'),
(199, 'Siti Aminah', '$2y$10$slwJX9lWmFzB0.QV3zqzzMch0HVR2ho0VfV6P91Yn0Xc1yBq7Lx34', 'csr', 'active', '2025-11-18 01:05:49'),
(200, 'Fikri Abdullah', '$2y$10$wh2MZsLg9kX5g6gUt9Bqs9pkrTRoK.v8pWjVx6fFvRVeqZxGVbsIS', 'admin', 'active', '2025-11-19 00:32:10'),
(201, 'PIN2', '$2y$10$agZ8FxFcSDBPm/tnc0cf8eVkGqEJQe1iw1XDHpzsHrOjd0I.nS/t.', 'pin', 'active', '2025-11-13 12:21:56'),
(202, 'PIN3', '$2y$10$K0tqkxCHt3KeKdJFn5aoK./BNxpvmaaLNb4K4p/NGIO.EHbSRDfpy', 'pin', 'active', '2025-11-13 12:22:08');

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
(1, 'admin', '2025-10-18 15:06:29', 'active'),
(2, 'csr', '2025-10-18 15:06:29', 'active'),
(3, 'pin', '2025-10-18 15:06:29', 'active'),
(4, 'platform', '2025-10-18 15:06:29', 'active'),
(5, 'managers', '2025-11-08 16:53:12', 'suspended'),
(6, 'test2', '2025-11-12 18:42:09', 'suspended'),
(7, 'test4', '2025-11-12 20:15:22', 'suspended'),
(8, 'test12', '2025-11-13 10:30:39', 'active');

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
(1, 'Alice Tan', 'alice.tan@example.com', '91234567', 'Female', 'First Aid, Elderly Care', 'Part-Time', '2025-10-27', 'Active'),
(2, 'Ben Ong', 'ben.ong@example.com', '98765432', 'Male', 'Tutoring, Event Support', 'Full-Time', '2025-10-27', 'Active'),
(3, 'Cindy Lee', 'cindy.lee@example.com', '96543218', 'Female', 'Logistics, Public Speaking', 'Occasional', '2025-10-27', 'Active'),
(4, 'David Lim', 'david.lim@example.com', '92345678', 'Male', 'Cooking, Outreach', 'Part-Time', '2025-10-27', 'Inactive'),
(5, 'Rina Tan', 'rina.tan@example.com', '93456789', 'Female', 'Community Outreach, Event Management', 'Full-Time', '2025-10-28', 'Active'),
(6, 'Koh Wei Leong', 'koh.wei@example.com', '91234567', 'Male', 'Tutoring, IT Support', 'Part-Time', '2025-10-28', 'Active'),
(7, 'Nur Aisyah', 'nur.aisyah@example.com', '94567890', 'Female', 'Fundraising, Public Speaking', 'Full-Time', '2025-10-28', 'Active'),
(8, 'Johan Tan', 'johan.tan@example.com', '98765432', 'Male', 'Logistics, Cooking', 'Occasional', '2025-10-28', 'Inactive'),
(9, 'Emily Lim', 'emily.lim@example.com', '96543210', 'Female', 'Mental Health Support, Counseling', 'Part-Time', '2025-10-28', 'Active'),
(10, 'Samuel Ong', 'samuel.ong@example.com', '92345678', 'Male', 'First Aid, Community Development', 'Full-Time', '2025-10-28', 'Active'),
(11, 'Irene Tan', 'irene.tan@example.com', '93847659', 'Female', 'Elderly Care, Counseling', 'Occasional', '2025-10-28', 'Active'),
(12, 'Vincent Lee', 'vincent.lee@example.com', '93126789', 'Male', 'Project Management, Mentorship', 'Part-Time', '2025-10-28', 'Active'),
(13, 'Rachel Yeo', 'rachel.yeo@example.com', '94785632', 'Female', 'Environmental Conservation, Event Coordination', 'Full-Time', '2025-10-28', 'Inactive'),
(14, 'Henry Chan', 'henry.chan@example.com', '91234501', 'Male', 'Fundraising, Youth Mentorship', 'Part-Time', '2025-10-28', 'Active'),
(15, 'Marcus Tan', 'marcus.tan@example.com', '91234500', 'Male', 'IT Support, Event Management', 'Full-Time', '2025-10-28', 'Active'),
(16, 'Grace Lim', 'grace.lim@example.com', '92345678', 'Female', 'Public Speaking, Fundraising', 'Part-Time', '2025-10-28', 'Active'),
(17, 'Jie Min', 'jie.min@example.com', '93456789', 'Female', 'Elderly Care, Tutoring', 'Occasional', '2025-10-28', 'Active'),
(18, 'Ryan Chia', 'ryan.chia@example.com', '94567890', 'Male', 'Youth Mentorship, Sports Coaching', 'Full-Time', '2025-10-28', 'Active'),
(19, 'Sophia Tay', 'sophia.tay@example.com', '95678901', 'Female', 'Community Outreach, Event Support', 'Part-Time', '2025-10-28', 'Inactive'),
(20, 'Daniel Ang', 'daniel.ang@example.com', '96789012', 'Male', 'Logistics, Public Speaking', 'Occasional', '2025-10-28', 'Active'),
(21, 'Xinyi Chen', 'xinyi.chen@example.com', '97890123', 'Female', 'Mental Health, Counseling', 'Full-Time', '2025-10-28', 'Active'),
(22, 'Raymond Ho', 'raymond.ho@example.com', '98901234', 'Male', 'Technology Support, IT Management', 'Part-Time', '2025-10-28', 'Inactive'),
(23, 'Lena Tan', 'lena.tan@example.com', '99012345', 'Female', 'Youth Development, Social Work', 'Full-Time', '2025-10-28', 'Active'),
(24, 'Cheryl Lee', 'cheryl.lee@example.com', '91234567', 'Female', 'Elderly Assistance, Social Work', 'Occasional', '2025-10-28', 'Active'),
(25, 'Jia Wei Ng', 'jiawei.ng@example.com', '91234567', 'Male', 'Community Engagement, Mentorship', 'Full-Time', '2025-10-29', 'Active'),
(26, 'Siti Amirah', 'siti.amirah@example.com', '92345678', 'Female', 'First Aid, Elderly Care', 'Part-Time', '2025-10-29', 'Inactive'),
(27, 'Abdul Rahman', 'abdul.rahman@example.com', '93456789', 'Male', 'Sports, Recreation', 'Full-Time', '2025-10-29', 'Active'),
(28, 'Nina Tan', 'nina.tan@example.com', '94567890', 'Female', 'Health & Wellness Support', 'Occasional', '2025-10-29', 'Inactive'),
(29, 'Farhan Shah', 'farhan.shah@example.com', '95678901', 'Male', 'Fundraising, Event Coordination', 'Full-Time', '2025-10-29', 'Active'),
(30, 'Melissa Lim', 'melissa.lim@example.com', '96789012', 'Female', 'Public Health, Community Outreach', 'Part-Time', '2025-10-29', 'Active'),
(31, 'Andrew Yeo', 'andrew.yeo@example.com', '97890123', 'Male', 'Public Relations, Youth Engagement', 'Occasional', '2025-10-29', 'Inactive'),
(32, 'Nina Lee', 'nina.lee@example.com', '98901234', 'Female', 'Elderly Assistance, Education', 'Full-Time', '2025-10-29', 'Active'),
(33, 'Vincent Tan', 'vincent.tan@example.com', '99012345', 'Male', 'Digital Literacy, Education', 'Part-Time', '2025-10-29', 'Inactive'),
(34, 'Irene Chia', 'irene.chia@example.com', '90012345', 'Female', 'Cultural Outreach, Event Planning', 'Full-Time', '2025-10-29', 'Active'),
(35, 'Kevin Tan', 'kevin.tan@example.com', '91234567', 'Male', 'Education, Mentorship', 'Full-Time', '2025-10-30', 'Active'),
(36, 'Rachael Tan', 'rachael.tan@example.com', '92345678', 'Female', 'Community Support, Event Management', 'Part-Time', '2025-10-30', 'Inactive'),
(37, 'Hassan Ali', 'hassan.ali@example.com', '93456789', 'Male', 'Logistics, Transportation', 'Full-Time', '2025-10-30', 'Active'),
(38, 'Lina Soh', 'lina.soh@example.com', '94567890', 'Female', 'Healthcare, Nursing', 'Occasional', '2025-10-30', 'Active'),
(39, 'Ibrahim Noor', 'ibrahim.noor@example.com', '95678901', 'Male', 'Youth Engagement, Sports', 'Full-Time', '2025-10-30', 'Inactive'),
(40, 'Jessica Lim', 'jessica.lim@example.com', '96789012', 'Female', 'Social Media, Marketing', 'Part-Time', '2025-10-30', 'Active'),
(41, 'Jasmine Yeo', 'jasmine.yeo@example.com', '97890123', 'Female', 'Crisis Counseling, Social Work', 'Occasional', '2025-10-30', 'Inactive'),
(42, 'Yusuf Ali', 'yusuf.ali@example.com', '98901234', 'Male', 'Cultural Affairs, Event Planning', 'Full-Time', '2025-10-30', 'Active'),
(43, 'Amina Abdul', 'amina.abdul@example.com', '99012345', 'Female', 'Elderly Support, Education', 'Part-Time', '2025-10-30', 'Inactive'),
(44, 'Lily Chen', 'lily.chen@example.com', '90012345', 'Female', 'Community Engagement, Education', 'Full-Time', '2025-10-30', 'Active'),
(45, 'Samuel Wong', 'samuel.wong@example.com', '91234578', 'Male', 'IT Support, Project Management', 'Full-Time', '2025-10-31', 'Active'),
(46, 'Sophia Chan', 'sophia.chan@example.com', '92345679', 'Female', 'Event Planning, Fundraising', 'Part-Time', '2025-10-31', 'Inactive'),
(47, 'Toby Lee', 'toby.lee@example.com', '93456780', 'Male', 'Tutoring, Mentoring', 'Occasional', '2025-10-31', 'Active'),
(48, 'Kaitlyn Ho', 'kaitlyn.ho@example.com', '94567891', 'Female', 'Logistics, Public Speaking', 'Full-Time', '2025-10-31', 'Active'),
(49, 'Zac Tan', 'zac.tan@example.com', '95678902', 'Male', 'Cooking, Community Engagement', 'Part-Time', '2025-10-31', 'Inactive'),
(50, 'Emma Tan', 'emma.tan@example.com', '96789023', 'Female', 'Social Work, Youth Mentoring', 'Full-Time', '2025-10-31', 'Active'),
(51, 'Ravi Kumar', 'ravi.kumar@example.com', '97890134', 'Male', 'Disaster Relief, Logistics', 'Occasional', '2025-10-31', 'Active'),
(52, 'Fiona Lee', 'fiona.lee@example.com', '98901245', 'Female', 'Elderly Care, Social Services', 'Part-Time', '2025-10-31', 'Inactive'),
(53, 'James Lee', 'james.lee@example.com', '90012356', 'Male', 'First Aid, Event Management', 'Full-Time', '2025-10-31', 'Active'),
(54, 'Linda Zhang', 'linda.zhang@example.com', '91234589', 'Female', 'Mental Health Support, Counseling', 'Occasional', '2025-11-01', 'Active'),
(55, 'Oliver Tan', 'oliver.tan@example.com', '92345680', 'Male', 'Elderly Support, Social Work', 'Part-Time', '2025-11-01', 'Inactive'),
(56, 'Chloe Lee', 'chloe.lee@example.com', '93456781', 'Female', 'Fundraising, Community Outreach', 'Full-Time', '2025-11-01', 'Active'),
(57, 'Hassan Chow', 'hassan.chow@example.com', '94567892', 'Male', 'Mental Health Support, Public Speaking', 'Occasional', '2025-11-01', 'Inactive'),
(58, 'David Ong', 'david.ong@example.com', '95678903', 'Male', 'Project Management, Fundraising', 'Part-Time', '2025-11-01', 'Active'),
(59, 'Cassandra Lim', 'cassandra.lim@example.com', '96789024', 'Female', 'Community Outreach, Event Planning', 'Full-Time', '2025-11-01', 'Active'),
(60, 'Siti Nura', 'siti.nura@example.com', '97890135', 'Female', 'Cooking, Elderly Care', 'Part-Time', '2025-11-01', 'Active'),
(61, 'Derek Tan', 'derek.tan@example.com', '98901246', 'Male', 'Logistics, Technology Support', 'Full-Time', '2025-11-01', 'Inactive'),
(62, 'Melissa Ong', 'melissa.ong@example.com', '90012357', 'Female', 'Youth Engagement, Community Service', 'Occasional', '2025-11-01', 'Active'),
(63, 'Kenneth Tan', 'kenneth.tan@example.com', '91234590', 'Male', 'Mental Health Counseling, Tutoring', 'Part-Time', '2025-11-01', 'Active'),
(64, 'Rachel Lee', 'rachel.lee@example.com', '92345681', 'Female', 'Fundraising, Project Management', 'Full-Time', '2025-11-01', 'Inactive'),
(65, 'Ivan Wong', 'ivan.wong@example.com', '93456782', 'Male', 'Community Engagement, Youth Mentoring', 'Occasional', '2025-11-02', 'Active'),
(66, 'Melody Tan', 'melody.tan@example.com', '94567893', 'Female', 'Event Support, Community Outreach', 'Part-Time', '2025-11-02', 'Inactive'),
(67, 'Jonathan Tan', 'jonathan.tan@example.com', '95678904', 'Male', 'Youth Development, Education', 'Full-Time', '2025-11-02', 'Active'),
(68, 'Ivy Yeo', 'ivy.yeo@example.com', '96789025', 'Female', 'Cooking, Social Work', 'Occasional', '2025-11-02', 'Active'),
(69, 'Edwin Wong', 'edwin.wong@example.com', '97890136', 'Male', 'Project Coordination, Education', 'Full-Time', '2025-11-02', 'Inactive'),
(70, 'Joyce Tan', 'joyce.tan@example.com', '98901247', 'Female', 'Elderly Support, Fundraising', 'Part-Time', '2025-11-02', 'Active'),
(71, 'Shawn Lim', 'shawn.lim@example.com', '90012358', 'Male', 'Public Speaking, Youth Mentoring', 'Full-Time', '2025-11-02', 'Inactive'),
(72, 'Vanessa Lee', 'vanessa.lee@example.com', '91234591', 'Female', 'Community Engagement, Cooking', 'Occasional', '2025-11-02', 'Active'),
(73, 'Noah Ong', 'noah.ong@example.com', '92345682', 'Male', 'First Aid, Event Planning', 'Full-Time', '2025-11-02', 'Active'),
(74, 'Maya Tan', 'maya.tan@example.com', '93456783', 'Female', 'Public Health, Community Support', 'Part-Time', '2025-11-02', 'Inactive'),
(75, 'Kyle Tan', 'kyle.tan@example.com', '91234592', 'Male', 'Logistics, Sports Coaching', 'Full-Time', '2025-11-03', 'Active'),
(76, 'Lily Yeo', 'lily.yeo@example.com', '92345683', 'Female', 'Mental Health Support, Counseling', 'Part-Time', '2025-11-03', 'Inactive'),
(77, 'Diana Lim', 'diana.lim@example.com', '93456784', 'Female', 'Elderly Care, Community Outreach', 'Occasional', '2025-11-03', 'Active'),
(78, 'Peter Tan', 'peter.tan@example.com', '94567894', 'Male', 'First Aid, Logistics', 'Full-Time', '2025-11-03', 'Active'),
(79, 'Jessica Ong', 'jessica.ong@example.com', '95678905', 'Female', 'Event Coordination, Fundraising', 'Part-Time', '2025-11-03', 'Inactive'),
(80, 'Benjamin Lee', 'benjamin.lee@example.com', '96789026', 'Male', 'Public Speaking, Youth Mentoring', 'Full-Time', '2025-11-03', 'Active'),
(81, 'Samantha Tan', 'samantha.tan@example.com', '97890137', 'Female', 'Healthcare, Mentoring', 'Occasional', '2025-11-03', 'Inactive'),
(82, 'Joshua Tan', 'joshua.tan@example.com', '98901248', 'Male', 'Community Engagement, Public Speaking', 'Full-Time', '2025-11-03', 'Active'),
(83, 'Charlotte Yeo', 'charlotte.yeo@example.com', '90012359', 'Female', 'Event Support, Social Media', 'Part-Time', '2025-11-03', 'Inactive'),
(84, 'George Lim', 'george.lim@example.com', '91234593', 'Male', 'Fundraising, Project Coordination', 'Full-Time', '2025-11-03', 'Active'),
(85, 'Zoe Lee', 'zoe.lee@example.com', '92345684', 'Female', 'Youth Engagement, Event Planning', 'Occasional', '2025-11-04', 'Active'),
(86, 'Alex Tan', 'alex.tan@example.com', '93456785', 'Male', 'IT Support, Public Speaking', 'Part-Time', '2025-11-04', 'Inactive'),
(87, 'Felicia Chan', 'felicia.chan@example.com', '94567895', 'Female', 'Logistics, Community Outreach', 'Full-Time', '2025-11-04', 'Active'),
(88, 'David Yeo', 'david.yeo@example.com', '95678906', 'Male', 'Youth Mentoring, Sports', 'Occasional', '2025-11-04', 'Inactive'),
(89, 'Rosa Lee', 'rosa.lee@example.com', '96789027', 'Female', 'Elderly Support, Tutoring', 'Full-Time', '2025-11-04', 'Active'),
(90, 'Liam Tan', 'liam.tan@example.com', '97890138', 'Male', 'Community Outreach, Education', 'Part-Time', '2025-11-04', 'Active'),
(91, 'Ashley Ong', 'ashley.ong@example.com', '98901249', 'Female', 'First Aid, Event Management', 'Occasional', '2025-11-04', 'Inactive'),
(92, 'Ronald Lim', 'ronald.lim@example.com', '90012360', 'Male', 'Logistics, Cooking', 'Full-Time', '2025-11-04', 'Active'),
(93, 'Natalie Tan', 'natalie.tan@example.com', '91234594', 'Female', 'Social Work, Youth Mentoring', 'Part-Time', '2025-11-04', 'Inactive'),
(94, 'Isaac Ong', 'isaac.ong@example.com', '92345685', 'Male', 'Public Relations, Project Management', 'Full-Time', '2025-11-04', 'Active'),
(95, 'Samuel Lee', 'samuel.lee@example.com', '91234595', 'Male', 'Mental Health, Counseling', 'Full-Time', '2025-11-05', 'Active'),
(96, 'Paula Ong', 'paula.ong@example.com', '92345686', 'Female', 'Tutoring, Youth Development', 'Part-Time', '2025-11-05', 'Inactive'),
(97, 'Megan Tan', 'megan.tan@example.com', '93456786', 'Female', 'Logistics, Event Planning', 'Occasional', '2025-11-05', 'Active'),
(98, 'Ethan Yeo', 'ethan.yeo@example.com', '94567896', 'Male', 'Public Speaking, Mentoring', 'Full-Time', '2025-11-05', 'Active'),
(99, 'Kathy Lee', 'kathy.lee@example.com', '95678907', 'Female', 'Elderly Support, Social Work', 'Part-Time', '2025-11-05', 'Inactive'),
(100, 'Oliver Lim', 'oliver.lim@example.com', '96789028', 'Male', 'Community Outreach, Event Support', 'Full-Time', '2025-11-05', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `csr_shortlist`
--
ALTER TABLE `csr_shortlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_csr_req` (`csr_id`,`request_id`),
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
  ADD KEY `fk_request_category` (`category_id`);

--
-- Indexes for table `request_shortlists`
--
ALTER TABLE `request_shortlists`
  ADD PRIMARY KEY (`request_id`,`csr_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `pin_history`
--
ALTER TABLE `pin_history`
  MODIFY `history_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=323;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `request_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=309;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `service_history`
--
ALTER TABLE `service_history`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `volunteers`
--
ALTER TABLE `volunteers`
  MODIFY `volunteer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

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
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `fk_request_category` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `request_shortlists`
--
ALTER TABLE `request_shortlists`
  ADD CONSTRAINT `fk_rs_req` FOREIGN KEY (`request_id`) REFERENCES `requests` (`request_id`) ON DELETE CASCADE;

--
-- Constraints for table `service_history`
--
ALTER TABLE `service_history`
  ADD CONSTRAINT `fk_volunteer_id` FOREIGN KEY (`volunteer_id`) REFERENCES `volunteers` (`volunteer_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_profile` FOREIGN KEY (`profile_type`) REFERENCES `user_profiles` (`profile_type`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
