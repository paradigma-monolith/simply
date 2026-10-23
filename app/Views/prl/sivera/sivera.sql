-- phpMyAdmin SQL Dump
-- SIVERA Database Tables

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

-- Pilih database yang digunakan
USE `bkkr3927_simply`;

-- --------------------------------------------------------
-- Table structure for table `prl_sivera_jentik`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `prl_sivera_jentik` (
  `id_jentik` int(11) NOT NULL AUTO_INCREMENT,
  `id_wilayah_kerja` int(11) DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `bulan_pelaksanaan` varchar(50) DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `kondisi_musim` varchar(50) DEFAULT NULL,
  `wilayah` varchar(100) DEFAULT NULL,
  `jml_rumah_diperiksa` int(11) DEFAULT 0,
  `jml_kontainer_diperiksa` int(11) DEFAULT 0,
  `jml_rumah_positif` int(11) DEFAULT 0,
  `jml_kontainer_positif` int(11) DEFAULT 0,
  `jenis_kontainer` varchar(255) DEFAULT NULL,
  `pengendalian` text DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_jentik`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `prl_sivera_lalat`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `prl_sivera_lalat` (
  `id_lalat` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `id_wilayah_kerja` int(11) DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `temperatur` decimal(10,2) DEFAULT NULL,
  `kelembapan` decimal(10,2) DEFAULT NULL,
  `kecepatan_angin` decimal(10,2) DEFAULT NULL,
  `musim` varchar(50) DEFAULT NULL,
  `t1` int(11) DEFAULT 0,
  `t2` int(11) DEFAULT 0,
  `t3` int(11) DEFAULT 0,
  `t4` int(11) DEFAULT 0,
  `t5` int(11) DEFAULT 0,
  `t6` int(11) DEFAULT 0,
  `t7` int(11) DEFAULT 0,
  `t8` int(11) DEFAULT 0,
  `t9` int(11) DEFAULT 0,
  `t10` int(11) DEFAULT 0,
  `titik_1` int(11) DEFAULT 0,
  `titik_2` int(11) DEFAULT 0,
  `titik_3` int(11) DEFAULT 0,
  `titik_4` int(11) DEFAULT 0,
  `titik_5` int(11) DEFAULT 0,
  `rata_rata` decimal(10,2) DEFAULT 0,
  `detail_lokasi` text DEFAULT NULL,
  `pengendalian` text DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_lalat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `prl_sivera_kecoa`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `prl_sivera_kecoa` (
  `id_kecoa` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `id_wilayah_kerja` int(11) DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `nama_ruangan` varchar(255) DEFAULT NULL,
  `perangkap_terpasang` int(11) DEFAULT 0,
  `jml_kecoa_tertangkap` int(11) DEFAULT 0,
  `spesies_tertangkap` varchar(255) DEFAULT NULL,
  `rata_rata` decimal(10,2) DEFAULT 0,
  `pengendalian` text DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_kecoa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `prl_sivera_tikus`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `prl_sivera_tikus` (
  `id_tikus` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `id_wilayah_kerja` int(11) DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `lokasi_pemasangan` varchar(255) DEFAULT NULL,
  `jenis_umpan` varchar(100) DEFAULT NULL,
  `spesies` text DEFAULT NULL,
  `jml_perangkap_dipasang` int(11) DEFAULT 0,
  `jml_perangkap_hilang` int(11) DEFAULT 0,
  `jml_tikus_tertangkap` int(11) DEFAULT 0,
  `success_trap` decimal(10,2) DEFAULT 0,
  `created_by` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_tikus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `prl_sivera_pinjal`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `prl_sivera_pinjal` (
  `id_pinjal` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `id_wilayah_kerja` int(11) DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `jml_tikus_tertangkap` int(11) DEFAULT 0,
  `hari_ke` int(11) DEFAULT NULL,
  `lokasi_tikus_tertangkap` varchar(255) DEFAULT NULL,
  `kode_spesimen` varchar(100) DEFAULT NULL,
  `jenis_kelamin` varchar(20) DEFAULT NULL,
  `berat_badan` decimal(10,2) DEFAULT NULL,
  `panjang_badan` decimal(10,2) DEFAULT NULL,
  `ekor` decimal(10,2) DEFAULT NULL,
  `kaki_belakang` decimal(10,2) DEFAULT NULL,
  `telinga` decimal(10,2) DEFAULT NULL,
  `testis` decimal(10,2) DEFAULT NULL,
  `jml_mamae` int(11) DEFAULT NULL,
  `spesies_tikus` varchar(100) DEFAULT NULL,
  `pinjal_umum` int(11) DEFAULT 0,
  `pinjal_khusus` int(11) DEFAULT 0,
  `indeks_pinjal_umum` decimal(10,2) DEFAULT 0,
  `indeks_pinjal_khusus` decimal(10,2) DEFAULT 0,
  `created_by` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_pinjal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;