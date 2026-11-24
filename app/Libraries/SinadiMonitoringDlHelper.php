<?php

namespace App\Libraries;

use DateTimeImmutable;

/**
 * Helper untuk pengelolaan hak akses, filter, pemetaan tim kerja,
 * serta agregasi dan pemeringkatan Monitoring Dinas Luar pada modul SINADI.
 */
class SinadiMonitoringDlHelper
{
    public const SUPER_ADMIN_USERNAME = '199404082022031001';
    public const SUPER_ADMIN_NAME = 'Mohamad Dahlan Fazhry';

    /**
     * Mengecek apakah username pengguna merupakan Super Admin khusus Monitoring Dinas Luar
     * (hanya Mohamad Dahlan Fazhry / NIP 199404082022031001).
     */
    public static function isSuperAdmin(?string $username): bool
    {
        return self::normalizeNip((string) $username) === self::SUPER_ADMIN_USERNAME;
    }

    /**
     * Mengecek apakah pengguna dapat mengakses halaman Monitoring Dinas Luar.
     * Default: hanya Super Admin khusus Monitoring DL (199404082022031001),
     * atau user lain yang telah diberi izin eksplisit akses_monitoring_dl = 1.
     */
    public static function canAccess(?string $username, ?array $sinadiAksesRow): bool
    {
        if (self::isSuperAdmin($username)) {
            return true;
        }

        return (int) ($sinadiAksesRow['akses_monitoring_dl'] ?? 0) === 1;
    }

