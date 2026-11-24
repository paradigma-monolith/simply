<?php

declare(strict_types=1);

use App\Libraries\SinadiMonitoringDlHelper;
use PHPUnit\Framework\TestCase;

final class SinadiMonitoringDlHelperTest extends TestCase
{
    public function testCanAccessDefaultsToSuperAdminOnly(): void
    {
        // Default Super Admin ('Admin') selalu bisa akses meskipun belum ada baris di sinadi_akses
        $this->assertTrue(SinadiMonitoringDlHelper::canAccess('Admin', null));
        $this->assertTrue(SinadiMonitoringDlHelper::canAccess('admin', ['akses_monitoring_dl' => 0]));

        // Non-Admin default tidak bisa akses meskipun dia Super User SINADI (status = Akses Diterima)
        $this->assertFalse(SinadiMonitoringDlHelper::canAccess('Pegawai', null));
        $this->assertFalse(SinadiMonitoringDlHelper::canAccess('Pegawai', ['status' => 'Akses Diterima', 'akses_monitoring_dl' => 0]));
        $this->assertFalse(SinadiMonitoringDlHelper::canAccess('Verifikator', ['akses_monitoring_dl' => '0']));

        // Non-Admin bisa akses setelah diberi izin eksplisit akses_monitoring_dl = 1
        $this->assertTrue(SinadiMonitoringDlHelper::canAccess('Pegawai', ['status' => 'Akses Diblokir', 'akses_monitoring_dl' => 1]));
        $this->assertTrue(SinadiMonitoringDlHelper::canAccess('Verifikator', ['akses_monitoring_dl' => '1']));
    }

    public function testResolveDateRangeDefaultsToStartOfYearUntilToday(): void
    {
        $range = SinadiMonitoringDlHelper::resolveDateRange('', '', '2026-11-24');
        $this->assertSame('2026-01-01', $range['start']);
        $this->assertSame('2026-11-24', $range['end']);

        // Jika input tanggal terbalik, otomatis ditukar
        $swapped = SinadiMonitoringDlHelper::resolveDateRange('2026-10-15', '2026-03-01', '2026-11-24');
        $this->assertSame('2026-03-01', $swapped['start']);
        $this->assertSame('2026-10-15', $swapped['end']);

        // Input tidak valid fallback ke default
        $fallback = SinadiMonitoringDlHelper::resolveDateRange('invalid', '2026-06-30', '2026-11-24');
        $this->assertSame('2026-01-01', $fallback['start']);
        $this->assertSame('2026-06-30', $fallback['end']);
    }

