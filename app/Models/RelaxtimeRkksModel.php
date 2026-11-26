<?php

namespace App\Models;

use App\Libraries\RelaxtimeRkksHelper;
use CodeIgniter\Model;

class RelaxtimeRkksModel extends Model
{
    protected $table = 'relaxtime_rkks_dokumen';
    protected $primaryKey = 'id_dokumen';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'tahun_anggaran',
        'nama_dokumen',
        'kode_satker',
        'nama_satker',
        'total_pagu',
        'is_active',
        'created_by',
    ];

    private static bool $tablesEnsured = false;

    public function ensureTables(): void
    {
        if (self::$tablesEnsured) {
            return;
        }

        $this->db->query("
            CREATE TABLE IF NOT EXISTS `relaxtime_rkks_dokumen` (
                `id_dokumen` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `tahun_anggaran` YEAR(4) NOT NULL,
                `nama_dokumen` VARCHAR(150) NOT NULL,
                `kode_satker` VARCHAR(30) NULL DEFAULT NULL,
                `nama_satker` VARCHAR(200) NULL DEFAULT NULL,
                `total_pagu` BIGINT(20) NOT NULL DEFAULT 0,
                `is_active` TINYINT(1) NOT NULL DEFAULT 1,
                `created_by` INT(11) UNSIGNED NULL DEFAULT NULL,
                `created_at` DATETIME NULL DEFAULT NULL,
                `updated_at` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id_dokumen`),
                KEY `idx_rkks_dok_tahun` (`tahun_anggaran`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS `relaxtime_rkks_items` (
                `id_item` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_dokumen` INT(11) UNSIGNED NOT NULL,
                `parent_id` INT(11) UNSIGNED NULL DEFAULT NULL,
                `level_type` VARCHAR(30) NOT NULL,
                `header_depth` TINYINT(1) NOT NULL DEFAULT 0,
                `kode` VARCHAR(50) NOT NULL DEFAULT '',
                `uraian` TEXT NOT NULL,
                `lokasi` VARCHAR(150) NOT NULL DEFAULT '',
                `kppn` VARCHAR(100) NOT NULL DEFAULT '',
                `jenis_komponen` VARCHAR(10) NOT NULL DEFAULT '',
                `volume` DECIMAL(15,2) NULL DEFAULT NULL,
                `satuan` VARCHAR(80) NOT NULL DEFAULT '',
                `harga_satuan` BIGINT(20) NOT NULL DEFAULT 0,
                `jumlah` BIGINT(20) NOT NULL DEFAULT 0,
                `sumber_dana` VARCHAR(20) NOT NULL DEFAULT '',
                `is_blokir` TINYINT(1) NOT NULL DEFAULT 0,
                `nilai_blokir` BIGINT(20) NOT NULL DEFAULT 0,
                `catatan` TEXT NULL,
                `urutan` INT(11) NOT NULL DEFAULT 0,
                `created_by` INT(11) UNSIGNED NULL DEFAULT NULL,
                `updated_by` INT(11) UNSIGNED NULL DEFAULT NULL,
                `created_at` DATETIME NULL DEFAULT NULL,
                `updated_at` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id_item`),
                KEY `idx_rkks_item_dok` (`id_dokumen`, `urutan`),
                KEY `idx_rkks_item_parent` (`parent_id`),
                KEY `idx_rkks_item_level` (`id_dokumen`, `level_type`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        self::$tablesEnsured = true;
    }

    public function getDokumenList(?int $tahun = null): array
    {
        $builder = $this->db->table('relaxtime_rkks_dokumen');
        if ($tahun !== null && $tahun > 0) {
            $builder->where('tahun_anggaran', $tahun);
        }

        return $builder
            ->orderBy('tahun_anggaran', 'DESC')
            ->orderBy('id_dokumen', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getDokumen(int $idDokumen): ?array
    {
        $row = $this->db->table('relaxtime_rkks_dokumen')
            ->where('id_dokumen', $idDokumen)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    public function createDokumen(array $data): int
    {
        $now = date('Y-m-d H:i:s');
        $insert = [
            'tahun_anggaran' => (int) ($data['tahun_anggaran'] ?? date('Y')),
            'nama_dokumen' => trim((string) ($data['nama_dokumen'] ?? 'RKKS')),
            'kode_satker' => trim((string) ($data['kode_satker'] ?? '')),
            'nama_satker' => trim((string) ($data['nama_satker'] ?? '')),
            'total_pagu' => 0,
            'is_active' => 1,
            'created_by' => $data['created_by'] ?? null,
            'created_at' => $now,
            'updated_at' => $now,
        ];
        $this->db->table('relaxtime_rkks_dokumen')->insert($insert);
        return (int) $this->db->insertID();
    }

    public function deleteDokumen(int $idDokumen): bool
    {
        $this->db->transStart();
        $this->db->table('relaxtime_rkks_items')->where('id_dokumen', $idDokumen)->delete();
        $this->db->table('relaxtime_rkks_dokumen')->where('id_dokumen', $idDokumen)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function getItemsByDokumen(int $idDokumen): array
    {
        return $this->db->table('relaxtime_rkks_items')
            ->where('id_dokumen', $idDokumen)
            ->orderBy('urutan', 'ASC')
            ->orderBy('id_item', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getItem(int $idItem): ?array
    {
        $row = $this->db->table('relaxtime_rkks_items')
            ->where('id_item', $idItem)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    public function replaceDokumenItems(int $idDokumen, array $parsedItems, ?int $userId): int
    {
        if (empty($parsedItems)) {
            $this->db->transStart();
            $this->db->table('relaxtime_rkks_items')->where('id_dokumen', $idDokumen)->delete();
            $this->db->table('relaxtime_rkks_dokumen')
                ->where('id_dokumen', $idDokumen)
                ->update([
                    'total_pagu' => 0,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            $this->db->transComplete();

            return 0;
        }

        // Precompute leaf-to-program rollups in memory to avoid hundreds of follow-up UPDATE queries.
        RelaxtimeRkksHelper::recalculateTreeAmounts($parsedItems);
        $summary = RelaxtimeRkksHelper::buildSummary($parsedItems);
        $totalPagu = (int) ($summary['total_pagu'] ?? 0);

        $tempToIdx = [];
        $hasChildTempIds = [];
        foreach ($parsedItems as $idx => $item) {
            if (isset($item['temp_id'])) {
                $tempToIdx[(int) $item['temp_id']] = $idx;
            }
        }
        foreach ($parsedItems as $item) {
            $parentTemp = $item['parent_temp_id'] ?? null;
            if ($parentTemp !== null && isset($tempToIdx[(int) $parentTemp])) {
                $hasChildTempIds[(int) $parentTemp] = true;
            }
        }

        // Group items by tree depth so each depth wave can be batch-inserted once parents have real IDs.
        $depthByIdx = [];
        $visiting = [];
        $getDepth = static function (int $idx) use (&$getDepth, &$depthByIdx, &$visiting, &$parsedItems, $tempToIdx): int {
            if (isset($depthByIdx[$idx])) {
                return $depthByIdx[$idx];
            }
            if (isset($visiting[$idx])) {
                return 0;
            }
            $visiting[$idx] = true;
            $parentTemp = $parsedItems[$idx]['parent_temp_id'] ?? null;
            $parentIdx = ($parentTemp !== null && isset($tempToIdx[(int) $parentTemp])) ? $tempToIdx[(int) $parentTemp] : null;
            $depth = ($parentIdx !== null && $parentIdx !== $idx) ? (1 + $getDepth($parentIdx)) : 0;
            unset($visiting[$idx]);

            return $depthByIdx[$idx] = $depth;
        };

        $byDepth = [];
        $maxDepth = 0;
        foreach ($parsedItems as $idx => $item) {
            $d = $getDepth($idx);
            if ($d > $maxDepth) {
                $maxDepth = $d;
            }
            $byDepth[$d][] = $idx;
        }

        $now = date('Y-m-d H:i:s');
        $tempToRealId = [];
        $itemsTable = $this->db->table('relaxtime_rkks_items');

        $this->db->transStart();
        $itemsTable->where('id_dokumen', $idDokumen)->delete();

        for ($depth = 0; $depth <= $maxDepth; $depth++) {
            $indices = $byDepth[$depth] ?? [];
            if (empty($indices)) {
                continue;
            }

            $batchRows = [];
            $neededParentUrutanToTemp = [];

            foreach ($indices as $idx) {
                $item = $parsedItems[$idx];
                $parentTemp = $item['parent_temp_id'] ?? null;
                $realParentId = ($parentTemp !== null && isset($tempToRealId[(int) $parentTemp]))
                    ? $tempToRealId[(int) $parentTemp]
                    : null;
                $urutan = $idx + 1;

                if (isset($item['temp_id'])) {
                    $tid = (int) $item['temp_id'];
                    if (isset($hasChildTempIds[$tid])) {
                        $neededParentUrutanToTemp[$urutan] = $tid;
                    }
                }

                $batchRows[] = [
                    'id_dokumen' => $idDokumen,
                    'parent_id' => $realParentId,
                    'level_type' => (string) ($item['level_type'] ?? 'detail'),
                    'header_depth' => (int) ($item['header_depth'] ?? 0),
                    'kode' => mb_substr((string) ($item['kode'] ?? ''), 0, 50),
                    'uraian' => (string) ($item['uraian'] ?? ''),
                    'lokasi' => mb_substr((string) ($item['lokasi'] ?? ''), 0, 150),
                    'kppn' => mb_substr((string) ($item['kppn'] ?? ''), 0, 100),
                    'jenis_komponen' => mb_substr((string) ($item['jenis_komponen'] ?? ''), 0, 10),
                    'volume' => $item['volume'] !== null ? (float) $item['volume'] : null,
                    'satuan' => mb_substr((string) ($item['satuan'] ?? ''), 0, 80),
                    'harga_satuan' => (int) ($item['harga_satuan'] ?? 0),
                    'jumlah' => (int) ($item['jumlah'] ?? 0),
                    'sumber_dana' => mb_substr((string) ($item['sumber_dana'] ?? ''), 0, 20),
                    'is_blokir' => !empty($item['is_blokir']) ? 1 : 0,
                    'nilai_blokir' => (int) ($item['nilai_blokir'] ?? 0),
                    'catatan' => $item['catatan'] ?? null,
                    'urutan' => $urutan,
                    'created_by' => $userId,
                    'updated_by' => $userId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            foreach (array_chunk($batchRows, 200) as $chunk) {
                $itemsTable->insertBatch($chunk);
            }

            if (!empty($neededParentUrutanToTemp)) {
                $neededUrutans = array_keys($neededParentUrutanToTemp);
                foreach (array_chunk($neededUrutans, 500) as $urutanChunk) {
                    $insertedParents = $this->db->table('relaxtime_rkks_items')
                        ->select('id_item, urutan')
                        ->where('id_dokumen', $idDokumen)
                        ->whereIn('urutan', $urutanChunk)
                        ->get()
                        ->getResultArray();
                    foreach ($insertedParents as $pRow) {
                        $u = (int) $pRow['urutan'];
                        if (isset($neededParentUrutanToTemp[$u])) {
                            $tempToRealId[$neededParentUrutanToTemp[$u]] = (int) $pRow['id_item'];
                        }
                    }
                }
            }
        }

        $this->db->table('relaxtime_rkks_dokumen')
            ->where('id_dokumen', $idDokumen)
            ->update([
                'total_pagu' => $totalPagu,
                'updated_at' => $now,
            ]);

        $this->db->transComplete();

        return $this->db->transStatus() ? $totalPagu : 0;
    }

    public function insertItemAndRollup(array $payload): int
    {
        $idDokumen = (int) $payload['id_dokumen'];
        $parentId = !empty($payload['parent_id']) ? (int) $payload['parent_id'] : null;
        $now = date('Y-m-d H:i:s');

        $this->db->transStart();
        $allItems = $this->getItemsByDokumen($idDokumen);

        $insertAfterUrutan = 0;
        if ($parentId !== null) {
            $subtreeMap = array_flip($this->collectSubtreeIds($allItems, $parentId));
            foreach ($allItems as $it) {
                if (isset($subtreeMap[(int) $it['id_item']])) {
                    $insertAfterUrutan = max($insertAfterUrutan, (int) $it['urutan']);
                }
            }
        } else {
            foreach ($allItems as $it) {
                $insertAfterUrutan = max($insertAfterUrutan, (int) $it['urutan']);
            }
        }

        $newUrutan = $insertAfterUrutan + 1;
        $this->db->query(
            'UPDATE `relaxtime_rkks_items` SET `urutan` = `urutan` + 1 WHERE `id_dokumen` = ? AND `urutan` >= ?',
            [$idDokumen, $newUrutan]
        );

        $insertData = [
            'id_dokumen' => $idDokumen,
            'parent_id' => $parentId,
            'level_type' => (string) $payload['level_type'],
            'header_depth' => (int) ($payload['header_depth'] ?? 0),
            'kode' => mb_substr((string) ($payload['kode'] ?? ''), 0, 50),
            'uraian' => (string) ($payload['uraian'] ?? ''),
            'lokasi' => mb_substr((string) ($payload['lokasi'] ?? ''), 0, 150),
            'kppn' => mb_substr((string) ($payload['kppn'] ?? ''), 0, 100),
            'jenis_komponen' => mb_substr((string) ($payload['jenis_komponen'] ?? ''), 0, 10),
            'volume' => $payload['volume'] !== null && $payload['volume'] !== '' ? (float) $payload['volume'] : null,
            'satuan' => mb_substr((string) ($payload['satuan'] ?? ''), 0, 80),
            'harga_satuan' => (int) ($payload['harga_satuan'] ?? 0),
            'jumlah' => (int) ($payload['jumlah'] ?? 0),
            'sumber_dana' => mb_substr((string) ($payload['sumber_dana'] ?? ''), 0, 20),
            'is_blokir' => !empty($payload['is_blokir']) ? 1 : 0,
            'nilai_blokir' => (int) ($payload['nilai_blokir'] ?? 0),
            'catatan' => $payload['catatan'] ?? null,
            'urutan' => $newUrutan,
            'created_by' => $payload['created_by'] ?? null,
            'updated_by' => $payload['created_by'] ?? null,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $this->db->table('relaxtime_rkks_items')->insert($insertData);
        $newId = (int) $this->db->insertID();
        $this->recalculateAndPersistRollup($idDokumen);
        $this->db->transComplete();

        return $newId;
    }

    public function updateItemAndRollup(int $idItem, array $payload): bool
    {
        $existing = $this->getItem($idItem);
        if (!$existing) {
            return false;
        }

        $idDokumen = (int) $existing['id_dokumen'];
        $updateData = [
            'kode' => mb_substr((string) ($payload['kode'] ?? $existing['kode']), 0, 50),
            'uraian' => (string) ($payload['uraian'] ?? $existing['uraian']),
            'header_depth' => (int) ($payload['header_depth'] ?? $existing['header_depth']),
            'lokasi' => mb_substr((string) ($payload['lokasi'] ?? $existing['lokasi']), 0, 150),
            'kppn' => mb_substr((string) ($payload['kppn'] ?? $existing['kppn']), 0, 100),
            'jenis_komponen' => mb_substr((string) ($payload['jenis_komponen'] ?? $existing['jenis_komponen']), 0, 10),
            'volume' => array_key_exists('volume', $payload) ? ($payload['volume'] !== null && $payload['volume'] !== '' ? (float) $payload['volume'] : null) : $existing['volume'],
            'satuan' => mb_substr((string) ($payload['satuan'] ?? $existing['satuan']), 0, 80),
            'harga_satuan' => (int) ($payload['harga_satuan'] ?? $existing['harga_satuan']),
            'jumlah' => (int) ($payload['jumlah'] ?? $existing['jumlah']),
            'sumber_dana' => mb_substr((string) ($payload['sumber_dana'] ?? $existing['sumber_dana']), 0, 20),
            'is_blokir' => array_key_exists('is_blokir', $payload) ? (!empty($payload['is_blokir']) ? 1 : 0) : (int) $existing['is_blokir'],
            'nilai_blokir' => (int) ($payload['nilai_blokir'] ?? $existing['nilai_blokir']),
            'catatan' => $payload['catatan'] ?? $existing['catatan'],
            'updated_by' => $payload['updated_by'] ?? null,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->db->transStart();
        $this->db->table('relaxtime_rkks_items')->where('id_item', $idItem)->update($updateData);
        $this->recalculateAndPersistRollup($idDokumen);
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    public function deleteSubtreeAndRollup(int $idItem): array
    {
        $existing = $this->getItem($idItem);
        if (!$existing) {
            return ['deleted_count' => 0, 'total_pagu' => 0];
        }

        $idDokumen = (int) $existing['id_dokumen'];
        $allItems = $this->getItemsByDokumen($idDokumen);
        $ids = $this->collectSubtreeIds($allItems, $idItem);

        $this->db->transStart();
        $this->db->table('relaxtime_rkks_items')->whereIn('id_item', $ids)->delete();
        $totalPagu = $this->recalculateAndPersistRollup($idDokumen);
        $this->db->transComplete();

        return [
            'deleted_count' => count($ids),
            'total_pagu' => $totalPagu,
        ];
    }

    public function recalculateAndPersistRollup(int $idDokumen): int
    {
        $items = $this->getItemsByDokumen($idDokumen);
        $original = [];
        foreach ($items as $it) {
            $original[(int) $it['id_item']] = [
                'jumlah' => (int) $it['jumlah'],
                'nilai_blokir' => (int) $it['nilai_blokir'],
            ];
        }

        RelaxtimeRkksHelper::recalculateTreeAmounts($items);

        $updateRows = [];
        foreach ($items as $it) {
            $id = (int) $it['id_item'];
            $newJml = (int) $it['jumlah'];
            $newBlk = (int) $it['nilai_blokir'];
            if ($original[$id]['jumlah'] !== $newJml || $original[$id]['nilai_blokir'] !== $newBlk) {
                $updateRows[] = [
                    'id_item' => $id,
                    'jumlah' => $newJml,
                    'nilai_blokir' => $newBlk,
                ];
            }
        }

        if (!empty($updateRows)) {
            $table = $this->db->table('relaxtime_rkks_items');
            foreach (array_chunk($updateRows, 200) as $chunk) {
                $table->updateBatch($chunk, 'id_item');
            }
        }

        $summary = RelaxtimeRkksHelper::buildSummary($items);
        $totalPagu = (int) ($summary['total_pagu'] ?? 0);

        $this->db->table('relaxtime_rkks_dokumen')
            ->where('id_dokumen', $idDokumen)
            ->update([
                'total_pagu' => $totalPagu,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return $totalPagu;
    }

    private function collectSubtreeIds(array $items, int $rootId): array
    {
        $childrenMap = [];
        foreach ($items as $it) {
            $pid = $it['parent_id'] !== null ? (int) $it['parent_id'] : 0;
            $childrenMap[$pid][] = (int) $it['id_item'];
        }

        $result = [];
        $visited = [];
        $queue = [$rootId];
        $qIdx = 0;
        while ($qIdx < count($queue)) {
            $curr = $queue[$qIdx++];
            if (isset($visited[$curr])) {
                continue;
            }
            $visited[$curr] = true;
            $result[] = $curr;
            foreach ($childrenMap[$curr] ?? [] as $childId) {
                $queue[] = $childId;
            }
        }

        return $result;
    }
}
