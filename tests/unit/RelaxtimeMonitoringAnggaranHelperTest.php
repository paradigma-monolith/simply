<?php

declare(strict_types=1);

use App\Libraries\RelaxtimeMonitoringAnggaranHelper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPUnit\Framework\TestCase;

final class RelaxtimeMonitoringAnggaranHelperTest extends TestCase
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

    public function testParseAmountHandlesVariousNumberFormats(): void
    {
        $this->assertSame(0, RelaxtimeMonitoringAnggaranHelper::parseAmount(null));
        $this->assertSame(0, RelaxtimeMonitoringAnggaranHelper::parseAmount(''));
        $this->assertSame(0, RelaxtimeMonitoringAnggaranHelper::parseAmount('NaN'));
        $this->assertSame(0, RelaxtimeMonitoringAnggaranHelper::parseAmount('#REF!'));
        $this->assertSame(2380000, RelaxtimeMonitoringAnggaranHelper::parseAmount(2380000));
        $this->assertSame(1013508149, RelaxtimeMonitoringAnggaranHelper::parseAmount('1.013.508.149'));
        $this->assertSame(1870000, RelaxtimeMonitoringAnggaranHelper::parseAmount('1,870,000'));
        $this->assertSame(1250000, RelaxtimeMonitoringAnggaranHelper::parseAmount('Rp 1.250.000'));
        $this->assertSame(1250001, RelaxtimeMonitoringAnggaranHelper::parseAmount('1.250.000,50'));
        $this->assertSame(-170000, RelaxtimeMonitoringAnggaranHelper::parseAmount('-170.000'));
    }

    public function testNormalizeAkunAndPicAndMonths(): void
    {
        $this->assertSame('51', RelaxtimeMonitoringAnggaranHelper::normalizeAkun('51'));
        $this->assertSame('52', RelaxtimeMonitoringAnggaranHelper::normalizeAkun('Akun 52'));
        $this->assertSame('53', RelaxtimeMonitoringAnggaranHelper::normalizeAkun('', '53'));

        $this->assertSame('Timker 1', RelaxtimeMonitoringAnggaranHelper::normalizePic('timker  1'));
        $this->assertSame('Timker 3', RelaxtimeMonitoringAnggaranHelper::normalizePic('TIM 3'));
        $this->assertSame('ADUM', RelaxtimeMonitoringAnggaranHelper::normalizePic('adum'));
        $this->assertSame('Timker 1,2,3,4', RelaxtimeMonitoringAnggaranHelper::normalizePic('Timker 1, 2, 3, 4'));

        $this->assertSame(1, RelaxtimeMonitoringAnggaranHelper::detectMonthFromText('JAN'));
        $this->assertSame(8, RelaxtimeMonitoringAnggaranHelper::detectMonthFromText('AGUSTUS '));
        $this->assertSame(12, RelaxtimeMonitoringAnggaranHelper::detectMonthFromText('DESEMBER'));
        $this->assertTrue(RelaxtimeMonitoringAnggaranHelper::isSummarySheetName('REKAP TwI'));
        $this->assertTrue(RelaxtimeMonitoringAnggaranHelper::isSummarySheetName('REKAP TW 3'));
        $this->assertFalse(RelaxtimeMonitoringAnggaranHelper::isSummarySheetName('NOVEMBER'));
    }

    public function testNormalizeDateRangeSwapsAndDefaults(): void
    {
        $default = RelaxtimeMonitoringAnggaranHelper::normalizeDateRange(null, null, false);
        $this->assertSame(date('Y') . '-01-01', $default['start']);
        $this->assertSame(date('Y') . '-12-31', $default['end']);

        $swapped = RelaxtimeMonitoringAnggaranHelper::normalizeDateRange('2026-09-01', '2026-01-01', false);
        $this->assertSame('2026-01-01', $swapped['start']);
        $this->assertSame('2026-09-01', $swapped['end']);

        $empty = RelaxtimeMonitoringAnggaranHelper::normalizeDateRange('', '', true);
        $this->assertSame('', $empty['start']);
        $this->assertSame('', $empty['end']);

        $monthDefault = RelaxtimeMonitoringAnggaranHelper::normalizeMonthRange(null, null, false);
        $this->assertSame(date('Y') . '-01', $monthDefault['start_month']);
        $this->assertSame(date('Y') . '-12', $monthDefault['end_month']);
        $this->assertSame(date('Y') . '-01-01', $monthDefault['start_date']);
        $this->assertSame(date('Y') . '-12-31', $monthDefault['end_date']);

        $monthSwapped = RelaxtimeMonitoringAnggaranHelper::normalizeMonthRange('2026-09', '2026-02', false);
        $this->assertSame('2026-02', $monthSwapped['start_month']);
        $this->assertSame('2026-09', $monthSwapped['end_month']);
        $this->assertSame('2026-02-01', $monthSwapped['start_date']);
        $this->assertSame('2026-09-30', $monthSwapped['end_date']);

        $this->assertSame('2026-05-01', RelaxtimeMonitoringAnggaranHelper::normalizeMonthToDate('2026-05', 2026));
        $this->assertSame('2026-08-01', RelaxtimeMonitoringAnggaranHelper::normalizeMonthToDate('Agustus 2026', 2025));
        $this->assertSame('2026-03-01', RelaxtimeMonitoringAnggaranHelper::normalizeMonthToDate('3', 2026));
        $this->assertSame('Januari 2026', RelaxtimeMonitoringAnggaranHelper::formatMonthLabel('2026-01-01'));
    }

    public function testParseWorkbookHandlesRekapAndShiftedAndSimpleSheets(): void
    {
        $spreadsheet = new Spreadsheet();

        // Sheet 1: JAN (Standar: A=RO, B=AKUN, C=KEGIATAN, D=RENCANA, E=PIC, F..J=Minggu, K=Realisasi)
        $jan = $spreadsheet->getActiveSheet();
        $jan->setTitle('JAN');
        $jan->fromArray([
            ['REKAP KEGIATAN AKUN 52 BULAN JANUARI'],
            ['RO', 'AKUN', 'KEGIATAN', 'RENCANA PENCAIRAN ANGGARAN', 'PIC', 'Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4', 'Minggu 5', 'Realisasi', 'Sisa'],
            ['7960.PEA.001', '52', 'Jejaring LP/LS', 2380000, 'Timker 1', null, null, null, 1700000, 170000, 1870000, 510000],
            ['4815.EBA.994.002', '52', 'Perbaikan Peralatan', 22500000, 'ADUM', null, null, 2747500, 9295747, 2701000, 14744247, 7755753],
            [null, '52', 'Perawatan Gedung Kantor', 2000000, null, null, null, 150000, 1168800, 109900, 1428700, 571300],
            ['Total RPD 52', null, null, 26880000],
            ['REKAP KEGIATAN AKUN 51 BULAN JANUARI'],
            ['RO', 'AKUN', 'KEGIATAN', 'RENCANA PENCAIRAN ANGGARAN', 'PIC'],
            ['4815.EBA.994.001', '51', 'Gaji dan Tunjangan', 435481000, 'ADUM', '435481000'],
        ], null, 'A1');

        // Sheet 2: REKAP TwI (harus dilewati otomatis)
        $rekap = $spreadsheet->createSheet();
        $rekap->setTitle('REKAP TwI');
        $rekap->fromArray([
            ['REKAP TW I 2026'],
            ['51', '4815', 'Total RPD Jan-Mar', 3407721349],
        ], null, 'A1');

        // Sheet 3: AGUSTUS (SEMULA/MENJADI di D/E, PIC di F, Realisasi di L)
        $agu = $spreadsheet->createSheet();
        $agu->setTitle('AGUSTUS ');
        $agu->fromArray([
            ['REKAP KEGIATAN AKUN 52 BULAN AGUSTUS'],
            ['RO', 'AKUN', 'KEGIATAN', 'RENCANA PENCAIRAN ANGGARAN', null, 'PIC', 'Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4', 'Minggu 5', 'Realisasi'],
            [null, null, null, 'SEMULA', 'MENJADI'],
            ['7960.PEA.001', '52', 'Koordinasi Pemeriksaan', null, 4600000, 'Timker 2', null, null, 340000, null, null, 340000],
            ['Total Rencana 52 7960 Bulan Agustus', null, null, 4600000],
        ], null, 'A1');

        // Sheet 4: DESEMBER (tanpa kolom AKUN; B=KEGIATAN, C=RENCANA, D=PIC, J=Realiasi; baris kosong harus di-skip)
        $des = $spreadsheet->createSheet();
        $des->setTitle('DESEMBER');
        $des->fromArray([
            ['REKAP KEGIATAN AKUN 52 BULAN DESEMBER'],
            ['RO', 'KEGIATAN', 'RENCANA PENCAIRAN ANGGARAN', 'PIC', 'Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4', 'Minggu 5', 'Realiasi'],
            ['7960.QAH.016', 'Uji Petik Kualitas Air', 19980000, 'Timker 3', 5000000, null, null, null, null, 5000000],
            [null, null, null, 'Timker 3'],
            ['REKAP KEGIATAN AKUN 53 BULAN SEPTEMBER'],
            ['Total Rencana Bulan September', null, 'RENCANA PENCAIRAN ANGGARAN', 'PIC', 'Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4', 'Minggu 5', 'Realiasi'],
            ['7960.RAB.001', 'Thermal Scan', 262508893, 'Timker 2', null, null, 255300000, null, null, null],
        ], null, 'A1');

        $dir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'writable' . DIRECTORY_SEPARATOR . 'uploads';
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        $path = $dir . DIRECTORY_SEPARATOR . 'rma_test_' . uniqid('', true) . '.xlsx';
        $this->tempFiles[] = $path;
        (new Xlsx($spreadsheet))->save($path);

        $result = RelaxtimeMonitoringAnggaranHelper::parseWorkbook($path, 2026);
        $rows = $result['rows'];

        $this->assertCount(7, $rows);
        $this->assertContains('REKAP TwI', $result['sheets_skipped']);

        // Verifikasi JAN
        $this->assertSame('2026-01-01', $rows[0]['tanggal']);
        $this->assertSame('7960.PEA.001', $rows[0]['ro']);
        $this->assertSame('52', $rows[0]['akun']);
        $this->assertSame('Timker 1', $rows[0]['pic']);
        $this->assertSame(2380000, $rows[0]['rencana']);
        $this->assertSame(1870000, $rows[0]['realisasi']);

        // Inherit RO + ADUM pada baris ke-3 JAN
        $this->assertSame('4815.EBA.994.002', $rows[2]['ro']);
        $this->assertSame('ADUM', $rows[2]['pic']);
        $this->assertSame(1428700, $rows[2]['realisasi']);

        // Akun 51 JAN
        $this->assertSame('51', $rows[3]['akun']);
        $this->assertSame(435481000, $rows[3]['rencana']);
        $this->assertSame(435481000, $rows[3]['realisasi']);

        // AGUSTUS MENJADI + geser PIC & Realisasi
        $this->assertSame('2026-08-01', $rows[4]['tanggal']);
        $this->assertSame(4600000, $rows[4]['rencana']);
        $this->assertSame('Timker 2', $rows[4]['pic']);
        $this->assertSame(340000, $rows[4]['realisasi']);

        // DESEMBER tanpa kolom AKUN + fallback Minggu 3 untuk Akun 53
        $this->assertSame('2026-12-01', $rows[5]['tanggal']);
        $this->assertSame('52', $rows[5]['akun']);
        $this->assertSame(5000000, $rows[5]['realisasi']);
        $this->assertSame('53', $rows[6]['akun']);
        $this->assertSame(255300000, $rows[6]['realisasi']);
    }

    public function testParseWorkbookHandlesSimpleTemplateFormat(): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data');
        $sheet->fromArray([
            ['Bulan', 'RO', 'Akun', 'Kegiatan', 'Rencana Pencairan Anggaran', 'PIC', 'Realisasi'],
            ['2026-05', '7960.QAH.U02', '52', 'Layanan Pengendalian', '7.040.000', 'timker 2', '6.620.000'],
            ['Juli 2026', '4815.EBA.994.001', '51', 'Gaji', '1000000000', 'ADUM', '950000000'],
        ], null, 'A1');

        $dir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'writable' . DIRECTORY_SEPARATOR . 'uploads';
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        $path = $dir . DIRECTORY_SEPARATOR . 'rma_tpl_' . uniqid('', true) . '.xlsx';
        $this->tempFiles[] = $path;
        (new Xlsx($spreadsheet))->save($path);

        $result = RelaxtimeMonitoringAnggaranHelper::parseWorkbook($path, 2026);
        $this->assertCount(2, $result['rows']);
        $this->assertSame('2026-05-01', $result['rows'][0]['tanggal']);
        $this->assertSame('7960.QAH.U02', $result['rows'][0]['ro']);
        $this->assertSame('52', $result['rows'][0]['akun']);
        $this->assertSame('Timker 2', $result['rows'][0]['pic']);
        $this->assertSame(7040000, $result['rows'][0]['rencana']);
        $this->assertSame(6620000, $result['rows'][0]['realisasi']);

        $this->assertSame('2026-07-01', $result['rows'][1]['tanggal']);
        $this->assertSame('51', $result['rows'][1]['akun']);
    }

    public function testBuildDashboardStatsAggregatesCorrectly(): void
    {
        $rows = [
            ['tanggal' => '2026-01-01', 'akun' => '52', 'pic' => 'Timker 1', 'rencana' => 1000000, 'realisasi' => 750000],
            ['tanggal' => '2026-01-01', 'akun' => '51', 'pic' => 'ADUM', 'rencana' => 5000000, 'realisasi' => 5000000],
            ['tanggal' => '2026-02-01', 'akun' => '53', 'pic' => '', 'rencana' => 2000000, 'realisasi' => 2500000],
        ];

        $stats = RelaxtimeMonitoringAnggaranHelper::buildDashboardStats($rows);

        $this->assertSame(8000000, $stats['summary']['total_rencana']);
        $this->assertSame(8250000, $stats['summary']['total_realisasi']);
        $this->assertSame(-250000, $stats['summary']['total_sisa']);
        $this->assertSame(250000, $stats['summary']['total_deviasi']);
        $this->assertSame(103.13, $stats['summary']['persen_realisasi']);
        $this->assertSame(3.13, $stats['summary']['persen_deviasi']);
        $this->assertSame(9.38, $stats['summary']['deviasi_dipa_tertimbang']);
        $this->assertSame(90.62, $stats['summary']['nilai_ikpa_deviasi']);
        $this->assertSame(1, $stats['summary']['deviasi_optimal_count']);
        $this->assertSame(2, $stats['summary']['deviasi_melebihi_ambang_count']);
        $this->assertSame(1, $stats['summary']['over_budget_count']);
        $this->assertCount(2, $stats['by_month']);
        $this->assertSame(-250000, $stats['by_akun']['52']['deviasi']);
        $this->assertSame(-25.0, $stats['by_akun']['52']['persen_deviasi']);
        $this->assertSame(25.0, $stats['by_akun']['52']['deviasi_dipa_persen']);
        $this->assertSame(75.0, $stats['by_akun']['52']['nilai_deviasi_dipa']);
        $this->assertSame('ADUM', $stats['by_pic'][0]['pic']);
        $this->assertSame(0, $stats['by_pic'][0]['deviasi']);
        $this->assertSame(0.0, $stats['by_pic'][0]['deviasi_dipa_persen']);
        $this->assertSame('Belum Ditentukan', $stats['by_pic'][1]['pic']);
        $this->assertSame(500000, $stats['by_pic'][1]['deviasi']);
        $this->assertSame(25.0, $stats['by_pic'][1]['deviasi_dipa_persen']);
    }

    public function testCalculateDeviasiDipaFollowsPer5Pb2024Rules(): void
    {
        $optimal = RelaxtimeMonitoringAnggaranHelper::calculateDeviasiDipa(1000000, 960000);
        $this->assertSame(4.0, $optimal['deviasi_persen']);
        $this->assertSame(100.0, $optimal['nilai_kinerja']);
        $this->assertTrue($optimal['memenuhi_ambang']);
        $this->assertSame('optimal', $optimal['status']);

        $boundary = RelaxtimeMonitoringAnggaranHelper::calculateDeviasiDipa(1000000, 1050000);
        $this->assertSame(5.0, $boundary['deviasi_persen']);
        $this->assertSame(100.0, $boundary['nilai_kinerja']);
        $this->assertTrue($boundary['memenuhi_ambang']);

        $waspada = RelaxtimeMonitoringAnggaranHelper::calculateDeviasiDipa(1000000, 900000);
        $this->assertSame(10.0, $waspada['deviasi_persen']);
        $this->assertSame(90.0, $waspada['nilai_kinerja']);
        $this->assertFalse($waspada['memenuhi_ambang']);
        $this->assertSame('waspada', $waspada['status']);
        $this->assertSame('Under RPD (>5%)', $waspada['status_label']);

        $capped = RelaxtimeMonitoringAnggaranHelper::calculateDeviasiDipa(1000000, 3500000);
        $this->assertSame(100.0, $capped['deviasi_persen']);
        $this->assertSame(0.0, $capped['nilai_kinerja']);
        $this->assertSame('tinggi', $capped['status']);
        $this->assertSame('Over RPD (>5%)', $capped['status_label']);

        $zeroPlanWithReal = RelaxtimeMonitoringAnggaranHelper::calculateDeviasiDipa(0, 500000);
        $this->assertSame(100.0, $zeroPlanWithReal['deviasi_persen']);
        $this->assertSame(0.0, $zeroPlanWithReal['nilai_kinerja']);

        $bothZero = RelaxtimeMonitoringAnggaranHelper::calculateDeviasiDipa(0, 0);
        $this->assertSame(0.0, $bothZero['deviasi_persen']);
        $this->assertSame(100.0, $bothZero['nilai_kinerja']);
    }

    public function testBuildTargetRealisasiComparisonComputesCumulativeByAkun(): void
    {
        $cfg = RelaxtimeMonitoringAnggaranHelper::getDefaultPaguTargetForYear(2026);
        $this->assertSame(14387215000, $cfg[1]['51']['pagu']);
        $this->assertSame(10810919000, $cfg[4]['52']['pagu']);
        $this->assertSame(20.0, $cfg[1]['51']['target_persen']);
        $this->assertSame(90.0, $cfg[4]['53']['target_persen']);

        $yearRows = [
            ['tanggal' => '2026-02-01', 'akun' => '51', 'realisasi' => 3000000000],
            ['tanggal' => '2026-05-01', 'akun' => '51', 'realisasi' => 4500000000],
            ['tanggal' => '2026-03-01', 'akun' => '52', 'realisasi' => 1500000000],
            ['tanggal' => '2026-08-01', 'akun' => '53', 'realisasi' => 1000000000],
            ['tanggal' => '2025-12-01', 'akun' => '52', 'realisasi' => 999999999],
        ];

        $res = RelaxtimeMonitoringAnggaranHelper::buildTargetRealisasiComparison($yearRows, $cfg, 2026);
        $q1 = $res['quarters'][1];
        $this->assertSame(2877443000, $q1['by_akun']['51']['target_nominal']);
        $this->assertSame(3000000000, $q1['by_akun']['51']['realisasi_kumulatif']);
        $this->assertTrue($q1['by_akun']['51']['tercapai']);
        $this->assertSame(100.0, $q1['by_akun']['51']['nilai_ikpa']);

        $this->assertSame(1131993000, $q1['by_akun']['52']['target_nominal']);
        $this->assertSame(1500000000, $q1['by_akun']['52']['realisasi_kumulatif']);
        $this->assertTrue($q1['by_akun']['52']['tercapai']);

        $this->assertSame(11910600, $q1['by_akun']['53']['target_nominal']);
        $this->assertSame(0, $q1['by_akun']['53']['realisasi_kumulatif']);
        $this->assertFalse($q1['by_akun']['53']['tercapai']);

        // TW II kumulatif untuk Akun 51 = 3.0M + 4.5M = 7.5M (> target 50% = 7.193.607.500)
        $q2 = $res['quarters'][2];
        $this->assertSame(7500000000, $q2['by_akun']['51']['realisasi_kumulatif']);
        $this->assertSame(4500000000, $q2['by_akun']['51']['realisasi_triwulan']);
        $this->assertTrue($q2['by_akun']['51']['tercapai']);

        // TW III kumulatif untuk Akun 53 = 1.0M (> target 70% dari 1.202.754.000 = 841.927.800)
        $q3 = $res['quarters'][3];
        $this->assertSame(1000000000, $q3['by_akun']['53']['realisasi_kumulatif']);
        $this->assertTrue($q3['by_akun']['53']['tercapai']);
    }

    public function testNormalizeAkunListSupportsSingleCsvAndArray(): void
    {
        $this->assertSame([], RelaxtimeMonitoringAnggaranHelper::normalizeAkunList(''));
        $this->assertSame([], RelaxtimeMonitoringAnggaranHelper::normalizeAkunList('all'));
        $this->assertSame(['52'], RelaxtimeMonitoringAnggaranHelper::normalizeAkunList('52'));
        $this->assertSame(['51', '53'], RelaxtimeMonitoringAnggaranHelper::normalizeAkunList('53, 51, 53'));
        $this->assertSame(['51', '52'], RelaxtimeMonitoringAnggaranHelper::normalizeAkunList(['52', 'Akun 51', 'invalid']));
    }
}