    public function testAggregateRanksByFrekuensiAndPenghasilanWithTimKerjaFilter(): void
    {
        $timMap = SinadiMonitoringDlHelper::buildPegawaiTimMap([
            ['nip' => '19800101', 'nama' => 'Budi Santoso', 'id_tim_kerja' => '1', 'nama_tim_kerja' => 'Tim Surveilans', 'jabatan' => 'Epidemiolog', 'golongan' => 'III/c'],
            // Duplikat NIP pada data_pegawai tidak boleh menggandakan DL
            ['nip' => '19800101', 'nama' => 'Budi Santoso', 'id_tim_kerja' => '1', 'nama_tim_kerja' => 'Tim Surveilans', 'jabatan' => 'Epidemiolog', 'golongan' => 'III/c'],
            ['nip' => '19810202', 'nama' => 'Siti Aminah', 'id_tim_kerja' => '2', 'nama_tim_kerja' => 'Tim Karantina', 'jabatan' => 'Dokter', 'golongan' => 'III/d'],
            ['nip' => '19820303', 'nama' => 'Andi Wijaya', 'id_tim_kerja' => '1', 'nama_tim_kerja' => 'Tim Surveilans', 'jabatan' => 'Sanitarian', 'golongan' => 'III/b'],
        ]);

        $pesertaRows = [
            // Budi: 3 DL unik (id 10, 11, 12; id 10 muncul dua baris namun dihitung 1 DL), total penghasilan 4.500.000
            ['id_perjalanan' => 10, 'status_surat' => 'Disetujui', 'nip_pegawai' => '19800101', 'nama_pegawai' => 'Budi Santoso', 'tgl_pergi' => '2026-02-01', 'tgl_pulang' => '2026-02-02', 'transport_jumlah' => 500000, 'harian_jumlah' => 800000, 'penginapan_jumlah' => 200000, 'jumlah_transfer' => 1500000],
            ['id_perjalanan' => 10, 'status_surat' => 'Disetujui', 'nip_pegawai' => '19800101', 'nama_pegawai' => 'Budi Santoso', 'tgl_pergi' => '2026-02-01', 'tgl_pulang' => '2026-02-02', 'transport_jumlah' => 0, 'harian_jumlah' => 0, 'penginapan_jumlah' => 0, 'jumlah_transfer' => 0],
            ['id_perjalanan' => 11, 'status_surat' => 'Disetujui', 'nip_pegawai' => '19800101', 'nama_pegawai' => 'Budi Santoso', 'tgl_pergi' => '2026-04-10', 'tgl_pulang' => '2026-04-10', 'transport_jumlah' => 300000, 'harian_jumlah' => 700000, 'penginapan_jumlah' => 0, 'jumlah_transfer' => 1000000],
            ['id_perjalanan' => 12, 'status_surat' => 'Disetujui', 'nip_pegawai' => '19800101', 'nama_pegawai' => 'Budi Santoso', 'tgl_pergi' => '2026-06-05', 'tgl_pulang' => '2026-06-07', 'transport_jumlah' => 600000, 'harian_jumlah' => 900000, 'penginapan_jumlah' => 500000, 'jumlah_transfer' => 2000000],

            // Siti: 2 DL, total penghasilan 6.200.000 (jumlah_transfer 0 di DL kedua -> fallback sum komponen)
            ['id_perjalanan' => 20, 'status_surat' => 'Disetujui', 'nip_pegawai' => '19810202', 'nama_pegawai' => 'Siti Aminah', 'tgl_pergi' => '2026-03-01', 'tgl_pulang' => '2026-03-04', 'transport_jumlah' => 1000000, 'harian_jumlah' => 1500000, 'penginapan_jumlah' => 1200000, 'jumlah_transfer' => 3700000],
            ['id_perjalanan' => 21, 'status_surat' => 'Disetujui', 'nip_pegawai' => '19810202', 'nama_pegawai' => 'Siti Aminah', 'tgl_pergi' => '2026-05-12', 'tgl_pulang' => '2026-05-14', 'transport_jumlah' => 800000, 'harian_jumlah' => 1000000, 'penginapan_jumlah' => 700000, 'jumlah_transfer' => 0],

            // Andi: 1 DL, total penghasilan 1.200.000
            ['id_perjalanan' => 30, 'status_surat' => 'Disetujui', 'nip_pegawai' => '19820303', 'nama_pegawai' => 'Andi Wijaya', 'tgl_pergi' => '2026-07-01', 'tgl_pulang' => '2026-07-02', 'transport_jumlah' => 400000, 'harian_jumlah' => 800000, 'penginapan_jumlah' => 0, 'jumlah_transfer' => 1200000],
        ];

        // 1. Sort berdasarkan frekuensi (default): Budi #1 (3x), Siti #2 (2x), Andi #3 (1x)
        $byFreq = SinadiMonitoringDlHelper::aggregate($pesertaRows, $timMap, 'all', 'frekuensi');
        $this->assertCount(3, $byFreq['rows']);
        $this->assertSame('Budi Santoso', $byFreq['rows'][0]['nama']);
        $this->assertSame(1, $byFreq['rows'][0]['rank']);
        $this->assertSame(3, $byFreq['rows'][0]['jumlah_dl']);
        $this->assertSame(6, $byFreq['rows'][0]['total_hari']); // 2 + 1 + 3
        $this->assertSame('Siti Aminah', $byFreq['top_penghasilan']['nama']);
        $this->assertSame('Budi Santoso', $byFreq['top_frekuensi']['nama']);
        $this->assertEqualsWithDelta(11900000.0, $byFreq['summary']['total_penghasilan'], 0.01);

        // 2. Sort berdasarkan penghasilan: Siti #1 (6.2jt), Budi #2 (4.5jt), Andi #3 (1.2jt)
        $byIncome = SinadiMonitoringDlHelper::aggregate($pesertaRows, $timMap, 'all', 'penghasilan');
        $this->assertSame('Siti Aminah', $byIncome['rows'][0]['nama']);
        $this->assertSame(1, $byIncome['rows'][0]['rank']);
        $this->assertSame(2, $byIncome['rows'][0]['rank_frekuensi']);
        $this->assertSame(1, $byIncome['rows'][0]['rank_penghasilan']);
        $this->assertEqualsWithDelta(6200000.0, $byIncome['rows'][0]['total_penghasilan'], 0.01);
        $this->assertSame('Budi Santoso', $byIncome['rows'][1]['nama']);

        // 3. Filter Tim Kerja = 1 (Tim Surveilans): hanya Budi dan Andi
        $filtered = SinadiMonitoringDlHelper::aggregate($pesertaRows, $timMap, '1', 'frekuensi');
        $this->assertCount(2, $filtered['rows']);
        $this->assertSame('Budi Santoso', $filtered['rows'][0]['nama']);
        $this->assertSame('Andi Wijaya', $filtered['rows'][1]['nama']);
    }

