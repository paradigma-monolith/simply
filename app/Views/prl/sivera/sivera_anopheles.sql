-- ========================================================
-- SQL MIGRATION / UPDATE SERVER: SIVERA ANOPHELES
-- Modul: Surveilans Jentik Anopheles & Surveilans Nyamuk Anopheles
-- Tanggal: 2026-10-24
-- ========================================================

-- Aktifkan baris di bawah jika ingin menentukan database secara eksplisit:
-- USE `bkkr3927_simply`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

-- --------------------------------------------------------
-- 1. Struktur Tabel: `prl_sivera_anopheles_jentik`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `prl_sivera_anopheles_jentik` (
  `id_anopheles_jentik` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `id_wilayah_kerja` int(11) DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `jenis_habitat` text DEFAULT NULL,
  `jenis_habitat_lainnya` varchar(255) DEFAULT NULL,
  `kondisi_pencahayaan` varchar(100) DEFAULT NULL,
  `kondisi_vegetasi` varchar(100) DEFAULT NULL,
  `kondisi_air` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `jml_habitat_disurvei` int(11) DEFAULT 0,
  `jml_habitat_positif` int(11) DEFAULT 0,
  `indeks_habitat` decimal(10,2) DEFAULT 0.00,
  `status` varchar(50) DEFAULT 'MS',
  `created_by` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_anopheles_jentik`),
  KEY `idx_anopheles_jentik_wilker` (`id_wilayah_kerja`),
  KEY `idx_anopheles_jentik_tanggal` (`tanggal`),
  KEY `idx_anopheles_jentik_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 2. Struktur Tabel: `prl_sivera_anopheles_nyamuk`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `prl_sivera_anopheles_nyamuk` (
  `id_anopheles_nyamuk` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `id_wilayah_kerja` int(11) DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `jml_penangkap` int(11) DEFAULT 0,
  `lama_penangkapan` decimal(10,2) DEFAULT 0.00,
  `kondisi_cuaca` varchar(100) DEFAULT NULL,
  `suhu` decimal(10,2) DEFAULT NULL,
  `kelembaban` decimal(10,2) DEFAULT NULL,
  `kecepatan_angin` decimal(10,2) DEFAULT NULL,
  `spesies` varchar(100) DEFAULT 'Anopheles sp',
  `data_tangkapan` longtext DEFAULT NULL,
  `total_dalam` int(11) DEFAULT 0,
  `total_luar` int(11) DEFAULT 0,
  `total_tertangkap` int(11) DEFAULT 0,
  `ada_anopheles` varchar(10) DEFAULT 'Tidak',
  `mbr` decimal(10,4) DEFAULT 0.0000,
  `mhd` decimal(10,4) DEFAULT 0.0000,
  `interpretasi` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_anopheles_nyamuk`),
  KEY `idx_anopheles_nyamuk_wilker` (`id_wilayah_kerja`),
  KEY `idx_anopheles_nyamuk_tanggal` (`tanggal`),
  KEY `idx_anopheles_nyamuk_temuan` (`ada_anopheles`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;
