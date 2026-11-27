<?php

declare(strict_types=1);

use App\Libraries\SinadiTracingTbSchema;
use PHPUnit\Framework\TestCase;

final class SinadiTracingTbSchemaTest extends TestCase
{
    /** @var list<string> */
    private array $tempFiles = [];

    protected function tearDown(): void
    {
        foreach ($this->tempFiles as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
        $this->tempFiles = [];
    }

    private function createTempFile(string $bytes): string
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . '.tmp_tb_test_' . uniqid('', true) . '.bin';
        $written = file_put_contents($path, $bytes);
        $this->assertNotFalse($written);
        $this->tempFiles[] = $path;
        return $path;
    }

    public function testValidateDokumenFileAcceptsPdfWordExcelPngAndJpeg(): void
    {
        $pdf = $this->createTempFile("%PDF-1.7\n1 0 obj\n");
        $png = $this->createTempFile("\x89PNG\r\n\x1a\n\x00\x00\x00\x0dIHDR");
        $jpg = $this->createTempFile("\xFF\xD8\xFF\xE0\x00\x10JFIF");
        $zip = $this->createTempFile("PK\x03\x04dummy-ooxml-content");
        $ole = $this->createTempFile("\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1legacy-office");
        $xlsHtml = $this->createTempFile("<html><table><tr><td>1</td></tr></table></html>");

        $this->assertNull(SinadiTracingTbSchema::validateDokumenFile($pdf, 'pdf'));
        $this->assertNull(SinadiTracingTbSchema::validateDokumenFile($png, 'png'));
        $this->assertNull(SinadiTracingTbSchema::validateDokumenFile($jpg, 'jpg'));
        $this->assertNull(SinadiTracingTbSchema::validateDokumenFile($jpg, 'jpeg'));
        $this->assertNull(SinadiTracingTbSchema::validateDokumenFile($zip, 'docx'));
        $this->assertNull(SinadiTracingTbSchema::validateDokumenFile($zip, 'xlsx'));
        $this->assertNull(SinadiTracingTbSchema::validateDokumenFile($ole, 'doc'));
        $this->assertNull(SinadiTracingTbSchema::validateDokumenFile($ole, 'xls'));
        $this->assertNull(SinadiTracingTbSchema::validateDokumenFile($xlsHtml, 'xls'));
    }

    public function testValidateDokumenFileRejectsInvalidSignaturesAndExtensions(): void
    {
        $fake = $this->createTempFile('MZexecutable-payload');

        $this->assertNotNull(SinadiTracingTbSchema::validateDokumenFile($fake, 'exe'));
        $this->assertNotNull(SinadiTracingTbSchema::validateDokumenFile($fake, 'pdf'));
        $this->assertNotNull(SinadiTracingTbSchema::validateDokumenFile($fake, 'png'));
        $this->assertNotNull(SinadiTracingTbSchema::validateDokumenFile($fake, 'jpg'));
        $this->assertNotNull(SinadiTracingTbSchema::validateDokumenFile($fake, 'docx'));
        $this->assertNotNull(SinadiTracingTbSchema::validateDokumenFile($fake, 'xlsx'));
        $this->assertNotNull(SinadiTracingTbSchema::validateDokumenFile($fake, 'doc'));
        $this->assertNotNull(SinadiTracingTbSchema::validateDokumenFile($fake, 'xls'));
    }

    public function testResolveDokumenMimeTypeMapsOfficeAndImagesCleanly(): void
    {
        $this->assertSame('application/pdf', SinadiTracingTbSchema::resolveDokumenMimeType('pdf', 'application/octet-stream'));
        $this->assertSame(
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            SinadiTracingTbSchema::resolveDokumenMimeType('docx', 'application/zip')
        );
        $this->assertSame(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            SinadiTracingTbSchema::resolveDokumenMimeType('xlsx', 'application/zip')
        );
        $this->assertSame('image/png', SinadiTracingTbSchema::resolveDokumenMimeType('png', 'image/png'));
        $this->assertSame('image/jpeg', SinadiTracingTbSchema::resolveDokumenMimeType('jpeg', 'image/jpeg'));
    }
}
