-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 29, 2025 at 01:43 AM
-- Server version: 9.3.0
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pendaftaran_kempo`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategoris`
--

CREATE TABLE `kategoris` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usia_awal` int NOT NULL,
  `usia_akhir` int NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tahun_awal` int NOT NULL,
  `tahun_akhir` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategoris`
--

INSERT INTO `kategoris` (`id`, `nama`, `usia_awal`, `usia_akhir`, `user_id`, `created_at`, `updated_at`, `tahun_awal`, `tahun_akhir`) VALUES
(1, 'Kelompok Usia 6 – 9 Tahun (Lahir Tahun 2019 – 2016)', 6, 9, 1, '2025-07-27 22:22:36', '2025-07-27 22:36:53', 2016, 2019),
(2, 'Kelompok Usia 10 – 12 Tahun (Lahir Tahun 2015 – 2013)', 10, 12, 1, '2025-07-27 22:22:49', '2025-07-27 22:37:01', 2013, 2015),
(3, 'Kelompok Usia 13 – 16 Tahun (Lahir Tahun 2012 – 2009) :', 13, 16, 1, '2025-07-27 22:23:03', '2025-07-27 22:37:16', 2009, 2012),
(4, 'Kelompok Usia 15 – 16 Tahun (Lahir Tahun 2010 – 2009)', 15, 16, 1, '2025-07-27 22:23:20', '2025-07-27 22:37:38', 2009, 2010);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_07_26_174308_create_kategoris_table', 1),
(7, '2025_07_28_064051_create_pertandingans_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pertandingans`
--

