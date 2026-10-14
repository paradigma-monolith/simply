-- ========================================================
-- Perubahan Skema Tabel SIVERA (Tab Pinjal)
-- Tanggal: 13 Oktober 2026
-- ========================================================

-- Menambahkan kolom jml_tikus_tertangkap pada tabel prl_sivera_pinjal
ALTER TABLE `prl_sivera_pinjal` 
ADD COLUMN `jml_tikus_tertangkap` INT(11) DEFAULT 0 AFTER `lokasi`;
