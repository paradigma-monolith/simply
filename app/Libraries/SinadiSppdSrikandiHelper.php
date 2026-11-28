<?php

namespace App\Libraries;

use CodeIgniter\HTTP\IncomingRequest;
use DateTimeImmutable;

/**
 * Helper untuk mengelola konfigurasi multi-pejabat tujuan (Baris II–IV)
 * dan perhitungan tanggal otomatis/manual pada SPPD Srikandi.
 */
class SinadiSppdSrikandiHelper
{
    public const MAX_PEJABAT_ROWS = 3;
    public const MIN_REKAP_PESERTA = 10;
    private const BACK_PAGE_STOP_SLOTS = 3;
    private const ROMAN_NUMERALS = ['II', 'III', 'IV'];

    public static function isRekapEligible(int $pesertaCount): bool
    {
        return $pesertaCount >= self::MIN_REKAP_PESERTA;
    }

    public static function formatDateIndoSafe(?string $ymd): string
    {
        $raw = trim((string) $ymd);
        if ($raw === '' || strpos($raw, '0000-00-00') === 0) {
            return '';
        }

        $dateOnly = self::sanitizeYmdDate(substr($raw, 0, 10));
        if ($dateOnly === '') {
            return '';
        }

        if (!function_exists('mediumdate_indo')) {
            helper('tanggal');
        }

        return mediumdate_indo($dateOnly);
    }

    public static function computeDurationDays(?string $tglPergi, ?string $tglPulang): string
    {
        $startYmd = self::sanitizeYmdDate(substr(trim((string) $tglPergi), 0, 10));
        $endYmd = self::sanitizeYmdDate(substr(trim((string) $tglPulang), 0, 10));
        if ($startYmd === '' || $endYmd === '') {
            return '';
        }

        $start = new DateTimeImmutable($startYmd);
        $end = new DateTimeImmutable($endYmd);
        if ($end < $start) {
            return '1';
        }

        return (string) ((int) $start->diff($end)->days + 1);
    }

    public static function formatNipLabel(?string $nip): string
    {
        $trimmed = trim((string) $nip);
        if ($trimmed === '' || $trimmed === '-') {
            return '';
        }

        if (stripos($trimmed, 'nip') === 0) {
            return $trimmed;
        }

        return 'NIP. ' . $trimmed;
    }

    /**
     * Memformat rentang tanggal penyelenggaraan untuk header Lampiran IV.
     *
     * @param array<int, array<string, mixed>> $perjalanan
     */
    public static function formatTanggalPenyelenggaraan(array $perjalanan): string
    {
        $first = $perjalanan[0] ?? [];
        $start = self::sanitizeYmdDate(substr(trim((string) ($first['tgl_pelaksanaan'] ?? '')), 0, 10));
        $end = self::sanitizeYmdDate(substr(trim((string) ($first['tgl_pelaksanaan_akhir'] ?? '')), 0, 10));

        if ($start === '' || $end === '') {
            $minStart = '';
            $maxEnd = '';
            foreach ($perjalanan as $row) {
                $pStart = self::sanitizeYmdDate(substr(trim((string) ($row['tgl_pergi'] ?? '')), 0, 10));
                $pEnd = self::sanitizeYmdDate(substr(trim((string) ($row['tgl_pulang'] ?? '')), 0, 10));
                if ($pStart !== '' && ($minStart === '' || $pStart < $minStart)) {
                    $minStart = $pStart;
                }
                if ($pEnd !== '' && ($maxEnd === '' || $pEnd > $maxEnd)) {
                    $maxEnd = $pEnd;
                }
            }
            if ($start === '') {
                $start = $minStart;
            }
            if ($end === '') {
                $end = $maxEnd;
            }
        }

        if ($start === '' && $end === '') {
            return '';
        }
        if ($start === '') {
            $start = $end;
        }
        if ($end === '' || $end === $start) {
            return self::formatDateIndoSafe($start);
        }

        return self::formatDateIndoSafe($start) . ' s.d. ' . self::formatDateIndoSafe($end);
    }

    /**
     * Menyusun baris II–IV halaman Visum SPPD Rekap dengan default "Terlampir" pada baris II
     * dan tetap mendukung pejabat tujuan Srikandi (${ttd_pengirim3}..${ttd_pengirim5}).
     *
     * @param array<string, mixed> $srikandiConfig
     * @return array<int, array<string, mixed>>
     */
    public static function buildRekapBackPageStops(array $srikandiConfig = []): array
    {
        $rows = is_array($srikandiConfig['rows'] ?? null) ? $srikandiConfig['rows'] : [];
        $modeTanggal = ($srikandiConfig['mode_tanggal'] ?? 'auto') === 'manual' ? 'manual' : 'auto';

        $stops = [];
        for ($i = 0; $i < self::BACK_PAGE_STOP_SLOTS; $i++) {
            $roman = self::ROMAN_NUMERALS[$i];
            $row = is_array($rows[$i] ?? null) ? $rows[$i] : [];

            $lokasi = trim((string) ($row['lokasi'] ?? ''));
            $jabatan = trim((string) ($row['jabatan'] ?? ''));
            $nama = trim((string) ($row['nama'] ?? ''));
            $nip = trim((string) ($row['nip'] ?? ''));
            $tglTibaRaw = trim((string) ($row['tgl_tiba'] ?? ''));
            $tglBerangkatRaw = trim((string) ($row['tgl_berangkat'] ?? ''));

            $hasPejabat = ($jabatan !== '' || $nama !== '');
            $hasManualDates = ($modeTanggal === 'manual' && ($tglTibaRaw !== '' || $tglBerangkatRaw !== ''));
            $hasContent = ($lokasi !== '' || $hasPejabat || $hasManualDates);

            if ($i > 0 && !$hasContent) {
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

            $nextLokasi = '';
            if ($i + 1 < self::MAX_PEJABAT_ROWS) {
                $nextLokasi = trim((string) ($rows[$i + 1]['lokasi'] ?? ''));
            }

            $tibaLabel = 'Terlampir';
            $berangkatLabel = 'Terlampir';
            if ($modeTanggal === 'manual') {
                $formattedTiba = self::formatDateIndoSafe($tglTibaRaw);
                $formattedBerangkat = self::formatDateIndoSafe($tglBerangkatRaw);
                if ($formattedTiba !== '') {
                    $tibaLabel = $formattedTiba;
                }
                if ($formattedBerangkat !== '') {
                    $berangkatLabel = $formattedBerangkat;
                }
            }

            $pejabat = null;
            if ($hasPejabat) {
                $pejabat = [
                    'jabatan'     => $jabatan,
                    'nama'        => $nama,
                    'nip'         => $nip,
                    'placeholder' => '${ttd_pengirim' . ($i + 3) . '}',
                ];
            }

            $locLabel = $lokasi !== '' ? $lokasi : 'Terlampir';
            $keLabel = $nextLokasi !== '' ? $nextLokasi : 'Terlampir';

            $stops[] = [
                'active'              => true,
                'roman'               => $roman,
                'tiba_di'             => $locLabel,
                'tgl_tiba_label'      => $tibaLabel,
                'berangkat_dari'      => $locLabel,
                'ke'                  => $keLabel,
                'tgl_berangkat_label' => $berangkatLabel,
                'pejabat'             => $pejabat,
            ];
        }

        return $stops;
    }

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
     * Menyusun data 3 baris tujuan (II–IV) pada halaman belakang SPPD beserta ringkasan pejabat terisi.
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
