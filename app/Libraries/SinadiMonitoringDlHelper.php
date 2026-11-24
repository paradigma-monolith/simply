<?php

namespace App\Libraries;

use DateTimeImmutable;

/**
 * Helper untuk pengelolaan hak akses, filter, pemetaan tim kerja,
 * serta agregasi dan pemeringkatan Monitoring Dinas Luar pada modul SINADI.
 */
class SinadiMonitoringDlHelper
{
    /**
     * Mengecek apakah role pengguna termasuk Super Admin ('Admin').
     */
    public static function isSuperAdmin(?string $role): bool
    {
        return strtolower(trim((string) $role)) === 'admin';
    }

    /**
     * Mengecek apakah pengguna dapat mengakses halaman Monitoring Dinas Luar.
     * Default: hanya Super Admin ('Admin'), atau user yang telah diberi akses_monitoring_dl = 1.
     */
    public static function canAccess(?string $role, ?array $sinadiAksesRow): bool
    {
        if (self::isSuperAdmin($role)) {
            return true;
        }

        return (int) ($sinadiAksesRow['akses_monitoring_dl'] ?? 0) === 1;
    }

    /**
     * Mengecek apakah pengguna berhak mengatur akses user ke Monitoring Dinas Luar.
     */
    public static function canManageAccess(?string $role, ?array $sinadiAksesRow): bool
    {
        if (self::isSuperAdmin($role)) {
            return true;
        }

        return trim((string) ($sinadiAksesRow['status'] ?? '')) === 'Akses Diterima';
    }

    /**
     * Memvalidasi rentang tanggal dengan default awal tahun berjalan s.d. hari berjalan.
     *
     * @return array{start: string, end: string}
     */
    public static function resolveDateRange(?string $start, ?string $end, ?string $todayOverride = null): array
    {
        $today = self::sanitizeYmd((string) $todayOverride);
        if ($today === '') {
            $today = date('Y-m-d');
        }

        $year = substr($today, 0, 4);
        $defaultStart = $year . '-01-01';

        $cleanStart = self::sanitizeYmd((string) $start);
        $cleanEnd = self::sanitizeYmd((string) $end);

        if ($cleanStart === '') {
            $cleanStart = $defaultStart;
        }
        if ($cleanEnd === '') {
            $cleanEnd = $today;
        }

        if ($cleanStart > $cleanEnd) {
            [$cleanStart, $cleanEnd] = [$cleanEnd, $cleanStart];
        }

        return [
            'start' => $cleanStart,
            'end'   => $cleanEnd,
        ];
    }

    /**
     * Menormalkan parameter urutan peringkat ('frekuensi' atau 'penghasilan').
     */
    public static function normalizeSort(?string $sort): string
    {
        return strtolower(trim((string) $sort)) === 'penghasilan' ? 'penghasilan' : 'frekuensi';
    }

    /**
     * Membangun lookup map tim kerja, jabatan, dan golongan pegawai berdasarkan NIP dan nama
     * agar join tidak menggandakan baris DL bila ada NIP ganda pada tabel data_pegawai.
     *
     * @param array<int, array<string, mixed>> $pegawaiRows
     * @return array{by_nip: array<string, array<string, mixed>>, by_name: array<string, array<string, mixed>>}
     */
    public static function buildPegawaiTimMap(array $pegawaiRows): array
    {
        $byNip = [];
        $byName = [];

        foreach ($pegawaiRows as $row) {
            $nip = self::normalizeNip((string) ($row['nip'] ?? ''));
            $namaKey = self::normalizeNameKey((string) ($row['nama'] ?? ''));
            $info = [
                'id_tim_kerja'   => isset($row['id_tim_kerja']) && $row['id_tim_kerja'] !== '' ? (string) $row['id_tim_kerja'] : '',
                'nama_tim_kerja' => trim((string) ($row['nama_tim_kerja'] ?? '')),
                'jabatan'        => trim((string) ($row['jabatan'] ?? '')),
                'golongan'       => trim((string) ($row['golongan'] ?? '')),
                'nama'           => trim((string) ($row['nama'] ?? '')),
                'nip'            => trim((string) ($row['nip'] ?? '')),
            ];

            if ($nip !== '' && !isset($byNip[$nip])) {
                $byNip[$nip] = $info;
            }
            if ($namaKey !== '' && !isset($byName[$namaKey])) {
                $byName[$namaKey] = $info;
            }
        }

        return [
            'by_nip'  => $byNip,
            'by_name' => $byName,
        ];
    }

