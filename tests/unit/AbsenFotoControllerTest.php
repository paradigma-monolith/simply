<?php

namespace Tests\Unit;

use App\Controllers\Absen\Foto;
use PHPUnit\Framework\TestCase;

class AbsenFotoControllerTest extends TestCase
{
    public function testExtractRekamFotoRefReturnsExactFilenameForValidIndex(): void
    {
        $json = json_encode([
            ['tombol' => 'Rekam Masuk', 'foto' => 'abs_first.jpg'],
            ['tombol' => 'Rekam Pulang', 'foto' => 'foto/2026/11/abs_second.jpg'],
        ]);

        $this->assertSame('abs_first.jpg', Foto::extractRekamFotoRef($json, 0));
        $this->assertSame('foto/2026/11/abs_second.jpg', Foto::extractRekamFotoRef($json, 1));
    }

    public function testExtractRekamFotoRefReturnsNullForInvalidJsonOrOutOfBoundsOrNegativeIndex(): void
    {
        $json = json_encode([
            ['tombol' => 'Rekam Masuk', 'foto' => 'abs_only.jpg'],
            ['tombol' => 'Rekam Pulang'],
        ]);

        $this->assertNull(Foto::extractRekamFotoRef($json, -1));
        $this->assertNull(Foto::extractRekamFotoRef($json, 1));
        $this->assertNull(Foto::extractRekamFotoRef($json, 5));
        $this->assertNull(Foto::extractRekamFotoRef('{invalid-json', 0));
        $this->assertNull(Foto::extractRekamFotoRef('', 0));
        $this->assertNull(Foto::extractRekamFotoRef(null, 0));
    }

    public function testRekamAbsenViewRendersExtensionlessAuthenticatedUrl(): void
    {
        $id_absen  = 50731;
        $superUser = true;
        $getData   = [
            [
                'foto'            => 'abs_123.jpg',
                'tombol'          => 'Rekam Masuk',
                'alamat_terdekat' => 'Kantor BKK',
                'longitude'       => '107.6191',
                'latitude'        => '-6.9175',
                'jam_absen'       => '2026-11-22 07:30:00',
            ],
        ];

        ob_start();
        include APPPATH . 'Views' . DIRECTORY_SEPARATOR . 'absen' . DIRECTORY_SEPARATOR . 'rekam_absen.php';
        $html = (string) ob_get_clean();

        $this->assertStringContainsString('src="/absen/rekam-foto/50731/0"', $html);
        $this->assertStringContainsString('data-src="/absen/rekam-foto/50731/0"', $html);
        $this->assertStringNotContainsString('/dist/foto_absen/', $html);
        $this->assertStringNotContainsString('.jpg', $html);
    }

    public function testFotoControllerSetsNoSniffHeaderOnImageResponses(): void
    {
        $src = (string) file_get_contents(APPPATH . 'Controllers' . DIRECTORY_SEPARATOR . 'Absen' . DIRECTORY_SEPARATOR . 'Foto.php');
        $this->assertGreaterThanOrEqual(2, substr_count($src, "setHeader('X-Content-Type-Options', 'nosniff')"));
    }
}
