<?php

namespace Tests\Unit;

use App\Controllers\Absen\Ajax;
use App\Controllers\Absen\Crud;
use App\Libraries\AbsenMediaStorage;
use PHPUnit\Framework\TestCase;

class AbsenMediaHandlersTest extends TestCase
{
    private string $tempRoot;
    private string $baseDir;
    private string $legacyDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tempRoot  = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'simply_handlers_test_' . uniqid('', true);
        $this->baseDir   = $this->tempRoot . DIRECTORY_SEPARATOR . 'absen_media';
        $this->legacyDir = $this->tempRoot . DIRECTORY_SEPARATOR . 'legacy_public';
        mkdir($this->baseDir . DIRECTORY_SEPARATOR . 'foto' . DIRECTORY_SEPARATOR . '2026' . DIRECTORY_SEPARATOR . '11', 0775, true);
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

    private function sampleJpegBytes(): string
    {
        return "\xFF\xD8\xFF\xE0\x00\x10JFIF\x00\x01\x01\x00\x00\x10\x00\x10\x00\x00"
            . str_repeat("\xAA\xBB\xCC\xDD", 80)
            . "\xFF\xD9";
    }

    public function testResolveRekamImageInputFromExtensionlessRouteAndLegacyPath(): void
    {
        $jpeg = $this->sampleJpegBytes();
        file_put_contents($this->baseDir . '/foto/2026/11/a.jpg', $jpeg);
        file_put_contents($this->baseDir . '/legacy/old.jpg', $jpeg);

        $storage   = new AbsenMediaStorage($this->baseDir, $this->legacyDir);
        $rekamJson = json_encode([
            ['tombol' => 'Rekam Masuk', 'foto' => 'foto/2026/11/a.jpg'],
            ['tombol' => 'Rekam Pulang', 'foto' => 'old.jpg'],
        ]);

        $this->assertSame('media:foto/2026/11/a.jpg', Crud::resolveRekamImageInput('/absen/rekam-foto/50/0', $rekamJson, $storage));
        $this->assertSame('media:legacy/old.jpg', Crud::resolveRekamImageInput('/absen/rekam-foto/50/1', $rekamJson, $storage));
        $this->assertSame('media:legacy/old.jpg', Crud::resolveRekamImageInput('/dist/foto_absen/old.jpg', $rekamJson, $storage));
        $this->assertNull(Crud::resolveRekamImageInput('/absen/rekam-foto/50/99', $rekamJson, $storage));
        $this->assertNull(Crud::resolveRekamImageInput('/dist/foto_absen/../../.env', $rekamJson, $storage));
        $this->assertNull(Crud::resolveRekamImageInput('', $rekamJson, $storage));
    }

    public function testMapImageColumnsToUrlsIgnoresEmptyAndEmptyCanvas(): void
    {
        $row = [
            'id_absen'       => 12,
            'image_masuk_1'  => 'media:foto/2026/11/x.jpg',
            'image_pulang_1' => 'data:,',
        ];

        $mapped = Ajax::mapImageColumnsToUrls(12, $row, [
            'image_masuk_1'  => 'masuk',
            'image_pulang_1' => 'pulang',
        ]);

        $this->assertSame('/absen/foto/12/masuk', $mapped['image_masuk_1']);
        $this->assertSame('', $mapped['image_pulang_1']);

        $row2 = [
            'id_absen'       => 12,
            'image_masuk_2'  => '',
            'image_pulang_2' => 'data:image/jpeg;base64,' . base64_encode($this->sampleJpegBytes()),
        ];

        $mapped2 = Ajax::mapImageColumnsToUrls(12, $row2, [
            'image_masuk_2'  => 'masuk2',
            'image_pulang_2' => 'pulang2',
        ]);

        $this->assertSame('', $mapped2['image_masuk_2']);
        $this->assertSame('/absen/foto/12/pulang2', $mapped2['image_pulang_2']);
    }
}