    public function testDukConfigContainsValidNinetySixEntries(): void
    {
        $items = \App\Config\SinadiDukPegawai::ITEMS;
        $this->assertCount(96, $items);

        $nips = [];
        foreach ($items as $item) {
            $this->assertMatchesRegularExpression('/^\d{18}$/', $item['nip']);
            $this->assertNotSame('', trim($item['nama']));
            $nips[$item['nip']] = true;
        }
        $this->assertCount(96, $nips);

        $whitelist = SinadiMonitoringDlHelper::buildDukWhitelist(\App\Libraries\SinadiMonitoringDlHelper::loadDukRows());
        $this->assertSame(96, $whitelist['total']);
    }

    public function testAggregateFiltersStrictlyToDukWhitelistAndNormalizesFormattedNips(): void
    {
        $dukRows = \App\Config\SinadiDukPegawai::ITEMS;
        $firstDuk = $dukRows[0];
        $secondDuk = $dukRows[1];

        $whitelist = SinadiMonitoringDlHelper::buildDukWhitelist($dukRows);
        $formattedNip = substr($firstDuk['nip'], 0, 8) . ' ' . substr($firstDuk['nip'], 8, 6) . ' ' . substr($firstDuk['nip'], 14, 1) . ' ' . substr($firstDuk['nip'], 15, 3);

        $timMap = SinadiMonitoringDlHelper::buildPegawaiTimMap([
            [
                'nip'            => $firstDuk['nip'],
                'nama'           => $firstDuk['nama'],
                'id_tim_kerja'   => 2,
                'nama_tim_kerja' => 'Tim KLB',
            ],
        ]);

        $pesertaRows = [
            [
                'id_perjalanan'   => 201,
                'status_surat'    => 'Disetujui',
                'nip_pegawai'     => $formattedNip,
                'nama_pegawai'    => 'Nama Lama Non Kapital',
                'jabatan_pegawai' => '',
                'golongan'        => '',
                'tgl_pergi'       => '2026-03-01',
                'tgl_pulang'      => '2026-03-02',
                'jumlah_transfer' => 1500000,
            ],
            [
                // Pegawai DUK ke-2 tanpa NIP, cocok lewat nama
                'id_perjalanan'   => 202,
                'status_surat'    => 'Disetujui',
                'nip_pegawai'     => '',
                'nama_pegawai'    => strtolower($secondDuk['nama']),
                'jabatan_pegawai' => '',
                'golongan'        => '',
                'tgl_pergi'       => '2026-03-05',
                'tgl_pulang'      => '2026-03-05',
                'jumlah_transfer' => 500000,
            ],
            [
                // Non-DUK: PPNPN / pegawai tidak terdaftar di DUK harus disaring keluar
                'id_perjalanan'   => 203,
                'status_surat'    => 'Disetujui',
                'nip_pegawai'     => '999999999999999999',
                'nama_pegawai'    => 'Pegawai Non DUK',
                'jabatan_pegawai' => 'Tenaga Kontrak',
                'golongan'        => '-',
                'tgl_pergi'       => '2026-03-10',
                'tgl_pulang'      => '2026-03-12',
                'jumlah_transfer' => 9000000,
            ],
        ];

        $result = SinadiMonitoringDlHelper::aggregate($pesertaRows, $timMap, 'all', 'frekuensi', $whitelist);

        $this->assertCount(2, $result['rows']);
        $nipsReturned = array_column($result['rows'], 'nip');
        $this->assertContains($firstDuk['nip'], $nipsReturned);
        $this->assertContains($secondDuk['nip'], $nipsReturned);
        $this->assertNotContains('999999999999999999', $nipsReturned);

        // Pastikan nama & tim tetap terpetakan dengan benar meski NIP di peserta pakai spasi
        $matchedFirst = null;
        foreach ($result['rows'] as $row) {
            if ($row['nip'] === $firstDuk['nip']) {
                $matchedFirst = $row;
                break;
            }
        }
        $this->assertNotNull($matchedFirst);
        $this->assertSame($firstDuk['nama'], $matchedFirst['nama']);
        $this->assertSame('Tim KLB', $matchedFirst['nama_tim_kerja']);
        $this->assertSame(2000000.0, $result['summary']['total_penghasilan']);
    }

