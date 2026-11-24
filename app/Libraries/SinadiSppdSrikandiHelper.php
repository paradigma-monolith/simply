<?php

namespace App\Libraries;

use CodeIgniter\HTTP\IncomingRequest;
use DateTimeImmutable;

/**
 * Helper untuk mengelola konfigurasi multi-pejabat tujuan (Baris II–V)
 * dan perhitungan tanggal otomatis/manual pada SPPD Srikandi.
 */
class SinadiSppdSrikandiHelper
{
    public const MAX_PEJABAT_ROWS = 4;
    private const BACK_PAGE_STOP_SLOTS = 4;
    private const ROMAN_NUMERALS = ['II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII'];

    /**
     * Mem-parsing input query string SPPD Srikandi menjadi konfigurasi terstruktur.
     * Mendukung parameter array maupun skalar lama untuk kompatibilitas mundur.
     *
     * @param array<string, mixed> $query
     * @return array{mode_tanggal: string, jumlah_baris: int, rows: array<int, array<string, string>>}
     */
    public static function parse(array $query): array
    {
        $modeTanggal = ($query['mode_tanggal'] ?? 'auto') === 'manual' ? 'manual' : 'auto';
        $jumlahBarisRaw = (int) ($query['jumlah_baris'] ?? 1);
        $jumlahBaris = max(1, min(self::MAX_PEJABAT_ROWS, $jumlahBarisRaw));

        $lokasiArr = self::normalizeParamList($query['lokasi_tujuan'] ?? null);
        $jabatanArr = self::normalizeParamList($query['jabatan_tujuan'] ?? null);
        $pegawaiArr = self::normalizeParamList($query['pegawai_tujuan'] ?? null);
        $namaManualArr = self::normalizeParamList($query['nama_tujuan_manual'] ?? null);
        $nipManualArr = self::normalizeParamList($query['nip_tujuan_manual'] ?? null);
        $tglTibaArr = self::normalizeParamList($query['tgl_tiba'] ?? null);
        $tglBerangkatArr = self::normalizeParamList($query['tgl_berangkat'] ?? null);

        $rows = [];
        for ($i = 0; $i < self::MAX_PEJABAT_ROWS; $i++) {
            $pegawaiVal = trim((string) ($pegawaiArr[$i] ?? ''));
            $nama = '';
            $nip = '';

            if ($pegawaiVal === 'manual') {
                $nama = trim((string) ($namaManualArr[$i] ?? ''));
                $nip = trim((string) ($nipManualArr[$i] ?? ''));
            } elseif ($pegawaiVal !== '' && strpos($pegawaiVal, '|') !== false) {
                $parts = explode('|', $pegawaiVal, 2);
                $nama = trim((string) ($parts[0] ?? ''));
                $nip = trim((string) ($parts[1] ?? ''));
            }

            $rows[$i] = [
                'lokasi'        => trim((string) ($lokasiArr[$i] ?? '')),
                'jabatan'       => trim((string) ($jabatanArr[$i] ?? '')),
                'nama'          => $nama,
                'nip'           => $nip,
                'tgl_tiba'      => self::sanitizeYmdDate((string) ($tglTibaArr[$i] ?? '')),
                'tgl_berangkat' => self::sanitizeYmdDate((string) ($tglBerangkatArr[$i] ?? '')),
            ];
        }

        return [
            'mode_tanggal' => $modeTanggal,
            'jumlah_baris' => $jumlahBaris,
            'rows'         => $rows,
        ];
    }

    /**
     * Membaca query GET dari IncomingRequest CodeIgniter 4.
     *
     * @return array{mode_tanggal: string, jumlah_baris: int, rows: array<int, array<string, string>>}
     */
    public static function parseFromRequest(IncomingRequest $request): array
    {
        $get = $request->getGet();

        return self::parse(is_array($get) ? $get : []);
    }

