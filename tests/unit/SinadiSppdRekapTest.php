<?php

declare(strict_types=1);

use App\Libraries\SimplePdfMerger;
use App\Libraries\SinadiSppdSrikandiHelper;
use Dompdf\Dompdf;
use PHPUnit\Framework\TestCase;

if (!function_exists('base_url')) {
    function base_url(string $uri = ''): string
    {
        return 'http://localhost/' . ltrim($uri, '/');
    }
}

final class SinadiSppdRekapTest extends TestCase
{
    /**
     * @return array<int, array<string, mixed>>
     */
    private function buildSamplePesertaList(int $count): array
    {
        $list = [];
        for ($i = 1; $i <= $count; $i++) {
            $list[] = [
                'id_perjalanan'         => 3994,
                'nama_kegiatan'         => 'Kegiatan Inspeksi Kesehatan Lingkungan Asrama Haji Indramayu Tahap I Tahun 1448 H',
                'tgl_pelaksanaan'       => '2026-10-21',
                'tgl_pelaksanaan_akhir' => '2026-10-21',
                'tempat_perjalanan'     => 'Kota Asrama Haji Indramayu',
                'mata_anggaran'         => '7960.QAH.U29.051.A.524111',
                'nama_kantor'           => 'Balai Kekarantinaan Kesehatan Kelas I Bandung',
                'ppk'                   => 'Muhammad Dani Suarman, SE',
                'ppk_nip'               => '198806032015031004',
                'kepala_kantor'         => 'dr. Sedya Dwisangka, M.Epid.',
                'kepala_kantor_nip'     => '196901042002121003',
                'nama_pegawai'          => $i === 1 ? 'dr. Sedya Dwisangka, M.Epid.' : "Peserta Perjalanan {$i}, SKM",
                'nip_pegawai'           => '19800101200501' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'golongan'              => $i % 2 === 0 ? 'IV/a' : 'IV/b',
                'jabatan_pegawai'       => $i === 1 ? 'Kepala Balai Kekarantinaan Kesehatan Kelas I Bandung' : 'Sanitarian Ahli Madya (JFT)',
                'kota_asal'             => 'Kota Bandung',
                'kota'                  => 'Kab. Indramayu',
                'transportasi'          => 'Transportasi darat',
                'no_surat_tugas'        => 'HJ.04.03/C.X.5/5291/2026',
                'tgl_surat_tugas'       => '2026-10-20',
                'tgl_pergi'             => '2026-10-21',
                'tgl_pulang'            => '2026-10-21',
            ];
        }

        return $list;
    }

    public function testRekapEligibilityThreshold(): void
    {
        $this->assertFalse(SinadiSppdSrikandiHelper::isRekapEligible(0));
        $this->assertFalse(SinadiSppdSrikandiHelper::isRekapEligible(9));
        $this->assertTrue(SinadiSppdSrikandiHelper::isRekapEligible(10));
        $this->assertTrue(SinadiSppdSrikandiHelper::isRekapEligible(25));
    }

    public function testRekapDateDurationAndNipHelpers(): void
    {
        $this->assertSame('', SinadiSppdSrikandiHelper::formatDateIndoSafe(null));
        $this->assertSame('', SinadiSppdSrikandiHelper::formatDateIndoSafe('0000-00-00'));
        $this->assertSame('01 Juni 2026', SinadiSppdSrikandiHelper::formatDateIndoSafe('2026-06-01'));
        $this->assertSame('08 Juni 2026', SinadiSppdSrikandiHelper::formatDateIndoSafe('2026-06-08 10:00:00'));

        $this->assertSame('8', SinadiSppdSrikandiHelper::computeDurationDays('2026-06-01', '2026-06-08'));
        $this->assertSame('1', SinadiSppdSrikandiHelper::computeDurationDays('2026-06-01', '2026-06-01'));
        $this->assertSame('', SinadiSppdSrikandiHelper::computeDurationDays('', '2026-06-08'));

        $this->assertSame('', SinadiSppdSrikandiHelper::formatNipLabel(''));
        $this->assertSame('NIP. 198806032015031004', SinadiSppdSrikandiHelper::formatNipLabel('198806032015031004'));
        $this->assertSame('NIP. 198806032015031004', SinadiSppdSrikandiHelper::formatNipLabel('NIP. 198806032015031004'));

        $sample = $this->buildSamplePesertaList(2);
        $this->assertSame('21 Oktober 2026', SinadiSppdSrikandiHelper::formatTanggalPenyelenggaraan($sample));
    }

