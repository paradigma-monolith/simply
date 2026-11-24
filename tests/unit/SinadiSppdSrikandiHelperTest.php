<?php

declare(strict_types=1);

use App\Libraries\SinadiSppdSrikandiHelper;
use Dompdf\Dompdf;
use PHPUnit\Framework\TestCase;

final class SinadiSppdSrikandiHelperTest extends TestCase
{
    private function samplePeserta(array $overrides = []): array
    {
        return array_merge([
            'no_sppd'           => 'PR.01.05/1/100/2026',
            'ppk'               => 'dr. Ahmad PPK',
            'ppk_nip'           => '197501012000011001',
            'nama_pegawai'      => 'Budi Pelaksana',
            'nip_pegawai'       => '198501012010011002',
            'golongan'          => 'III/c',
            'jabatan_pegawai'   => 'Analis Kesehatan',
            'nama_kegiatan'     => 'Koordinasi Penanggulangan Penyakit',
            'transportasi'      => 'Transportasi darat',
            'kota_asal'         => 'Kota Bandung',
            'kota'              => 'Kab. Sukabumi',
            'tgl_pergi'         => '2026-11-25',
            'tgl_pulang'        => '2026-11-26',
            'tgl_sppd'          => '2026-11-24',
            'nama_kantor'       => 'Balai Kekarantinaan Kesehatan Kelas I Bandung',
            'mata_anggaran'     => '524111',
            'kepala_kantor'     => 'dr. Kepala Balai',
            'kepala_kantor_nip' => '196901042002121003',
            'kasubag_adum'      => 'Kasubag Adum',
            'kasubag_adum_nip'  => '197801012005011004',
        ], $overrides);
    }

    public function testEmptyModalWithSingleCityUsesRowIiOnly(): void
    {
        $config = SinadiSppdSrikandiHelper::parse([
            'mode_tanggal' => 'auto',
            'jumlah_baris' => '1',
            'lokasi_tujuan' => [''],
            'jabatan_tujuan' => [''],
            'pegawai_tujuan' => [''],
        ]);

        $res = SinadiSppdSrikandiHelper::buildBackPageStops($this->samplePeserta(), $config, true);

        $this->assertSame(0, $res['filled_pejabat_count']);
        $this->assertSame(1, $res['active_stop_count']);
        $this->assertTrue($res['stops'][0]['active']);
        $this->assertSame('II', $res['stops'][0]['roman']);
        $this->assertSame('Kab. Sukabumi', $res['stops'][0]['tiba_di']);
        $this->assertSame('25 November 2026', $res['stops'][0]['tgl_tiba_label']);
        $this->assertSame('Kota Bandung', $res['stops'][0]['ke']);
        $this->assertSame('26 November 2026', $res['stops'][0]['tgl_berangkat_label']);
        $this->assertNull($res['stops'][0]['pejabat']);

        for ($i = 1; $i <= 2; $i++) {
            $this->assertFalse($res['stops'][$i]['active']);
            $this->assertSame('', $res['stops'][$i]['tiba_di']);
            $this->assertNull($res['stops'][$i]['pejabat']);
        }
    }