    /**
     * Mengagregasi data peserta Dinas Luar menjadi peringkat perorangan beserta ringkasan dan riwayat.
     *
     * @param array<int, array<string, mixed>> $pesertaRows
     * @param array{by_nip?: array<string, array<string, mixed>>, by_name?: array<string, array<string, mixed>>} $pegawaiTimMap
     * @return array{
     *   rows: array<int, array<string, mixed>>,
     *   summary: array<string, mixed>,
     *   top_frekuensi: array<string, mixed>|null,
     *   top_penghasilan: array<string, mixed>|null
     * }
     */
    public static function aggregate(
        array $pesertaRows,
        array $pegawaiTimMap,
        string $timKerjaFilter = 'all',
        string $sort = 'frekuensi'
    ): array {
        $sortMode = self::normalizeSort($sort);
        $timFilter = trim($timKerjaFilter);
        if ($timFilter === '') {
            $timFilter = 'all';
        }

        $byNip = $pegawaiTimMap['by_nip'] ?? [];
        $byName = $pegawaiTimMap['by_name'] ?? [];

        $grouped = [];
        $uniquePerjalananAll = [];

        foreach ($pesertaRows as $row) {
            $rawNip = trim((string) ($row['nip_pegawai'] ?? ''));
            $normNip = self::normalizeNip($rawNip);
            $rawNama = trim((string) ($row['nama_pegawai'] ?? ''));
            $normNama = self::normalizeNameKey($rawNama);

            if ($normNip === '' && $normNama === '') {
                continue;
            }

            $personKey = $normNip !== '' ? ('nip:' . $normNip) : ('name:' . $normNama);

            $timInfo = null;
            if ($normNip !== '' && isset($byNip[$normNip])) {
                $timInfo = $byNip[$normNip];
            } elseif ($normNama !== '' && isset($byName[$normNama])) {
                $timInfo = $byName[$normNama];
            }

            $idTim = (string) ($timInfo['id_tim_kerja'] ?? '');
            $namaTim = trim((string) ($timInfo['nama_tim_kerja'] ?? ''));
            if ($namaTim === '') {
                $namaTim = 'Tanpa Tim Kerja';
            }

            if ($timFilter !== 'all') {
                if ($timFilter === 'none') {
                    if ($idTim !== '') {
                        continue;
                    }
                } elseif ($idTim !== $timFilter) {
                    continue;
                }
            }

            $idPerjalanan = (int) ($row['id_perjalanan'] ?? 0);
            $tglPergi = self::sanitizeYmd((string) ($row['tgl_pergi'] ?? ''));
            if ($tglPergi === '') {
                $tglPergi = self::sanitizeYmd((string) ($row['tgl_pelaksanaan'] ?? ''));
            }
            $tglPulang = self::sanitizeYmd((string) ($row['tgl_pulang'] ?? ''));
            if ($tglPulang === '') {
                $tglPulang = self::sanitizeYmd((string) ($row['tgl_pelaksanaan_akhir'] ?? ''));
            }
            if ($tglPulang === '' || ($tglPergi !== '' && $tglPulang < $tglPergi)) {
                $tglPulang = $tglPergi;
            }

            $hari = self::calculateDays($tglPergi, $tglPulang);
            $transport = (float) ($row['transport_jumlah'] ?? 0);
            $harian = (float) ($row['harian_jumlah'] ?? 0);
            $penginapan = (float) ($row['penginapan_jumlah'] ?? 0);
            $transfer = (float) ($row['jumlah_transfer'] ?? 0);
            $penghasilan = $transfer > 0 ? $transfer : ($transport + $harian + $penginapan);

            if (!isset($grouped[$personKey])) {
                $grouped[$personKey] = [
                    'person_key'            => $personKey,
                    'nip'                   => $rawNip !== '' ? $rawNip : (string) ($timInfo['nip'] ?? ''),
                    'nama'                  => $rawNama !== '' ? $rawNama : (string) ($timInfo['nama'] ?? '-'),
                    'jabatan'               => trim((string) ($row['jabatan_pegawai'] ?? '')) !== '' ? trim((string) $row['jabatan_pegawai']) : (string) ($timInfo['jabatan'] ?? ''),
                    'golongan'              => trim((string) ($row['golongan'] ?? '')) !== '' ? trim((string) $row['golongan']) : (string) ($timInfo['golongan'] ?? ''),
                    'id_tim_kerja'          => $idTim,
                    'nama_tim_kerja'        => $namaTim,
                    'jumlah_dl'             => 0,
                    'total_hari'            => 0,
                    'total_transport'       => 0.0,
                    'total_harian'          => 0.0,
                    'total_penginapan'      => 0.0,
                    'total_penghasilan'     => 0.0,
                    'rata_rata_penghasilan' => 0.0,
                    'dl_terakhir'           => '',
                    'riwayat'               => [],
                    '_seen_perjalanan'      => [],
                ];
            } else {
                // Gunakan nama/jabatan terbaru bila baris sebelumnya kosong
                if ($grouped[$personKey]['jabatan'] === '' && trim((string) ($row['jabatan_pegawai'] ?? '')) !== '') {
                    $grouped[$personKey]['jabatan'] = trim((string) $row['jabatan_pegawai']);
                }
                if ($grouped[$personKey]['golongan'] === '' && trim((string) ($row['golongan'] ?? '')) !== '') {
                    $grouped[$personKey]['golongan'] = trim((string) $row['golongan']);
                }
            }

            if ($idPerjalanan > 0) {
                $uniquePerjalananAll[$idPerjalanan] = true;
            }

            // Cegah double counting bila satu pegawai muncul lebih dari satu baris pada id_perjalanan yang sama
            $tripKey = $idPerjalanan > 0
                ? ('id:' . $idPerjalanan)
                : ('row:' . $tglPergi . '|' . $tglPulang . '|' . trim((string) ($row['nama_kegiatan'] ?? '')));

            if (!isset($grouped[$personKey]['_seen_perjalanan'][$tripKey])) {
                $grouped[$personKey]['_seen_perjalanan'][$tripKey] = true;
                $grouped[$personKey]['jumlah_dl']++;
                $grouped[$personKey]['total_hari'] += $hari;
            }

            $grouped[$personKey]['total_transport'] += $transport;
            $grouped[$personKey]['total_harian'] += $harian;
            $grouped[$personKey]['total_penginapan'] += $penginapan;
            $grouped[$personKey]['total_penghasilan'] += $penghasilan;

            $refDate = $tglPulang !== '' ? $tglPulang : $tglPergi;
            if ($refDate !== '' && ($grouped[$personKey]['dl_terakhir'] === '' || $refDate > $grouped[$personKey]['dl_terakhir'])) {
                $grouped[$personKey]['dl_terakhir'] = $refDate;
            }

            $grouped[$personKey]['riwayat'][] = [
                'id_perjalanan'     => $idPerjalanan,
                'nama_kegiatan'     => trim((string) ($row['nama_kegiatan'] ?? '')),
                'tempat_perjalanan' => trim((string) ($row['kota'] ?? ($row['tempat_perjalanan'] ?? ''))),
                'kota_asal'         => trim((string) ($row['kota_asal'] ?? '')),
                'tgl_pergi'         => $tglPergi,
                'tgl_pulang'        => $tglPulang,
                'hari'              => $hari,
                'transport'         => $transport,
                'harian'            => $harian,
                'penginapan'        => $penginapan,
                'penghasilan'       => $penghasilan,
                'status_surat'      => trim((string) ($row['status_surat'] ?? '')),
                'sumber_anggaran'   => trim((string) ($row['sumber_anggaran'] ?? '')),
            ];
        }

        $items = [];
        $summary = [
            'total_pegawai'     => 0,
            'total_dl_kegiatan' => count($uniquePerjalananAll),
            'total_penugasan'   => 0,
            'total_hari'        => 0,
            'total_transport'   => 0.0,
            'total_harian'      => 0.0,
            'total_penginapan'  => 0.0,
            'total_penghasilan' => 0.0,
        ];

        foreach ($grouped as $item) {
            unset($item['_seen_perjalanan']);
            $item['rata_rata_penghasilan'] = $item['jumlah_dl'] > 0
                ? ($item['total_penghasilan'] / $item['jumlah_dl'])
                : 0.0;

            usort($item['riwayat'], static function (array $a, array $b): int {
                return strcmp($b['tgl_pergi'], $a['tgl_pergi'])
                    ?: ($b['id_perjalanan'] <=> $a['id_perjalanan']);
            });

            $summary['total_pegawai']++;
            $summary['total_penugasan'] += $item['jumlah_dl'];
            $summary['total_hari'] += $item['total_hari'];
            $summary['total_transport'] += $item['total_transport'];
            $summary['total_harian'] += $item['total_harian'];
            $summary['total_penginapan'] += $item['total_penginapan'];
            $summary['total_penghasilan'] += $item['total_penghasilan'];

            $items[] = $item;
        }

        // Hitung peringkat frekuensi masing-masing pegawai
        usort($items, static fn (array $a, array $b): int => self::compareFrekuensi($a, $b));
        foreach ($items as $idx => &$item) {
            $item['rank_frekuensi'] = $idx + 1;
        }
        unset($item);
        $topFrekuensi = $items[0] ?? null;

        // Hitung peringkat penghasilan masing-masing pegawai
        usort($items, static fn (array $a, array $b): int => self::comparePenghasilan($a, $b));
        foreach ($items as $idx => &$item) {
            $item['rank_penghasilan'] = $idx + 1;
        }
        unset($item);
        $topPenghasilan = $items[0] ?? null;

        // Terapkan urutan utama sesuai parameter $sortMode
        if ($sortMode === 'frekuensi') {
            usort($items, static fn (array $a, array $b): int => self::compareFrekuensi($a, $b));
        }

        foreach ($items as $idx => &$item) {
            $item['rank'] = $idx + 1;
        }
        unset($item);

        // Sinkronkan juga top_frekuensi & top_penghasilan setelah rank terisi lengkap
        foreach ($items as $item) {
            if (($item['rank_frekuensi'] ?? 0) === 1) {
                $topFrekuensi = $item;
            }
            if (($item['rank_penghasilan'] ?? 0) === 1) {
                $topPenghasilan = $item;
            }
        }

        return [
            'rows'            => $items,
            'summary'         => $summary,
            'top_frekuensi'   => $topFrekuensi,
            'top_penghasilan' => $topPenghasilan,
        ];
    }

