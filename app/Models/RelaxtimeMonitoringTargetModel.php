<?php

namespace App\Models;

use App\Libraries\RelaxtimeMonitoringAnggaranHelper;
use CodeIgniter\Model;

class RelaxtimeMonitoringTargetModel extends Model
{
    protected $table = 'relaxtime_monitoring_target_triwulan';
    protected $primaryKey = 'id_target';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'tahun_anggaran',
        'triwulan',
        'akun',
        'pagu',
        'target_persen',
        'updated_by',
    ];

    private static bool $tableEnsured = false;

    /**
     * Pastikan tabel pagu & target realisasi triwulanan tersedia.
     */
    public function ensureTable(): void
    {
        if (self::$tableEnsured) {
            return;
        }

        $sql = "
            CREATE TABLE IF NOT EXISTS `relaxtime_monitoring_target_triwulan` (
                `id_target` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `tahun_anggaran` INT(11) NOT NULL,
                `triwulan` TINYINT(1) NOT NULL,
                `akun` VARCHAR(10) NOT NULL,
                `pagu` BIGINT(20) NOT NULL DEFAULT 0,
                `target_persen` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
                `updated_by` INT(11) UNSIGNED NULL DEFAULT NULL,
                `created_at` DATETIME NULL DEFAULT NULL,
                `updated_at` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id_target`),
                UNIQUE KEY `uq_rmtt_tahun_tw_akun` (`tahun_anggaran`, `triwulan`, `akun`),
                KEY `idx_rmtt_tahun` (`tahun_anggaran`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";

        $this->db->query($sql);
        self::$tableEnsured = true;
    }

    /**
     * Ambil konfigurasi Pagu & Target (%) Triwulan I-IV per jenis akun (51, 52, 53) untuk satu tahun.
     * Jika tahun tersebut belum memiliki record, otomatis isi default standar (dan pagu awal 2026).
     *
     * @return array<int, array<string, array<string, mixed>>>
     */
    public function getConfigByYear(int $tahun): array
    {
        $this->ensureTable();

        $rows = $this->db->table($this->table)
            ->where('tahun_anggaran', $tahun)
            ->orderBy('triwulan', 'ASC')
            ->orderBy('akun', 'ASC')
            ->get()
            ->getResultArray();

        $defaults = RelaxtimeMonitoringAnggaranHelper::getDefaultPaguTargetForYear($tahun);

        if (empty($rows)) {
            $now = date('Y-m-d H:i:s');
            $seedRows = [];
            foreach ($defaults as $tw => $akuns) {
                foreach ($akuns as $akun => $cfg) {
                    $seedRows[] = [
                        'tahun_anggaran' => $tahun,
                        'triwulan' => (int) $tw,
                        'akun' => (string) $akun,
                        'pagu' => (int) $cfg['pagu'],
                        'target_persen' => (float) $cfg['target_persen'],
                        'updated_by' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
            if (!empty($seedRows)) {
                $this->db->table($this->table)->insertBatch($seedRows);
                $rows = $seedRows;
            }
        }

        $config = $defaults;
        foreach ($rows as $row) {
            $tw = (int) ($row['triwulan'] ?? 0);
            $akun = (string) ($row['akun'] ?? '');
            if ($tw >= 1 && $tw <= 4 && isset(RelaxtimeMonitoringAnggaranHelper::AKUN_LABELS[$akun])) {
                $config[$tw][$akun] = [
                    'pagu' => (int) ($row['pagu'] ?? 0),
                    'target_persen' => (float) ($row['target_persen'] ?? 0),
                    'updated_at' => (string) ($row['updated_at'] ?? ''),
                ];
            }
        }

        return $config;
    }

    /**
     * Simpan perubahan Pagu & Target (%) Triwulan I-IV untuk tahun tertentu.
     */
    public function saveYearConfig(int $tahun, array $config, ?int $userId): array
    {
        $this->ensureTable();
        $now = date('Y-m-d H:i:s');
        $defaults = RelaxtimeMonitoringAnggaranHelper::getDefaultPaguTargetForYear($tahun);

        $this->db->transStart();
        $existingRows = $this->db->table($this->table)
            ->where('tahun_anggaran', $tahun)
            ->get()
            ->getResultArray();
        $existingMap = [];
        foreach ($existingRows as $er) {
            $existingMap[(int) $er['triwulan'] . '_' . (string) $er['akun']] = (int) $er['id_target'];
        }

        $table = $this->db->table($this->table);
        for ($tw = 1; $tw <= 4; $tw++) {
            foreach (array_keys(RelaxtimeMonitoringAnggaranHelper::AKUN_LABELS) as $akun) {
                $in = $config[$tw][$akun] ?? [];
                $def = $defaults[$tw][$akun];
                $pagu = max(0, (int) ($in['pagu'] ?? $def['pagu']));
                $targetPersen = max(0.0, min(100.0, round((float) ($in['target_persen'] ?? $def['target_persen']), 2)));

                $key = $tw . '_' . $akun;
                if (isset($existingMap[$key])) {
                    $table->where('id_target', $existingMap[$key])->update([
                        'pagu' => $pagu,
                        'target_persen' => $targetPersen,
                        'updated_by' => $userId,
                        'updated_at' => $now,
                    ]);
                } else {
                    $table->insert([
                        'tahun_anggaran' => $tahun,
                        'triwulan' => $tw,
                        'akun' => $akun,
                        'pagu' => $pagu,
                        'target_persen' => $targetPersen,
                        'updated_by' => $userId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
        $this->db->transComplete();

        return $this->getConfigByYear($tahun);
    }
}
