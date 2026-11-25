<?php

declare(strict_types=1);

use App\Libraries\SinadiMonitoringDlHelper;
use PHPUnit\Framework\TestCase;

final class SinadiMonitoringDlHelperTest extends TestCase
{
    public function testCanAccessDefaultsToSuperAdminOnly(): void
    {
        // Super Admin khusus Monitoring DL hanya Mohamad Dahlan Fazhry (199404082022031001)
        $this->assertTrue(SinadiMonitoringDlHelper::canAccess('199404082022031001', null));
        $this->assertTrue(SinadiMonitoringDlHelper::canAccess('199404082022031001', ['akses_monitoring_dl' => 0]));
        $this->assertTrue(SinadiMonitoringDlHelper::canManageAccess('199404082022031001', null));

        // User lain (termasuk role Admin biasa / username lain) default tidak bisa akses & tidak bisa kelola akses
        $this->assertFalse(SinadiMonitoringDlHelper::canAccess('Admin', null));
        $this->assertFalse(SinadiMonitoringDlHelper::canAccess('hadianzm', ['status' => 'Akses Diterima', 'akses_monitoring_dl' => 0]));
        $this->assertFalse(SinadiMonitoringDlHelper::canAccess('198408022006042002', ['akses_monitoring_dl' => '0']));
        $this->assertFalse(SinadiMonitoringDlHelper::canManageAccess('198408022006042002', ['status' => 'Akses Diterima', 'akses_monitoring_dl' => 1]));

        // User lain bisa akses setelah diberi izin eksplisit akses_monitoring_dl = 1 (namun tetap bukan pengelola akses)
        $this->assertTrue(SinadiMonitoringDlHelper::canAccess('198408022006042002', ['status' => 'Akses Diblokir', 'akses_monitoring_dl' => 1]));
        $this->assertTrue(SinadiMonitoringDlHelper::canAccess('hadianzm', ['akses_monitoring_dl' => '1']));
        $this->assertFalse(SinadiMonitoringDlHelper::canManageAccess('hadianzm', ['akses_monitoring_dl' => 1]));
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
        $this->assertSame('Keadaan November 2026', \App\Config\SinadiDukPegawai::PERIODE_LABEL);

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

        // Verifikasi pembaruan DUK Keadaan November 2026
        $this->assertSame('Administrator Kesehatan Ahli Muda (JFT)', $whitelist['by_nip']['197606191999031001']['jabatan']);
        $this->assertSame('III/c', $whitelist['by_nip']['198910072010121005']['golongan']);
        $this->assertSame('Pembimbing Kesehatan Kerja Ahli Muda (JFT)', $whitelist['by_nip']['198310152008121002']['jabatan']);
        $this->assertSame('Pranata Laboratorium Kesehatan Mahir (JFT)', $whitelist['by_nip']['198007102003122002']['jabatan']);
        $this->assertSame('Perawat Ahli Pertama (JFT)', $whitelist['by_nip']['198204152014022001']['jabatan']);
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

    public function testResolveCostComponentsDefaultsToAllTrueAndRespectsUnchecked(): void
    {
        // Default pertama dibuka: Transport, Uang Harian, dan Penginapan seluruhnya aktif (checked)
        $default = SinadiMonitoringDlHelper::resolveCostComponents([]);
        $this->assertSame(['transport' => true, 'harian' => true, 'penginapan' => true], $default);

        $withoutPenginapan = SinadiMonitoringDlHelper::resolveCostComponents([
            'biaya_applied' => '1',
            'inc_transport' => '1',
            'inc_harian'    => '1',
        ]);
        $this->assertSame(['transport' => true, 'harian' => true, 'penginapan' => false], $withoutPenginapan);

        $onlyHarian = SinadiMonitoringDlHelper::resolveCostComponents([
            'biaya_applied' => '1',
            'inc_harian'    => '1',
        ]);
        $this->assertSame(['transport' => false, 'harian' => true, 'penginapan' => false], $onlyHarian);

        $noneChecked = SinadiMonitoringDlHelper::resolveCostComponents([
            'biaya_applied' => '1',
        ]);
        $this->assertSame(['transport' => false, 'harian' => false, 'penginapan' => false], $noneChecked);
    }

    public function testAggregateCalculatesDalamKotaLuarKotaAndRespectsCostComponents(): void
    {
        $timMap = SinadiMonitoringDlHelper::buildPegawaiTimMap([
            ['nip' => '19800101', 'nama' => 'Budi Santoso', 'id_tim_kerja' => '1', 'nama_tim_kerja' => 'Tim Surveilans'],
            ['nip' => '19810202', 'nama' => 'Siti Aminah', 'id_tim_kerja' => '2', 'nama_tim_kerja' => 'Tim Karantina'],
        ]);

        $pesertaRows = [
            // Budi: Luar Kota (transport 500k + harian 1jt + inap 500k = 2jt) + Dalam Kota (transport 170k = 170k)
            [
                'id_perjalanan'          => 101,
                'status_surat'           => 'Disetujui',
                'jenis_perjalanan_dinas' => 'luar kota',
                'nip_pegawai'            => '19800101',
                'nama_pegawai'           => 'Budi Santoso',
                'kota_asal'              => 'Kota Bandung',
                'kota'                   => 'Kab. Garut',
                'tgl_pergi'              => '2026-02-01',
                'tgl_pulang'             => '2026-02-02',
                'transport_jumlah'       => 500000,
                'harian_jumlah'          => 1000000,
                'penginapan_jumlah'      => 500000,
                'jumlah_transfer'        => 2000000,
            ],
            [
                'id_perjalanan'          => 102,
                'status_surat'           => 'Disetujui',
                'jenis_perjalanan_dinas' => 'dalam kota',
                'nip_pegawai'            => '19800101',
                'nama_pegawai'           => 'Budi Santoso',
                'kota_asal'              => 'Kota Bandung',
                'kota'                   => 'Kota Bandung',
                'tgl_pergi'              => '2026-02-10',
                'tgl_pulang'             => '2026-02-10',
                'transport_jumlah'       => 170000,
                'harian_jumlah'          => 0,
                'penginapan_jumlah'      => 0,
                'jumlah_transfer'        => 170000,
            ],
            // Siti: Luar Kota (transport 1.5jt + harian 600k + inap 0 = 2.1jt)
            [
                'id_perjalanan'          => 103,
                'status_surat'           => 'Disetujui',
                'jenis_perjalanan_dinas' => 'luar kota',
                'nip_pegawai'            => '19810202',
                'nama_pegawai'           => 'Siti Aminah',
                'kota_asal'              => 'Kota Bandung',
                'kota'                   => 'Kab. Sukabumi',
                'tgl_pergi'              => '2026-03-01',
                'tgl_pulang'             => '2026-03-02',
                'transport_jumlah'       => 1500000,
                'harian_jumlah'          => 600000,
                'penginapan_jumlah'      => 0,
                'jumlah_transfer'        => 2100000,
            ],
        ];

        // 1. Semua biaya aktif: Budi total 2.17jt (LK 2jt, DK 170rb), Siti total 2.1jt (LK 2.1jt, DK 0)
        $all = SinadiMonitoringDlHelper::aggregate($pesertaRows, $timMap, 'all', 'penghasilan');
        $this->assertSame('Budi Santoso', $all['rows'][0]['nama']);
        $this->assertSame(1, $all['rows'][0]['jumlah_dl_luar_kota']);
        $this->assertSame(1, $all['rows'][0]['jumlah_dl_dalam_kota']);
        $this->assertEqualsWithDelta(2000000.0, $all['rows'][0]['penghasilan_luar_kota'], 0.01);
        $this->assertEqualsWithDelta(170000.0, $all['rows'][0]['penghasilan_dalam_kota'], 0.01);
        $this->assertEqualsWithDelta(4100000.0, $all['summary']['total_penghasilan_luar_kota'], 0.01);
        $this->assertEqualsWithDelta(170000.0, $all['summary']['total_penghasilan_dalam_kota'], 0.01);
        $this->assertSame(2, $all['summary']['total_penugasan_luar_kota']);
        $this->assertSame(1, $all['summary']['total_penugasan_dalam_kota']);

        // 2. Hanya Uang Harian yang dicentang: Budi = 1jt, Siti = 600rb
        $harianOnly = SinadiMonitoringDlHelper::aggregate(
            $pesertaRows,
            $timMap,
            'all',
            'penghasilan',
            null,
            true,
            ['transport' => false, 'harian' => true, 'penginapan' => false]
        );
        $this->assertSame('Budi Santoso', $harianOnly['rows'][0]['nama']);
        $this->assertEqualsWithDelta(1000000.0, $harianOnly['rows'][0]['total_penghasilan'], 0.01);
        $this->assertEqualsWithDelta(0.0, $harianOnly['rows'][0]['total_transport'], 0.01);
        $this->assertEqualsWithDelta(600000.0, $harianOnly['rows'][1]['total_penghasilan'], 0.01);
        $this->assertEqualsWithDelta(1600000.0, $harianOnly['summary']['total_penghasilan'], 0.01);

        // 3. Hanya Transport yang dicentang: Siti (1.5jt) naik ke #1 mengalahkan Budi (670rb)
        $transportOnly = SinadiMonitoringDlHelper::aggregate(
            $pesertaRows,
            $timMap,
            'all',
            'penghasilan',
            null,
            true,
            ['transport' => true, 'harian' => false, 'penginapan' => false]
        );
        $this->assertSame('Siti Aminah', $transportOnly['rows'][0]['nama']);
        $this->assertEqualsWithDelta(1500000.0, $transportOnly['rows'][0]['total_penghasilan'], 0.01);
        $this->assertSame('Budi Santoso', $transportOnly['rows'][1]['nama']);
        $this->assertEqualsWithDelta(670000.0, $transportOnly['rows'][1]['total_penghasilan'], 0.01);
        $this->assertEqualsWithDelta(170000.0, $transportOnly['rows'][1]['penghasilan_dalam_kota'], 0.01);
        $this->assertEqualsWithDelta(500000.0, $transportOnly['rows'][1]['penghasilan_luar_kota'], 0.01);
    }

    public function testFormatTimKerjaNameMapsDefaultAndSubAdum(): void
    {
        $this->assertSame(
            'Kepala Balai Kekarantinaan Kesehatan Kelas I Bandung',
            SinadiMonitoringDlHelper::formatTimKerjaName('Default')
        );
        $this->assertSame(
            'Kepala Balai Kekarantinaan Kesehatan Kelas I Bandung',
            SinadiMonitoringDlHelper::formatTimKerjaName('  default ')
        );
        $this->assertSame(
            'Tim Kerja Dukungan Manajemen',
            SinadiMonitoringDlHelper::formatTimKerjaName('Sub Adum')
        );
        $this->assertSame(
            'Tim Kerja Dukungan Manajemen',
            SinadiMonitoringDlHelper::formatTimKerjaName('sub adum')
        );
        $this->assertSame('Tanpa Tim Kerja', SinadiMonitoringDlHelper::formatTimKerjaName(''));
        $this->assertSame('Tim Kerja Surveilans', SinadiMonitoringDlHelper::formatTimKerjaName('Tim Kerja Surveilans'));

        $timMap = SinadiMonitoringDlHelper::buildPegawaiTimMap([
            ['nip' => '196901042002121003', 'nama' => 'Kepala Balai', 'id_tim_kerja' => '12', 'nama_tim_kerja' => 'Default'],
            ['nip' => '199404082022031001', 'nama' => 'Mohamad Dahlan Fazhry', 'id_tim_kerja' => '11', 'nama_tim_kerja' => 'Sub Adum'],
        ]);
        $pesertaRows = [
            ['id_perjalanan' => 1, 'status_surat' => 'Disetujui', 'nip_pegawai' => '196901042002121003', 'nama_pegawai' => 'Kepala Balai', 'tgl_pergi' => '2026-02-01', 'tgl_pulang' => '2026-02-02', 'jumlah_transfer' => 2000000],
            ['id_perjalanan' => 2, 'status_surat' => 'Disetujui', 'nip_pegawai' => '199404082022031001', 'nama_pegawai' => 'Mohamad Dahlan Fazhry', 'tgl_pergi' => '2026-02-03', 'tgl_pulang' => '2026-02-04', 'jumlah_transfer' => 1500000],
        ];
        $aggregated = SinadiMonitoringDlHelper::aggregate($pesertaRows, $timMap);
        $this->assertSame('Kepala Balai Kekarantinaan Kesehatan Kelas I Bandung', $aggregated['rows'][0]['nama_tim_kerja']);
        $this->assertSame('Tim Kerja Dukungan Manajemen', $aggregated['rows'][1]['nama_tim_kerja']);
        $this->assertSame('Kepala Balai Kekarantinaan Kesehatan Kelas I Bandung', $aggregated['tim_kerja_summary'][0]['nama_tim_kerja']);
        $this->assertSame('Tim Kerja Dukungan Manajemen', $aggregated['tim_kerja_summary'][1]['nama_tim_kerja']);
    }

    public function testAggregateIncludesSpecialReturnedPerjalananIdsEvenWhenDiajukan(): void
    {
        $timMap = SinadiMonitoringDlHelper::buildPegawaiTimMap([
            ['nip' => '19800101', 'nama' => 'Budi Santoso', 'id_tim_kerja' => '1', 'nama_tim_kerja' => 'Tim Surveilans'],
        ]);

        $pesertaRows = [
            // Kegiatan Haji yang dikembalikan ke Verifikator Pertama (Diajukan) tetap harus dihitung di Monitoring DL
            [
                'id_perjalanan'   => 3994,
                'status_surat'    => 'Diajukan',
                'nip_pegawai'     => '19800101',
                'nama_pegawai'    => 'Budi Santoso',
                'tgl_pergi'       => '2026-06-01',
                'tgl_pulang'      => '2026-06-08',
                'jumlah_transfer' => 2500000,
            ],
            [
                'id_perjalanan'   => 4203,
                'status_surat'    => 'Diajukan',
                'nip_pegawai'     => '19800101',
                'nama_pegawai'    => 'Budi Santoso',
                'tgl_pergi'       => '2026-07-21',
                'tgl_pulang'      => '2026-07-22',
                'jumlah_transfer' => 500000,
            ],
            // ID lain berstatus Diajukan tetap TIDAK dihitung
            [
                'id_perjalanan'   => 999999,
                'status_surat'    => 'Diajukan',
                'nip_pegawai'     => '19800101',
                'nama_pegawai'    => 'Budi Santoso',
                'tgl_pergi'       => '2026-08-01',
                'tgl_pulang'      => '2026-08-02',
                'jumlah_transfer' => 9000000,
            ],
        ];

        $result = SinadiMonitoringDlHelper::aggregate($pesertaRows, $timMap);
        $this->assertCount(1, $result['rows']);
        $this->assertSame(2, $result['rows'][0]['jumlah_dl']);
        $this->assertEqualsWithDelta(3000000.0, $result['rows'][0]['total_penghasilan'], 0.01);
        $this->assertEqualsWithDelta(3000000.0, $result['summary']['total_penghasilan'], 0.01);
    }

    public function testAggregateIncludesZeroTripDukEmployeesWhenEnabled(): void
    {
        $dukRows = [
            ['nip' => '198001012005011001', 'nama' => 'Budi Santoso, SKM', 'golongan' => 'III/d', 'jabatan' => 'Epidemiolog'],
            ['nip' => '197603032008121002', 'nama' => 'TATANG SUPRIATNA', 'golongan' => 'II/d', 'jabatan' => 'Operator Layanan Operasional (SMU) (JFU)'],
        ];
        $whitelist = SinadiMonitoringDlHelper::buildDukWhitelist($dukRows);

        $timMap = SinadiMonitoringDlHelper::buildPegawaiTimMap([
            ['nip' => '198001012005011001', 'nama' => 'Budi Santoso, SKM', 'id_tim_kerja' => '1', 'nama_tim_kerja' => 'Tim Surveilans'],
            ['nip' => '197603032008121002', 'nama' => 'TATANG SUPRIATNA', 'id_tim_kerja' => '11', 'nama_tim_kerja' => 'Sub Adum'],
        ]);

        $pesertaRows = [
            [
                'id_perjalanan'   => 101,
                'status_surat'    => 'Disetujui',
                'nip_pegawai'     => '198001012005011001',
                'nama_pegawai'    => 'Budi Santoso, SKM',
                'tgl_pergi'       => '2026-02-01',
                'tgl_pulang'      => '2026-02-02',
                'jumlah_transfer' => 1500000,
            ],
        ];

        $all = SinadiMonitoringDlHelper::aggregate($pesertaRows, $timMap, 'all', 'penghasilan', $whitelist, true, null, true);
        $this->assertCount(2, $all['rows']);
        $this->assertSame('Budi Santoso, SKM', $all['rows'][0]['nama']);
        $this->assertSame('TATANG SUPRIATNA', $all['rows'][1]['nama']);
        $this->assertSame(0, $all['rows'][1]['jumlah_dl']);
        $this->assertSame(0, $all['rows'][1]['total_hari']);
        $this->assertEqualsWithDelta(0.0, $all['rows'][1]['total_penghasilan'], 0.01);
        $this->assertSame([], $all['rows'][1]['riwayat']);
        $this->assertSame('Tim Kerja Dukungan Manajemen', $all['rows'][1]['nama_tim_kerja']);

        // Filter khusus Tim 11 tetap memunculkan TATANG SUPRIATNA dengan nilai 0
        $filtered = SinadiMonitoringDlHelper::aggregate($pesertaRows, $timMap, '11', 'penghasilan', $whitelist, true, null, true);
        $this->assertCount(1, $filtered['rows']);
        $this->assertSame('TATANG SUPRIATNA', $filtered['rows'][0]['nama']);
        $this->assertSame(0, $filtered['rows'][0]['jumlah_dl']);
    }

    public function testAggregateWithoutRiwayatProvidesAllRowsAndKegiatanByTim(): void
    {
        $timMap = SinadiMonitoringDlHelper::buildPegawaiTimMap([
            ['nip' => '19800101', 'nama' => 'Budi Santoso', 'id_tim_kerja' => '1', 'nama_tim_kerja' => 'Tim Surveilans'],
            ['nip' => '19810202', 'nama' => 'Siti Aminah', 'id_tim_kerja' => '2', 'nama_tim_kerja' => 'Tim Karantina'],
        ]);

        $pesertaRows = [
            [
                'id_perjalanan'          => 10,
                'status_surat'           => 'Disetujui',
                'jenis_perjalanan_dinas' => 'luar kota',
                'nip_pegawai'            => '19800101',
                'nama_pegawai'           => 'Budi Santoso',
                'tgl_pergi'              => '2026-02-01',
                'tgl_pulang'             => '2026-02-02',
                'transport_jumlah'       => 500000,
                'harian_jumlah'          => 800000,
                'penginapan_jumlah'      => 200000,
                'jumlah_transfer'        => 1500000,
            ],
            [
                'id_perjalanan'          => 10,
                'status_surat'           => 'Disetujui',
                'jenis_perjalanan_dinas' => 'dalam kota',
                'nip_pegawai'            => '19810202',
                'nama_pegawai'           => 'Siti Aminah',
                'tgl_pergi'              => '2026-02-01',
                'tgl_pulang'             => '2026-02-01',
                'transport_jumlah'       => 170000,
                'harian_jumlah'          => 0,
                'penginapan_jumlah'      => 0,
                'jumlah_transfer'        => 170000,
            ],
        ];

        $res = SinadiMonitoringDlHelper::aggregate($pesertaRows, $timMap, '1', 'penghasilan', null, true, null, false, false);

        // Filtered rows hanya Tim 1, all_rows berisi seluruh pegawai, riwayat kosong
        $this->assertCount(1, $res['rows']);
        $this->assertCount(2, $res['all_rows']);
        $this->assertSame([], $res['rows'][0]['riwayat']);
        $this->assertEqualsWithDelta(500000.0, $res['rows'][0]['lk_transport'], 0.01);
        $this->assertEqualsWithDelta(800000.0, $res['rows'][0]['lk_harian'], 0.01);
        $this->assertEqualsWithDelta(200000.0, $res['rows'][0]['lk_penginapan'], 0.01);
        $this->assertEqualsWithDelta(1500000.0, $res['rows'][0]['lk_full'], 0.01);

        // Kegiatan unik per tim vs total semua tim
        $this->assertSame(1, $res['kegiatan_by_tim']['all']);
        $this->assertSame(1, $res['kegiatan_by_tim']['1']);
        $this->assertSame(1, $res['kegiatan_by_tim']['2']);
    }

    public function testExtractPersonRiwayatFiltersByNipSpecialStatusAndCostComponents(): void
    {
        $pesertaRows = [
            [
                'id_perjalanan'          => 3994,
                'status_surat'           => 'Diajukan',
                'jenis_perjalanan_dinas' => 'luar kota',
                'nip_pegawai'            => '19800101 200501 1 001',
                'nama_pegawai'           => 'BUDI SANTOSO',
                'kota_asal'              => 'Kota Bandung',
                'kota'                   => 'Kota Bekasi',
                'tgl_pergi'              => '2026-06-01',
                'tgl_pulang'             => '2026-06-08',
                'transport_jumlah'       => 300000,
                'harian_jumlah'          => 2000000,
                'penginapan_jumlah'      => 500000,
                'jumlah_transfer'        => 2800000,
            ],
            [
                'id_perjalanan'          => 5001,
                'status_surat'           => 'Disetujui',
                'jenis_perjalanan_dinas' => 'dalam kota',
                'nip_pegawai'            => '-',
                'nama_pegawai'           => 'Budi Santoso',
                'kota_asal'              => 'Kota Bandung',
                'kota'                   => 'Kota Bandung',
                'tgl_pergi'              => '2026-07-10',
                'tgl_pulang'             => '2026-07-10',
                'transport_jumlah'       => 170000,
                'harian_jumlah'          => 0,
                'penginapan_jumlah'      => 0,
                'jumlah_transfer'        => 170000,
            ],
            [
                // Pegawai lain -> harus disaring keluar
                'id_perjalanan'          => 5002,
                'status_surat'           => 'Disetujui',
                'nip_pegawai'            => '199999999999999999',
                'nama_pegawai'           => 'Pegawai Lain',
                'tgl_pergi'              => '2026-08-01',
                'tgl_pulang'             => '2026-08-02',
                'jumlah_transfer'        => 1000000,
            ],
        ];

        $riwayat = SinadiMonitoringDlHelper::extractPersonRiwayat(
            $pesertaRows,
            '198001012005011001',
            'Budi Santoso, SKM',
            ['transport' => true, 'harian' => true, 'penginapan' => false],
            true
        );

        $this->assertCount(2, $riwayat);
        // Terurut terbaru lebih dahulu
        $this->assertSame(5001, $riwayat[0]['id_perjalanan']);
        $this->assertSame('dalam_kota', $riwayat[0]['jenis_perjalanan']);
        $this->assertSame(3994, $riwayat[1]['id_perjalanan']);
        $this->assertEqualsWithDelta(0.0, $riwayat[1]['penginapan'], 0.01);
        $this->assertEqualsWithDelta(2300000.0, $riwayat[1]['penghasilan'], 0.01);
    }
}
