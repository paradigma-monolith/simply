<?php

declare(strict_types=1);

namespace App\Libraries;

use CodeIgniter\Database\BaseConnection;

/**
 * Engine migrasi backfill media absensi (base64 DB -> writable/absen_media/)
 * dengan deduplikasi SHA-256 terhadap file rekam_absen dan throttling InnoDB purge lag.
 */
class AbsenMediaMigrator
{
    public const PHOTO_COLUMNS = [
        'image_masuk_1',
        'image_masuk_2',
        'image_pulang_1',
        'image_pulang_2',
    ];

    public const TTD_COLUMNS = [
        'ttd_masuk',
        'ttd_pulang',
    ];

    private AbsenMediaStorage $storage;
    /** @var callable(): int */
    private $historyLenProvider;
    /** @var callable(int): void */
    private $sleepCallback;

    public function __construct(
        AbsenMediaStorage $storage,
        ?callable $historyLenProvider = null,
        ?callable $sleepCallback = null
    ) {
        $this->storage = $storage;
        $this->historyLenProvider = $historyLenProvider ?? static fn (): int => 0;
        $this->sleepCallback = $sleepCallback ?? static function (int $ms): void {
            if ($ms > 0) {
                usleep($ms * 1000);
            }
        };
    }

    /**
     * Proses 1 baris tabel `absen` dan kembalikan patch kolom siap-update + statistik + warnings.
     * Tidak pernah memodifikasi DB secara langsung.
     *
     * @param array<string, mixed> $row
     * @return array{
     *   patch: array<string, string>,
     *   stats: array{
     *     dedup_reused: int,
     *     extracted_new_photos: int,
     *     extracted_new_ttd: int,
     *     truncated_recovered: int,
     *     skipped_warnings: int,
     *     errors: int
     *   },
     *   warnings: string[]
     * }
     */
    public function processRow(array $row, bool $dryRun = false): array
    {
        $id = (int) ($row['id_absen'] ?? 0);
        $dateStr = $this->pickRowDate($row);
        [$year, $month] = $this->extractYearMonth($dateStr);

        $patch = [];
        $warnings = [];
        $stats = [
            'dedup_reused'         => 0,
            'extracted_new_photos' => 0,
            'extracted_new_ttd'    => 0,
            'truncated_recovered'  => 0,
            'skipped_warnings'     => 0,
            'errors'               => 0,
        ];

        $rekamHashes = null;

        foreach (self::PHOTO_COLUMNS as $col) {
            $val = isset($row[$col]) ? (string) $row[$col] : '';
            if (!$this->needsMigration($val)) {
                continue;
            }

            if ($rekamHashes === null) {
                $rekamHashes = $this->storage->buildRekamHashMap(
                    isset($row['rekam_absen']) ? (string) $row['rekam_absen'] : null
                );
            }

            $decoded = $this->storage->decodeBase64Tolerant($val);
            if ($decoded === null) {
                $stats['skipped_warnings']++;
                $warnings[] = "id_absen={$id} col={$col}: gagal decode base64 (len=" . strlen($val) . "), kolom dipertahankan.";
                continue;
            }

            if ($decoded['truncated']) {
                $stats['truncated_recovered']++;
            }

            $sha = hash('sha256', $decoded['binary']);
            if (isset($rekamHashes[$sha])) {
                $existingRef = $rekamHashes[$sha];
                if ($this->storage->resolveToFile($existingRef) !== null) {
                    $patch[$col] = $existingRef;
                    $stats['dedup_reused']++;
                    continue;
                }
            }

            $hash8 = substr($sha, 0, 8);
            $filename = "abs_{$id}_{$col}_{$hash8}.{$decoded['ext']}";

            if ($dryRun) {
                $expectedRef = AbsenMediaStorage::MEDIA_PREFIX . "foto/{$year}/{$month}/{$filename}";
                $patch[$col] = $expectedRef;
                $rekamHashes[$sha] = $expectedRef;
                $stats['extracted_new_photos']++;
                continue;
            }

            $saved = $this->storage->saveBinary($decoded['binary'], $decoded['ext'], 'foto', $dateStr, $filename);
            if ($saved === null || $this->storage->resolveToFile($saved['mediaRef']) === null) {
                $stats['errors']++;
                $warnings[] = "id_absen={$id} col={$col}: gagal menulis/memverifikasi file foto ke disk, kolom dipertahankan.";
                continue;
            }

            $patch[$col] = $saved['mediaRef'];
            $rekamHashes[$sha] = $saved['mediaRef'];
            $stats['extracted_new_photos']++;
        }

        foreach (self::TTD_COLUMNS as $col) {
            $val = isset($row[$col]) ? (string) $row[$col] : '';
            if (!$this->needsMigration($val)) {
                continue;
            }

            $decoded = $this->storage->decodeBase64Tolerant($val);
            if ($decoded === null) {
                $stats['skipped_warnings']++;
                $warnings[] = "id_absen={$id} col={$col}: gagal decode TTD base64 (len=" . strlen($val) . "), kolom dipertahankan.";
                continue;
            }

            if ($decoded['truncated']) {
                $stats['truncated_recovered']++;
            }

            $sha = hash('sha256', $decoded['binary']);
            $hash8 = substr($sha, 0, 8);
            $filename = "ttd_{$id}_{$col}_{$hash8}.{$decoded['ext']}";

            if ($dryRun) {
                $patch[$col] = AbsenMediaStorage::MEDIA_PREFIX . "ttd/{$year}/{$month}/{$filename}";
                $stats['extracted_new_ttd']++;
                continue;
            }

            $saved = $this->storage->saveBinary($decoded['binary'], $decoded['ext'], 'ttd', $dateStr, $filename);
            if ($saved === null || $this->storage->resolveToFile($saved['mediaRef']) === null) {
                $stats['errors']++;
                $warnings[] = "id_absen={$id} col={$col}: gagal menulis/memverifikasi file TTD ke disk, kolom dipertahankan.";
                continue;
            }

            $patch[$col] = $saved['mediaRef'];
            $stats['extracted_new_ttd']++;
        }

        return [
            'patch'    => $patch,
            'stats'    => $stats,
            'warnings' => $warnings,
        ];
    }

