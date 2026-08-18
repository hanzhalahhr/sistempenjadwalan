-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Agu 2026 pada 17.59
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_penjadwalan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `dosen`
--

DROP TABLE IF EXISTS `dosen`;
CREATE TABLE `dosen` (
  `id` bigint(20) NOT NULL,
  `nidn` varchar(30) DEFAULT NULL,
  `nama_dosen` varchar(150) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dosen`
--

INSERT INTO `dosen` (`id`, `nidn`, `nama_dosen`, `jabatan`, `created_at`, `updated_at`) VALUES
(2, '0321057001', 'Anita Muliawati S.Kom., MTI.', 'Lektor', '2026-07-21 03:27:03', '2026-07-21 03:27:03'),
(3, '0321027401', 'Ati Zaidiah S.Kom., MTI.', 'Lektor', '2026-07-21 03:27:03', '2026-07-21 03:27:03'),
(4, '0107077801', 'Bambang Saras Yulistiawan Dr., S.T., M.Kom', 'Lektor', '2026-07-21 03:27:03', '2026-07-21 03:27:03'),
(5, '0023088701', 'Catur Nugrahaeni P. D. M.Kom', 'Lektor', '2026-07-21 03:27:03', '2026-07-21 20:27:16'),
(6, '0308097401', 'Erly Krisnanik S.Kom, MM.', 'Lektor', '2026-07-21 03:27:03', '2026-07-21 03:27:03'),
(8, '0025068104', 'I Wayan Widi Pradnyana S.Kom., MTI.', 'Lektor', '2026-07-21 03:27:03', '2026-07-21 03:27:03'),
(9, '322087501', 'Kraugusteeliana SKom., MKom., MM.', 'Lektor', '2026-07-21 03:27:03', '2026-07-21 03:27:03'),
(10, '0420018601', 'Rio Wirawan S.Kom., MMSI.', 'Lektor', '2026-07-21 03:27:03', '2026-07-21 03:27:03'),
(12, '0429038801', 'Ruth Mariana Bunga Wadu S.Kom., MMSI.', 'Lektor', '2026-07-21 03:27:03', '2026-07-21 03:27:03'),
(13, '0419107003', 'Tjahjanto Dr., S.Kom., M.M.', 'Lektor', '2026-07-21 03:27:03', '2026-07-21 03:27:03'),
(18, '0215128702', 'Susanto Dr., M. Kom', 'S3', '2026-07-21 03:27:03', '2026-07-29 07:58:00'),
(19, '4241776677230140', 'Ade Hikma Tiana S.Kom.,', 'S2', '2026-07-21 03:27:03', '2026-07-21 03:27:03'),
(27, '0028088401', 'Andhika Octa Indarso S.Kom., MMSI.', 'Lektor', '2026-07-30 05:14:50', '2026-07-30 05:45:01'),
(30, '0023088312', 'Arif Setiawan, S.Kom., M.Kom.', 'Lektor', '2026-08-15 09:56:56', '2026-08-15 09:57:14'),
(31, '0033088561', 'Budi Pratama, S.Kom., M.T.', 'Lektor', '2026-08-15 09:57:56', '2026-08-15 09:58:06'),
(32, '0056088572', 'Dinda Permatasari, S.Kom., M.Kom.', 'Asisten Ahli', '2026-08-15 09:58:58', '2026-08-15 09:58:58'),
(33, '0046088571', 'Fajar Nugroho, S.Kom., MMSI.', 'Lektor', '2026-08-15 09:59:24', '2026-08-15 10:02:23'),
(34, '0023608857', 'Galih Ramadhan, S.Kom., M.Kom.', 'Asisten Ahli', '2026-08-15 09:59:45', '2026-08-15 10:02:33'),
(35, '0076088632', 'Intan Maharani, S.Kom., M.T.', 'Lektor', '2026-08-15 10:00:06', '2026-08-15 10:03:00'),
(36, '04191073427', 'Joko Santoso, S.Kom., M.Kom.', 'Lektor', '2026-08-15 10:00:34', '2026-08-15 10:03:51'),
(37, '0107077982', 'Laila Nuraini, S.Kom., MMSI.', 'Asisten Ahli', '2026-08-15 10:01:03', '2026-08-15 10:04:07'),
(38, '00120887341', 'Muhammad Rizky, S.Kom., M.Kom.', 'Lektor', '2026-08-15 10:01:32', '2026-08-15 10:04:33'),
(39, '0081308821', 'Nabila Putri, S.Kom., M.T.', 'Asisten Ahli', '2026-08-15 10:01:58', '2026-08-15 10:04:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dosen_ketersediaan`
--

DROP TABLE IF EXISTS `dosen_ketersediaan`;
CREATE TABLE `dosen_ketersediaan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dosen_id` bigint(20) UNSIGNED NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `dosen_ketersediaan`
--

INSERT INTO `dosen_ketersediaan` (`id`, `dosen_id`, `hari`, `jam_mulai`, `jam_selesai`, `created_at`, `updated_at`) VALUES
(1, 1, 'Senin', '07:30:00', '09:10:00', '2026-07-28 00:35:42', '2026-07-28 00:35:42'),
(2, 1, 'Rabu', '09:20:00', '11:00:00', '2026-07-28 00:35:52', '2026-07-28 00:35:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dosen_mata_kuliah`
--

DROP TABLE IF EXISTS `dosen_mata_kuliah`;
CREATE TABLE `dosen_mata_kuliah` (
  `dosen_id` bigint(20) NOT NULL,
  `mata_kuliah_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `dosen_mata_kuliah`
--

INSERT INTO `dosen_mata_kuliah` (`dosen_id`, `mata_kuliah_id`) VALUES
(3, 7),
(19, 8),
(2, 6),
(4, 4),
(5, 2),
(6, 10),
(8, 5),
(9, 1),
(10, 3),
(12, 3),
(13, 7),
(18, 8),
(27, 9),
(30, 33),
(31, 34),
(32, 35),
(33, 36),
(34, 37),
(35, 38),
(36, 39),
(37, 40),
(38, 44),
(39, 45);

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `generate_jadwal`
--

DROP TABLE IF EXISTS `generate_jadwal`;
CREATE TABLE `generate_jadwal` (
  `id` bigint(20) NOT NULL,
  `semester_akademik_id` bigint(20) NOT NULL,
  `generate_ke` int(11) NOT NULL,
  `kode_generate` varchar(255) DEFAULT NULL,
  `tanggal_generate` datetime NOT NULL,
  `status` enum('Berhasil','Gagal') DEFAULT 'Berhasil',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal`
--

DROP TABLE IF EXISTS `jadwal`;
CREATE TABLE `jadwal` (
  `id` bigint(20) NOT NULL,
  `generate_jadwal_id` bigint(20) NOT NULL,
  `kelas_perkuliahan_id` bigint(20) NOT NULL,
  `ruangan_id` bigint(20) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_dosen`
--

DROP TABLE IF EXISTS `jadwal_dosen`;
CREATE TABLE `jadwal_dosen` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jadwal_id` bigint(20) NOT NULL,
  `dosen_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas_perkuliahan`
--

DROP TABLE IF EXISTS `kelas_perkuliahan`;
CREATE TABLE `kelas_perkuliahan` (
  `id` bigint(20) NOT NULL,
  `mata_kuliah_id` bigint(20) NOT NULL,
  `prodi_id` bigint(20) NOT NULL,
  `semester_akademik_id` bigint(20) NOT NULL,
  `angkatan` int(11) NOT NULL,
  `nama_kelas` varchar(20) NOT NULL,
  `jumlah_mahasiswa` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelas_perkuliahan`
--

INSERT INTO `kelas_perkuliahan` (`id`, `mata_kuliah_id`, `prodi_id`, `semester_akademik_id`, `angkatan`, `nama_kelas`, `jumlah_mahasiswa`, `created_at`, `updated_at`) VALUES
(1, 1, 8, 2, 2026, '1E', 41, '2026-07-21 06:38:52', '2026-08-18 09:28:17'),
(2, 1, 5, 1, 2026, '1F', 35, '2026-07-28 03:05:47', '2026-07-28 03:05:47'),
(5, 2, 6, 1, 2026, '1A', 41, '2026-07-28 10:43:31', '2026-07-30 21:36:27'),
(6, 3, 6, 1, 2026, '1B', 39, '2026-07-28 10:49:02', '2026-07-30 21:36:35'),
(7, 4, 8, 1, 2026, '1E', 40, '2026-07-28 10:49:22', '2026-07-30 21:36:44'),
(8, 5, 5, 1, 2026, '1A', 40, '2026-07-28 10:49:37', '2026-07-30 21:36:56'),
(9, 6, 5, 1, 2026, '1A', 41, '2026-07-28 10:49:49', '2026-07-30 21:37:05'),
(10, 7, 5, 1, 2026, '1G', 40, '2026-07-28 10:50:11', '2026-07-30 21:37:16'),
(11, 8, 5, 1, 2026, '2A', 39, '2026-07-28 10:50:21', '2026-07-30 21:37:25'),
(12, 9, 5, 1, 2026, '2A', 40, '2026-07-28 10:50:31', '2026-08-15 09:35:57'),
(13, 10, 6, 1, 2026, '2C', 39, '2026-07-28 10:50:41', '2026-07-30 21:37:45'),
(32, 7, 7, 2, 2025, '2D', 39, '2026-07-30 12:02:35', '2026-07-30 21:37:53'),
(33, 33, 8, 2, 2026, '3A', 39, '2026-08-15 09:49:49', '2026-08-15 10:08:00'),
(34, 34, 8, 2, 2026, '3B', 40, '2026-08-15 09:50:16', '2026-08-15 10:09:04'),
(35, 35, 8, 2, 2025, '3C', 40, '2026-08-15 09:50:48', '2026-08-15 10:11:20'),
(36, 36, 5, 2, 2026, '1A', 38, '2026-08-15 09:51:23', '2026-08-15 10:12:14'),
(37, 37, 6, 2, 2026, '1B', 40, '2026-08-15 09:51:52', '2026-08-15 10:13:01'),
(38, 38, 6, 2, 2026, '1C', 41, '2026-08-15 09:52:14', '2026-08-15 10:13:42'),
(39, 39, 8, 1, 2025, '1C', 38, '2026-08-15 09:52:41', '2026-08-15 10:14:47'),
(40, 40, 8, 1, 2026, '2A', 40, '2026-08-15 09:53:01', '2026-08-15 10:15:27'),
(41, 40, 5, 2, 2026, '2B', 37, '2026-08-15 09:53:28', '2026-08-15 10:16:29'),
(42, 44, 8, 1, 2025, '2C', 37, '2026-08-15 09:54:15', '2026-08-15 10:17:34'),
(43, 45, 8, 1, 2026, '3A', 39, '2026-08-15 09:54:41', '2026-08-15 10:18:20'),
(44, 46, 8, 1, 2025, '3B', 36, '2026-08-15 09:55:13', '2026-08-15 10:20:10'),
(45, 47, 8, 1, 2025, '3C', 39, '2026-08-15 09:55:41', '2026-08-15 10:21:16'),
(46, 41, 5, 1, 2026, 'A', 0, '2026-08-18 09:10:56', '2026-08-18 09:10:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas_perkuliahan_dosen`
--

DROP TABLE IF EXISTS `kelas_perkuliahan_dosen`;
CREATE TABLE `kelas_perkuliahan_dosen` (
  `id` bigint(20) NOT NULL,
  `kelas_perkuliahan_id` bigint(20) NOT NULL,
  `dosen_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kelas_perkuliahan_dosen`
--

INSERT INTO `kelas_perkuliahan_dosen` (`id`, `kelas_perkuliahan_id`, `dosen_id`, `created_at`, `updated_at`) VALUES
(4, 2, 9, NULL, NULL),
(5, 5, 5, NULL, NULL),
(6, 6, 10, NULL, NULL),
(7, 6, 12, NULL, NULL),
(8, 7, 4, NULL, NULL),
(9, 8, 8, NULL, NULL),
(10, 9, 2, NULL, NULL),
(11, 10, 3, NULL, NULL),
(12, 10, 13, NULL, NULL),
(13, 11, 19, NULL, NULL),
(14, 11, 18, NULL, NULL),
(15, 13, 6, NULL, NULL),
(17, 32, 19, NULL, NULL),
(18, 1, 6, NULL, NULL),
(19, 12, 13, NULL, NULL),
(20, 33, 30, NULL, NULL),
(21, 34, 9, NULL, NULL),
(22, 35, 32, NULL, NULL),
(23, 36, 33, NULL, NULL),
(24, 37, 34, NULL, NULL),
(25, 38, 35, NULL, NULL),
(26, 39, 36, NULL, NULL),
(27, 40, 37, NULL, NULL),
(28, 41, 37, NULL, NULL),
(29, 42, 38, NULL, NULL),
(30, 43, 39, NULL, NULL),
(31, 44, 2, NULL, NULL),
(32, 45, 38, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `konfigurasi_hari_kuliah`
--

DROP TABLE IF EXISTS `konfigurasi_hari_kuliah`;
CREATE TABLE `konfigurasi_hari_kuliah` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `semester_akademik_id` bigint(20) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `konfigurasi_hari_kuliah`
--

INSERT INTO `konfigurasi_hari_kuliah` (`id`, `semester_akademik_id`, `hari`, `is_active`, `created_at`, `updated_at`) VALUES
(325, 1, 'Senin', 1, '2026-08-18 13:49:45', '2026-08-18 13:49:45'),
(326, 1, 'Selasa', 1, '2026-08-18 13:49:45', '2026-08-18 13:49:45'),
(327, 1, 'Rabu', 1, '2026-08-18 13:49:45', '2026-08-18 13:49:45'),
(328, 1, 'Kamis', 1, '2026-08-18 13:49:45', '2026-08-18 13:49:45'),
(329, 1, 'Jumat', 1, '2026-08-18 13:49:45', '2026-08-18 13:49:45'),
(330, 1, 'Sabtu', 0, '2026-08-18 13:49:45', '2026-08-18 13:49:45'),
(337, 2, 'Senin', 1, '2026-08-18 13:51:12', '2026-08-18 13:51:12'),
(338, 2, 'Selasa', 1, '2026-08-18 13:51:12', '2026-08-18 13:51:12'),
(339, 2, 'Rabu', 1, '2026-08-18 13:51:12', '2026-08-18 13:51:12'),
(340, 2, 'Kamis', 1, '2026-08-18 13:51:12', '2026-08-18 13:51:12'),
(341, 2, 'Jumat', 1, '2026-08-18 13:51:12', '2026-08-18 13:51:12'),
(342, 2, 'Sabtu', 0, '2026-08-18 13:51:12', '2026-08-18 13:51:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `konfigurasi_jadwal`
--

DROP TABLE IF EXISTS `konfigurasi_jadwal`;
CREATE TABLE `konfigurasi_jadwal` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `semester_akademik_id` bigint(20) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `konfigurasi_jadwal`
--

INSERT INTO `konfigurasi_jadwal` (`id`, `semester_akademik_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 0, '2026-08-15 05:45:47', '2026-08-18 13:51:12'),
(2, 2, 1, '2026-08-15 05:46:04', '2026-08-18 13:51:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

DROP TABLE IF EXISTS `mahasiswa`;
CREATE TABLE `mahasiswa` (
  `id` int(11) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `nim`, `nama`, `username`, `user_password`, `created_at`) VALUES
(1, '20240001', 'Ajeng', 'ajeng123', 'ajengupn2', '2026-07-20 14:48:59'),
(2, '20240002', 'Hanz', 'hanz21', 'hanz1711', '2026-07-20 14:48:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mata_kuliah`
--

DROP TABLE IF EXISTS `mata_kuliah`;
CREATE TABLE `mata_kuliah` (
  `id` bigint(20) NOT NULL,
  `kode_mk` varchar(20) NOT NULL,
  `nama_mk` varchar(150) NOT NULL,
  `semester` int(11) NOT NULL,
  `sks` int(11) NOT NULL,
  `jenis_mk` enum('Teori','Praktikum') DEFAULT 'Teori',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mata_kuliah`
--

INSERT INTO `mata_kuliah` (`id`, `kode_mk`, `nama_mk`, `semester`, `sks`, `jenis_mk`, `created_at`, `updated_at`) VALUES
(1, 'ASI001', 'Audit Sistem Informasi', 5, 2, 'Teori', '2026-07-21 06:10:07', '2026-07-29 23:31:55'),
(2, 'PW002', 'Pemrograman Web', 5, 1, 'Teori', '2026-07-21 06:10:07', '2026-07-21 06:10:07'),
(3, 'PPW003', 'Praktikum Pemrograman Web', 5, 2, 'Praktikum', '2026-07-21 06:10:07', '2026-07-31 05:42:12'),
(4, 'TKI004', 'Tata Kelola TI', 5, 3, 'Teori', '2026-07-21 06:10:07', '2026-07-21 06:10:07'),
(5, 'TD005', 'Transformasi Digital', 5, 2, 'Teori', '2026-07-21 06:10:07', '2026-07-21 06:10:07'),
(6, 'KSI006', 'Konsep Sistem Informasi', 5, 2, 'Teori', '2026-07-21 06:10:07', '2026-07-21 06:10:07'),
(7, 'CRM007', 'Customer Relationship Management', 5, 3, 'Teori', '2026-07-21 06:10:07', '2026-07-21 06:10:07'),
(8, 'PBD008', 'Pengantar Basis Data', 5, 2, 'Teori', '2026-07-21 06:10:07', '2026-07-21 06:10:07'),
(9, 'PPBD009', 'Praktikum Pengantar Basis Data', 5, 1, 'Praktikum', '2026-07-21 06:10:07', '2026-07-31 05:42:12'),
(10, 'SPK010', 'Sistem Pendukung Keputusan', 4, 3, 'Teori', '2026-07-21 06:10:07', '2026-07-30 05:02:12'),
(33, 'MK011', 'Analisis dan Perancangan Sistem', 4, 3, 'Teori', '2026-08-15 09:49:49', '2026-08-15 09:49:49'),
(34, 'MK012', 'Rekayasa Perangkat Lunak', 4, 3, 'Teori', '2026-08-15 09:50:16', '2026-08-15 09:50:16'),
(35, 'MK013', 'Praktikum Rekayasa Perangkat Lunak', 4, 2, 'Teori', '2026-08-15 09:50:42', '2026-08-15 09:50:42'),
(36, 'MK014', 'Interaksi Manusia dan Komputer', 4, 2, 'Teori', '2026-08-15 09:51:23', '2026-08-15 09:51:23'),
(37, 'MK015', 'Jaringan Komputer', 4, 3, 'Teori', '2026-08-15 09:51:52', '2026-08-15 09:51:52'),
(38, 'MK016', 'Praktikum Jaringan Komputer', 4, 2, 'Teori', '2026-08-15 09:52:14', '2026-08-15 09:52:14'),
(39, 'MK017', 'Pemrograman Berorientasi Objek', 3, 3, 'Teori', '2026-08-15 09:52:41', '2026-08-15 09:52:41'),
(40, 'MK018', 'Struktur Data', 3, 3, 'Teori', '2026-08-15 09:53:01', '2026-08-15 09:53:01'),
(41, 'MK019', 'Struktur Data', 4, 3, 'Teori', '2026-08-15 09:53:28', '2026-08-15 09:53:28'),
(44, 'MK020', 'Sistem Operasi', 3, 3, 'Teori', '2026-08-15 09:54:15', '2026-08-15 09:54:15'),
(45, 'MK021', 'Keamanan Sistem Informasi', 5, 3, 'Teori', '2026-08-15 09:54:41', '2026-08-15 09:55:21'),
(46, 'MK022', 'Manajemen Proyek TI', 5, 2, 'Teori', '2026-08-15 09:55:13', '2026-08-15 09:55:13'),
(47, 'MK023', 'Data Mining', 5, 3, 'Teori', '2026-08-15 09:55:41', '2026-08-15 09:55:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_07_19_183931_create_personal_access_tokens_table', 1),
(5, '2026_07_21_104601_create_dosen_mata_kuliah_table', 2),
(6, '2026_07_28_042227_add_angkatan_to_kelas_perkuliahan_table', 3),
(7, '2026_07_28_042556_create_kelas_perkuliahan_dosen_table', 4),
(8, '2026_07_28_050010_add_jenis_to_mata_kuliah_table', 5),
(9, '2026_07_28_050634_add_tipe_ruangan_to_ruangan_table', 6),
(10, '2026_07_28_052836_create_dosen_ketersediaan_table', 7),
(11, '2026_07_28_055323_create_slot_waktu_kuliah_table', 8),
(13, '2026_07_28_055933_create_jadwal_dosen_table', 9),
(15, '2026_07_28_065414_fix_created_by_type_in_generate_jadwal_table', 10),
(17, '2026_07_31_025113_add_urutan_to_slot_waktu_kuliah_table', 11),
(18, '2026_08_01_115344_add_kode_generate_to_generate_jadwal_table', 12),
(19, '2026_08_15_113326_create_konfigurasi_hari_kuliah_table', 13),
(20, '2026_08_15_114655_create_konfigurasi_jadwal_table', 14);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Mahasiswa', 1, 'auth_token', '1123b7c0c033aade60fdc39be04035fcb6de9fc603f0f3df83e687451918a425', '[\"*\"]', NULL, NULL, '2026-07-20 02:44:13', '2026-07-20 02:44:13'),
(2, 'App\\Models\\Mahasiswa', 2, 'auth_token', '3c779a718f6f1b9d724a3f7e2855cb3e9a4223a6dbe40d695a1e4f31a6fcf739', '[\"*\"]', NULL, NULL, '2026-07-20 02:45:57', '2026-07-20 02:45:57'),
(3, 'App\\Models\\Mahasiswa', 2, 'auth_token', 'b25da29d68c923ea8874100e64720733d259bf5a19ed300fa8bd93fae55180f8', '[\"*\"]', NULL, NULL, '2026-07-20 02:59:54', '2026-07-20 02:59:54'),
(4, 'App\\Models\\Mahasiswa', 1, 'auth_token', '72bee25d8d4ceabfebc59b008992f330eeff0480dcd7431dcd6604fa2436090b', '[\"*\"]', NULL, NULL, '2026-07-20 03:00:17', '2026-07-20 03:00:17'),
(5, 'App\\Models\\Mahasiswa', 1, 'auth_token', 'ed30f584991402c78063f087669b0a036e10810e04ef87007c4970683ba940ba', '[\"*\"]', NULL, NULL, '2026-07-20 03:03:41', '2026-07-20 03:03:41'),
(6, 'App\\Models\\Mahasiswa', 1, 'auth_token', '08f14c0b700985083775874ae1db22cbfa49629370afc1bfb01e1f729ada5fc1', '[\"*\"]', NULL, NULL, '2026-07-20 03:04:39', '2026-07-20 03:04:39'),
(7, 'App\\Models\\Mahasiswa', 1, 'auth_token', 'fb3be75d5121fadc17c820f334845d2651c3273c976ce78553ac6c3887eb0f3d', '[\"*\"]', NULL, NULL, '2026-07-20 03:15:53', '2026-07-20 03:15:53'),
(8, 'App\\Models\\Mahasiswa', 2, 'auth_token', '5960568add9e03304899ac20ca4f00ba933c65441eb2e366b6eb92c67e7a945d', '[\"*\"]', NULL, NULL, '2026-07-20 03:36:42', '2026-07-20 03:36:42'),
(9, 'App\\Models\\Mahasiswa', 2, 'auth_token', '4e1ec72cbe0ae01c1ecd6a26d6a6baff404f49116eee625ed5f491c94e52e844', '[\"*\"]', NULL, NULL, '2026-07-20 03:37:06', '2026-07-20 03:37:06'),
(10, 'App\\Models\\Mahasiswa', 2, 'auth_token', '087ac6012b07f3375880cb8c21c4b81d1ac5314c8aad32e47c4fe56f89688c2f', '[\"*\"]', NULL, NULL, '2026-07-20 07:13:54', '2026-07-20 07:13:54'),
(11, 'App\\Models\\Mahasiswa', 2, 'auth_token', '43469502da6e0392b921357df15e3b3bfa041e8bf538dd8ed0b6e72941cb0026', '[\"*\"]', NULL, NULL, '2026-07-20 07:49:44', '2026-07-20 07:49:44'),
(12, 'App\\Models\\Mahasiswa', 2, 'auth_token', '7fce75268933c078e1562cd772db09df2a17428170e482f9952dacc9709784c9', '[\"*\"]', NULL, NULL, '2026-07-21 07:51:35', '2026-07-21 07:51:35'),
(13, 'App\\Models\\Mahasiswa', 2, 'auth_token', '5f9b1da743366880d2b3286d8483ec3ef608aab203f0de4eee812870b51d1675', '[\"*\"]', NULL, NULL, '2026-07-21 09:39:58', '2026-07-21 09:39:58'),
(14, 'App\\Models\\Mahasiswa', 2, 'auth_token', '80355501e825dec8f806e968e7c8ee7a443891bc5cf9054f2d508590caed2c02', '[\"*\"]', NULL, NULL, '2026-07-21 18:48:47', '2026-07-21 18:48:47'),
(15, 'App\\Models\\Mahasiswa', 2, 'auth_token', '1fb5d72e2469e2cbebb6c6b23444fab4915a4a8299d537977fea274e5d6a09aa', '[\"*\"]', NULL, NULL, '2026-07-28 19:11:04', '2026-07-28 19:11:04'),
(16, 'App\\Models\\Mahasiswa', 2, 'auth_token', '6bf0c8f275f3704ace890e19e3aa9de3daca5d35a659e20f127213e38565dac8', '[\"*\"]', NULL, NULL, '2026-07-28 19:11:06', '2026-07-28 19:11:06'),
(17, 'App\\Models\\Mahasiswa', 2, 'auth_token', '50e8e380a5f47eb68015e4f07ea9718fdba9952a11bf6f073557dcd39ebb98dc', '[\"*\"]', NULL, NULL, '2026-07-28 19:11:06', '2026-07-28 19:11:06'),
(18, 'App\\Models\\Mahasiswa', 2, 'auth_token', '5fa4ba36b453b4ec3e2f0027ee36a8a8a831bd008d779ddff5fd0355a80724bd', '[\"*\"]', NULL, NULL, '2026-07-28 19:11:07', '2026-07-28 19:11:07'),
(19, 'App\\Models\\Mahasiswa', 2, 'auth_token', 'f375bb8de3567316e8210a3757db8ac5209bbee2c9c89b081110e954da46e881', '[\"*\"]', NULL, NULL, '2026-07-28 21:19:56', '2026-07-28 21:19:56'),
(20, 'App\\Models\\Mahasiswa', 2, 'auth_token', '39753439af286270a4e9cb62330c69ce2950097d809448eb6975215c483347fc', '[\"*\"]', NULL, NULL, '2026-07-29 11:32:38', '2026-07-29 11:32:38'),
(21, 'App\\Models\\Mahasiswa', 2, 'auth_token', '1d282d1c488740979d115b38bfb6b7d04c8d06f17d76beaf2253b2414d3613e2', '[\"*\"]', NULL, NULL, '2026-07-30 21:33:31', '2026-07-30 21:33:31'),
(22, 'App\\Models\\Mahasiswa', 2, 'auth_token', '212ae8d0aa45e682bb6d98a4b851b79274da9d1af7d15398c29340f72c4a9c2e', '[\"*\"]', NULL, NULL, '2026-08-01 04:17:24', '2026-08-01 04:17:24'),
(23, 'App\\Models\\Mahasiswa', 2, 'auth_token', 'adec705d6464a9307aa84123f11b9db6c4e902d31316635b2ed6e4456d4302e0', '[\"*\"]', NULL, NULL, '2026-08-01 18:07:00', '2026-08-01 18:07:00'),
(24, 'App\\Models\\Mahasiswa', 2, 'auth_token', 'a876202f750e0ebbe38bb155cb0b150b8fe1d98f6557e0deec52f48553b3612e', '[\"*\"]', NULL, NULL, '2026-08-01 18:33:10', '2026-08-01 18:33:10'),
(25, 'App\\Models\\Mahasiswa', 2, 'auth_token', 'cf8b030f49bb42ebc04ea25c1217a3cee84cdef8a39b6cd3d525926f7117bba0', '[\"*\"]', NULL, NULL, '2026-08-01 18:42:00', '2026-08-01 18:42:00'),
(26, 'App\\Models\\Mahasiswa', 2, 'auth_token', '9c1c3cf22bb99bb06fab5fe7327486d1802bba3fb6cc862783b8e0cb4580c966', '[\"*\"]', NULL, NULL, '2026-08-01 18:42:01', '2026-08-01 18:42:01'),
(27, 'App\\Models\\Mahasiswa', 2, 'auth_token', '77e75432498df027c129969ba153315b9761b7e9afb801b28b0a70e887d14529', '[\"*\"]', NULL, NULL, '2026-08-01 18:47:56', '2026-08-01 18:47:56'),
(28, 'App\\Models\\Mahasiswa', 2, 'auth_token', '50cfda35873b13901dd3023ef1df674fbc28985fd1c1d1f5f84c430b2fa946be', '[\"*\"]', NULL, NULL, '2026-08-01 18:48:17', '2026-08-01 18:48:17'),
(29, 'App\\Models\\Mahasiswa', 2, 'auth_token', 'e05141cc667eee2d2c95e4b5a5d9c908bf8d5c4bda7fce5578b66bf05944ac90', '[\"*\"]', NULL, NULL, '2026-08-09 07:09:22', '2026-08-09 07:09:22'),
(30, 'App\\Models\\Mahasiswa', 2, 'auth_token', '0a187ade3a27536c21f850e80f66b5cba16c4d6b1431860153f2baffe16bbb98', '[\"*\"]', NULL, NULL, '2026-08-14 04:27:17', '2026-08-14 04:27:17'),
(31, 'App\\Models\\Mahasiswa', 2, 'auth_token', '6f0a25518ea639ecf2020b61323abf72e4f1130b60fa9962d9e66448511d4ab6', '[\"*\"]', NULL, NULL, '2026-08-15 17:26:31', '2026-08-15 17:26:31'),
(32, 'App\\Models\\Mahasiswa', 2, 'auth_token', '8c1683971e913061884e25e3d5d42d364db417a214d880d3f4ef15255f8aa737', '[\"*\"]', NULL, NULL, '2026-08-18 09:30:10', '2026-08-18 09:30:10'),
(33, 'App\\Models\\Mahasiswa', 2, 'auth_token', '355b8eb942aeb4c0efdb1823f87f08b8ff0e766e91b4fe12affff1de1ae3e90e', '[\"*\"]', NULL, NULL, '2026-08-18 09:37:09', '2026-08-18 09:37:09'),
(34, 'App\\Models\\Mahasiswa', 2, 'auth_token', 'b7f78a408a146cdccab951b03411c4752a36e8ffb01cb2959f62ff72e455d035', '[\"*\"]', NULL, NULL, '2026-08-18 09:57:17', '2026-08-18 09:57:17'),
(35, 'App\\Models\\Mahasiswa', 2, 'auth_token', 'c6fd6cce059314ce85c2c6881c83be91752ca3da9f1a9c1ccb4be17288c08e0e', '[\"*\"]', NULL, NULL, '2026-08-18 09:58:51', '2026-08-18 09:58:51'),
(36, 'App\\Models\\Mahasiswa', 2, 'auth_token', '61744cf050a6a1c76e62908e3eb6f506cad2ddf470f72626419276c4c2074325', '[\"*\"]', NULL, NULL, '2026-08-18 10:02:34', '2026-08-18 10:02:34'),
(37, 'App\\Models\\Mahasiswa', 2, 'auth_token', '6ee1f963ee5ebaf8da32e03ec7f21a13fdbc18730f3d812c34a4dab789da3f46', '[\"*\"]', NULL, NULL, '2026-08-18 10:04:37', '2026-08-18 10:04:37'),
(38, 'App\\Models\\Mahasiswa', 2, 'auth_token', '3674e68874e59838fbe590b918004ec6da665e38a0b8a4a01d038c2ed189fd6c', '[\"*\"]', NULL, NULL, '2026-08-18 10:33:34', '2026-08-18 10:33:34'),
(39, 'App\\Models\\Mahasiswa', 2, 'auth_token', 'c334d578932071fada537dfbf5c649e1a06ea768ddcdad7b22c36db9d874ad3c', '[\"*\"]', '2026-08-18 15:08:51', NULL, '2026-08-18 14:28:52', '2026-08-18 15:08:51'),
(40, 'App\\Models\\Mahasiswa', 2, 'auth_token', 'fe0b31172b0334df1f8d6d66308d3ac24a3d95d1551e0a4a099a649933f594d9', '[\"*\"]', '2026-08-18 15:09:34', NULL, '2026-08-18 15:09:00', '2026-08-18 15:09:34'),
(41, 'App\\Models\\Mahasiswa', 2, 'auth_token', 'a0258258f663c2a2f31d56f1657325d75e9bcb94ebcc78da649c40f2e9906f05', '[\"*\"]', '2026-08-18 15:09:47', NULL, '2026-08-18 15:09:42', '2026-08-18 15:09:47'),
(42, 'App\\Models\\Mahasiswa', 2, 'auth_token', 'efaf74c1d5bd76900034773cbf5b2967924560ddf6370998aba9df28d23ea063', '[\"*\"]', '2026-08-18 15:12:32', NULL, '2026-08-18 15:09:55', '2026-08-18 15:12:32'),
(43, 'App\\Models\\Mahasiswa', 2, 'auth_token', 'b96264a7e459bd43119bc36cf9524632c81b828281f133aef4ebf11ef4e6ee1f', '[\"*\"]', '2026-08-18 15:25:58', NULL, '2026-08-18 15:12:41', '2026-08-18 15:25:58'),
(44, 'App\\Models\\Mahasiswa', 2, 'auth_token', '0962541ec00e30ab5b79e9e46e80df94978e341138652c08c95bdade7dde1445', '[\"*\"]', '2026-08-18 15:26:41', NULL, '2026-08-18 15:26:04', '2026-08-18 15:26:41'),
(45, 'App\\Models\\Mahasiswa', 2, 'auth_token', '2e7a209f131232f45f47d1d5e9227843f7cba4d9bc96775bc795650436ee8408', '[\"*\"]', '2026-08-18 15:29:08', NULL, '2026-08-18 15:28:20', '2026-08-18 15:29:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `prodi`
--

DROP TABLE IF EXISTS `prodi`;
CREATE TABLE `prodi` (
  `id` bigint(20) NOT NULL,
  `kode_prodi` varchar(20) NOT NULL,
  `nama_prodi` varchar(100) NOT NULL,
  `jenjang` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `prodi`
--

INSERT INTO `prodi` (`id`, `kode_prodi`, `nama_prodi`, `jenjang`, `created_at`, `updated_at`) VALUES
(5, 'SI', 'Sistem Informasi', 'S1', '2026-07-21 04:07:57', '2026-07-21 04:07:57'),
(6, 'SI', 'Sistem Informasi', 'D3', '2026-07-21 04:07:57', '2026-07-21 04:07:57'),
(7, 'SD', 'Sains Data', 'S1', '2026-07-21 04:07:57', '2026-07-21 04:07:57'),
(8, 'IF', 'Informatika', 'S1', '2026-07-21 04:07:57', '2026-07-21 04:07:57');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ruangan`
--

DROP TABLE IF EXISTS `ruangan`;
CREATE TABLE `ruangan` (
  `id` bigint(20) NOT NULL,
  `nama_ruangan` varchar(100) NOT NULL,
  `lantai` int(11) NOT NULL,
  `gedung` varchar(100) NOT NULL,
  `kapasitas` int(11) NOT NULL,
  `tipe_ruangan` enum('Kelas','Lab') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ruangan`
--

INSERT INTO `ruangan` (`id`, `nama_ruangan`, `lantai`, `gedung`, `kapasitas`, `tipe_ruangan`, `created_at`, `updated_at`) VALUES
(1, 'FIK-201', 1, 'Dewi Sartika', 41, 'Kelas', '2026-07-21 07:09:35', '2026-07-30 21:34:04'),
(2, 'FIK-202', 2, 'Dewi Sartika', 45, 'Kelas', '2026-07-21 07:09:35', '2026-07-30 21:34:13'),
(3, 'FIK-203', 2, 'Dewi Sartika', 40, 'Kelas', '2026-07-21 07:09:35', '2026-07-21 07:09:35'),
(4, 'FIK-301', 3, 'Dewi Sartika', 42, 'Kelas', '2026-07-21 07:09:35', '2026-07-30 21:34:24'),
(5, 'FIK-302', 3, 'Dewi Sartika', 40, 'Kelas', '2026-07-21 07:09:35', '2026-07-21 07:09:35'),
(6, 'FIK-303', 3, 'Dewi Sartika', 40, 'Kelas', '2026-07-21 07:09:35', '2026-07-21 07:09:35'),
(7, 'FIK-401', 4, 'Dewi Sartika', 40, 'Kelas', '2026-07-21 07:09:35', '2026-07-21 07:09:35'),
(8, 'FIK-402', 4, 'Dewi Sartika', 40, 'Kelas', '2026-07-21 07:09:35', '2026-07-21 07:09:35'),
(9, 'FIK-403', 4, 'Dewi Sartika', 40, 'Kelas', '2026-07-21 07:09:35', '2026-07-21 07:09:35'),
(10, 'FIKLAB-202', 2, 'Ki Hajar Dewantara', 40, 'Lab', '2026-07-21 07:09:35', '2026-07-30 21:45:07'),
(11, 'FIK-203', 2, 'Ki Hajar Dewantara', 41, 'Kelas', '2026-07-21 07:09:35', '2026-07-30 22:46:41'),
(12, 'FIK-201', 2, 'Ki Hajar Dewantara', 40, 'Kelas', '2026-07-21 07:09:35', '2026-07-30 22:46:23'),
(13, 'FIK-301', 3, 'Ki Hajar Dewantara', 50, 'Kelas', '2026-07-21 07:09:35', '2026-07-30 22:46:18'),
(14, 'FIK-302', 3, 'Ki Hajar Dewantara', 40, 'Kelas', '2026-07-21 07:09:35', '2026-07-30 22:46:11'),
(15, 'FIK-303', 3, 'Ki Hajar Dewantara', 40, 'Kelas', '2026-07-21 07:09:35', '2026-07-30 22:46:04'),
(16, 'FIK-401', 4, 'Ki Hajar Dewantara', 40, 'Kelas', '2026-07-21 07:09:35', '2026-07-30 22:45:58'),
(17, 'FIK-402', 4, 'Ki Hajar Dewantara', 40, 'Kelas', '2026-07-21 07:09:35', '2026-07-30 22:45:38'),
(18, 'FIK-403', 4, 'Ki Hajar Dewantara', 40, 'Kelas', '2026-07-21 07:09:35', '2026-07-30 22:45:51');

-- --------------------------------------------------------

--
-- Struktur dari tabel `semester_akademik`
--

DROP TABLE IF EXISTS `semester_akademik`;
CREATE TABLE `semester_akademik` (
  `id` bigint(20) NOT NULL,
  `tahun_akademik` varchar(20) NOT NULL,
  `periode` enum('Ganjil','Genap') NOT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `semester_akademik`
--

INSERT INTO `semester_akademik` (`id`, `tahun_akademik`, `periode`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '2025/2026', 'Ganjil', 1, '2026-07-21 05:49:15', '2026-07-21 05:49:15'),
(2, '2025/2026', 'Genap', 0, '2026-07-21 05:49:15', '2026-07-21 05:49:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `slot_waktu_kuliah`
--

DROP TABLE IF EXISTS `slot_waktu_kuliah`;
CREATE TABLE `slot_waktu_kuliah` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `urutan` int(11) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `slot_waktu_kuliah`
--

INSERT INTO `slot_waktu_kuliah` (`id`, `hari`, `urutan`, `jam_mulai`, `jam_selesai`, `created_at`, `updated_at`) VALUES
(1, 'Senin', 1, '07:30:00', '08:20:00', '2026-07-29 17:13:18', '2026-08-15 06:02:02'),
(2, 'Senin', 2, '08:20:00', '09:10:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(3, 'Senin', 3, '09:20:00', '10:10:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(4, 'Senin', 4, '10:10:00', '11:00:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(5, 'Senin', 5, '11:10:00', '12:00:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(6, 'Senin', 6, '12:00:00', '12:50:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(7, 'Senin', 7, '13:30:00', '14:20:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(8, 'Senin', 8, '14:20:00', '15:10:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(9, 'Senin', 9, '15:20:00', '16:10:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(10, 'Senin', 10, '16:10:00', '17:00:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(11, 'Selasa', 1, '07:30:00', '08:20:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(12, 'Selasa', 2, '08:20:00', '09:10:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(13, 'Selasa', 3, '09:20:00', '10:10:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(14, 'Selasa', 4, '10:10:00', '11:00:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(15, 'Selasa', 5, '11:10:00', '12:00:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(16, 'Selasa', 6, '12:00:00', '12:50:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(17, 'Selasa', 7, '13:30:00', '14:20:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(18, 'Selasa', 8, '14:20:00', '15:10:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(19, 'Selasa', 9, '15:20:00', '16:10:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(20, 'Selasa', 10, '16:10:00', '17:00:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(21, 'Rabu', 1, '07:30:00', '08:20:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(22, 'Rabu', 2, '08:20:00', '09:10:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(23, 'Rabu', 3, '09:20:00', '10:10:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(24, 'Rabu', 4, '10:10:00', '11:00:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(25, 'Rabu', 5, '11:10:00', '12:00:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(26, 'Rabu', 6, '12:00:00', '12:50:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(27, 'Rabu', 7, '13:30:00', '14:20:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(28, 'Rabu', 8, '14:20:00', '15:10:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(29, 'Rabu', 9, '15:20:00', '16:10:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(30, 'Rabu', 10, '16:10:00', '17:00:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(31, 'Kamis', 1, '07:30:00', '08:20:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(32, 'Kamis', 2, '08:20:00', '09:10:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(33, 'Kamis', 3, '09:20:00', '10:10:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(34, 'Kamis', 4, '10:10:00', '11:00:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(35, 'Kamis', 5, '11:10:00', '12:00:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(36, 'Kamis', 6, '12:00:00', '12:50:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(37, 'Kamis', 7, '13:30:00', '14:20:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(38, 'Kamis', 8, '14:20:00', '15:10:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(39, 'Kamis', 9, '15:20:00', '16:10:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(40, 'Kamis', 10, '16:10:00', '17:00:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(41, 'Jumat', 1, '07:30:00', '08:20:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(42, 'Jumat', 2, '08:20:00', '09:10:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(43, 'Jumat', 3, '09:20:00', '10:10:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(44, 'Jumat', 4, '10:10:00', '11:00:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(45, 'Jumat', 5, '13:30:00', '14:20:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(46, 'Jumat', 6, '14:20:00', '15:10:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(47, 'Jumat', 7, '15:20:00', '16:10:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(48, 'Jumat', 8, '16:10:00', '17:00:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(49, 'Sabtu', 1, '08:00:00', '08:50:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(50, 'Sabtu', 2, '08:50:00', '09:40:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(51, 'Sabtu', 3, '10:00:00', '10:50:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(52, 'Sabtu', 4, '10:50:00', '11:40:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(53, 'Sabtu', 5, '13:00:00', '13:50:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(54, 'Sabtu', 6, '13:50:00', '14:40:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(55, 'Sabtu', 7, '15:00:00', '15:50:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(56, 'Sabtu', 8, '15:50:00', '16:40:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(57, 'Sabtu', 9, '17:00:00', '17:50:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51'),
(58, 'Sabtu', 10, '17:50:00', '18:40:00', '2026-07-29 17:13:18', '2026-07-30 20:06:51');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nidn` (`nidn`);

--
-- Indeks untuk tabel `dosen_ketersediaan`
--
ALTER TABLE `dosen_ketersediaan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `dosen_mata_kuliah`
--
ALTER TABLE `dosen_mata_kuliah`
  ADD KEY `dosen_mata_kuliah_dosen_id_foreign` (`dosen_id`),
  ADD KEY `dosen_mata_kuliah_mata_kuliah_id_foreign` (`mata_kuliah_id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indeks untuk tabel `generate_jadwal`
--
ALTER TABLE `generate_jadwal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_generate_semester` (`semester_akademik_id`);

--
-- Indeks untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jadwal_kelas` (`kelas_perkuliahan_id`),
  ADD KEY `fk_jadwal_ruangan` (`ruangan_id`),
  ADD KEY `fk_jadwal_generate` (`generate_jadwal_id`);

--
-- Indeks untuk tabel `jadwal_dosen`
--
ALTER TABLE `jadwal_dosen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwal_dosen_jadwal_id_foreign` (`jadwal_id`),
  ADD KEY `jadwal_dosen_dosen_id_foreign` (`dosen_id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kelas_perkuliahan`
--
ALTER TABLE `kelas_perkuliahan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kelas_prodi` (`prodi_id`),
  ADD KEY `fk_kelas_semester` (`semester_akademik_id`),
  ADD KEY `fk_kelas_mk` (`mata_kuliah_id`);

--
-- Indeks untuk tabel `kelas_perkuliahan_dosen`
--
ALTER TABLE `kelas_perkuliahan_dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kelas_perkuliahan_dosen_kelas_perkuliahan_id_dosen_id_unique` (`kelas_perkuliahan_id`,`dosen_id`),
  ADD KEY `kelas_perkuliahan_dosen_dosen_id_foreign` (`dosen_id`);

--
-- Indeks untuk tabel `konfigurasi_hari_kuliah`
--
ALTER TABLE `konfigurasi_hari_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `konfigurasi_hari_kuliah_semester_akademik_id_hari_unique` (`semester_akademik_id`,`hari`);

--
-- Indeks untuk tabel `konfigurasi_jadwal`
--
ALTER TABLE `konfigurasi_jadwal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `konfigurasi_jadwal_semester_akademik_id_unique` (`semester_akademik_id`);

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_mk` (`kode_mk`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indeks untuk tabel `prodi`
--
ALTER TABLE `prodi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `semester_akademik`
--
ALTER TABLE `semester_akademik`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `slot_waktu_kuliah`
--
ALTER TABLE `slot_waktu_kuliah`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT untuk tabel `dosen_ketersediaan`
--
ALTER TABLE `dosen_ketersediaan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `generate_jadwal`
--
ALTER TABLE `generate_jadwal`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=787;

--
-- AUTO_INCREMENT untuk tabel `jadwal_dosen`
--
ALTER TABLE `jadwal_dosen`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=630;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kelas_perkuliahan`
--
ALTER TABLE `kelas_perkuliahan`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT untuk tabel `kelas_perkuliahan_dosen`
--
ALTER TABLE `kelas_perkuliahan_dosen`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `konfigurasi_hari_kuliah`
--
ALTER TABLE `konfigurasi_hari_kuliah`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=343;

--
-- AUTO_INCREMENT untuk tabel `konfigurasi_jadwal`
--
ALTER TABLE `konfigurasi_jadwal`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT untuk tabel `prodi`
--
ALTER TABLE `prodi`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `semester_akademik`
--
ALTER TABLE `semester_akademik`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `slot_waktu_kuliah`
--
ALTER TABLE `slot_waktu_kuliah`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `dosen_mata_kuliah`
--
ALTER TABLE `dosen_mata_kuliah`
  ADD CONSTRAINT `dosen_mata_kuliah_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dosen_mata_kuliah_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `generate_jadwal`
--
ALTER TABLE `generate_jadwal`
  ADD CONSTRAINT `fk_generate_semester` FOREIGN KEY (`semester_akademik_id`) REFERENCES `semester_akademik` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `fk_jadwal_generate` FOREIGN KEY (`generate_jadwal_id`) REFERENCES `generate_jadwal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jadwal_kelas` FOREIGN KEY (`kelas_perkuliahan_id`) REFERENCES `kelas_perkuliahan` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jadwal_ruangan` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jadwal_dosen`
--
ALTER TABLE `jadwal_dosen`
  ADD CONSTRAINT `jadwal_dosen_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_dosen_jadwal_id_foreign` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kelas_perkuliahan`
--
ALTER TABLE `kelas_perkuliahan`
  ADD CONSTRAINT `fk_kelas_mk` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kelas_prodi` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kelas_semester` FOREIGN KEY (`semester_akademik_id`) REFERENCES `semester_akademik` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kelas_perkuliahan_dosen`
--
ALTER TABLE `kelas_perkuliahan_dosen`
  ADD CONSTRAINT `kelas_perkuliahan_dosen_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kelas_perkuliahan_dosen_kelas_perkuliahan_id_foreign` FOREIGN KEY (`kelas_perkuliahan_id`) REFERENCES `kelas_perkuliahan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `konfigurasi_hari_kuliah`
--
ALTER TABLE `konfigurasi_hari_kuliah`
  ADD CONSTRAINT `konfigurasi_hari_kuliah_semester_akademik_id_foreign` FOREIGN KEY (`semester_akademik_id`) REFERENCES `semester_akademik` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `konfigurasi_jadwal`
--
ALTER TABLE `konfigurasi_jadwal`
  ADD CONSTRAINT `konfigurasi_jadwal_semester_akademik_id_foreign` FOREIGN KEY (`semester_akademik_id`) REFERENCES `semester_akademik` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