    /**
     * Menghitung tanggal tiba & berangkat secara berurutan sesuai durasi perjalanan.
     *
     * @return array<int, array{tiba: string, berangkat: string}>
     */
    public static function computeAutoDates(string $tglPergi, string $tglPulang, int $stopCount): array
    {
        if ($stopCount <= 0) {
            return [];
        }

        $startYmd = self::sanitizeYmdDate($tglPergi);
        $endYmd = self::sanitizeYmdDate($tglPulang);

        if ($startYmd === '' || $endYmd === '') {
            $empty = [];
            for ($i = 0; $i < $stopCount; $i++) {
                $empty[] = ['tiba' => '', 'berangkat' => ''];
            }
            return $empty;
        }

        $start = new DateTimeImmutable($startYmd);
        $end = new DateTimeImmutable($endYmd);
        if ($end < $start) {
            $end = $start;
        }

        $totalDays = (int) $start->diff($end)->days + 1;
        $result = [];

        if ($stopCount === 1) {
            $result[] = [
                'tiba'      => $start->format('Y-m-d'),
                'berangkat' => $end->format('Y-m-d'),
            ];
            return $result;
        }

        if ($totalDays >= $stopCount) {
            $base = intdiv($totalDays, $stopCount);
            $rem = $totalDays % $stopCount;
            $offset = 0;

            for ($i = 0; $i < $stopCount; $i++) {
                $len = $base + ($i < $rem ? 1 : 0);
                $tiba = $start->modify("+{$offset} days")->format('Y-m-d');
                $berangkatOffset = $offset + $len - 1;
                $berangkat = $start->modify("+{$berangkatOffset} days")->format('Y-m-d');
                $result[] = [
                    'tiba'      => $tiba,
                    'berangkat' => $berangkat,
                ];
                $offset += $len;
            }
            return $result;
        }

        for ($i = 0; $i < $stopCount; $i++) {
            $dayOffset = min($totalDays - 1, (int) floor(($i * $totalDays) / $stopCount));
            $dateStr = $start->modify("+{$dayOffset} days")->format('Y-m-d');
            $result[] = [
                'tiba'      => $dateStr,
                'berangkat' => $dateStr,
            ];
        }

        return $result;
    }