    public function testNormalizeSortDefaultsToPenghasilan(): void
    {
        $this->assertSame('penghasilan', SinadiMonitoringDlHelper::normalizeSort(null));
        $this->assertSame('penghasilan', SinadiMonitoringDlHelper::normalizeSort(''));
        $this->assertSame('penghasilan', SinadiMonitoringDlHelper::normalizeSort('invalid'));
        $this->assertSame('penghasilan', SinadiMonitoringDlHelper::normalizeSort('penghasilan'));
        $this->assertSame('frekuensi', SinadiMonitoringDlHelper::normalizeSort('frekuensi'));
        $this->assertSame('frekuensi', SinadiMonitoringDlHelper::normalizeSort(' FREKUENSI '));
    }

    public function testAggregateDefaultsToPenghasilanAndExcludesNonApprovedStatuses(): void
    {
        $timMap = SinadiMonitoringDlHelper::buildPegawaiTimMap([
            ['nip' => '19800101', 'nama' => 'Budi Santoso', 'id_tim_kerja' => '1', 'nama_tim_kerja' => 'Tim A'],
            ['nip' => '19810202', 'nama' => 'Siti Aminah', 'id_tim_kerja' => '2', 'nama_tim_kerja' => 'Tim B'],
        ]);

        $pesertaRows = [
            // Budi punya 2 perjalanan Disetujui total 3.000.000, plus Draft/Diajukan/Ditolak besar yang harus diabaikan
            ['id_perjalanan' => 1, 'status_surat' => 'Disetujui', 'nip_pegawai' => '19800101', 'nama_pegawai' => 'Budi Santoso', 'tgl_pergi' => '2026-02-01', 'tgl_pulang' => '2026-02-02', 'jumlah_transfer' => 1500000],
            ['id_perjalanan' => 2, 'status_surat' => 'disetujui', 'nip_pegawai' => '19800101', 'nama_pegawai' => 'Budi Santoso', 'tgl_pergi' => '2026-02-10', 'tgl_pulang' => '2026-02-11', 'jumlah_transfer' => 1500000],
            ['id_perjalanan' => 3, 'status_surat' => 'Draft', 'nip_pegawai' => '19800101', 'nama_pegawai' => 'Budi Santoso', 'tgl_pergi' => '2026-03-01', 'tgl_pulang' => '2026-03-03', 'jumlah_transfer' => 9000000],
            ['id_perjalanan' => 4, 'status_surat' => 'Diajukan', 'nip_pegawai' => '19800101', 'nama_pegawai' => 'Budi Santoso', 'tgl_pergi' => '2026-03-05', 'tgl_pulang' => '2026-03-06', 'jumlah_transfer' => 4000000],
            ['id_perjalanan' => 5, 'status_surat' => 'Dalam Verifikasi', 'nip_pegawai' => '19800101', 'nama_pegawai' => 'Budi Santoso', 'tgl_pergi' => '2026-03-07', 'tgl_pulang' => '2026-03-08', 'jumlah_transfer' => 4000000],
            ['id_perjalanan' => 6, 'status_surat' => 'Perlu Revisi', 'nip_pegawai' => '19800101', 'nama_pegawai' => 'Budi Santoso', 'tgl_pergi' => '2026-03-09', 'tgl_pulang' => '2026-03-10', 'jumlah_transfer' => 4000000],
            ['id_perjalanan' => 7, 'status_surat' => 'Ditolak', 'nip_pegawai' => '19800101', 'nama_pegawai' => 'Budi Santoso', 'tgl_pergi' => '2026-03-11', 'tgl_pulang' => '2026-03-12', 'jumlah_transfer' => 4000000],
            ['id_perjalanan' => 8, 'status_surat' => '', 'nip_pegawai' => '19800101', 'nama_pegawai' => 'Budi Santoso', 'tgl_pergi' => '2026-03-13', 'tgl_pulang' => '2026-03-14', 'jumlah_transfer' => 4000000],

            // Siti punya 1 perjalanan Disetujui senilai 5.000.000 -> default sort tanpa argumen $sort harus menaruh Siti di #1
            ['id_perjalanan' => 9, 'status_surat' => 'Disetujui', 'nip_pegawai' => '19810202', 'nama_pegawai' => 'Siti Aminah', 'tgl_pergi' => '2026-04-01', 'tgl_pulang' => '2026-04-03', 'jumlah_transfer' => 5000000],
        ];

        $result = SinadiMonitoringDlHelper::aggregate($pesertaRows, $timMap);

        $this->assertCount(2, $result['rows']);
        $this->assertSame('Siti Aminah', $result['rows'][0]['nama']);
        $this->assertSame(1, $result['rows'][0]['rank']);
        $this->assertSame('Budi Santoso', $result['rows'][1]['nama']);
        $this->assertSame(2, $result['rows'][1]['jumlah_dl']);
        $this->assertEqualsWithDelta(3000000.0, $result['rows'][1]['total_penghasilan'], 0.01);
        $this->assertEqualsWithDelta(8000000.0, $result['summary']['total_penghasilan'], 0.01);
    }