    public function testBuildRekapBackPageStopsDefaultsAndMultiPejabat(): void
    {
        $emptyStops = SinadiSppdSrikandiHelper::buildRekapBackPageStops([]);
        $this->assertCount(3, $emptyStops);
        $this->assertTrue($emptyStops[0]['active']);
        $this->assertSame('II', $emptyStops[0]['roman']);
        $this->assertSame('Terlampir', $emptyStops[0]['tiba_di']);
        $this->assertSame('Terlampir', $emptyStops[0]['tgl_tiba_label']);
        $this->assertSame('Terlampir', $emptyStops[0]['berangkat_dari']);
        $this->assertSame('Terlampir', $emptyStops[0]['ke']);
        $this->assertSame('Terlampir', $emptyStops[0]['tgl_berangkat_label']);
        $this->assertNull($emptyStops[0]['pejabat']);
        $this->assertFalse($emptyStops[1]['active']);
        $this->assertFalse($emptyStops[2]['active']);

        $cfg = SinadiSppdSrikandiHelper::parse([
            'mode_tanggal'   => 'manual',
            'jumlah_baris'   => '3',
            'lokasi_tujuan'  => ['Bekasi', 'Indramayu', 'Bandung'],
            'jabatan_tujuan' => ['Ka Dinkes', 'Direktur', 'Camat'],
            'pegawai_tujuan' => ['Pejabat 1|101', 'Pejabat 2|102', 'Pejabat 3|103'],
            'tgl_tiba'       => ['2026-06-01', '2026-06-03', '2026-06-05'],
            'tgl_berangkat'  => ['2026-06-02', '2026-06-04', '2026-06-06'],
        ]);

        $stops = SinadiSppdSrikandiHelper::buildRekapBackPageStops($cfg);
        $this->assertSame('${ttd_pengirim3}', $stops[0]['pejabat']['placeholder']);
        $this->assertSame('${ttd_pengirim4}', $stops[1]['pejabat']['placeholder']);
        $this->assertSame('${ttd_pengirim5}', $stops[2]['pejabat']['placeholder']);
        $this->assertSame('Bekasi', $stops[0]['tiba_di']);
        $this->assertSame('Indramayu', $stops[0]['ke']);
        $this->assertSame('01 Juni 2026', $stops[0]['tgl_tiba_label']);
        $this->assertSame('06 Juni 2026', $stops[2]['tgl_berangkat_label']);
    }

    public function testUnifiedRekapUsesSameF4PortraitPaperForAllPagesAndZeroAndThreePejabat(): void
    {
        $perjalanan = $this->buildSamplePesertaList(10);

        $configs = [
            [],
            SinadiSppdSrikandiHelper::parse([
                'mode_tanggal'   => 'manual',
                'jumlah_baris'   => '3',
                'lokasi_tujuan'  => ['Kab. Indramayu', 'Kab. Bekasi', 'Kota Bandung'],
                'jabatan_tujuan' => ['Kepala Dinkes Indramayu', 'Kepala Asrama Haji Bekasi', 'Kepala BKK Bandung'],
                'pegawai_tujuan' => ['Dr. Pejabat Satu|198001012005011001', 'Dr. Pejabat Dua|198102022006012002', 'Dr. Pejabat Tiga|198203032007011003'],
                'tgl_tiba'       => ['2026-10-21', '2026-10-22', '2026-10-23'],
                'tgl_berangkat'  => ['2026-10-21', '2026-10-22', '2026-10-23'],
            ]),
        ];

        $baselineCatatanY = null;

        foreach ($configs as $idx => $srikandi_config) {
            ob_start();
            include APPPATH . 'Views/sinadi/sppd_rekap.php';
            $html = (string) ob_get_clean();

            $this->assertStringContainsString('${nomor_naskah}', $html);
            $this->assertStringContainsString('${tanggal_naskah}', $html);
            $this->assertStringContainsString('${ttd_pengirim1}', $html);
            $this->assertStringContainsString('${ttd_pengirim2}', $html);
            $this->assertStringContainsString('dr. Sedya Dwisangka, M.Epid.', $html);
            $this->assertStringContainsString('Peserta Perjalanan 10, SKM', $html);
            $this->assertStringContainsString('style="width: 13.5%;">NOMOR</th>', $html);
            $this->assertStringContainsString('style="width: 9.5%;">KEBERANGKATAN</th>', $html);

            $catatanY = null;
            $dompdf = new Dompdf();
            $dompdf->setCallbacks([
                'measureCatatan' => [
                    'event' => 'end_frame',
                    'f' => static function ($frame) use (&$catatanY): void {
                        if ($catatanY === null && strpos($frame->get_node()->textContent ?? '', 'CATATAN LAIN-LAIN') !== false && $frame->get_node()->nodeName === '#text') {
                            $pos = $frame->get_position();
                            $catatanY = (float) ($pos['y'] ?? 0.0);
                        }
                    },
                ],
            ]);
            $dompdf->loadHtml($html);
            $dompdf->setPaper([0, 0, 595, 935], 'portrait');
            $dompdf->render();

            $this->assertSame(
                3,
                $dompdf->getCanvas()->get_page_count(),
                "SPPD Rekap unified config #{$idx} expected 3 portrait pages, got " . $dompdf->getCanvas()->get_page_count()
            );

            $this->assertNotNull($catatanY, "CATATAN LAIN-LAIN frame should be measured on config #{$idx}");
            if ($baselineCatatanY === null) {
                $baselineCatatanY = $catatanY;
            } else {
                $this->assertEqualsWithDelta(
                    $baselineCatatanY,
                    $catatanY,
                    0.5,
                    "Page 2 table height shifted with 3 pejabat: baseline={$baselineCatatanY}, actual={$catatanY}"
                );
            }

            $pdfBinary = $dompdf->output();
            $mediaBoxMatches = [];
            preg_match_all('/\/MediaBox\s*\[([^\]]+)\]/', $pdfBinary, $mediaBoxMatches);
            $this->assertNotEmpty($mediaBoxMatches[1]);
            foreach ($mediaBoxMatches[1] as $box) {
                $this->assertSame('0.000 0.000 595.000 935.000', trim($box));
            }
            $this->assertStringNotContainsString('[0.000 0.000 935.000 595.000]', $pdfBinary);
        }

        // Verify 30 participants paginate cleanly across multiple F4 Portrait pages
        $perjalanan = $this->buildSamplePesertaList(30);
        $srikandi_config = [];
        ob_start();
        include APPPATH . 'Views/sinadi/sppd_rekap.php';
        $html30 = (string) ob_get_clean();

        $dompdf30 = new Dompdf();
        $dompdf30->loadHtml($html30);
        $dompdf30->setPaper([0, 0, 595, 935], 'portrait');
        $dompdf30->render();
        $this->assertGreaterThan(3, $dompdf30->getCanvas()->get_page_count());
    }