    public function testThreePejabatAutoDatesOverThreeDays(): void
    {
        $config = SinadiSppdSrikandiHelper::parse([
            'mode_tanggal'   => 'auto',
            'jumlah_baris'   => '3',
            'lokasi_tujuan'  => ['Dinkes Kab. Sukabumi', 'RSUD Sekarwangi', 'Puskesmas Cibadak'],
            'jabatan_tujuan' => ['Kepala Dinkes', 'Direktur RSUD', 'Kepala Puskesmas'],
            'pegawai_tujuan' => ['Pejabat Satu|19800101', 'Pejabat Dua|19800202', 'manual'],
            'nama_tujuan_manual' => ['', '', 'Pejabat Tiga'],
            'nip_tujuan_manual'  => ['', '', '19800303'],
        ]);

        $peserta = $this->samplePeserta(['tgl_pergi' => '2026-11-25', 'tgl_pulang' => '2026-11-27']);
        $res = SinadiSppdSrikandiHelper::buildBackPageStops($peserta, $config, true);

        $this->assertSame(3, $res['filled_pejabat_count']);
        $this->assertSame('25 November 2026', $res['stops'][0]['tgl_tiba_label']);
        $this->assertSame('25 November 2026', $res['stops'][0]['tgl_berangkat_label']);
        $this->assertSame('RSUD Sekarwangi', $res['stops'][0]['ke']);
        $this->assertSame('${ttd_pengirim3}', $res['stops'][0]['pejabat']['placeholder']);

        $this->assertSame('26 November 2026', $res['stops'][1]['tgl_tiba_label']);
        $this->assertSame('26 November 2026', $res['stops'][1]['tgl_berangkat_label']);
        $this->assertSame('Puskesmas Cibadak', $res['stops'][1]['ke']);
        $this->assertSame('${ttd_pengirim4}', $res['stops'][1]['pejabat']['placeholder']);

        $this->assertSame('27 November 2026', $res['stops'][2]['tgl_tiba_label']);
        $this->assertSame('27 November 2026', $res['stops'][2]['tgl_berangkat_label']);
        $this->assertSame('Kota Bandung', $res['stops'][2]['ke']);
        $this->assertSame('${ttd_pengirim5}', $res['stops'][2]['pejabat']['placeholder']);
        $this->assertSame('Pejabat Tiga', $res['stops'][2]['pejabat']['nama']);

        $this->assertCount(3, $res['stops']);
    }

    public function testTwoPejabatAutoDatesOverFourDaysAndOneDayThreePejabat(): void
    {
        $fourDays = SinadiSppdSrikandiHelper::computeAutoDates('2026-11-25', '2026-11-28', 2);
        $this->assertSame([
            ['tiba' => '2026-11-25', 'berangkat' => '2026-11-26'],
            ['tiba' => '2026-11-27', 'berangkat' => '2026-11-28'],
        ], $fourDays);

        $oneDayThree = SinadiSppdSrikandiHelper::computeAutoDates('2026-11-25', '2026-11-25', 3);
        $this->assertSame([
            ['tiba' => '2026-11-25', 'berangkat' => '2026-11-25'],
            ['tiba' => '2026-11-25', 'berangkat' => '2026-11-25'],
            ['tiba' => '2026-11-25', 'berangkat' => '2026-11-25'],
        ], $oneDayThree);
    }

    public function testManualModeRespectsUserDatesIncludingEmptyDates(): void
    {
        $config = SinadiSppdSrikandiHelper::parse([
            'mode_tanggal'   => 'manual',
            'jumlah_baris'   => '2',
            'jabatan_tujuan' => ['Pejabat A', 'Pejabat B'],
            'pegawai_tujuan' => ['Nama A|111', 'Nama B|222'],
            'tgl_tiba'       => ['2026-12-01', ''],
            'tgl_berangkat'  => ['2026-12-03', '2026-12-05'],
        ]);

        $res = SinadiSppdSrikandiHelper::buildBackPageStops($this->samplePeserta(), $config, true);

        $this->assertSame('01 Desember 2026', $res['stops'][0]['tgl_tiba_label']);
        $this->assertSame('03 Desember 2026', $res['stops'][0]['tgl_berangkat_label']);
        $this->assertSame('', $res['stops'][1]['tgl_tiba_label']);
        $this->assertSame('05 Desember 2026', $res['stops'][1]['tgl_berangkat_label']);
    }

    public function testLegacyScalarQueryParametersStillPopulateRowIi(): void
    {
        $config = SinadiSppdSrikandiHelper::parse([
            'jabatan_tujuan' => 'KaDinkes',
            'pegawai_tujuan' => 'Budi|1980',
        ]);

        $res = SinadiSppdSrikandiHelper::buildBackPageStops($this->samplePeserta(), $config, true);
        $this->assertSame(1, $res['filled_pejabat_count']);
        $this->assertSame('KaDinkes', $res['stops'][0]['pejabat']['jabatan']);
        $this->assertSame('Budi', $res['stops'][0]['pejabat']['nama']);
        $this->assertSame('1980', $res['stops'][0]['pejabat']['nip']);
        $this->assertSame('${ttd_pengirim3}', $res['stops'][0]['pejabat']['placeholder']);
    }

