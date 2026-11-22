<?php

namespace Tests\Unit;

use App\Libraries\AbsenMediaMigrator;
use App\Libraries\AbsenMediaStorage;
use PHPUnit\Framework\TestCase;

class AbsenMediaMigratorTest extends TestCase
{
    private string $tempRoot;
    private string $baseDir;
    private string $legacyDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tempRoot  = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'simply_migrator_test_' . uniqid('', true);
        $this->baseDir   = $this->tempRoot . DIRECTORY_SEPARATOR . 'absen_media';
        $this->legacyDir = $this->tempRoot . DIRECTORY_SEPARATOR . 'legacy_public';
        mkdir($this->baseDir . DIRECTORY_SEPARATOR . 'legacy', 0775, true);
        mkdir($this->legacyDir, 0775, true);
    }

    protected function tearDown(): void
    {
        if (is_dir($this->tempRoot)) {
            $items = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($this->tempRoot, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($items as $item) {
                $item->isDir() ? @rmdir($item->getPathname()) : @unlink($item->getPathname());
            }
            @rmdir($this->tempRoot);
        }
        parent::tearDown();
    }

    private function sampleJpegBytes(string $fill = "\x11\x22\x33\x44"): string
    {
        return "\xFF\xD8\xFF\xE0\x00\x10JFIF\x00\x01\x01\x00\x00\x01\x00\x01\x00\x00"
            . str_repeat($fill, 80)
            . "\xFF\xD9";
    }

    private function samplePngDataUri(): string
    {
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==';
    }

    public function testProcessRowDeduplicatesMatchingRekamAbsenFileWithoutWritingNewFile(): void
    {
        $jpeg = $this->sampleJpegBytes();
        file_put_contents($this->baseDir . DIRECTORY_SEPARATOR . 'legacy' . DIRECTORY_SEPARATOR . 'abs_1.jpg', $jpeg);

        $storage  = new AbsenMediaStorage($this->baseDir, $this->legacyDir);
        $migrator = new AbsenMediaMigrator($storage);

        $row = [
            'id_absen'       => 50001,
            'created_at'     => '2026-11-22 07:30:00',
            'jam_masuk'      => '2026-11-22 07:30:00',
            'image_masuk_1'  => 'data:image/jpeg;base64,' . base64_encode($jpeg),
            'image_masuk_2'  => '',
            'image_pulang_1' => '',
            'image_pulang_2' => '',
            'ttd_masuk'      => null,
            'ttd_pulang'     => null,
            'rekam_absen'    => json_encode([['tombol' => 'Rekam Masuk', 'foto' => 'abs_1.jpg']]),
        ];

        $res = $migrator->processRow($row, false);

        $this->assertSame(['image_masuk_1' => 'media:legacy/abs_1.jpg'], $res['patch']);
        $this->assertSame(1, $res['stats']['dedup_reused']);
        $this->assertSame(0, $res['stats']['extracted_new_photos']);
        $this->assertDirectoryDoesNotExist($this->baseDir . DIRECTORY_SEPARATOR . 'foto');
        $this->assertSame([], $res['warnings']);
    }

    public function testProcessRowExtractsDbOnlyPhotoAndTtdWithVerification(): void
    {
        $jpeg     = $this->sampleJpegBytes("\x55\x66\x77\x88");
        $storage  = new AbsenMediaStorage($this->baseDir, $this->legacyDir);
        $migrator = new AbsenMediaMigrator($storage);

        $row = [
            'id_absen'       => 1,
            'created_at'     => '2024-07-03 10:36:24',
            'jam_masuk'      => '2024-07-03 10:36:24',
            'image_masuk_1'  => 'data:image/jpeg;base64,' . base64_encode($jpeg),
            'image_masuk_2'  => '',
            'image_pulang_1' => '',
            'image_pulang_2' => '',
            'ttd_masuk'      => $this->samplePngDataUri(),
            'ttd_pulang'     => null,
            'rekam_absen'    => '',
        ];

        $res = $migrator->processRow($row, false);

        $this->assertArrayHasKey('image_masuk_1', $res['patch']);
        $this->assertArrayHasKey('ttd_masuk', $res['patch']);
        $this->assertMatchesRegularExpression('#^media:foto/2024/07/abs_1_image_masuk_1_[a-f0-9]{8}\.jpg$#', $res['patch']['image_masuk_1']);
        $this->assertMatchesRegularExpression('#^media:ttd/2024/07/ttd_1_ttd_masuk_[a-f0-9]{8}\.png$#', $res['patch']['ttd_masuk']);

        $fotoFile = $storage->resolveToFile($res['patch']['image_masuk_1']);
        $ttdFile  = $storage->resolveToFile($res['patch']['ttd_masuk']);
        $this->assertIsArray($fotoFile);
        $this->assertIsArray($ttdFile);
        $this->assertGreaterThan(0, filesize($fotoFile['path']));
        $this->assertGreaterThan(0, filesize($ttdFile['path']));
        $this->assertSame(1, $res['stats']['extracted_new_photos']);
        $this->assertSame(1, $res['stats']['extracted_new_ttd']);
    }

    public function testProcessRowRecoversTruncated2024Base64AndSkipsCorruptAndEmptyCanvas(): void
    {
        $jpeg = $this->sampleJpegBytes("\x99\x88\x77\x66");
        $b64  = base64_encode($jpeg);
        $cut  = strlen($b64) - 3; // truncated mid-quantum, no EOI
        $truncUri = 'data:image/jpeg;base64,' . substr($b64, 0, $cut);

        $storage  = new AbsenMediaStorage($this->baseDir, $this->legacyDir);
        $migrator = new AbsenMediaMigrator($storage);

        $row = [
            'id_absen'       => 4,
            'created_at'     => '2024-07-03 13:51:46',
            'jam_masuk'      => '2024-07-03 13:51:46',
            'image_masuk_1'  => $truncUri,
            'image_masuk_2'  => 'data:,',
            'image_pulang_1' => 'data:image/jpeg;base64,' . str_repeat('@', 120),
            'image_pulang_2' => 'media:legacy/x.jpg',
            'ttd_masuk'      => '',
            'ttd_pulang'     => null,
            'rekam_absen'    => '',
        ];

        $res = $migrator->processRow($row, false);

        $this->assertArrayHasKey('image_masuk_1', $res['patch']);
        $this->assertArrayNotHasKey('image_masuk_2', $res['patch']);
        $this->assertArrayNotHasKey('image_pulang_1', $res['patch']);
        $this->assertArrayNotHasKey('image_pulang_2', $res['patch']);
        $this->assertSame(1, $res['stats']['truncated_recovered']);
        $this->assertSame(1, $res['stats']['skipped_warnings']);
        $this->assertCount(1, $res['warnings']);
    }

    public function testProcessRowDryRunWritesZeroFiles(): void
    {
        $jpeg     = $this->sampleJpegBytes("\x12\x34\x56\x78");
        $storage  = new AbsenMediaStorage($this->baseDir, $this->legacyDir);
        $migrator = new AbsenMediaMigrator($storage);

        $row = [
            'id_absen'       => 99,
            'created_at'     => '2026-11-22 08:00:00',
            'jam_masuk'      => '2026-11-22 08:00:00',
            'image_masuk_1'  => 'data:image/jpeg;base64,' . base64_encode($jpeg),
            'image_masuk_2'  => '',
            'image_pulang_1' => '',
            'image_pulang_2' => '',
            'ttd_masuk'      => $this->samplePngDataUri(),
            'ttd_pulang'     => null,
            'rekam_absen'    => '',
        ];

        $res = $migrator->processRow($row, true);

        $this->assertArrayHasKey('image_masuk_1', $res['patch']);
        $this->assertArrayHasKey('ttd_masuk', $res['patch']);
        $this->assertSame(1, $res['stats']['extracted_new_photos']);
        $this->assertSame(1, $res['stats']['extracted_new_ttd']);
        $this->assertDirectoryDoesNotExist($this->baseDir . DIRECTORY_SEPARATOR . 'foto');
        $this->assertDirectoryDoesNotExist($this->baseDir . DIRECTORY_SEPARATOR . 'ttd');
    }

    public function testWaitForPurgeLagThrottlesUntilHistoryDropsBelowThreshold(): void
    {
        $readings = [350, 260, 120];
        $idx      = 0;
        $sleeps   = 0;

        $storage  = new AbsenMediaStorage($this->baseDir, $this->legacyDir);
        $migrator = new AbsenMediaMigrator(
            $storage,
            static function () use ($readings, &$idx): int {
                $val = $readings[min($idx, count($readings) - 1)];
                $idx++;
                return $val;
            },
            static function (int $ms) use (&$sleeps): void {
                $sleeps++;
            }
        );

        $finalLen = $migrator->waitForPurgeLag(200, 60);

        $this->assertSame(2, $sleeps);
        $this->assertSame(120, $finalLen);
    }
}