    public function testSimplePdfMergerPreservesXrefIntegrity(): void
    {
        $d1 = new Dompdf();
        $d1->loadHtml('<p>Portrait</p>');
        $d1->setPaper([0, 0, 595, 935], 'portrait');
        $d1->render();

        $d2 = new Dompdf();
        $d2->loadHtml('<p>Landscape</p>');
        $d2->setPaper([0, 0, 595, 935], 'landscape');
        $d2->render();

        $merged = SimplePdfMerger::merge([$d1->output(), $d2->output()]);
        $this->assertSame(0, strpos($merged, '%PDF-'));
        $this->assertSame('%%EOF', substr(trim($merged), -5));
        $this->assertStringContainsString('[0.000 0.000 595.000 935.000]', $merged);
        $this->assertStringContainsString('[0.000 0.000 935.000 595.000]', $merged);
    }

    public function testModalRendersRekapButtonDisabledBelowTenAndActiveAtTen(): void
    {
        $getData = ['id_perjalanan' => 3994, 'tgl_pelaksanaan' => '2026-06-01', 'tgl_pelaksanaan_akhir' => '2026-06-08'];
        $all_pegawai = [['nama' => 'Test Pegawai', 'nip' => '19800101']];

        $getPeserta = $this->buildSamplePesertaList(9);
        ob_start();
        include APPPATH . 'Views/sinadi/_modal_sppd_srikandi.php';
        $modal9 = (string) ob_get_clean();
        $this->assertSame(1, preg_match('/<button[^>]*id="btnDownloadSppdRekap"[^>]*>/s', $modal9, $btn9Match));
        $this->assertStringContainsString('disabled', $btn9Match[0]);
        $this->assertStringNotContainsString('onclick="cetakSppdRekap()"', $btn9Match[0]);

        $getPeserta = $this->buildSamplePesertaList(10);
        ob_start();
        include APPPATH . 'Views/sinadi/_modal_sppd_srikandi.php';
        $modal10 = (string) ob_get_clean();
        $this->assertSame(1, preg_match('/<button[^>]*id="btnDownloadSppdRekap"[^>]*>/s', $modal10, $btn10Match));
        $this->assertStringNotContainsString('disabled', $btn10Match[0]);
        $this->assertStringContainsString('onclick="cetakSppdRekap()"', $btn10Match[0]);
    }
}
