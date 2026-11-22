<?php

declare(strict_types=1);

namespace App\Commands;

use App\Libraries\AbsenMediaMigrator;
use App\Libraries\AbsenMediaStorage;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class MigrateAbsenMedia extends BaseCommand
{
    protected $group       = 'Absen';
    protected $name        = 'absen:migrate-media';
    protected $description = 'Migrasikan foto & tanda tangan base64 pada tabel absen ke writable/absen_media/ secara bertahap dan aman.';

    protected $usage = 'absen:migrate-media [--dry-run] [--batch=100] [--start-id=0] [--sleep-ms=50] [--max-history=200] [--limit=0] [--legacy-dir=PATH]';

    protected $options = [
        '--dry-run'     => 'Simulasikan tanpa menulis file atau mengubah database.',
        '--batch'       => 'Jumlah baris per batch Primary Key (default: 100).',
        '--start-id'    => 'Mulai migrasi dari id_absen > nilai ini (default: 0).',
        '--sleep-ms'    => 'Jeda antar batch dalam milidetik (default: 50).',
        '--max-history' => 'Batas maksimum InnoDB History list length sebelum throttle (default: 200).',
        '--limit'       => 'Batas total baris yang diproses; 0 berarti semua (default: 0).',
        '--legacy-dir'  => 'Override direktori legacy dist/foto_absen jika berbeda dari FCPATH.',
    ];

    /**
     * Normalisasi opsi CLI CI4 agar mendukung baik `--opt=val` maupun `--opt val`.
     *
     * @param array<string, mixed> $cliOptions Output dari CLI::getOptions()
     * @param array<int|string, mixed> $params Parameter posisional/tambahan dari run($params)
     * @return array{
     *   dry_run: bool,
     *   batch: int,
     *   start_id: int,
     *   sleep_ms: int,
     *   max_history: int,
     *   limit: int,
     *   legacy_dir: ?string
     * }
     */
    public static function parseCliOptions(array $cliOptions, array $params = []): array
    {
        $normalized = [];

        foreach ($cliOptions as $k => $v) {
            $key = ltrim((string) $k, '-');
            if (strpos($key, '=') !== false) {
                [$optName, $optVal] = explode('=', $key, 2);
                $normalized[$optName] = $optVal;
            } else {
                $normalized[$key] = $v;
            }
        }

        foreach ($params as $p) {
            if (!is_string($p)) {
                continue;
            }
            $clean = ltrim($p, '-');
            if (strpos($clean, '=') !== false) {
                [$optName, $optVal] = explode('=', $clean, 2);
                if (!array_key_exists($optName, $normalized)) {
                    $normalized[$optName] = $optVal;
                }
            } elseif ($clean === 'dry-run' && !array_key_exists('dry-run', $normalized)) {
                $normalized['dry-run'] = true;
            }
        }

        $dryRun = false;
        if (array_key_exists('dry-run', $normalized)) {
            $rawDry = $normalized['dry-run'];
            if ($rawDry === null || $rawDry === true || $rawDry === '') {
                $dryRun = true;
            } else {
                $low = strtolower(trim((string) $rawDry));
                $dryRun = !in_array($low, ['0', 'false', 'no', 'off'], true);
            }
        }

        $legacyDir = isset($normalized['legacy-dir']) && is_string($normalized['legacy-dir']) && trim($normalized['legacy-dir']) !== ''
            ? trim($normalized['legacy-dir'])
            : null;

        return [
            'dry_run'     => $dryRun,
            'batch'       => max(1, (int) ($normalized['batch'] ?? 100)),
            'start_id'    => max(0, (int) ($normalized['start-id'] ?? 0)),
            'sleep_ms'    => max(0, (int) ($normalized['sleep-ms'] ?? 50)),
            'max_history' => max(10, (int) ($normalized['max-history'] ?? 200)),
            'limit'       => max(0, (int) ($normalized['limit'] ?? 0)),
            'legacy_dir'  => $legacyDir,
        ];
    }

    public function run(array $params)
    {
        @ini_set('memory_limit', '512M');

        $opts       = self::parseCliOptions(CLI::getOptions(), $params);
        $dryRun     = $opts['dry_run'];
        $batch      = $opts['batch'];
        $lastId     = $opts['start_id'];
        $sleepMs    = $opts['sleep_ms'];
        $maxHistory = $opts['max_history'];
        $limit      = $opts['limit'];
        $legacyDir  = $opts['legacy_dir'];

        $db       = \Config\Database::connect();
        $storage  = new AbsenMediaStorage(null, $legacyDir);
        $migrator = new AbsenMediaMigrator(
            $storage,
            static fn (): ?int => AbsenMediaMigrator::readInnoDbHistoryLength($db)
        );

        $warnLogPath = rtrim(WRITEPATH, '/\\') . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'absen-migrate-warnings.log';

        CLI::write('=== absen:migrate-media ===', 'cyan');
        CLI::write(sprintf(
            'mode=%s batch=%d start-id=%d sleep-ms=%d max-history=%d limit=%d baseDir=%s',
            $dryRun ? 'DRY-RUN' : 'LIVE',
            $batch,
            $lastId,
            $sleepMs,
            $maxHistory,
            $limit,
            $storage->getBaseDir()
        ));

        $initialHistory = AbsenMediaMigrator::readInnoDbHistoryLength($db);
        if ($initialHistory === null) {
            CLI::write(
                '[WARNING] InnoDB History list length tidak dapat dibaca (user DB kemungkinan tidak memiliki privilege PROCESS). Menggunakan jeda konservatif 200ms per batch.',
                'yellow'
            );
        } else {
            CLI::write("InnoDB History list length awal: {$initialHistory}");
        }

        $totals = [
            'rows_scanned'         => 0,
            'rows_updated'         => 0,
            'dedup_reused'         => 0,
            'extracted_new_photos' => 0,
            'extracted_new_ttd'    => 0,
            'truncated_recovered'  => 0,
            'skipped_warnings'     => 0,
            'errors'               => 0,
            'throttle_waits'       => 0,
        ];

        $startedAt = microtime(true);
        $batchNum  = 0;

        while (true) {
            $take = $batch;
            if ($limit > 0) {
                $remaining = $limit - $totals['rows_scanned'];
                if ($remaining <= 0) {
                    break;
                }
                $take = min($batch, $remaining);
            }

            try {
                $rows = $db->query(
                    'SELECT id_absen, created_at, jam_masuk, jam_pulang,
                            image_masuk_1, image_masuk_2, image_pulang_1, image_pulang_2,
                            ttd_masuk, ttd_pulang, rekam_absen
                     FROM absen
                     WHERE id_absen > ?
                     ORDER BY id_absen ASC
                     LIMIT ?',
                    [$lastId, $take]
                )->getResultArray();
            } catch (\Throwable $e) {
                $totals['errors']++;
                $errMsg = '[' . date('Y-m-d H:i:s') . "] Batch SELECT gagal pada last_id={$lastId}: " . $e->getMessage() . PHP_EOL;
                @file_put_contents($warnLogPath, $errMsg, FILE_APPEND | LOCK_EX);
                CLI::error("Batch SELECT gagal pada last_id={$lastId}: " . $e->getMessage());
                break;
            }

            if (empty($rows)) {
                break;
            }

            $batchNum++;
            $batchUpdated = 0;

            foreach ($rows as $row) {
                $id = (int) $row['id_absen'];
                $lastId = $id;
                $totals['rows_scanned']++;

                $res = $migrator->processRow($row, $dryRun);

                foreach ($res['stats'] as $k => $v) {
                    $totals[$k] += $v;
                }

                if (!empty($res['warnings'])) {
                    $lines = implode(PHP_EOL, array_map(
                        static fn (string $w): string => '[' . date('Y-m-d H:i:s') . '] ' . $w,
                        $res['warnings']
                    )) . PHP_EOL;
                    @file_put_contents($warnLogPath, $lines, FILE_APPEND | LOCK_EX);
                }

                if (!empty($res['patch'])) {
                    if (!$dryRun) {
                        try {
                            // Direct Query Builder: update kolom media saja tanpa mengubah `updated_at` historis
                            $ok = $db->table('absen')->where('id_absen', $id)->update($res['patch']);
                            if (!$ok) {
                                $totals['errors']++;
                                @file_put_contents(
                                    $warnLogPath,
                                    '[' . date('Y-m-d H:i:s') . "] id_absen={$id}: UPDATE DB mengembalikan false." . PHP_EOL,
                                    FILE_APPEND | LOCK_EX
                                );
                                continue;
                            }
                        } catch (\Throwable $e) {
                            $totals['errors']++;
                            @file_put_contents(
                                $warnLogPath,
                                '[' . date('Y-m-d H:i:s') . "] id_absen={$id}: UPDATE DB exception: " . $e->getMessage() . PHP_EOL,
                                FILE_APPEND | LOCK_EX
                            );
                            continue;
                        }
                    }
                    $totals['rows_updated']++;
                    $batchUpdated++;
                }
            }

            if (!$dryRun && $batchUpdated > 0) {
                $histBefore = AbsenMediaMigrator::readInnoDbHistoryLength($db);
                if ($histBefore === null || $histBefore > $maxHistory) {
                    $totals['throttle_waits']++;
                    $migrator->waitForPurgeLag($maxHistory, 60);
                }
                if ($sleepMs > 0) {
                    usleep($sleepMs * 1000);
                }
            }

            if ($batchNum % 10 === 0 || ($batchUpdated > 0 && $batchNum === 1)) {
                $elapsed = round(microtime(true) - $startedAt, 1);
                CLI::write(sprintf(
                    '[batch %d | last_id=%d | %.1fs] scanned=%d updated=%d dedup=%d new_foto=%d new_ttd=%d trunc=%d warn=%d err=%d',
                    $batchNum,
                    $lastId,
                    $elapsed,
                    $totals['rows_scanned'],
                    $totals['rows_updated'],
                    $totals['dedup_reused'],
                    $totals['extracted_new_photos'],
                    $totals['extracted_new_ttd'],
                    $totals['truncated_recovered'],
                    $totals['skipped_warnings'],
                    $totals['errors']
                ));
            }
        }

        $elapsed = round(microtime(true) - $startedAt, 2);
        CLI::write('=== SUMMARY ===', 'green');
        foreach ($totals as $k => $v) {
            CLI::write(sprintf('  %-22s : %d', $k, $v));
        }
        CLI::write(sprintf('  %-22s : %d', 'last_id', $lastId));
        CLI::write(sprintf('  %-22s : %.2fs', 'elapsed', $elapsed));
        if ($totals['skipped_warnings'] > 0 || $totals['errors'] > 0) {
            CLI::write("  warning log            : {$warnLogPath}", 'yellow');
        }

        if ($totals['errors'] > 0) {
            return defined('EXIT_ERROR') ? EXIT_ERROR : 1;
        }
        return defined('EXIT_SUCCESS') ? EXIT_SUCCESS : 0;
    }
}
