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

    protected $usage = 'absen:migrate-media [--dry-run] [--batch 100] [--start-id 0] [--sleep-ms 50] [--max-history 200] [--limit 0] [--legacy-dir PATH]';

    protected $options = [
        '--dry-run'     => 'Simulasikan tanpa menulis file atau mengubah database.',
        '--batch'       => 'Jumlah baris per batch Primary Key (default: 100).',
        '--start-id'    => 'Mulai migrasi dari id_absen > nilai ini (default: 0).',
        '--sleep-ms'    => 'Jeda antar batch dalam milidetik (default: 50).',
        '--max-history' => 'Batas maksimum InnoDB History list length sebelum throttle (default: 200).',
        '--limit'       => 'Batas total baris yang diproses; 0 berarti semua (default: 0).',
        '--legacy-dir'  => 'Override direktori legacy dist/foto_absen jika berbeda dari FCPATH.',
    ];

    public function run(array $params)
    {
        @ini_set('memory_limit', '512M');

        $dryRun     = (CLI::getOption('dry-run') !== null) || in_array('dry-run', $params, true);
        $batch      = max(1, (int) (CLI::getOption('batch') ?? 100));
        $lastId     = max(0, (int) (CLI::getOption('start-id') ?? 0));
        $sleepMs    = max(0, (int) (CLI::getOption('sleep-ms') ?? 50));
        $maxHistory = max(10, (int) (CLI::getOption('max-history') ?? 200));
        $limit      = max(0, (int) (CLI::getOption('limit') ?? 0));
        $legacyDir  = CLI::getOption('legacy-dir');
        $legacyDir  = is_string($legacyDir) && trim($legacyDir) !== '' ? trim($legacyDir) : null;

        $db       = \Config\Database::connect();
        $storage  = new AbsenMediaStorage(null, $legacyDir);
        $migrator = new AbsenMediaMigrator(
            $storage,
            static fn (): int => AbsenMediaMigrator::readInnoDbHistoryLength($db)
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
                        // Direct Query Builder: update kolom media saja tanpa mengubah `updated_at` historis
                        $ok = $db->table('absen')->where('id_absen', $id)->update($res['patch']);
                        if (!$ok) {
                            $totals['errors']++;
                            @file_put_contents(
                                $warnLogPath,
                                '[' . date('Y-m-d H:i:s') . "] id_absen={$id}: UPDATE DB gagal." . PHP_EOL,
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
                if ($histBefore > $maxHistory) {
                    $totals['throttle_waits']++;
                    $migrator->waitForPurgeLag($maxHistory, 60);
                }
                if ($sleepMs > 0) {
                    usleep($sleepMs * 1000);
                }
            }

            if ($batchNum % 10 === 0 || $batchUpdated > 0 && $batchNum === 1) {
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
    }
}