    private static function compareFrekuensi(array $a, array $b): int
    {
        return ($b['jumlah_dl'] <=> $a['jumlah_dl'])
            ?: ($b['total_penghasilan'] <=> $a['total_penghasilan'])
            ?: ($b['total_hari'] <=> $a['total_hari'])
            ?: strcasecmp((string) $a['nama'], (string) $b['nama']);
    }

    private static function comparePenghasilan(array $a, array $b): int
    {
        return ($b['total_penghasilan'] <=> $a['total_penghasilan'])
            ?: ($b['jumlah_dl'] <=> $a['jumlah_dl'])
            ?: ($b['total_hari'] <=> $a['total_hari'])
            ?: strcasecmp((string) $a['nama'], (string) $b['nama']);
    }

    private static function calculateDays(string $startYmd, string $endYmd): int
    {
        if ($startYmd === '' || $endYmd === '') {
            return 1;
        }

        try {
            $start = new DateTimeImmutable($startYmd);
            $end = new DateTimeImmutable($endYmd);
            if ($end < $start) {
                return 1;
            }
            return (int) $start->diff($end)->days + 1;
        } catch (\Throwable $e) {
            return 1;
        }
    }

    private static function normalizeNip(string $nip): string
    {
        return preg_replace('/\s+/', '', trim($nip)) ?? '';
    }

    private static function normalizeNameKey(string $nama): string
    {
        $clean = preg_replace('/\s+/', ' ', trim($nama)) ?? '';
        return strtolower($clean);
    }

    private static function sanitizeYmd(string $value): string
    {
        $trimmed = trim($value);
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $trimmed) !== 1) {
            return '';
        }

        $parts = explode('-', $trimmed);
        if (!checkdate((int) $parts[1], (int) $parts[2], (int) $parts[0])) {
            return '';
        }

        return $trimmed;
    }
}
