<?php

namespace Tests\Unit;

use App\Libraries\AbsenMediaStorage;
use PHPUnit\Framework\TestCase;

class AbsenMediaStorageTest extends TestCase
{
    private string $tempRoot;
    private string $baseDir;
    private string $legacyDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tempRoot  = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'simply_media_test_' . uniqid('', true);
        $this->baseDir   = $this->tempRoot . DIRECTORY_SEPARATOR . 'absen_media';
        $this->legacyDir = $this->tempRoot . DIRECTORY_SEPARATOR . 'legacy_public_foto_absen';
        mkdir($this->baseDir, 0775, true);
        mkdir($this->legacyDir, 0775, true);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->tempRoot);
        parent::tearDown();
    }

    private function removeDir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $items = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($items as $item) {
            if ($item->isDir()) {
                @rmdir($item->getPathname());
            } else {
                @unlink($item->getPathname());
            }
        }
        @rmdir($dir);
    }

    private function sampleJpegBytes(): string
    {
        // Valid JPEG SOI + APP0 JFIF header + payload (> 256 bytes) + EOI
        return "\xFF\xD8\xFF\xE0\x00\x10JFIF\x00\x01\x01\x00\x00\x01\x00\x01\x00\x00"
            . str_repeat("\x11\x22\x33\x44\x55\x66\x77\x88", 40)
            . "\xFF\xD9";
    }

    private function samplePngBytes(): string
    {
        // Real 1x1 PNG (68 bytes)
        return base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==',
            true
        );
    }

    public function testSaveBase64CreatesAtomicFileInYearMonthSubdirAndResolvesBack(): void
    {
        $storage = new AbsenMediaStorage($this->baseDir, $this->legacyDir);
        $jpeg    = $this->sampleJpegBytes();
        $dataUri = 'data:image/jpeg;base64,' . base64_encode($jpeg);

        $saved = $storage->saveBase64($dataUri, 'foto', '2026-11-22');

        $this->assertIsArray($saved);
        $this->assertMatchesRegularExpression('#^media:foto/2026/11/abs_.+\.jpg$#', $saved['mediaRef']);
        $this->assertMatchesRegularExpression('#^foto/2026/11/abs_.+\.jpg$#', $saved['relPath']);
        $this->assertFileExists($saved['fullPath']);
        $this->assertGreaterThan(0, filesize($saved['fullPath']));

        $resolvedFile = $storage->resolveToFile($saved['mediaRef']);
        $this->assertIsArray($resolvedFile);
        $this->assertSame(realpath($saved['fullPath']), realpath($resolvedFile['path']));
        $this->assertSame('image/jpeg', $resolvedFile['mime']);

        $resolvedBin = $storage->resolveToBinary($saved['mediaRef']);
        $this->assertIsArray($resolvedBin);
        $this->assertSame($jpeg, $resolvedBin['binary']);
        $this->assertSame('image/jpeg', $resolvedBin['mime']);

        $resolvedUri = $storage->resolveToDataUri($saved['mediaRef']);
        $this->assertSame($dataUri, $resolvedUri);
    }

    public function testSaveBase64SignatureUsesTtdFolderAndPngExtension(): void
    {
        $storage = new AbsenMediaStorage($this->baseDir, $this->legacyDir);
        $png     = $this->samplePngBytes();
        $dataUri = 'data:image/png;base64,' . base64_encode($png);

        $saved = $storage->saveBase64($dataUri, 'ttd', '2026-11-22');

        $this->assertIsArray($saved);
        $this->assertMatchesRegularExpression('#^media:ttd/2026/11/ttd_.+\.png$#', $saved['mediaRef']);
        $this->assertSame('image/png', $saved['mime']);
        $this->assertSame($png, file_get_contents($saved['fullPath']));
    }

    public function testIsEmptyValueHandlesEmptyNullAndEmptyCanvas(): void
    {
        $this->assertTrue(AbsenMediaStorage::isEmptyValue(null));
        $this->assertTrue(AbsenMediaStorage::isEmptyValue(''));
        $this->assertTrue(AbsenMediaStorage::isEmptyValue('   '));
        $this->assertTrue(AbsenMediaStorage::isEmptyValue('data:,'));
        $this->assertFalse(AbsenMediaStorage::isEmptyValue('media:foto/2026/11/a.jpg'));
        $this->assertFalse(AbsenMediaStorage::isEmptyValue('data:image/jpeg;base64,' . base64_encode($this->sampleJpegBytes())));
    }

    public function testDecodeBase64TolerantRecoversTruncatedPayloadAndRejectsGarbage(): void
    {
        $storage = new AbsenMediaStorage($this->baseDir, $this->legacyDir);
        $jpeg    = $this->sampleJpegBytes();
        $b64     = base64_encode($jpeg);
        // Cut at non-multiple-of-4 length to simulate 65535-byte TEXT column truncation
        $cutLen = strlen($b64) - 2;
        if ($cutLen % 4 === 0) {
            $cutLen -= 1;
        }
        $truncatedUri = 'data:image/jpeg;base64,' . substr($b64, 0, $cutLen);

        $decoded = $storage->decodeBase64Tolerant($truncatedUri);
        $this->assertIsArray($decoded);
        $this->assertTrue($decoded['truncated']);
        $this->assertSame('jpg', $decoded['ext']);
        $this->assertSame('image/jpeg', $decoded['mime']);
        $this->assertGreaterThan(32, strlen($decoded['binary']));
        $this->assertStringStartsWith("\xFF\xD8\xFF", $decoded['binary']);

        // Also verify len % 4 === 1 where PHP strict base64_decode returns false
        $rem1Len = strlen($b64) - ((strlen($b64) - 1) % 4);
        if ($rem1Len >= strlen($b64)) {
            $rem1Len -= 4;
        }
        $decodedRem1 = $storage->decodeBase64Tolerant('data:image/jpeg;base64,' . substr($b64, 0, $rem1Len));
        $this->assertIsArray($decodedRem1);
        $this->assertTrue($decodedRem1['truncated']);
        $this->assertGreaterThan(32, strlen($decodedRem1['binary']));

        $this->assertNull($storage->decodeBase64Tolerant('data:,'));
        $this->assertNull($storage->decodeBase64Tolerant('data:image/jpeg;base64,@@@@not-valid!!!'));
        $this->assertNull($storage->decodeBase64Tolerant('data:image/jpeg;base64,' . base64_encode('tiny')));
    }

    public function testPathTraversalAndEscapeAttemptsAreRejected(): void
    {
        $storage = new AbsenMediaStorage($this->baseDir, $this->legacyDir);
        file_put_contents($this->tempRoot . DIRECTORY_SEPARATOR . 'secret.txt', 'SECRET');

        $this->assertNull($storage->resolveToFile('media:../secret.txt'));
        $this->assertNull($storage->resolveToFile('media:../../.env'));
        $this->assertNull($storage->resolveToFile("media:foto/2026/11/a.jpg\0.png"));
        $this->assertNull($storage->resolveToFile('media:..\\..\\app\\Config\\Database.php'));
        $this->assertNull($storage->resolveRekamFotoPath('../../secret.txt'));
        $this->assertNull($storage->resolveRekamFotoPath("../secret.txt"));
        $this->assertNull($storage->canonicalFromRekamFoto('../../.env'));
    }

    public function testLegacyFallbackResolvesFromLegacyDirBeforeMoveAndFromWritableLegacyAfterMove(): void
    {
        $storage = new AbsenMediaStorage($this->baseDir, $this->legacyDir);
        $jpeg    = $this->sampleJpegBytes();
        $oldFile = $this->legacyDir . DIRECTORY_SEPARATOR . 'old.jpg';
        file_put_contents($oldFile, $jpeg);

        // Before atomic move: resolves from legacy public dir
        $resBefore = $storage->resolveToFile('media:legacy/old.jpg');
        $this->assertIsArray($resBefore);
        $this->assertSame(realpath($oldFile), realpath($resBefore['path']));

        $rekamBefore = $storage->resolveRekamFotoPath('old.jpg');
        $this->assertIsArray($rekamBefore);
        $this->assertSame('media:legacy/old.jpg', $rekamBefore['mediaRef']);

        // After atomic move to writable/absen_media/legacy/
        $writableLegacy = $this->baseDir . DIRECTORY_SEPARATOR . 'legacy';
        mkdir($writableLegacy, 0775, true);
        $movedFile = $writableLegacy . DIRECTORY_SEPARATOR . 'old.jpg';
        rename($oldFile, $movedFile);

        $resAfter = $storage->resolveToFile('media:legacy/old.jpg');
        $this->assertIsArray($resAfter);
        $this->assertSame(realpath($movedFile), realpath($resAfter['path']));

        $rekamAfter = $storage->resolveRekamFotoPath('old.jpg');
        $this->assertIsArray($rekamAfter);
        $this->assertSame(realpath($movedFile), realpath($rekamAfter['path']));
        $this->assertSame('media:legacy/old.jpg', $rekamAfter['mediaRef']);
    }

    public function testBuildRekamHashMapMatchesSha256ToCanonicalMediaRef(): void
    {
        $storage = new AbsenMediaStorage($this->baseDir, $this->legacyDir);
        $jpeg    = $this->sampleJpegBytes();
        $legacy  = $this->baseDir . DIRECTORY_SEPARATOR . 'legacy';
        mkdir($legacy, 0775, true);
        file_put_contents($legacy . DIRECTORY_SEPARATOR . 'abc.jpg', $jpeg);

        $json = json_encode([['tombol' => 'Rekam Masuk', 'foto' => 'abc.jpg']]);
        $map  = $storage->buildRekamHashMap($json);

        $expectedHash = hash('sha256', $jpeg);
        $this->assertArrayHasKey($expectedHash, $map);
        $this->assertSame('media:legacy/abc.jpg', $map[$expectedHash]);
    }

    public function testSaveBinaryReturnsNullGracefullyWhenBaseDirIsUnwritable(): void
    {
        // A regular file cannot have subdirectories created underneath it (cross-platform unwritable baseDir)
        $blockingFile = $this->tempRoot . DIRECTORY_SEPARATOR . 'not_a_dir';
        file_put_contents($blockingFile, 'block');

        $storage = new AbsenMediaStorage($blockingFile, $this->legacyDir);
        $dataUri = 'data:image/jpeg;base64,' . base64_encode($this->sampleJpegBytes());

        $this->assertNull($storage->saveBase64($dataUri, 'foto', '2026-11-22'));
    }

    public function testSvgPayloadIsRejectedToPreventStoredXssAndDisguisedUploadIsRejected(): void
    {
        $storage = new AbsenMediaStorage($this->baseDir, $this->legacyDir);
        $svgXss  = '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>' . str_repeat(' ', 64);
        $svgUri  = 'data:image/svg+xml;base64,' . base64_encode($svgXss);

        $this->assertNull($storage->decodeBase64Tolerant($svgUri));
        $this->assertNull($storage->saveBase64($svgUri, 'foto', '2026-11-22'));

        // Disguised non-image file claiming to be .jpg must fail saveUploadedFile()
        $fakeTmp = $this->tempRoot . DIRECTORY_SEPARATOR . 'fake.jpg';
        file_put_contents($fakeTmp, '<?php echo "not an image"; ?>' . str_repeat('A', 64));
        $fakeUpload = new class($fakeTmp, 'evil.jpg', 'image/jpeg', (int) filesize($fakeTmp), UPLOAD_ERR_OK) extends \CodeIgniter\HTTP\Files\UploadedFile {
            public function isValid(): bool
            {
                return true;
            }
        };

        $this->assertNull($storage->saveUploadedFile($fakeUpload, 'foto', '2026-11-22'));
    }

    public function testDecodeBase64TolerantAcceptsEmptyImageSubtypeFromLegacyTempUpload(): void
    {
        $storage = new AbsenMediaStorage($this->baseDir, $this->legacyDir);
        $jpeg    = $this->sampleJpegBytes();
        // Legacy generate_base64($file->getTempName()) produced `data:image/;base64,...`
        $legacyUri = 'data:image/;base64,' . base64_encode($jpeg);

        $decoded = $storage->decodeBase64Tolerant($legacyUri);
        $this->assertIsArray($decoded);
        $this->assertSame('jpg', $decoded['ext']);
        $this->assertSame('image/jpeg', $decoded['mime']);
        $this->assertSame($jpeg, $decoded['binary']);
    }
}
