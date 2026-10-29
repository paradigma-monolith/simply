-- ========================================================
-- Perubahan Skema Basis Data Travel Consent
-- Menambahkan Upload Foto Geotagging
-- Tanggal: 26 Oktober 2026
-- ========================================================

-- --------------------------------------------------------
-- OPSI 1: ALTER TABLE (Gunakan jika tabel sudah ada sebelumnya)
-- --------------------------------------------------------

-- 1. Tambah kolom foto_geotagging pada Aircraft Sanitation
ALTER TABLE `tc_aircraft_sanitation` 
  ADD COLUMN `foto_geotagging` VARCHAR(255) NULL AFTER `petugas_bkk_ttd`;

-- 2. Tambah kolom foto_geotagging pada Alat Kesehatan Kotak P3K & Emergensi
ALTER TABLE `tc_alat_kesehatan` 
  ADD COLUMN `foto_geotagging` VARCHAR(255) NULL AFTER `pelapor_ttd`;

-- 3. Tambah kolom foto_geotagging pada Obat Kotak P3K & Emergensi
ALTER TABLE `tc_obat` 
  ADD COLUMN `foto_geotagging` VARCHAR(255) NULL AFTER `pelapor_ttd`;

-- 4. Tambah kolom foto_geotagging pada Sanitasi Kapal
ALTER TABLE `tc_sanitasi_kapal` 
  ADD COLUMN `foto_geotagging` VARCHAR(255) NULL AFTER `tanda_tangan`;


-- --------------------------------------------------------
-- OPSI 2: CREATE TABLE (Gunakan jika tabel belum pernah dibuat sebelumnya)
-- --------------------------------------------------------

-- 1. Tabel Aircraft Sanitation Inspection
CREATE TABLE IF NOT EXISTS `tc_aircraft_sanitation` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pemilik` VARCHAR(255) DEFAULT NULL,
  `tanda_kebangsaan` VARCHAR(255) DEFAULT NULL,
  `tanggal` DATE DEFAULT NULL,
  `waktu` TIME DEFAULT NULL,
  `kabin_penumpang` TEXT DEFAULT NULL,
  `kompartemen_toilet` TEXT DEFAULT NULL,
  `galley` TEXT DEFAULT NULL,
  `dokumen_kesehatan` TEXT DEFAULT NULL,
  `pembuangan_kotoran` VARCHAR(50) DEFAULT NULL,
  `penanganan_air` TEXT DEFAULT NULL,
  `keadaan_makanan` TEXT DEFAULT NULL,
  `binatang_pengganggu` TEXT DEFAULT NULL,
  `surat_sanitasi` VARCHAR(50) DEFAULT NULL,
  `kesimpulan` VARCHAR(50) DEFAULT NULL,
  `rekomendasi` VARCHAR(50) DEFAULT NULL,
  `tindakan` VARCHAR(100) DEFAULT NULL,
  `petugas_kabin_nama` VARCHAR(255) DEFAULT NULL,
  `petugas_kabin_ttd` LONGTEXT DEFAULT NULL,
  `petugas_bkk_nama` VARCHAR(255) DEFAULT NULL,
  `petugas_bkk_ttd` LONGTEXT DEFAULT NULL,
  `foto_geotagging` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 2. Tabel Alat Kesehatan Kotak P3K & Emergensi
CREATE TABLE IF NOT EXISTS `tc_alat_kesehatan` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `penerbangan_haji_h` VARCHAR(100) DEFAULT NULL,
  `penerbangan_haji_m` VARCHAR(100) DEFAULT NULL,
  `embarkasi` VARCHAR(255) DEFAULT NULL,
  `nomor_kloter` VARCHAR(100) DEFAULT NULL,
  `no_kontak` VARCHAR(100) DEFAULT NULL,
  `items_p3k` TEXT DEFAULT NULL,
  `items_emergensi` TEXT DEFAULT NULL,
  `crew_nama` VARCHAR(255) DEFAULT NULL,
  `crew_ttd` LONGTEXT DEFAULT NULL,
  `pelapor_nama` VARCHAR(255) DEFAULT NULL,
  `pelapor_ttd` LONGTEXT DEFAULT NULL,
  `foto_geotagging` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 3. Tabel Obat Kotak P3K & Emergensi
CREATE TABLE IF NOT EXISTS `tc_obat` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `penerbangan_haji_h` VARCHAR(100) DEFAULT NULL,
  `penerbangan_haji_m` VARCHAR(100) DEFAULT NULL,
  `embarkasi` VARCHAR(255) DEFAULT NULL,
  `nomor_kloter` VARCHAR(100) DEFAULT NULL,
  `no_kontak` VARCHAR(100) DEFAULT NULL,
  `items_p3k` TEXT DEFAULT NULL,
  `items_emergensi` TEXT DEFAULT NULL,
  `crew_nama` VARCHAR(255) DEFAULT NULL,
  `crew_ttd` LONGTEXT DEFAULT NULL,
  `pelapor_nama` VARCHAR(255) DEFAULT NULL,
  `pelapor_ttd` LONGTEXT DEFAULT NULL,
  `foto_geotagging` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4. Tabel Sanitasi Kapal (Ship Sanitation)
CREATE TABLE IF NOT EXISTS `tc_sanitasi_kapal` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pelabuhan` VARCHAR(255) DEFAULT NULL,
  `tanggal` DATE DEFAULT NULL,
  `sertifikat_mencatat` VARCHAR(100) DEFAULT NULL,
  `imo_no` VARCHAR(100) DEFAULT NULL,
  `jenis_kapal` VARCHAR(100) DEFAULT NULL,
  `detail_jenis_kapal` VARCHAR(100) DEFAULT NULL,
  `nama_kapal` VARCHAR(255) DEFAULT NULL,
  `bendera` VARCHAR(100) DEFAULT NULL,
  `berat_gt` VARCHAR(100) DEFAULT NULL,
  `kondisi_palka` VARCHAR(255) DEFAULT NULL,
  `nama_pemeriksa` VARCHAR(255) DEFAULT NULL,
  `alamat_pemeriksa` TEXT DEFAULT NULL,
  `area_diperiksa` LONGTEXT DEFAULT NULL,
  `pejabat_penerbit` VARCHAR(255) DEFAULT NULL,
  `keterangan` TEXT DEFAULT NULL,
  `tanda_tangan` LONGTEXT DEFAULT NULL,
  `foto_geotagging` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
