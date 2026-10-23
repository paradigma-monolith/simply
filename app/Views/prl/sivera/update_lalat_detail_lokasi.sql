-- ========================================================
-- Perubahan Skema Tabel SIVERA (Tab Lalat Nilai Per Lokasi)
-- Tanggal: 19 Oktober 2026
-- ========================================================

-- Menambahkan kolom detail_lokasi untuk menyimpan data pengamatan per lokasi dalam format JSON
ALTER TABLE `prl_sivera_lalat` 
  ADD COLUMN `detail_lokasi` TEXT NULL AFTER `pengendalian`;

-- (Opsional) Migrasi data lama yang belum memiliki detail_lokasi:
-- Jika satu baris hanya memiliki satu lokasi tanpa koma:
UPDATE `prl_sivera_lalat`
SET `detail_lokasi` = JSON_ARRAY(
  JSON_OBJECT(
    'lokasi', `lokasi`,
    't', JSON_ARRAY(`t1`, `t2`, `t3`, `t4`, `t5`, `t6`, `t7`, `t8`, `t9`, `t10`),
    'titik', JSON_ARRAY(`titik_1`, `titik_2`, `titik_3`, `titik_4`, `titik_5`),
    'total_pengamatan', (`t1` + `t2` + `t3` + `t4` + `t5` + `t6` + `t7` + `t8` + `t9` + `t10`),
    'total_top5', (`titik_1` + `titik_2` + `titik_3` + `titik_4` + `titik_5`),
    'rata_rata', `rata_rata`,
    'status', IF(`rata_rata` < 2, 'MS', 'TMS')
  )
)
WHERE (`detail_lokasi` IS NULL OR `detail_lokasi` = '')
  AND `lokasi` IS NOT NULL
  AND `lokasi` NOT LIKE '%,%';
