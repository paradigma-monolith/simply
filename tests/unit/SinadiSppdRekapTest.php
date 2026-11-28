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
                'id_perjalanan'        => 3994,
                'nama_kegiatan'        => 'Pelayanan Operasional Kekarantinaan Kesehatan Debarkasi Haji',
                'tgl_pelaksanaan'      => '2026-06-01',
                'tgl_pelaksanaan_akhir'=> '2026-06-08',
                'tempat_perjalanan'    => 'Kota Bekasi',
                'mata_anggaran'        => '7960.QAH.U29.051.A.524111',
                'nama_kantor'          => 'Balai Kekarantinaan Kesehatan Kelas I Bandung',
                'ppk'                  => 'Muhammad Dani Suarman, SE',
                'ppk_nip'              => '198806032015031004',
                'kepala_kantor'        => 'dr. Sedya Dwisangka, M.Epid.',
                'kepala_kantor_nip'    => '196901042002121003',
                'nama_pegawai'         => $i === 1 ? 'dr. Nanang Lioncu, M.K.M' : "Peserta Perjalanan {$i}",
                'nip_pegawai'          => '19800101200501' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'golongan'             => $i % 2 === 0 ? 'III/c' : 'IV/a',
                'jabatan_pegawai'      => $i === 1 ? 'Dokter Ahli Madya (JFT)' : 'Petugas Kesehatan',
                'kota_asal'            => 'Bandung',
                'kota'                 => 'Kota Bekasi',
                'transportasi'         => 'Transportasi Darat',
                'no_surat_tugas'       => 'HJ.01.01/C.X.5/' . (2260 + $i) . '/2026',
                'tgl_surat_tugas'      => '2026-05-29',
                'tgl_pergi'            => '2026-06-01',
                'tgl_pulang'           => '2026-06-08',
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
        $this->assertSame('01 Juni 2026 s.d. 08 Juni 2026', SinadiSppdSrikandiHelper::formatTanggalPenyelenggaraan($sample));
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

    public function testRekapMainFitsStrictlyOnTwoPortraitPagesWithZeroAndThreePejabat(): void
    {
        $perjalanan = $this->buildSamplePesertaList(10);

        $configs = [
            [],
            SinadiSppdSrikandiHelper::parse([
                'mode_tanggal'   => 'manual',
                'jumlah_baris'   => '3',
                'lokasi_tujuan'  => ['Kab. Bekasi', 'Kab. Indramayu', 'Kota Cirebon'],
                'jabatan_tujuan' => ['Kepala Dinas Kesehatan Kabupaten Bekasi', 'Kepala Asrama Haji Indramayu', 'Kepala Kantor Kesehatan Pelabuhan'],
                'pegawai_tujuan' => ['Dr. Pejabat Satu|198001012005011001', 'Dr. Pejabat Dua|198102022006012002', 'Dr. Pejabat Tiga|198203032007011003'],
                'tgl_tiba'       => ['2026-06-01', '2026-06-03', '2026-06-05'],
                'tgl_berangkat'  => ['2026-06-02', '2026-06-04', '2026-06-06'],
            ]),
        ];

        foreach ($configs as $idx => $srikandi_config) {
            ob_start();
            include APPPATH . 'Views/sinadi/sppd_rekap_main.php';
            $html = (string) ob_get_clean();

            $this->assertStringContainsString('${nomor_naskah}', $html);
            $this->assertStringContainsString('${tanggal_naskah}', $html);
            $this->assertStringContainsString('${ttd_pengirim1}', $html);
            $this->assertStringContainsString('${ttd_pengirim2}', $html);

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper([0, 0, 595, 935], 'portrait');
            $dompdf->render();

            $this->assertSame(
                2,
                $dompdf->getCanvas()->get_page_count(),
                "SPPD Rekap main config #{$idx} expected 2 pages, got " . $dompdf->getCanvas()->get_page_count()
            );
        }
    }

    public function testLampiranAndMixedOrientationMergeValidPdfStructure(): void
    {
        $perjalanan10 = $this->buildSamplePesertaList(10);
        $srikandi_config = [];

        ob_start();
        include APPPATH . 'Views/sinadi/sppd_rekap_main.php';
        $htmlMain = (string) ob_get_clean();

        $dompdfMain = new Dompdf();
        $dompdfMain->loadHtml($htmlMain);
        $dompdfMain->setPaper([0, 0, 595, 935], 'portrait');
        $dompdfMain->render();
        $mainPages = $dompdfMain->getCanvas()->get_page_count();
        $pdfMain = $dompdfMain->output();

        $perjalanan = $perjalanan10;
        ob_start();
        include APPPATH . 'Views/sinadi/sppd_rekap_lampiran.php';
        $htmlLampiran = (string) ob_get_clean();

        $this->assertStringContainsString('dr. Nanang Lioncu, M.K.M', $htmlLampiran);
        $this->assertStringContainsString('Peserta Perjalanan 10', $htmlLampiran);
        $this->assertStringContainsString('${nomor_naskah}', $htmlLampiran);
        $this->assertStringContainsString('${tanggal_naskah}', $htmlLampiran);
        $this->assertStringContainsString('${ttd_pengirim1}', $htmlLampiran);

        $dompdfLampiran = new Dompdf();
        $dompdfLampiran->loadHtml($htmlLampiran);
        $dompdfLampiran->setPaper([0, 0, 595, 935], 'landscape');
        $dompdfLampiran->render();
        $lampiranPages = $dompdfLampiran->getCanvas()->get_page_count();
        $pdfLampiran = $dompdfLampiran->output();

        $merged = SimplePdfMerger::merge([$pdfMain, $pdfLampiran]);

        $this->assertSame(0, strpos($merged, '%PDF-'));
        $this->assertSame('%%EOF', substr(trim($merged), -5));

        // Verify xref integrity
        $startxrefPos = strrpos($merged, 'startxref');
        $this->assertNotFalse($startxrefPos);
        $this->assertSame(1, preg_match('/startxref\s+(\d+)/', $merged, $sxMatch, 0, (int) $startxrefPos));
        $xrefOffset = (int) $sxMatch[1];

        $this->assertSame(1, preg_match('/\/Root\s+(\d+)\s+0\s+R/', $merged, $rootMatch, 0, $xrefOffset));
        $rootId = (int) $rootMatch[1];

        $trailerPos = strpos($merged, 'trailer', $xrefOffset);
        $this->assertNotFalse($trailerPos);
        $xrefBlock = substr($merged, $xrefOffset, (int) $trailerPos - $xrefOffset);
        $this->assertSame(1, preg_match_all('/^(\d{10})\s+00000\s+n\s*$/m', $xrefBlock, $entryMatches) > 0 ? 1 : 0);

        $id = 1;
        foreach ($entryMatches[1] as $offStr) {
            $off = (int) $offStr;
            $this->assertSame(0, strpos(substr($merged, $off, 20), "{$id} 0 obj"), "Object #{$id} offset mismatch");
            $id++;
        }

        // Verify total /Count equals main + lampiran pages
        $this->assertSame(1, preg_match('/' . $rootId . '\s+0\s+obj\s*<<[^>]*\/Pages\s+(\d+)\s+0\s+R/', $merged, $pagesRefMatch));
        $rootPagesId = (int) $pagesRefMatch[1];
        $this->assertSame(1, preg_match('/' . $rootPagesId . '\s+0\s+obj\s*<<[^>]*\/Count\s+(\d+)/', $merged, $countMatch));
        $this->assertSame($mainPages + $lampiranPages, (int) $countMatch[1]);

        // Verify both F4 Portrait and F4 Landscape MediaBoxes are preserved
        $this->assertStringContainsString('[0.000 0.000 595.000 935.000]', $merged);
        $this->assertStringContainsString('[0.000 0.000 935.000 595.000]', $merged);

        // Also ensure 25 participants paginate cleanly across multiple landscape pages
        $perjalanan = $this->buildSamplePesertaList(25);
        ob_start();
        include APPPATH . 'Views/sinadi/sppd_rekap_lampiran.php';
        $html25 = (string) ob_get_clean();
        $dompdf25 = new Dompdf();
        $dompdf25->loadHtml($html25);
        $dompdf25->setPaper([0, 0, 595, 935], 'landscape');
        $dompdf25->render();
        $this->assertGreaterThan(1, $dompdf25->getCanvas()->get_page_count());
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