CREATE TABLE `pertandingans` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pertandingans`
--

INSERT INTO `pertandingans` (`id`, `nama`, `kategori_id`, `user_id`, `jenis_kelamin`, `created_at`, `updated_at`) VALUES
(2, 'Tan’en Perorangan Kyu VI – IV Putra', 1, 1, 'Laki-Laki', '2025-07-28 00:26:05', '2025-07-28 00:26:05'),
(3, 'Tan’en Perorangan Kyu III Putra', 1, 1, 'Laki-Laki', '2025-07-28 00:26:15', '2025-07-28 00:26:15'),
(4, 'Tan’en Perorangan Kyu VI – IV Putri', 1, 1, 'Perempuan', '2025-07-28 00:26:40', '2025-07-28 00:26:40'),
(5, 'Tan’en Perorangan Kyu III Putri', 1, 1, 'Perempuan', '2025-07-28 00:27:42', '2025-07-28 00:27:42'),
(6, 'Tan’en Beregu Kyu VI – Kyu III Pa/Pi/Mix', 1, 1, NULL, '2025-07-28 00:28:33', '2025-07-28 00:28:33'),
(7, 'Tan’en Perorangan ≤ Kyu IV Putri', 2, 1, 'Perempuan', '2025-07-28 00:29:11', '2025-07-28 00:29:11'),
(8, 'Tan’en Perorangan Kyu III Putra', 2, 1, 'Laki-Laki', '2025-07-28 00:29:29', '2025-07-28 00:29:29'),
(9, 'Tan’en Perorangan Kyu II/I Putra', 2, 1, 'Laki-Laki', '2025-07-28 00:29:48', '2025-07-28 00:29:48'),
(10, 'Tan’en Beregu Putra', 2, 1, 'Laki-Laki', '2025-07-28 00:30:11', '2025-07-28 00:30:11'),
(11, 'Embu Pasangan Putra', 2, 1, 'Laki-Laki', '2025-07-28 00:30:23', '2025-07-28 00:30:23'),
(12, 'Embu Beregu Putra', 2, 1, 'Laki-Laki', '2025-07-28 00:30:41', '2025-07-28 00:30:41'),
(13, 'Tan’en Perorangan ≤ Kyu IV Putri', 2, 1, 'Perempuan', '2025-07-28 00:31:47', '2025-07-28 00:31:47'),
(14, 'Tan’en Perorangan Kyu III Putri', 2, 1, 'Perempuan', '2025-07-28 00:31:56', '2025-07-28 00:31:56'),
(15, 'Tan’en Perorangan Kyu II/I Putri', 2, 1, 'Perempuan', '2025-07-28 00:32:06', '2025-07-28 00:32:06'),
(16, 'Tan’en Beregu Putri', 2, 1, 'Perempuan', '2025-07-28 00:32:18', '2025-07-28 00:32:18'),
(17, 'Embu Pasangan Putri', 2, 1, 'Perempuan', '2025-07-28 00:32:27', '2025-07-28 00:32:27'),
(18, 'Embu Beregu Putri', 2, 1, 'Perempuan', '2025-07-28 00:32:44', '2025-07-28 00:32:44'),
(19, 'Embu Pasangan Campuran', 2, 1, NULL, '2025-07-28 00:32:58', '2025-07-28 00:32:58'),
(20, 'Embu Beregu Campuran', 2, 1, NULL, '2025-07-28 00:33:09', '2025-07-28 00:33:09'),
(21, 'Tan’en Perorangan ≤ Kyu IV Putra', 3, 1, 'Laki-Laki', '2025-07-28 00:33:31', '2025-07-28 00:33:31'),
(22, 'Tan’en Perorangan Kyu III Putra', 3, 1, 'Laki-Laki', '2025-07-28 00:33:43', '2025-07-28 00:33:43'),
(23, 'Tan’en Perorangan Kyu II/I Putra', 3, 1, 'Laki-Laki', '2025-07-28 00:34:09', '2025-07-28 00:34:09'),
(24, 'Tan’en Beregu Putra', 3, 1, 'Laki-Laki', '2025-07-28 00:34:20', '2025-07-28 00:34:20'),
(25, 'Embu Pasangan Putra', 3, 1, 'Laki-Laki', '2025-07-28 00:34:31', '2025-07-28 00:34:31'),
(26, 'Embu Beregu Putra', 3, 1, 'Laki-Laki', '2025-07-28 00:34:43', '2025-07-28 00:34:43'),
(27, 'Tan’en Perorangan ≤ Kyu IV Putri', 3, 1, 'Perempuan', '2025-07-28 00:34:54', '2025-07-28 00:34:54'),
(28, 'Tan’en Perorangan Kyu III Putri', 3, 1, 'Perempuan', '2025-07-28 00:35:10', '2025-07-28 00:35:10'),
(29, 'Tan’en Perorangan Kyu II/I Putri', 3, 1, 'Perempuan', '2025-07-28 00:35:23', '2025-07-28 00:35:23'),
(30, 'Tan’en Beregu Putri', 3, 1, 'Perempuan', '2025-07-28 00:35:33', '2025-07-28 00:35:33'),
(31, 'Embu Pasangan Putri', 3, 1, 'Perempuan', '2025-07-28 00:35:44', '2025-07-28 00:35:44'),
(32, 'Embu Beregu Putri', 3, 1, 'Perempuan', '2025-07-28 00:35:55', '2025-07-28 00:35:55'),
(33, 'Embu Pasangan Campuran', 3, 1, NULL, '2025-07-28 00:36:22', '2025-07-28 00:36:22'),
(34, 'Embu Beregu Campuran', 3, 1, NULL, '2025-07-28 00:36:36', '2025-07-28 00:36:36'),
(35, 'Randori Putra Kelas 40 - 45 Kg', 4, 1, 'Laki-Laki', '2025-07-28 00:36:51', '2025-07-28 00:36:51'),
(36, 'Randori Putra Kelas 45 - 50 Kg', 4, 1, 'Laki-Laki', '2025-07-28 00:37:03', '2025-07-28 00:37:03'),
(37, 'Randori Putra Kelas 50 - 55 Kg', 4, 1, 'Laki-Laki', '2025-07-28 00:37:55', '2025-07-28 00:37:55'),
(38, 'Randori Putra Kelas 55 - 60 Kg', 4, 1, 'Laki-Laki', '2025-07-28 00:38:15', '2025-07-28 00:38:15'),
(39, 'Randori Putra Kelas 60 - 65 Kg', 4, 1, 'Laki-Laki', '2025-07-28 00:38:26', '2025-07-28 00:38:26'),
(40, 'Randori Putra Kelas 65 - 70 Kg', 4, 1, 'Laki-Laki', '2025-07-28 00:38:37', '2025-07-28 00:38:37'),
(41, 'Randori Putri Kelas 40 - 45 Kg', 4, 1, 'Perempuan', '2025-07-28 00:38:47', '2025-07-28 00:38:47'),
(42, 'Randori Putri Kelas 45 - 50 Kg', 4, 1, 'Perempuan', '2025-07-28 00:38:56', '2025-07-28 00:38:56'),
(43, 'Randori Putri Kelas 50 - 55 Kg', 4, 1, 'Perempuan', '2025-07-28 00:39:11', '2025-07-28 00:39:11'),
(44, 'Randori Putri Kelas 55 - 60 Kg', 4, 1, 'Perempuan', '2025-07-28 00:39:53', '2025-07-28 00:39:53'),
(45, 'Randori Putri Kelas 60 - 65 Kg', 4, 1, 'Perempuan', '2025-07-28 00:40:09', '2025-07-28 00:40:09'),
(46, 'Randori Putri Kelas 65 - 70 K', 4, 1, 'Perempuan', '2025-07-28 00:40:20', '2025-07-28 00:40:20');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('DoUV58YOxvmZS83omPwOvbaKovEJs7rp4HTjJW1U', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMEdmZUprSzBVaGczbmc3VFc5WUJ3V3Vvd3FsUGFhQWZUT0VZY1E1eCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9wZW5kYWZ0YXJhbi1rZW1wby50ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753692473);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@email.com', '2025-07-27 22:15:10', '$2y$12$RmFwcjF/nx/MCeXvV1E0fuIFdqHajUMlT66s7TauSCNua3ZiiPode', 1, 'qJY7XZ92Ln9iSMhhjCFcoFAKtOIG797nDq0ms7rFQmF9J2rv8A2fkcO0Rrto', '2025-07-27 22:15:11', '2025-07-27 22:15:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategoris`
--
ALTER TABLE `kategoris`
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
-- Indexes for table `pertandingans`
--
ALTER TABLE `pertandingans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategoris`
--
ALTER TABLE `kategoris`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pertandingans`
--
ALTER TABLE `pertandingans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
