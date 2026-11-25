<?php

declare(strict_types=1);

use App\Libraries\RelaxtimeRkksHelper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPUnit\Framework\TestCase;

final class RelaxtimeRkksHelperTest extends TestCase
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

    public function testClassifyCodeAndVolumeParsing(): void
    {
        $this->assertSame('program', RelaxtimeRkksHelper::classifyCode('024.05.DZ'));
        $this->assertSame('kegiatan', RelaxtimeRkksHelper::classifyCode('7960'));
        $this->assertSame('kro', RelaxtimeRkksHelper::classifyCode('7960.PEA'));
        $this->assertSame('ro', RelaxtimeRkksHelper::classifyCode('7960.PEA.001'));
        $this->assertSame('ro', RelaxtimeRkksHelper::classifyCode('7960.QAH.U02'));
        $this->assertSame('komponen', RelaxtimeRkksHelper::classifyCode('051'));
        $this->assertSame('subkomponen', RelaxtimeRkksHelper::classifyCode('A'));
        $this->assertSame('akun', RelaxtimeRkksHelper::classifyCode('524111'));
        $this->assertNull(RelaxtimeRkksHelper::classifyCode('Lokasi : Bandung'));

        $v1 = RelaxtimeRkksHelper::parseVolumeSatuan('25.0 OK');
        $this->assertSame(25.0, $v1['volume']);
        $this->assertSame('OK', $v1['satuan']);

        $v2 = RelaxtimeRkksHelper::parseVolumeSatuan('1700.0 Orang, Akta, Bidang, Laporan');
        $this->assertSame(1700.0, $v2['volume']);
        $this->assertSame('Orang, Akta, Bidang, Laporan', $v2['satuan']);
    }

    public function testParseSaktiWorkbookHandlesAllLevelsShiftsHeadersAndRollup(): void
    {
        $spreadsheet = new Spreadsheet();
        $ws = $spreadsheet->getActiveSheet();
        $ws->setTitle('RKK_MULTIYEAR_SATKER');

        $rows = [
            ['024.05.DZ', null, null, 'Program Sistem Ketahanan Kesehatan', null, null, null, null, null, 0],
            ['7960', null, null, 'Penyelenggaraan Pelayanan Kekarantinaan Kesehatan', null, null, null, null, null, 0],
            ['7960.PEA', null, null, 'Koordinasi[Base Line]', null, null, '33.0 kegiatan', null, null, 0],
            [null, null, null, 'Lokasi : KOTA BANDUNG'],
            ['7960.PEA.001', null, null, 'Koordinasi Bidang Kesehatan', null, null, '33.0 kegiatan', null, null, 0],
            ['051', null, null, 'Pelaksanaan Koordinasi', null, null, null, null, null, 0, 'U'],
            ['A', null, null, 'Jejaring LP/LS', null, null, null, null, null, 0],
            [null, null, null, 'Wilayah Jawa Barat'],
            ['521211', null, null, 'Belanja Bahan', null, null, null, null, null, 0, null, 'PNP'],
            [null, null, null, '(KPPN.022-Bandung I )'],
            [null, null, null, '>', 'Belanja Operasional'],
            [null, null, null, '>>', 'Sub Konsumsi'],
            [null, null, null, '-', 'Konsumsi [25 OR x 1 KL]', null, '25.0 OK', 60000, null, 1500000],
            // Baris bergeser ke Col K untuk Jumlah & Col N untuk Sumber Dana (seperti row 839+)
            ['524111', null, null, 'Belanja Perjalanan Dinas Biasa', null, null, null, null, null, null, 5250000, null, null, 'PNP'],
            [null, null, null, '-', 'Transport', null, '15.0 OK', 350000, null, null, 5250000, null, '*'],
            [null, 'Catatan :', '1. U = Komponen Utama'],
        ];
        $ws->fromArray($rows, null, 'A1');

        $rekap = $spreadsheet->createSheet();
        $rekap->setTitle('REKAP');
        $rekap->fromArray([['No', 'RINCIAN OUTPUT']], null, 'A1');

        $dir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'writable' . DIRECTORY_SEPARATOR . 'uploads';
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        $path = $dir . DIRECTORY_SEPARATOR . 'rkks_test_' . uniqid('', true) . '.xlsx';
        $this->tempFiles[] = $path;
        (new Xlsx($spreadsheet))->save($path);

        $parsed = RelaxtimeRkksHelper::parseSaktiWorkbook($path);
        $items = $parsed['items'];

        $this->assertSame('RKK_MULTIYEAR_SATKER', $parsed['sheet']);
        // Harusnya berhenti sebelum 'Catatan :', sehingga 12 node hierarki terbentuk
        $this->assertCount(12, $items);

        // Program total pagu = 1.500.000 + 5.250.000 = 6.750.000
        $this->assertSame('program', $items[0]['level_type']);
        $this->assertSame(6750000, $items[0]['jumlah']);

        // KRO mewarisi lokasi
        $this->assertSame('KOTA BANDUNG', $items[2]['lokasi']);

        // Komponen menyimpan U
        $this->assertSame('U', $items[4]['jenis_komponen']);

        // Subkomponen A menggabungkan baris uraian lanjutannya
        $this->assertSame('Jejaring LP/LS Wilayah Jawa Barat', $items[5]['uraian']);

        // Akun 521211 mewarisi KPPN dan header '>' / '>>'
        $this->assertSame('022-Bandung I', $items[6]['kppn']);
        $this->assertSame('header_detail', $items[7]['level_type']);
        $this->assertSame(1, $items[7]['header_depth']);
        $this->assertSame(2, $items[8]['header_depth']);

        // Baris bergeser ke Col K (524111 + Transport) & flag blokir '*'
        $this->assertSame('524111', $items[10]['kode']);
        $this->assertSame('PNP', $items[10]['sumber_dana']);
        $this->assertSame(5250000, $items[10]['jumlah']);
        $this->assertSame(1, $items[11]['is_blokir']);
        $this->assertSame(5250000, $items[11]['nilai_blokir']);

        $summary = $parsed['summary'];
        $this->assertSame(6750000, $summary['total_pagu']);
        $this->assertSame(5250000, $summary['total_blokir']);
        $this->assertSame(1, $summary['blokir_count']);

        $rekapRows = RelaxtimeRkksHelper::buildRekapSubkomponen($items);
        $this->assertCount(1, $rekapRows);
        $this->assertSame('7960.PEA.001 051', $rekapRows[0]['ro_komponen']);
        $this->assertSame('A', $rekapRows[0]['subkomponen_kode']);
        $this->assertSame(6750000, $rekapRows[0]['pagu']);
    }
}
