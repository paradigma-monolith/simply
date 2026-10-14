-- ========================================================
-- Perubahan Skema Tabel SIVERA (Tab Tikus Rentang Tanggal)
-- Tanggal: 14 Oktober 2026
-- ========================================================

-- Menambahkan kolom tanggal_mulai dan tanggal_selesai pada prl_sivera_tikus
ALTER TABLE `prl_sivera_tikus` 
  ADD COLUMN `tanggal_mulai` DATE NULL AFTER `tanggal`,
  ADD COLUMN `tanggal_selesai` DATE NULL AFTER `tanggal_mulai`;

-- Migrasi data tanggal lama ke tanggal_mulai dan tanggal_selesai
UPDATE `prl_sivera_tikus` 
SET `tanggal_mulai` = `tanggal` 
WHERE `tanggal_mulai` IS NULL AND `tanggal` IS NOT NULL;

UPDATE `prl_sivera_tikus` 
SET `tanggal_selesai` = `tanggal` 
WHERE `tanggal_selesai` IS NULL AND `tanggal` IS NOT NULL;
