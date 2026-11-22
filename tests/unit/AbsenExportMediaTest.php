<?php

namespace Tests\Unit;

use App\Libraries\AbsenMediaStorage;
use PHPUnit\Framework\TestCase;

class AbsenExportMediaTest extends TestCase
{
    private string $tempRoot;
    private string $baseDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tempRoot = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'simply_export_test_' . uniqid('', true);
        $this->baseDir  = $this->tempRoot . DIRECTORY_SEPARATOR . 'absen_media';
        mkdir($this->baseDir, 0775, true);
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

    private function samplePngDataUri(): string
    {
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==';
    }

    public function testPdfViewRendersMigratedFileSignatureAsDataUriAndSkipsEmptyCanvas(): void
    {
        $storage = new AbsenMediaStorage($this->baseDir);
        $pngUri  = $this->samplePngDataUri();
        $saved   = $storage->saveBase64($pngUri, 'ttd', '2026-11-22');
        $this->assertIsArray($saved);

        $tglAwal      = '2026-11-01';
        $tglAkhir     = '2026-11-22';
        $mediaStorage = $storage;
        $absen        = [
            [
                'nip'           => '199001012020011001',
                'nama'          => 'Pegawai Satu',
                'status_masuk'  => 'DL',
                'alamat_masuk'  => 'Bandung',
                'jam_masuk'     => '2026-11-22 07:30:00',
                'ttd_masuk'     => $saved['mediaRef'],
                'status_pulang' => 'DL',
                'alamat_pulang' => 'Bandung',
                'jam_pulang'    => '2026-11-22 16:30:00',
                'ttd_pulang'    => 'data:,',
            ],
            [
                'nip'           => '199001012020011002',
                'nama'          => 'Pegawai Dua',
                'status_masuk'  => 'DL',
                'alamat_masuk'  => 'Cimahi',
                'jam_masuk'     => '2026-11-22 08:00:00',
                'ttd_masuk'     => $pngUri,
                'status_pulang' => 'DL',
                'alamat_pulang' => 'Cimahi',
                'jam_pulang'    => '2026-11-22 17:00:00',
                'ttd_pulang'    => '',
            ],
        ];

        ob_start();
        include APPPATH . 'Views' . DIRECTORY_SEPARATOR . 'absen' . DIRECTORY_SEPARATOR . 'pdf_rekam_absen_dl.php';
        $html = (string) ob_get_clean();

        $this->assertSame(2, substr_count($html, 'src="' . $pngUri . '"'));
        $this->assertStringNotContainsString('media:ttd/', $html);
        $this->assertStringNotContainsString('src="data:,"', $html);
        $this->assertStringNotContainsString('base64,data:,', $html);
    }

    public function testStorageResolveToFileReturnsDirectPathForExcelDrawing(): void
    {
        $storage = new AbsenMediaStorage($this->baseDir);
        $pngUri  = $this->samplePngDataUri();
        $saved   = $storage->saveBase64($pngUri, 'ttd', '2026-11-22');
        $this->assertIsArray($saved);

        $fileRes = $storage->resolveToFile($saved['mediaRef']);
        $this->assertIsArray($fileRes);
        $this->assertSame(realpath($saved['fullPath']), realpath($fileRes['path']));
        $this->assertSame('image/png', $fileRes['mime']);

        $this->assertNull($storage->resolveToFile($pngUri));
        $binRes = $storage->resolveToBinary($pngUri);
        $this->assertIsArray($binRes);
        $this->assertSame('image/png', $binRes['mime']);
        $this->assertStringStartsWith("\x89PNG", $binRes['binary']);
    }

    public function testExcelSupportedDrawingGuardAcceptsRealPngAndRejectsCorruptFile(): void
    {
        $storage = new AbsenMediaStorage($this->baseDir);
        $saved   = $storage->saveBase64($this->samplePngDataUri(), 'ttd', '2026-11-22');
        $this->assertIsArray($saved);

        $this->assertTrue(\App\Controllers\Export\Excel::isSupportedDrawingImage($saved['fullPath']));

        $corrupt = $this->tempRoot . DIRECTORY_SEPARATOR . 'corrupt.png';
        file_put_contents($corrupt, 'not-a-real-image-bytes');
        $this->assertFalse(\App\Controllers\Export\Excel::isSupportedDrawingImage($corrupt));
    }
}