    /**
     * Menyusun data 4 baris tujuan (II–V) pada halaman belakang SPPD beserta ringkasan pejabat terisi.
     *
     * @param array<string, mixed> $pesertaRow
     * @param array<string, mixed> $srikandiConfig
     * @return array{stops: array<int, array<string, mixed>>, filled_pejabat_count: int, active_stop_count: int}
     */
    public static function buildBackPageStops(array $pesertaRow, array $srikandiConfig = [], bool $isBorang = false): array
    {
        if (!function_exists('mediumdate_indo')) {
            helper('tanggal');
        }

        $kotaRaw = (string) ($pesertaRow['kota'] ?? '');
        $kotaList = array_values(array_filter(
            array_map('trim', explode(',', $kotaRaw)),
            static fn($v) => $v !== ''
        ));
        $kotaAsal = trim((string) ($pesertaRow['kota_asal'] ?? ''));

        $rows = is_array($srikandiConfig['rows'] ?? null) ? $srikandiConfig['rows'] : [];
        $modeTanggal = ($srikandiConfig['mode_tanggal'] ?? 'auto') === 'manual' ? 'manual' : 'auto';
        $jumlahBaris = max(1, min(self::MAX_PEJABAT_ROWS, (int) ($srikandiConfig['jumlah_baris'] ?? 1)));

        $highestFilledIdx = -1;
        for ($i = 0; $i < self::MAX_PEJABAT_ROWS; $i++) {
            $row = $rows[$i] ?? [];
            $hasContent = trim((string) ($row['lokasi'] ?? '')) !== ''
                || trim((string) ($row['jabatan'] ?? '')) !== ''
                || trim((string) ($row['nama'] ?? '')) !== ''
                || ($modeTanggal === 'manual' && (
                    trim((string) ($row['tgl_tiba'] ?? '')) !== ''
                    || trim((string) ($row['tgl_berangkat'] ?? '')) !== ''
                ));
            if ($hasContent) {
                $highestFilledIdx = $i;
            }
        }

        if ($highestFilledIdx >= 0 || $jumlahBaris > 1 || $modeTanggal === 'manual') {
            $configuredStopCount = min(self::MAX_PEJABAT_ROWS, max($jumlahBaris, $highestFilledIdx + 1));
        } else {
            $configuredStopCount = 0;
        }

        $activeStopCount = min(self::BACK_PAGE_STOP_SLOTS, max(count($kotaList), $configuredStopCount));

        $autoDates = self::computeAutoDates(
            (string) ($pesertaRow['tgl_pergi'] ?? ''),
            (string) ($pesertaRow['tgl_pulang'] ?? ''),
            $activeStopCount
        );

        $lokasiPerStop = [];
        $lastKota = !empty($kotaList) ? $kotaList[count($kotaList) - 1] : '';
        for ($j = 0; $j < $activeStopCount; $j++) {
            $customLokasi = $j < self::MAX_PEJABAT_ROWS ? trim((string) ($rows[$j]['lokasi'] ?? '')) : '';
            if ($customLokasi !== '') {
                $lokasiPerStop[$j] = $customLokasi;
            } else {
                $lokasiPerStop[$j] = $kotaList[$j] ?? $lastKota;
            }
        }

        $stops = [];
        $filledPejabatCount = 0;

        for ($i = 0; $i < self::BACK_PAGE_STOP_SLOTS; $i++) {
            $roman = self::ROMAN_NUMERALS[$i];

            if ($i >= $activeStopCount) {
                $stops[] = [
                    'active'              => false,
                    'roman'               => $roman,
                    'tiba_di'             => '',
                    'tgl_tiba_label'      => '',
                    'berangkat_dari'      => '',
                    'ke'                  => '',
                    'tgl_berangkat_label' => '',
                    'pejabat'             => null,
                ];
                continue;
            }

            if ($modeTanggal === 'manual' && $i < self::MAX_PEJABAT_ROWS) {
                $tibaYmd = trim((string) ($rows[$i]['tgl_tiba'] ?? ''));
                $berangkatYmd = trim((string) ($rows[$i]['tgl_berangkat'] ?? ''));
            } else {
                $tibaYmd = $autoDates[$i]['tiba'] ?? '';
                $berangkatYmd = $autoDates[$i]['berangkat'] ?? '';
            }

            $pejabat = null;
            if ($isBorang && $i < self::MAX_PEJABAT_ROWS) {
                $jabatan = trim((string) ($rows[$i]['jabatan'] ?? ''));
                $nama = trim((string) ($rows[$i]['nama'] ?? ''));
                $nip = trim((string) ($rows[$i]['nip'] ?? ''));

                if ($jabatan !== '' || $nama !== '') {
                    $filledPejabatCount++;
                    $pejabat = [
                        'jabatan'     => $jabatan,
                        'nama'        => $nama,
                        'nip'         => $nip,
                        'placeholder' => '${ttd_pengirim' . ($i + 3) . '}',
                    ];
                }
            }

            $stops[] = [
                'active'              => true,
                'roman'               => $roman,
                'tiba_di'             => $lokasiPerStop[$i],
                'tgl_tiba_label'      => $tibaYmd !== '' ? mediumdate_indo($tibaYmd) : '',
                'berangkat_dari'      => $lokasiPerStop[$i],
                'ke'                  => ($i + 1 < $activeStopCount) ? $lokasiPerStop[$i + 1] : $kotaAsal,
                'tgl_berangkat_label' => $berangkatYmd !== '' ? mediumdate_indo($berangkatYmd) : '',
                'pejabat'             => $pejabat,
            ];
        }

        return [
            'stops'                => $stops,
            'filled_pejabat_count' => $filledPejabatCount,
            'active_stop_count'    => $activeStopCount,
        ];
    }

    /**
     * Menormalkan nilai input query (skalar atau array) menjadi array terindeks numerik.
     *
     * @param mixed $value
     * @return array<int, mixed>
     */
    private static function normalizeParamList($value): array
    {
        if (is_array($value)) {
            return array_values($value);
        }
        if ($value === null) {
            return [];
        }
        return [$value];
    }

    /**
     * Memvalidasi tanggal format YYYY-MM-DD.
     */
    private static function sanitizeYmdDate(string $value): string
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
