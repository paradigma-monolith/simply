<?php

namespace App\Models;

use CodeIgniter\Model;

class RelaxtimeMonitoringAnggaranModel extends Model
{
    protected $table = 'relaxtime_monitoring_anggaran';
    protected $primaryKey = 'id_monitoring';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'tanggal',
        'ro',
        'akun',
        'kegiatan',
        'rencana',
        'pic',
        'realisasi',
        'sumber_input',
        'import_batch',
        'created_by',
        'updated_by',
    ];

    private static bool $tableEnsured = false;

    /**
     * Pastikan tabel relaxtime_monitoring_anggaran tersedia di database.
     */
    public function ensureTable(): void
    {
        if (self::$tableEnsured) {
            return;
        }

        $sql = "
            CREATE TABLE IF NOT EXISTS `relaxtime_monitoring_anggaran` (
                `id_monitoring` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `tanggal` DATE NOT NULL,
                `ro` VARCHAR(50) NOT NULL DEFAULT '',
                `akun` VARCHAR(10) NOT NULL DEFAULT '52',
                `kegiatan` TEXT NOT NULL,
                `rencana` BIGINT(20) NOT NULL DEFAULT 0,
                `pic` VARCHAR(100) NOT NULL DEFAULT '',
                `realisasi` BIGINT(20) NOT NULL DEFAULT 0,
                `sumber_input` ENUM('manual', 'import') NOT NULL DEFAULT 'manual',
                `import_batch` VARCHAR(64) NULL DEFAULT NULL,
                `created_by` INT(11) UNSIGNED NULL DEFAULT NULL,
                `updated_by` INT(11) UNSIGNED NULL DEFAULT NULL,
                `created_at` DATETIME NULL DEFAULT NULL,
                `updated_at` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id_monitoring`),
                KEY `idx_rma_tanggal` (`tanggal`),
                KEY `idx_rma_akun` (`akun`),
                KEY `idx_rma_pic` (`pic`),
                KEY `idx_rma_ro` (`ro`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";

        $this->db->query($sql);
        self::$tableEnsured = true;
    }

    /**
     * Ambil daftar item monitoring anggaran sesuai filter rentang tanggal, akun, dan PIC.
     */
    public function getFiltered(array $filters = []): array
    {
        $builder = $this->db->table($this->table);

        $tglMulai = trim((string) ($filters['tgl_mulai'] ?? ''));
        $tglSelesai = trim((string) ($filters['tgl_selesai'] ?? ''));
        $akun = trim((string) ($filters['akun'] ?? ''));
        $pic = trim((string) ($filters['pic'] ?? ''));

        if ($tglMulai !== '') {
            $builder->where('tanggal >=', $tglMulai);
        }
        if ($tglSelesai !== '') {
            $builder->where('tanggal <=', $tglSelesai);
        }
        if ($akun !== '' && strtolower($akun) !== 'all') {
            $builder->where('akun', $akun);
        }
        if ($pic !== '' && strtolower($pic) !== 'all') {
            $builder->where('pic', $pic);
        }

        return $builder
            ->orderBy('id_monitoring', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Ambil opsi PIC dan RO unik untuk dropdown filter & datalist input manual.
     */
    public function getDistinctOptions(): array
    {
        $defaultPics = ['Timker 1', 'Timker 2', 'Timker 3', 'Timker 4', 'ADUM'];
        $picRows = $this->db->table($this->table)
            ->select('DISTINCT(pic) AS pic', false)
            ->where("pic <> ''", null, false)
            ->orderBy('pic', 'ASC')
            ->get()
            ->getResultArray();

        $pics = $defaultPics;
        foreach ($picRows as $row) {
            $p = trim((string) ($row['pic'] ?? ''));
            if ($p !== '' && !in_array($p, $pics, true)) {
                $pics[] = $p;
            }
        }
        natcasesort($pics);

        $roRows = $this->db->table($this->table)
            ->select('DISTINCT(ro) AS ro', false)
            ->where("ro <> ''", null, false)
            ->orderBy('ro', 'ASC')
            ->get()
            ->getResultArray();

        $ros = [];
        foreach ($roRows as $row) {
            $r = trim((string) ($row['ro'] ?? ''));
            if ($r !== '') {
                $ros[] = $r;
            }
        }

        return [
            'pics' => array_values($pics),
            'ros' => $ros,
        ];
    }
}