    public function testAggregateBuildsTimKerjaSummarySortedByTotalPenghasilan(): void
    {
        $timMap = SinadiMonitoringDlHelper::buildPegawaiTimMap([
            ['nip' => '19800101', 'nama' => 'Budi Santoso', 'id_tim_kerja' => '1', 'nama_tim_kerja' => 'Tim Surveilans'],
            ['nip' => '19820303', 'nama' => 'Andi Wijaya', 'id_tim_kerja' => '1', 'nama_tim_kerja' => 'Tim Surveilans'],
            ['nip' => '19810202', 'nama' => 'Siti Aminah', 'id_tim_kerja' => '2', 'nama_tim_kerja' => 'Tim Karantina'],
        ]);

        $pesertaRows = [
            ['id_perjalanan' => 10, 'status_surat' => 'Disetujui', 'nip_pegawai' => '19800101', 'nama_pegawai' => 'Budi Santoso', 'tgl_pergi' => '2026-02-01', 'tgl_pulang' => '2026-02-02', 'transport_jumlah' => 500000, 'harian_jumlah' => 1000000, 'penginapan_jumlah' => 0, 'jumlah_transfer' => 1500000],
            ['id_perjalanan' => 11, 'status_surat' => 'Disetujui', 'nip_pegawai' => '19820303', 'nama_pegawai' => 'Andi Wijaya', 'tgl_pergi' => '2026-02-05', 'tgl_pulang' => '2026-02-06', 'transport_jumlah' => 500000, 'harian_jumlah' => 1000000, 'penginapan_jumlah' => 1000000, 'jumlah_transfer' => 2500000],
            ['id_perjalanan' => 12, 'status_surat' => 'Disetujui', 'nip_pegawai' => '19810202', 'nama_pegawai' => 'Siti Aminah', 'tgl_pergi' => '2026-03-01', 'tgl_pulang' => '2026-03-04', 'transport_jumlah' => 1000000, 'harian_jumlah' => 2000000, 'penginapan_jumlah' => 3000000, 'jumlah_transfer' => 6000000],
            ['id_perjalanan' => 13, 'status_surat' => 'Draft', 'nip_pegawai' => '19800101', 'nama_pegawai' => 'Budi Santoso', 'tgl_pergi' => '2026-04-01', 'tgl_pulang' => '2026-04-02', 'jumlah_transfer' => 9999999],
        ];

        // Meskipun filter Tim Kerja = 1, tim_kerja_summary tetap menampilkan perbandingan semua Tim Kerja
        $result = SinadiMonitoringDlHelper::aggregate($pesertaRows, $timMap, '1', 'penghasilan');

        $this->assertCount(2, $result['rows']);
        $this->assertCount(2, $result['tim_kerja_summary']);

        // Urutan tim: Tim Karantina (6jt, 60%) lalu Tim Surveilans (4jt, 40%)
        $this->assertSame('Tim Karantina', $result['tim_kerja_summary'][0]['nama_tim_kerja']);
        $this->assertSame(1, $result['tim_kerja_summary'][0]['total_pegawai']);
        $this->assertEqualsWithDelta(6000000.0, $result['tim_kerja_summary'][0]['total_penghasilan'], 0.01);
        $this->assertEqualsWithDelta(60.0, $result['tim_kerja_summary'][0]['persentase'], 0.01);

        $this->assertSame('Tim Surveilans', $result['tim_kerja_summary'][1]['nama_tim_kerja']);
        $this->assertSame(2, $result['tim_kerja_summary'][1]['total_pegawai']);
        $this->assertSame(2, $result['tim_kerja_summary'][1]['total_penugasan']);
        $this->assertEqualsWithDelta(4000000.0, $result['tim_kerja_summary'][1]['total_penghasilan'], 0.01);
        $this->assertEqualsWithDelta(40.0, $result['tim_kerja_summary'][1]['persentase'], 0.01);

        $this->assertEqualsWithDelta(10000000.0, $result['tim_kerja_grand_total']['total_penghasilan'], 0.01);
        $this->assertSame(3, $result['tim_kerja_grand_total']['total_pegawai']);
    }
}