    /**
     * Mengecek apakah pengguna berhak mengatur akses user ke Monitoring Dinas Luar.
     * Khusus Monitoring DL hanya Super Admin 199404082022031001 (Mohamad Dahlan Fazhry).
     */
    public static function canManageAccess(?string $username, ?array $sinadiAksesRow = null): bool
    {
        return self::isSuperAdmin($username);
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
     * Menormalkan parameter urutan peringkat ('penghasilan' atau 'frekuensi').
     * Default saat pertama dibuka adalah 'penghasilan' (Penghasilan Terbanyak).
     */
    public static function normalizeSort(?string $sort): string
    {
        return strtolower(trim((string) $sort)) === 'frekuensi' ? 'frekuensi' : 'penghasilan';
    }

    /**
     * Mengecek apakah status surat perjalanan dinas berstatus Disetujui.
     */
    public static function isApprovedStatus(?string $status): bool
    {
        return strtolower(trim((string) $status)) === 'disetujui';
    }

    /**
     * Menormalkan ceklis komponen biaya (Transport, Uang Harian, Penginapan) dari query parameter.
     * Default saat pertama dibuka (biaya_applied tidak ada): Transport & Uang Harian aktif, Penginapan unchecked.
     *
     * @return array{transport: bool, harian: bool, penginapan: bool}
     */
    public static function resolveCostComponents(array $queryParams): array
    {
        $applied = !empty($queryParams['biaya_applied']) && (string) $queryParams['biaya_applied'] !== '0';
        if (!$applied) {
            return [
                'transport'  => true,
                'harian'     => true,
                'penginapan' => false,
            ];
        }

        return [
            'transport'  => isset($queryParams['inc_transport']) && (string) $queryParams['inc_transport'] !== '0',
            'harian'     => isset($queryParams['inc_harian']) && (string) $queryParams['inc_harian'] !== '0',
            'penginapan' => isset($queryParams['inc_penginapan']) && (string) $queryParams['inc_penginapan'] !== '0',
        ];
    }

    /**
     * Memetakan nama Tim Kerja mentah dari database ke label tampilan yang sesuai pada Monitoring DL.
     */
    public static function formatTimKerjaName(?string $namaTim): string
    {
        $clean = preg_replace('/\s+/', ' ', trim((string) $namaTim)) ?? '';
        if ($clean === '') {
            return 'Tanpa Tim Kerja';
        }

        $lower = strtolower($clean);
        if ($lower === 'default') {
            return 'Kepala Balai Kekarantinaan Kesehatan Kelas I Bandung';
        }
        if ($lower === 'sub adum') {
            return 'Tim Kerja Dukungan Manajemen';
        }

        return $clean;
    }

    /**
     * Mengklasifikasikan perjalanan dinas sebagai 'dalam_kota' atau 'luar_kota'.
     */
    public static function classifyJenisPerjalanan(array $row): string
    {
        $jenis = strtolower(trim((string) ($row['jenis_perjalanan_dinas'] ?? '')));
        if ($jenis !== '') {
            if (strpos($jenis, 'dalam') !== false) {
                return 'dalam_kota';
            }
            if (strpos($jenis, 'luar') !== false) {
                return 'luar_kota';
            }
        }

        $kota = strtolower(trim((string) ($row['kota'] ?? '')));
        $kotaAsal = strtolower(trim((string) ($row['kota_asal'] ?? '')));
        if ($kota !== '' && $kotaAsal !== '' && $kota === $kotaAsal) {
            return 'dalam_kota';
        }

        return 'luar_kota';
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
            $rawTimNama = trim((string) ($row['nama_tim_kerja'] ?? ''));
            $info = [
                'id_tim_kerja'   => isset($row['id_tim_kerja']) && $row['id_tim_kerja'] !== '' ? (string) $row['id_tim_kerja'] : '',
                'nama_tim_kerja' => $rawTimNama !== '' ? self::formatTimKerjaName($rawTimNama) : '',
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
    /**
     * Memuat daftar pegawai sesuai DUK.
     * Jika file DUK PEGAWAI.xlsx tersedia di root proyek, baca secara dinamis (dengan cache),
     * dan jika tidak ada, gunakan fallback konfigurasi App\Config\SinadiDukPegawai::ITEMS.
     */
    public static function loadDukRows(?string $xlsxPath = null): array
    {
        $path = $xlsxPath ?? (ROOTPATH . 'DUK PEGAWAI.xlsx');

        if (is_file($path) && is_readable($path)) {
            $mtime = (string) (@filemtime($path) ?: '0');
            $cacheKey = 'sinadi_duk_pegawai_xlsx_' . md5($path . '|' . $mtime);

            if (function_exists('cache')) {
                $cached = cache($cacheKey);
                if (is_array($cached) && !empty($cached)) {
                    return $cached;
                }
            }

            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
                $sheet = $spreadsheet->getSheetByName('LAPORAN DUK') ?? $spreadsheet->getActiveSheet();
                $highestRow = $sheet->getHighestDataRow();
                $rows = [];

                for ($r = 8; $r <= $highestRow; $r++) {
                    $rawNama = (string) $sheet->getCell("B{$r}")->getValue();
                    $rawNip  = (string) $sheet->getCell("C{$r}")->getValue();
                    $rawGol  = (string) $sheet->getCell("D{$r}")->getValue();
                    $rawJab  = (string) $sheet->getCell("F{$r}")->getValue();

                    $lines = preg_split('/\r\n|\r|\n/', trim($rawNama));
                    $nama = preg_replace('/\s+/', ' ', trim((string) ($lines[0] ?? ''))) ?? '';
                    $nip = self::normalizeNip($rawNip);

                    if ($nip === '' || $nama === '') {
                        continue;
                    }

                    $rows[] = [
                        'nip'      => $nip,
                        'nama'     => $nama,
                        'golongan' => preg_replace('/\s+/', ' ', trim($rawGol)) ?? '',
                        'jabatan'  => preg_replace('/\s+/', ' ', trim($rawJab)) ?? '',
                    ];
                }

                if (!empty($rows)) {
                    if (function_exists('cache')) {
                        cache()->save($cacheKey, $rows, 86400);
                    }
                    return $rows;
                }
            } catch (\Throwable $e) {
                if (function_exists('log_message')) {
                    log_message('warning', 'Gagal membaca DUK PEGAWAI.xlsx, fallback ke SinadiDukPegawai: ' . $e->getMessage());
                }
            }
        }

        return \App\Config\SinadiDukPegawai::ITEMS;
    }

    /**
     * Membangun indeks whitelist pegawai DUK berdasarkan NIP 18 digit, nama lengkap, dan nama dasar tanpa gelar.
     */
    public static function buildDukWhitelist(array $dukRows): array
    {
        $byNip = [];
        $byName = [];
        $byBaseName = [];
        $ambiguousBase = [];

        foreach ($dukRows as $row) {
            $nip = self::normalizeNip((string) ($row['nip'] ?? ''));
            $nama = preg_replace('/\s+/', ' ', trim((string) ($row['nama'] ?? ''))) ?? '';
            if ($nip === '' && $nama === '') {
                continue;
            }

            $entry = [
                'nip'      => $nip,
                'nama'     => $nama,
                'golongan' => trim((string) ($row['golongan'] ?? '')),
                'jabatan'  => trim((string) ($row['jabatan'] ?? '')),
            ];

            if ($nip !== '' && !isset($byNip[$nip])) {
                $byNip[$nip] = $entry;
            }

            $normName = self::normalizeNameKey($nama);
            if ($normName !== '' && !isset($byName[$normName])) {
                $byName[$normName] = $entry;
            }

            $baseName = self::normalizeBaseName($nama);
            if ($baseName !== '') {
                if (isset($byBaseName[$baseName]) && $byBaseName[$baseName]['nip'] !== $nip) {
                    $ambiguousBase[$baseName] = true;
                } else {
                    $byBaseName[$baseName] = $entry;
                }
            }
        }

        foreach (array_keys($ambiguousBase) as $dupBase) {
            unset($byBaseName[$dupBase]);
        }

        return [
            'by_nip'       => $byNip,
            'by_name'      => $byName,
            'by_base_name' => $byBaseName,
            'total'        => count($byNip) > 0 ? count($byNip) : count($byName),
        ];
    }

    public static function aggregate(
        array $pesertaRows,
        array $pegawaiTimMap,
        string $timKerjaFilter = 'all',
        string $sort = 'penghasilan',
        ?array $dukWhitelist = null,
        bool $onlyApproved = true,
        ?array $costComponents = null
    ): array {
        $sortMode = self::normalizeSort($sort);
        $timFilter = trim($timKerjaFilter);
        if ($timFilter === '') {
            $timFilter = 'all';
        }

        $incTransport = $costComponents === null ? true : !empty($costComponents['transport']);
        $incHarian = $costComponents === null ? true : !empty($costComponents['harian']);
        $incPenginapan = $costComponents === null ? true : !empty($costComponents['penginapan']);
        $allComponentsEnabled = $incTransport && $incHarian && $incPenginapan;

        $byNip = $pegawaiTimMap['by_nip'] ?? [];
        $byName = $pegawaiTimMap['by_name'] ?? [];
        $dukByNip = $dukWhitelist['by_nip'] ?? [];
        $dukByName = $dukWhitelist['by_name'] ?? [];
        $dukByBase = $dukWhitelist['by_base_name'] ?? [];

        $allGrouped = [];

        foreach ($pesertaRows as $row) {
            if ($onlyApproved && !self::isApprovedStatus((string) ($row['status_surat'] ?? ''))) {
                continue;
            }

            $rawNip = trim((string) ($row['nip_pegawai'] ?? ''));
            $normNip = self::normalizeNip($rawNip);
            $rawNama = trim((string) ($row['nama_pegawai'] ?? ''));
            $normNama = self::normalizeNameKey($rawNama);

            if ($normNip === '' && $normNama === '') {
                continue;
            }

            $dukInfo = null;
            if ($dukWhitelist !== null) {
                if ($normNip !== '' && isset($dukByNip[$normNip])) {
                    $dukInfo = $dukByNip[$normNip];
                } elseif ($normNama !== '' && isset($dukByName[$normNama])) {
                    $dukInfo = $dukByName[$normNama];
                } else {
                    $baseName = self::normalizeBaseName($rawNama);
                    if ($baseName !== '' && isset($dukByBase[$baseName])) {
                        $dukInfo = $dukByBase[$baseName];
                    }
                }

                if ($dukInfo === null) {
                    continue;
                }

                if (!empty($dukInfo['nip'])) {
                    $normNip = (string) $dukInfo['nip'];
                }
                if (!empty($dukInfo['nama'])) {
                    $normNama = self::normalizeNameKey((string) $dukInfo['nama']);
                }
            }

            $personKey = $normNip !== '' ? ('nip:' . $normNip) : ('name:' . $normNama);

            $timInfo = null;
            if ($normNip !== '' && isset($byNip[$normNip])) {
                $timInfo = $byNip[$normNip];
            } elseif ($normNama !== '' && isset($byName[$normNama])) {
                $timInfo = $byName[$normNama];
            }

            $idTim = (string) ($timInfo['id_tim_kerja'] ?? '');
            $namaTim = self::formatTimKerjaName((string) ($timInfo['nama_tim_kerja'] ?? ''));

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

            $effTransport = $incTransport ? $transport : 0.0;
            $effHarian = $incHarian ? $harian : 0.0;
            $effPenginapan = $incPenginapan ? $penginapan : 0.0;
            if ($allComponentsEnabled) {
                $penghasilan = $transfer > 0 ? $transfer : ($transport + $harian + $penginapan);
            } else {
                $penghasilan = $effTransport + $effHarian + $effPenginapan;
            }

            $jenisPerjalanan = self::classifyJenisPerjalanan($row);

            if (!isset($allGrouped[$personKey])) {
                $displayNip = $dukInfo !== null && ($dukInfo['nip'] ?? '') !== ''
                    ? (string) $dukInfo['nip']
                    : ($normNip !== '' ? $normNip : ($rawNip !== '' ? $rawNip : (string) ($timInfo['nip'] ?? '')));
                $displayNama = $dukInfo !== null && ($dukInfo['nama'] ?? '') !== ''
                    ? (string) $dukInfo['nama']
                    : ($rawNama !== '' ? $rawNama : (string) ($timInfo['nama'] ?? '-'));
                $rowJab = trim((string) ($row['jabatan_pegawai'] ?? ''));
                $displayJab = ($dukInfo !== null && ($dukInfo['jabatan'] ?? '') !== '')
                    ? (string) $dukInfo['jabatan']
                    : ($rowJab !== '' ? $rowJab : (string) ($timInfo['jabatan'] ?? ''));
                $rowGol = trim((string) ($row['golongan'] ?? ''));
                $displayGol = ($dukInfo !== null && ($dukInfo['golongan'] ?? '') !== '')
                    ? (string) $dukInfo['golongan']
                    : ($rowGol !== '' ? $rowGol : (string) ($timInfo['golongan'] ?? ''));

                $allGrouped[$personKey] = [
                    'person_key'             => $personKey,
                    'nip'                    => $displayNip,
                    'nama'                   => $displayNama,
                    'jabatan'                => $displayJab,
                    'golongan'               => $displayGol,
                    'id_tim_kerja'           => $idTim,
                    'nama_tim_kerja'         => $namaTim,
                    'jumlah_dl'              => 0,
                    'jumlah_dl_luar_kota'    => 0,
                    'jumlah_dl_dalam_kota'   => 0,
                    'total_hari'             => 0,
                    'total_transport'        => 0.0,
                    'total_harian'           => 0.0,
                    'total_penginapan'       => 0.0,
                    'total_penghasilan'      => 0.0,
                    'penghasilan_luar_kota'  => 0.0,
                    'penghasilan_dalam_kota' => 0.0,
                    'rata_rata_penghasilan'  => 0.0,
                    'dl_terakhir'            => '',
                    'riwayat'                => [],
                    '_seen_perjalanan'       => [],
                    '_perjalanan_ids'        => [],
                ];
            } else {
                // Gunakan nama/jabatan terbaru bila baris sebelumnya kosong
                if ($allGrouped[$personKey]['jabatan'] === '' && trim((string) ($row['jabatan_pegawai'] ?? '')) !== '') {
                    $allGrouped[$personKey]['jabatan'] = trim((string) $row['jabatan_pegawai']);
                }
                if ($allGrouped[$personKey]['golongan'] === '' && trim((string) ($row['golongan'] ?? '')) !== '') {
                    $allGrouped[$personKey]['golongan'] = trim((string) $row['golongan']);
                }
            }

            if ($idPerjalanan > 0) {
                $allGrouped[$personKey]['_perjalanan_ids'][$idPerjalanan] = true;
            }

            // Cegah double counting bila satu pegawai muncul lebih dari satu baris pada id_perjalanan yang sama
            $tripKey = $idPerjalanan > 0
                ? ('id:' . $idPerjalanan)
                : ('row:' . $tglPergi . '|' . $tglPulang . '|' . trim((string) ($row['nama_kegiatan'] ?? '')));

            if (!isset($allGrouped[$personKey]['_seen_perjalanan'][$tripKey])) {
                $allGrouped[$personKey]['_seen_perjalanan'][$tripKey] = true;
                $allGrouped[$personKey]['jumlah_dl']++;
                $allGrouped[$personKey]['total_hari'] += $hari;
                if ($jenisPerjalanan === 'dalam_kota') {
                    $allGrouped[$personKey]['jumlah_dl_dalam_kota']++;
                } else {
                    $allGrouped[$personKey]['jumlah_dl_luar_kota']++;
                }
            }

            $allGrouped[$personKey]['total_transport'] += $effTransport;
            $allGrouped[$personKey]['total_harian'] += $effHarian;
            $allGrouped[$personKey]['total_penginapan'] += $effPenginapan;
            $allGrouped[$personKey]['total_penghasilan'] += $penghasilan;
            if ($jenisPerjalanan === 'dalam_kota') {
                $allGrouped[$personKey]['penghasilan_dalam_kota'] += $penghasilan;
            } else {
                $allGrouped[$personKey]['penghasilan_luar_kota'] += $penghasilan;
            }

            $refDate = $tglPulang !== '' ? $tglPulang : $tglPergi;
            if ($refDate !== '' && ($allGrouped[$personKey]['dl_terakhir'] === '' || $refDate > $allGrouped[$personKey]['dl_terakhir'])) {
                $allGrouped[$personKey]['dl_terakhir'] = $refDate;
            }

            $allGrouped[$personKey]['riwayat'][] = [
                'id_perjalanan'     => $idPerjalanan,
                'jenis_perjalanan'  => $jenisPerjalanan,
                'nama_kegiatan'     => trim((string) ($row['nama_kegiatan'] ?? '')),
                'tempat_perjalanan' => trim((string) ($row['kota'] ?? ($row['tempat_perjalanan'] ?? ''))),
                'kota_asal'         => trim((string) ($row['kota_asal'] ?? '')),
                'tgl_pergi'         => $tglPergi,
                'tgl_pulang'        => $tglPulang,
                'hari'              => $hari,
                'transport'         => $effTransport,
                'harian'            => $effHarian,
                'penginapan'        => $effPenginapan,
                'penghasilan'       => $penghasilan,
                'status_surat'      => trim((string) ($row['status_surat'] ?? '')),
                'sumber_anggaran'   => trim((string) ($row['sumber_anggaran'] ?? '')),
            ];
        }

        // Bangun ringkasan total penghasilan per Tim Kerja (dari semua pegawai DUK & Disetujui pada periode filter)
        $timKerjaBuckets = [];
        $timKerjaGrandTotal = [
            'total_pegawai'              => 0,
            'total_penugasan'            => 0,
            'total_penugasan_luar_kota'  => 0,
            'total_penugasan_dalam_kota' => 0,
            'total_hari'                 => 0,
            'total_transport'            => 0.0,
            'total_harian'               => 0.0,
            'total_penginapan'           => 0.0,
            'total_penghasilan'          => 0.0,
            'total_penghasilan_luar_kota'  => 0.0,
            'total_penghasilan_dalam_kota' => 0.0,
        ];

        $items = [];
        $uniquePerjalananFiltered = [];
        $summary = [
            'total_pegawai'                => 0,
            'total_dl_kegiatan'            => 0,
            'total_penugasan'              => 0,
            'total_penugasan_luar_kota'    => 0,
            'total_penugasan_dalam_kota'   => 0,
            'total_hari'                   => 0,
            'total_transport'              => 0.0,
            'total_harian'                 => 0.0,
            'total_penginapan'             => 0.0,
            'total_penghasilan'            => 0.0,
            'total_penghasilan_luar_kota'  => 0.0,
            'total_penghasilan_dalam_kota' => 0.0,
        ];

        foreach ($allGrouped as $item) {
            $idTim = (string) ($item['id_tim_kerja'] ?? '');
            $filterKey = $idTim !== '' ? $idTim : 'none';
            $namaTim = (string) ($item['nama_tim_kerja'] ?? 'Tanpa Tim Kerja');

            if (!isset($timKerjaBuckets[$filterKey])) {
                $timKerjaBuckets[$filterKey] = [
                    'id_tim_kerja'                 => $idTim,
                    'filter_key'                   => $filterKey,
                    'nama_tim_kerja'               => $namaTim,
                    'total_pegawai'                => 0,
                    'total_penugasan'              => 0,
                    'total_penugasan_luar_kota'    => 0,
                    'total_penugasan_dalam_kota'   => 0,
                    'total_hari'                   => 0,
                    'total_transport'              => 0.0,
                    'total_harian'                 => 0.0,
                    'total_penginapan'             => 0.0,
                    'total_penghasilan'            => 0.0,
                    'total_penghasilan_luar_kota'  => 0.0,
                    'total_penghasilan_dalam_kota' => 0.0,
                    'persentase'                   => 0.0,
                ];
            }

            $timKerjaBuckets[$filterKey]['total_pegawai']++;
            $timKerjaBuckets[$filterKey]['total_penugasan'] += (int) $item['jumlah_dl'];
            $timKerjaBuckets[$filterKey]['total_penugasan_luar_kota'] += (int) $item['jumlah_dl_luar_kota'];
            $timKerjaBuckets[$filterKey]['total_penugasan_dalam_kota'] += (int) $item['jumlah_dl_dalam_kota'];
            $timKerjaBuckets[$filterKey]['total_hari'] += (int) $item['total_hari'];
            $timKerjaBuckets[$filterKey]['total_transport'] += (float) $item['total_transport'];
            $timKerjaBuckets[$filterKey]['total_harian'] += (float) $item['total_harian'];
            $timKerjaBuckets[$filterKey]['total_penginapan'] += (float) $item['total_penginapan'];
            $timKerjaBuckets[$filterKey]['total_penghasilan'] += (float) $item['total_penghasilan'];
            $timKerjaBuckets[$filterKey]['total_penghasilan_luar_kota'] += (float) $item['penghasilan_luar_kota'];
            $timKerjaBuckets[$filterKey]['total_penghasilan_dalam_kota'] += (float) $item['penghasilan_dalam_kota'];

            $timKerjaGrandTotal['total_pegawai']++;
            $timKerjaGrandTotal['total_penugasan'] += (int) $item['jumlah_dl'];
            $timKerjaGrandTotal['total_penugasan_luar_kota'] += (int) $item['jumlah_dl_luar_kota'];
            $timKerjaGrandTotal['total_penugasan_dalam_kota'] += (int) $item['jumlah_dl_dalam_kota'];
            $timKerjaGrandTotal['total_hari'] += (int) $item['total_hari'];
            $timKerjaGrandTotal['total_transport'] += (float) $item['total_transport'];
            $timKerjaGrandTotal['total_harian'] += (float) $item['total_harian'];
            $timKerjaGrandTotal['total_penginapan'] += (float) $item['total_penginapan'];
            $timKerjaGrandTotal['total_penghasilan'] += (float) $item['total_penghasilan'];
            $timKerjaGrandTotal['total_penghasilan_luar_kota'] += (float) $item['penghasilan_luar_kota'];
            $timKerjaGrandTotal['total_penghasilan_dalam_kota'] += (float) $item['penghasilan_dalam_kota'];

            $matchesTimFilter = true;
            if ($timFilter !== 'all') {
                if ($timFilter === 'none') {
                    $matchesTimFilter = ($idTim === '');
                } else {
                    $matchesTimFilter = ($idTim === $timFilter);
                }
            }

            if (!$matchesTimFilter) {
                continue;
            }

            foreach (array_keys($item['_perjalanan_ids']) as $tripId) {
                $uniquePerjalananFiltered[$tripId] = true;
            }
            unset($item['_seen_perjalanan'], $item['_perjalanan_ids']);

            $item['rata_rata_penghasilan'] = $item['jumlah_dl'] > 0
                ? ($item['total_penghasilan'] / $item['jumlah_dl'])
                : 0.0;

            usort($item['riwayat'], static function (array $a, array $b): int {
                return strcmp($b['tgl_pergi'], $a['tgl_pergi'])
                    ?: ($b['id_perjalanan'] <=> $a['id_perjalanan']);
            });

            $summary['total_pegawai']++;
            $summary['total_penugasan'] += $item['jumlah_dl'];
            $summary['total_penugasan_luar_kota'] += $item['jumlah_dl_luar_kota'];
            $summary['total_penugasan_dalam_kota'] += $item['jumlah_dl_dalam_kota'];
            $summary['total_hari'] += $item['total_hari'];
            $summary['total_transport'] += $item['total_transport'];
            $summary['total_harian'] += $item['total_harian'];
            $summary['total_penginapan'] += $item['total_penginapan'];
            $summary['total_penghasilan'] += $item['total_penghasilan'];
            $summary['total_penghasilan_luar_kota'] += $item['penghasilan_luar_kota'];
            $summary['total_penghasilan_dalam_kota'] += $item['penghasilan_dalam_kota'];

            $items[] = $item;
        }

        $summary['total_dl_kegiatan'] = count($uniquePerjalananFiltered);

        $timKerjaSummary = array_values($timKerjaBuckets);
        $grandIncome = (float) $timKerjaGrandTotal['total_penghasilan'];
        foreach ($timKerjaSummary as &$tkRow) {
            $tkRow['persentase'] = $grandIncome > 0
                ? round(($tkRow['total_penghasilan'] / $grandIncome) * 100, 1)
                : 0.0;
        }
        unset($tkRow);

        usort($timKerjaSummary, static function (array $a, array $b): int {
            return ($b['total_penghasilan'] <=> $a['total_penghasilan'])
                ?: ($b['total_penugasan'] <=> $a['total_penugasan'])
                ?: ($b['total_pegawai'] <=> $a['total_pegawai'])
                ?: strcasecmp((string) $a['nama_tim_kerja'], (string) $b['nama_tim_kerja']);
        });

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
            'rows'                  => $items,
            'summary'               => $summary,
            'top_frekuensi'         => $topFrekuensi,
            'top_penghasilan'       => $topPenghasilan,
            'tim_kerja_summary'     => $timKerjaSummary,
            'tim_kerja_grand_total' => $timKerjaGrandTotal,
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
        return preg_replace('/\D+/', '', trim($nip)) ?? '';
    }

    private static function normalizeNameKey(string $nama): string
    {
        $clean = preg_replace('/\s+/', ' ', trim($nama)) ?? '';
        return strtolower($clean);
    }

    private static function normalizeBaseName(string $nama): string
    {
        $clean = preg_replace('/\s+/', ' ', trim($nama)) ?? '';
        if ($clean === '') {
            return '';
        }

        $commaPos = strpos($clean, ',');
        if ($commaPos !== false) {
            $clean = substr($clean, 0, $commaPos);
        }

        $clean = strtolower(trim($clean));
        $clean = preg_replace('/^(?:(?:prof|drg|dr|ns|apt|hj|h)\.?\s+)+/i', '', $clean) ?? $clean;
        $clean = preg_replace('/[^a-z0-9\s]/', ' ', $clean) ?? $clean;
        return preg_replace('/\s+/', ' ', trim($clean)) ?? '';
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
