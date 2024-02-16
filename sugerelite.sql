-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2024 at 10:09 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sugerelite`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message_from` int(11) DEFAULT NULL,
  `message_to` int(11) DEFAULT NULL,
  `text` text DEFAULT NULL,
  `milisecondtime` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user_id`, `sender_id`, `message_from`, `message_to`, `text`, `milisecondtime`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 2, 1, 2, 'HI am Gautam', '1706867039998', '2024-02-02 04:14:00', '2024-02-02 04:14:00', NULL),
(2, 2, 1, 2, 1, 'HI', '1706867052609', '2024-02-02 04:14:12', '2024-02-02 04:14:12', NULL),
(3, 2, 1, 2, 1, 'HI', '1706867381721', '2024-02-02 04:19:41', '2024-02-02 04:19:41', NULL),
(4, 2, 1, 2, 1, 'HIvvxcv', '1707133157029', '2024-02-05 06:09:18', '2024-02-05 06:09:18', NULL),
(5, 2, 1, 2, 1, 'Hello', '1707134324724', '2024-02-05 06:28:44', '2024-02-05 06:28:44', NULL),
(6, 2, 1, 2, 1, 'Hello', '1707135506252', '2024-02-05 06:48:26', '2024-02-05 06:48:26', NULL),
(7, 2, 1, 2, 1, 'Hello', '1707136984110', '2024-02-05 07:13:04', '2024-02-05 07:13:04', NULL),
(8, 2, 1, 2, 1, 'Hello', '1707137049835', '2024-02-05 07:14:10', '2024-02-05 07:14:10', NULL),
(9, 2, 1, 2, 1, 'Hello', '1707137111374', '2024-02-05 07:15:11', '2024-02-05 07:15:11', NULL),
(10, 2, 1, 2, 1, 'Hello there...!', '1707137213352', '2024-02-05 07:16:53', '2024-02-05 07:16:53', NULL),
(11, 2, 3, 2, 3, 'Hello', '1707137289112', '2024-02-05 07:18:09', '2024-02-05 07:18:09', NULL),
(12, 2, 7, 2, 7, 'Hello there...!', '1707138940164', '2024-02-05 07:45:40', '2024-02-05 07:45:40', NULL),
(13, 1, 2, 1, 2, 'k', '1707139710474', '2024-02-05 07:58:30', '2024-02-05 07:58:30', NULL),
(14, 1, 2, 1, 2, 'kal', '1707139762163', '2024-02-05 07:59:22', '2024-02-05 07:59:22', NULL),
(15, 1, 2, 1, 2, 'hlo', '1707139881875', '2024-02-05 08:01:22', '2024-02-05 08:01:22', NULL),
(16, 1, 2, 1, 2, 'dd', '1707140047924', '2024-02-05 08:04:08', '2024-02-05 08:04:08', NULL),
(17, 1, 2, 1, 2, 'jj', '1707140286982', '2024-02-05 08:08:07', '2024-02-05 08:08:07', NULL),
(18, 1, 2, 1, 2, 'aaad', '1707140424933', '2024-02-05 08:10:25', '2024-02-05 08:10:25', NULL),
(19, 2, 7, 2, 7, 'Hello there...!', '1707282352468', '2024-02-06 23:35:52', '2024-02-06 23:35:52', NULL),
(20, 2, 3, 2, 3, NULL, '1707282367581', '2024-02-06 23:36:07', '2024-02-06 23:36:07', NULL),
(21, 2, 3, 2, 3, 'hello', '1707282375348', '2024-02-06 23:36:16', '2024-02-06 23:36:16', NULL),
(22, 1, 4, 1, 4, 'hlo venkat', '1707283204506', '2024-02-06 23:50:04', '2024-02-06 23:50:04', NULL),
(23, 1, 4, 1, 4, 'hlo', '1707283347956', '2024-02-06 23:52:28', '2024-02-06 23:52:28', NULL),
(24, 1, 5, 1, 5, 'hey bro', '1707283386299', '2024-02-06 23:53:06', '2024-02-06 23:53:06', NULL),
(25, 1, 5, 1, 5, 'hlo', '1707283443300', '2024-02-06 23:54:03', '2024-02-06 23:54:03', NULL),
(26, 1, 5, 1, 5, 'tell me', '1707283504555', '2024-02-06 23:55:04', '2024-02-06 23:55:04', NULL),
(27, 1, 5, 1, 5, 'tell me', '1707283541297', '2024-02-06 23:55:41', '2024-02-06 23:55:41', NULL),
(28, 1, 5, 1, 5, 'hey man', '1707283576149', '2024-02-06 23:56:16', '2024-02-06 23:56:16', NULL),
(29, 1, 3, 1, 3, 'ok done', '1707283728958', '2024-02-06 23:58:49', '2024-02-06 23:58:49', NULL),
(30, 1, 3, 1, 3, 'hey', '1707283750174', '2024-02-06 23:59:10', '2024-02-06 23:59:10', NULL),
(31, 1, 3, 1, 3, 'hlo', '1707283766458', '2024-02-06 23:59:26', '2024-02-06 23:59:26', NULL),
(32, 1, 3, 1, 3, 'hlo', '1707283930856', '2024-02-07 00:02:11', '2024-02-07 00:02:11', NULL),
(33, 1, 3, 1, 3, 'dg', '1707283963793', '2024-02-07 00:02:43', '2024-02-07 00:02:43', NULL),
(34, 1, 3, 1, 3, 'get', '1707284005766', '2024-02-07 00:03:26', '2024-02-07 00:03:26', NULL),
(35, 1, 2, 1, 2, 'hlo', '1707284039134', '2024-02-07 00:03:59', '2024-02-07 00:03:59', NULL),
(36, 1, 2, 1, 2, 'hey', '1707284089929', '2024-02-07 00:04:50', '2024-02-07 00:04:50', NULL),
(37, 1, 2, 1, 2, 'hlo', '1707284280764', '2024-02-07 00:08:01', '2024-02-07 00:08:01', NULL),
(38, 1, 3, 1, 3, 'hlo', '1707284437647', '2024-02-07 00:10:37', '2024-02-07 00:10:37', NULL),
(39, 1, 2, 1, 2, 'hlo', '1707284549974', '2024-02-07 00:12:30', '2024-02-07 00:12:30', NULL),
(40, 1, 2, 1, 2, 'hey man', '1707284625037', '2024-02-07 00:13:45', '2024-02-07 00:13:45', NULL),
(41, 1, 2, 1, 2, 'f', '1707284723766', '2024-02-07 00:15:23', '2024-02-07 00:15:23', NULL),
(42, 1, 2, 1, 2, 'd', '1707284758804', '2024-02-07 00:15:59', '2024-02-07 00:15:59', NULL),
(43, 1, 5, 1, 5, 'd', '1707285112185', '2024-02-07 00:21:52', '2024-02-07 00:21:52', NULL),
(44, 1, 3, 1, 3, 'g', '1707285180602', '2024-02-07 00:23:00', '2024-02-07 00:23:00', NULL),
(45, 1, 3, 1, 3, 's', '1707285219973', '2024-02-07 00:23:40', '2024-02-07 00:23:40', NULL),
(46, 1, 3, 1, 3, 'd', '1707285233675', '2024-02-07 00:23:53', '2024-02-07 00:23:53', NULL),
(47, 1, 3, 1, 3, 'f', '1707285251038', '2024-02-07 00:24:11', '2024-02-07 00:24:11', NULL),
(48, 1, 3, 1, 3, 'd', '1707285311014', '2024-02-07 00:25:11', '2024-02-07 00:25:11', NULL),
(49, 1, 4, 1, 4, 'd', '1707285331954', '2024-02-07 00:25:32', '2024-02-07 00:25:32', NULL),
(50, 1, 4, 1, 4, 'dd', '1707285458671', '2024-02-07 00:27:38', '2024-02-07 00:27:38', NULL),
(51, 4, 1, 4, 1, 'hlo', '1707285501727', '2024-02-07 00:28:21', '2024-02-07 00:28:21', NULL),
(52, 4, 1, 4, 1, 'hlo', '1707285534295', '2024-02-07 00:28:54', '2024-02-07 00:28:54', NULL),
(53, 1, 4, 1, 4, 'bye', '1707285621857', '2024-02-07 00:30:22', '2024-02-07 00:30:22', NULL),
(54, 1, 4, 1, 4, 'ok na', '1707285629524', '2024-02-07 00:30:29', '2024-02-07 00:30:29', NULL),
(55, 4, 1, 4, 1, 'ha ok', '1707285635014', '2024-02-07 00:30:35', '2024-02-07 00:30:35', NULL),
(56, 4, 1, 4, 1, 'hlo', '1707285787680', '2024-02-07 00:33:07', '2024-02-07 00:33:07', NULL),
(57, 4, 1, 4, 1, 'f', '1707285997242', '2024-02-07 00:36:37', '2024-02-07 00:36:37', NULL),
(58, 4, 1, 4, 1, 'hlo', '1707286129401', '2024-02-07 00:38:49', '2024-02-07 00:38:49', NULL),
(59, 4, 1, 4, 1, 'tk', '1707286147941', '2024-02-07 00:39:08', '2024-02-07 00:39:08', NULL),
(60, 4, 1, 4, 1, 'khjkh', '1707286158473', '2024-02-07 00:39:18', '2024-02-07 00:39:18', NULL),
(61, 4, 1, 4, 1, 'hlo', '1707286262971', '2024-02-07 00:41:03', '2024-02-07 00:41:03', NULL),
(62, 4, 1, 4, 1, 'bye', '1707286454164', '2024-02-07 00:44:14', '2024-02-07 00:44:14', NULL),
(63, 4, 6, 4, 6, 'hlo', '1707286919337', '2024-02-07 00:51:59', '2024-02-07 00:51:59', NULL),
(64, 4, 6, 4, 6, 'bye', '1707286930932', '2024-02-07 00:52:11', '2024-02-07 00:52:11', NULL),
(65, 4, 6, 4, 6, 'r uh there', '1707287013545', '2024-02-07 00:53:33', '2024-02-07 00:53:33', NULL),
(66, 4, 6, 4, 6, 'hlo', '1707287099502', '2024-02-07 00:54:59', '2024-02-07 00:54:59', NULL),
(67, 4, 6, 4, 6, 'bye bye', '1707287156939', '2024-02-07 00:55:57', '2024-02-07 00:55:57', NULL),
(68, 4, 6, 4, 6, 'hlo', '1707287184556', '2024-02-07 00:56:24', '2024-02-07 00:56:24', NULL),
(69, 6, 4, 6, 4, 'hlo', '1707287221946', '2024-02-07 00:57:02', '2024-02-07 00:57:02', NULL),
(70, 6, 4, 6, 4, 'bye', '1707288124318', '2024-02-07 01:12:04', '2024-02-07 01:12:04', NULL),
(71, 4, 6, 4, 6, 'bye', '1707288162589', '2024-02-07 01:12:42', '2024-02-07 01:12:42', NULL),
(72, 6, 4, 6, 4, 'hey', '1707288744746', '2024-02-07 01:22:24', '2024-02-07 01:22:24', NULL),
(73, 4, 6, 4, 6, 'ok bye', '1707288927556', '2024-02-07 01:25:27', '2024-02-07 01:25:27', NULL),
(74, 6, 4, 6, 4, 'hlo', '1707288987365', '2024-02-07 01:26:27', '2024-02-07 01:26:27', NULL),
(75, 6, 4, 6, 4, 'hlo', '1707288999397', '2024-02-07 01:26:39', '2024-02-07 01:26:39', NULL),
(76, 4, 6, 4, 6, 'hey', '1707289004538', '2024-02-07 01:26:44', '2024-02-07 01:26:44', NULL),
(77, 6, 4, 6, 4, 'hlo', '1707289024778', '2024-02-07 01:27:05', '2024-02-07 01:27:05', NULL),
(78, 6, 4, 6, 4, 'bye', '1707289322652', '2024-02-07 01:32:02', '2024-02-07 01:32:02', NULL),
(79, 4, 6, 4, 6, 'hlo', '1707289388421', '2024-02-07 01:33:08', '2024-02-07 01:33:08', NULL),
(80, 4, 6, 4, 6, 'ok', '1707289449375', '2024-02-07 01:34:09', '2024-02-07 01:34:09', NULL),
(81, 4, 6, 4, 6, 'ok', '1707289457228', '2024-02-07 01:34:17', '2024-02-07 01:34:17', NULL),
(82, 4, 6, 4, 6, 'bye', '1707289482288', '2024-02-07 01:34:42', '2024-02-07 01:34:42', NULL),
(83, 4, 6, 4, 6, 'bye', '1707289561410', '2024-02-07 01:36:01', '2024-02-07 01:36:01', NULL),
(84, 6, 4, 6, 4, 'all done', '1707289596343', '2024-02-07 01:36:36', '2024-02-07 01:36:36', NULL),
(85, 6, 1, 6, 1, 'Hello', '1707290140899', '2024-02-07 01:45:41', '2024-02-07 01:45:41', NULL),
(86, 6, 1, 6, 1, 'Hello', '1707290157036', '2024-02-07 01:45:57', '2024-02-07 01:45:57', NULL),
(87, 1, 6, 1, 6, 'ok fine', '1707290170956', '2024-02-07 01:46:11', '2024-02-07 01:46:11', NULL),
(88, 6, 1, 6, 1, 'Hii', '1707290271233', '2024-02-07 01:47:51', '2024-02-07 01:47:51', NULL),
(89, 1, 6, 1, 6, 'hlo', '1707290276683', '2024-02-07 01:47:56', '2024-02-07 01:47:56', NULL),
(90, 6, 1, 6, 1, 'am gautam', '1707290278741', '2024-02-07 01:47:58', '2024-02-07 01:47:58', NULL),
(91, 1, 6, 1, 6, 'rajesh here', '1707290289622', '2024-02-07 01:48:09', '2024-02-07 01:48:09', NULL),
(92, 6, 1, 6, 1, 'its real time', '1707290290132', '2024-02-07 01:48:10', '2024-02-07 01:48:10', NULL),
(93, 1, 6, 1, 6, 'yeah', '1707290294995', '2024-02-07 01:48:15', '2024-02-07 01:48:15', NULL),
(94, 6, 1, 6, 1, 'good', '1707290299366', '2024-02-07 01:48:19', '2024-02-07 01:48:19', NULL),
(95, 1, 6, 1, 6, 'fine', '1707290311781', '2024-02-07 01:48:31', '2024-02-07 01:48:31', NULL),
(96, 1, 6, 1, 6, 'bye', '1707291303298', '2024-02-07 02:05:03', '2024-02-07 02:05:03', NULL),
(97, 1, 6, 1, 6, 'jlo', '1707291319044', '2024-02-07 02:05:19', '2024-02-07 02:05:19', NULL),
(98, 1, 4, 1, 4, 'bye', '1707298812151', '2024-02-07 04:10:12', '2024-02-07 04:10:12', NULL),
(99, 1, 4, 1, 4, 'animation done bro', '1707299885454', '2024-02-07 04:28:05', '2024-02-07 04:28:05', NULL),
(100, 4, 1, 4, 1, 'g', '1707299996054', '2024-02-07 04:29:56', '2024-02-07 04:29:56', NULL),
(101, 4, 1, 4, 1, 'done', '1707300006922', '2024-02-07 04:30:07', '2024-02-07 04:30:07', NULL),
(102, 4, 1, 4, 1, 'ok', '1707300021011', '2024-02-07 04:30:21', '2024-02-07 04:30:21', NULL),
(103, 1, 4, 1, 4, 'ok', '1707300026763', '2024-02-07 04:30:26', '2024-02-07 04:30:26', NULL),
(104, 4, 1, 4, 1, 'bye', '1707300293847', '2024-02-07 04:34:54', '2024-02-07 04:34:54', NULL),
(105, 4, 1, 4, 1, 'bye', '1707300295127', '2024-02-07 04:34:55', '2024-02-07 04:34:55', NULL),
(106, 4, 1, 4, 1, 'bye', '1707300295597', '2024-02-07 04:34:55', '2024-02-07 04:34:55', NULL),
(107, 4, 1, 4, 1, 'bye', '1707300296052', '2024-02-07 04:34:56', '2024-02-07 04:34:56', NULL),
(108, 4, 1, 4, 1, 'bye', '1707300296490', '2024-02-07 04:34:56', '2024-02-07 04:34:56', NULL),
(109, 4, 1, 4, 1, 'd', '1707300303728', '2024-02-07 04:35:04', '2024-02-07 04:35:04', NULL),
(110, 4, 1, 4, 1, 'bye', '1707300321727', '2024-02-07 04:35:21', '2024-02-07 04:35:21', NULL),
(111, 1, 4, 1, 4, '12', '1707300392261', '2024-02-07 04:36:32', '2024-02-07 04:36:32', NULL),
(112, 1, 4, 1, 4, '12', '1707300396102', '2024-02-07 04:36:36', '2024-02-07 04:36:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '0000_00_00_000000_create_websockets_statistics_entries_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `user_role` varchar(255) DEFAULT NULL,
  `profile_image` varchar(500) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `user_role`, `profile_image`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@sugarelite.com', NULL, '$2y$10$rg5xQwNHEY0hUesEfGFQa.tHcUKPKer3y8veZBxmB7kHiJ/3/Jg2a', 'admin', NULL, NULL, NULL, NULL),
(2, 'yash', 'yash@tec-sense.com', NULL, '$2y$12$QoS2Tv5q1SMfyWe5ajJDzOx0fXzeDFJ.X9AZv8w33qfHGYXv9JHEy', NULL, NULL, NULL, '2024-02-02 08:27:24', '2024-02-02 08:27:24'),
(3, 'yash', 'bhavin@tec-sense.com', NULL, '$2y$12$Z5Vta61y0wauINeBnjfBWuJfuChmeKELUxllBAJE4u5O8DAsizwji', NULL, NULL, NULL, '2024-02-05 00:16:05', '2024-02-05 00:16:05');

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `id` int(11) NOT NULL,
  `username` text DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `sex` text DEFAULT NULL,
  `height` varchar(255) DEFAULT NULL,
  `premium` text DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `weight` varchar(255) DEFAULT NULL,
  `country` text DEFAULT NULL,
  `sugar_type` text DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `region` text DEFAULT NULL,
  `public_images` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `total_private_images` varchar(255) DEFAULT NULL,
  `ethnicity` text DEFAULT NULL,
  `body_structure` text DEFAULT NULL,
  `hair_color` text DEFAULT NULL,
  `piercings` text DEFAULT NULL,
  `tattoos` text DEFAULT NULL,
  `education` text DEFAULT NULL,
  `smoking` text DEFAULT NULL,
  `drinks` text DEFAULT NULL,
  `employment` text DEFAULT NULL,
  `civil_status` text DEFAULT NULL,
  `confirmed_email` text DEFAULT NULL,
  `online` text DEFAULT NULL,
  `last_online` datetime DEFAULT NULL,
  `last_activity_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `websockets_statistics_entries`
--

CREATE TABLE `websockets_statistics_entries` (
  `id` int(10) UNSIGNED NOT NULL,
  `app_id` varchar(255) NOT NULL,
  `peak_connection_count` int(11) NOT NULL,
  `websocket_message_count` int(11) NOT NULL,
  `api_message_count` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `websockets_statistics_entries`
--
ALTER TABLE `websockets_statistics_entries`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `websockets_statistics_entries`
--
ALTER TABLE `websockets_statistics_entries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