    public function testMaxThreePejabatClampingAndStops(): void
    {
        $config = SinadiSppdSrikandiHelper::parse([
            'mode_tanggal'   => 'auto',
            'jumlah_baris'   => '4',
            'lokasi_tujuan'  => ['Dinkes Kab. Sukabumi', 'RSUD Sekarwangi', 'Puskesmas Cibadak', 'Labkesda Sukabumi'],
            'jabatan_tujuan' => ['Pejabat 1', 'Pejabat 2', 'Pejabat 3', 'Pejabat 4'],
            'pegawai_tujuan' => ['Nama 1|101', 'Nama 2|102', 'Nama 3|103', 'Nama 4|104'],
        ]);

        $peserta = $this->samplePeserta(['tgl_pergi' => '2026-11-25', 'tgl_pulang' => '2026-11-28']);
        $res = SinadiSppdSrikandiHelper::buildBackPageStops($peserta, $config, true);

        $this->assertSame(3, $res['filled_pejabat_count']);
        $this->assertSame(3, $res['active_stop_count']);
        $this->assertCount(3, $res['stops']);
        $this->assertSame('${ttd_pengirim3}', $res['stops'][0]['pejabat']['placeholder']);
        $this->assertSame('${ttd_pengirim4}', $res['stops'][1]['pejabat']['placeholder']);
        $this->assertSame('${ttd_pengirim5}', $res['stops'][2]['pejabat']['placeholder']);
        $this->assertSame('25 November 2026', $res['stops'][0]['tgl_tiba_label']);
        $this->assertSame('Kota Bandung', $res['stops'][2]['ke']);
    }

    public function testDompdfLayoutAndPageCountStayIdenticalZeroThroughThreePejabat(): void
    {
        $cases = [
            0 => ['mode_tanggal' => 'auto', 'jumlah_baris' => '1'],
            1 => [
                'mode_tanggal'   => 'auto',
                'jumlah_baris'   => '1',
                'jabatan_tujuan' => ['Kepala Dinas Kesehatan Kabupaten Sukabumi'],
                'pegawai_tujuan' => ['Dr. Pejabat Satu|198001012005011001'],
            ],
            2 => [
                'mode_tanggal'   => 'auto',
                'jumlah_baris'   => '2',
                'lokasi_tujuan'  => ['Kab. Sukabumi', 'Kota Sukabumi'],
                'jabatan_tujuan' => ['Kepala Dinas Kesehatan Kab. Sukabumi', 'Direktur RSUD Syamsudin'],
                'pegawai_tujuan' => ['Dr. Pejabat Satu|198001012005011001', 'Dr. Pejabat Dua|198102022006012002'],
            ],
            3 => [
                'mode_tanggal'   => 'auto',
                'jumlah_baris'   => '3',
                'lokasi_tujuan'  => ['Kab. Sukabumi', 'Kota Sukabumi', 'Kab. Cianjur'],
                'jabatan_tujuan' => ['Kepala Dinkes Kab. Sukabumi', 'Kepala Dinkes Kota Sukabumi', 'Kepala Dinkes Kab. Cianjur'],
                'pegawai_tujuan' => [
                    'Dr. Pejabat Satu|198001012005011001',
                    'Dr. Pejabat Dua|198102022006012002',
                    'Dr. Pejabat Tiga|198203032007011003',
                ],
            ],
        ];

        $perjalanan = [$this->samplePeserta(['tgl_pulang' => '2026-11-28'])];
        $baselineCatatanY = null;

        foreach ($cases as $count => $query) {
            $srikandi_config = SinadiSppdSrikandiHelper::parse($query);
            $borang = 'borang';
            $ttd_verifikator_3 = null;

            ob_start();
            include APPPATH . 'Views/sinadi/sppd.php';
            $html = (string) ob_get_clean();

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
                2,
                $dompdf->getCanvas()->get_page_count(),
                "Expected 2 pages for {$count} pejabat, got " . $dompdf->getCanvas()->get_page_count()
            );

            $this->assertNotNull($catatanY, "CATATAN LAIN-LAIN frame should be measured for {$count} pejabat");
            if ($baselineCatatanY === null) {
                $baselineCatatanY = $catatanY;
            } else {
                $this->assertEqualsWithDelta(
                    $baselineCatatanY,
                    $catatanY,
                    0.5,
                    "Table height changed at {$count} pejabat: baseline={$baselineCatatanY}, actual={$catatanY}"
                );
            }
        }
    }
}
