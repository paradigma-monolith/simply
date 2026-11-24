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
            ['id_perjalanan' => 10, 'nip_pegawai' => '19800101', 'nama_pegawai' => 'Budi Santoso', 'tgl_pergi' => '2026-02-01', 'tgl_pulang' => '2026-02-02', 'transport_jumlah' => 500000, 'harian_jumlah' => 800000, 'penginapan_jumlah' => 200000, 'jumlah_transfer' => 1500000],
            ['id_perjalanan' => 10, 'nip_pegawai' => '19800101', 'nama_pegawai' => 'Budi Santoso', 'tgl_pergi' => '2026-02-01', 'tgl_pulang' => '2026-02-02', 'transport_jumlah' => 0, 'harian_jumlah' => 0, 'penginapan_jumlah' => 0, 'jumlah_transfer' => 0],
            ['id_perjalanan' => 11, 'nip_pegawai' => '19800101', 'nama_pegawai' => 'Budi Santoso', 'tgl_pergi' => '2026-04-10', 'tgl_pulang' => '2026-04-10', 'transport_jumlah' => 300000, 'harian_jumlah' => 700000, 'penginapan_jumlah' => 0, 'jumlah_transfer' => 1000000],
            ['id_perjalanan' => 12, 'nip_pegawai' => '19800101', 'nama_pegawai' => 'Budi Santoso', 'tgl_pergi' => '2026-06-05', 'tgl_pulang' => '2026-06-07', 'transport_jumlah' => 600000, 'harian_jumlah' => 900000, 'penginapan_jumlah' => 500000, 'jumlah_transfer' => 2000000],

            // Siti: 2 DL, total penghasilan 6.200.000 (jumlah_transfer 0 di DL kedua -> fallback sum komponen)
            ['id_perjalanan' => 20, 'nip_pegawai' => '19810202', 'nama_pegawai' => 'Siti Aminah', 'tgl_pergi' => '2026-03-01', 'tgl_pulang' => '2026-03-04', 'transport_jumlah' => 1000000, 'harian_jumlah' => 1500000, 'penginapan_jumlah' => 1200000, 'jumlah_transfer' => 3700000],
            ['id_perjalanan' => 21, 'nip_pegawai' => '19810202', 'nama_pegawai' => 'Siti Aminah', 'tgl_pergi' => '2026-05-12', 'tgl_pulang' => '2026-05-14', 'transport_jumlah' => 800000, 'harian_jumlah' => 1000000, 'penginapan_jumlah' => 700000, 'jumlah_transfer' => 0],

            // Andi: 1 DL, total penghasilan 1.200.000
            ['id_perjalanan' => 30, 'nip_pegawai' => '19820303', 'nama_pegawai' => 'Andi Wijaya', 'tgl_pergi' => '2026-07-01', 'tgl_pulang' => '2026-07-02', 'transport_jumlah' => 400000, 'harian_jumlah' => 800000, 'penginapan_jumlah' => 0, 'jumlah_transfer' => 1200000],
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
}