    /**
     * Tunggu sampai InnoDB History list length <= $maxHistory agar ibdata1 tidak membengkak.
     */
    public function waitForPurgeLag(int $maxHistory, int $maxWaitSec = 60): int
    {
        $maxHistory = max(1, $maxHistory);
        $deadline = microtime(true) + max(1, $maxWaitSec);
        $len = (int) ($this->historyLenProvider)();

        while ($len > $maxHistory && microtime(true) < $deadline) {
            ($this->sleepCallback)(500);
            $len = (int) ($this->historyLenProvider)();
        }

        return $len;
    }

    /**
     * Baca InnoDB History list length aktual dari MariaDB/MySQL.
     */
    public static function readInnoDbHistoryLength(BaseConnection $db): int
    {
        try {
            $row = $db->query(
                "SELECT `COUNT` AS hist FROM information_schema.INNODB_METRICS WHERE NAME = 'trx_rseg_history_len' AND STATUS = 'enabled' LIMIT 1"
            )->getRowArray();
            if (is_array($row) && isset($row['hist'])) {
                return (int) $row['hist'];
            }
        } catch (\Throwable $e) {
            // Fallback ke SHOW ENGINE INNODB STATUS di bawah
        }

        try {
            $status = $db->query('SHOW ENGINE INNODB STATUS')->getRowArray();
            $text = is_array($status) ? (string) ($status['Status'] ?? '') : '';
            if (preg_match('/History list length\s+(\d+)/i', $text, $m)) {
                return (int) $m[1];
            }
        } catch (\Throwable $e) {
            return 0;
        }

        return 0;
    }

    private function needsMigration(string $val): bool
    {
        if (AbsenMediaStorage::isEmptyValue($val) || AbsenMediaStorage::isMediaRef($val)) {
            return false;
        }
        return strlen(trim($val)) > 100;
    }

    /**
     * @param array<string, mixed> $row
     */
    private function pickRowDate(array $row): string
    {
        foreach (['created_at', 'jam_masuk', 'jam_pulang'] as $field) {
            $val = isset($row[$field]) ? trim((string) $row[$field]) : '';
            if ($val !== '' && strpos($val, '0000-') !== 0 && preg_match('/^\d{4}-\d{2}-\d{2}/', $val)) {
                return $val;
            }
        }
        return date('Y-m-d');
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function extractYearMonth(string $dateStr): array
    {
        if (preg_match('/^(\d{4})-(\d{2})/', $dateStr, $m)) {
            $y = (int) $m[1];
            $mo = (int) $m[2];
            if ($y >= 2000 && $y <= 2100 && $mo >= 1 && $mo <= 12) {
                return [$m[1], $m[2]];
            }
        }
        return [date('Y'), date('m')];
    }
}
