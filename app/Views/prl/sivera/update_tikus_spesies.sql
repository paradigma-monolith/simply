-- ========================================================
-- Perubahan Skema Tabel SIVERA (Tab Tikus Multi-Spesies)
-- Tanggal: 13 Oktober 2026
-- ========================================================

-- Mengubah tipe kolom spesies menjadi TEXT agar dapat menampung format multi-spesies dan jumlah (JSON)
ALTER TABLE `prl_sivera_tikus` 
MODIFY COLUMN `spesies` TEXT DEFAULT NULL;
