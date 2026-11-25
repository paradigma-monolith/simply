<?php

namespace App\Libraries;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class RelaxtimeMonitoringAnggaranHelper
{
    public const AKUN_LABELS = [
        '51' => 'Belanja Pegawai',
        '52' => 'Belanja Barang',
        '53' => 'Belanja Modal',
    ];

    public const MONTH_NAMES = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    /**
     * Normalisasi kode akun menjadi '51', '52', '53', atau fallback.
     */
    public static function normalizeAkun($raw, string $fallback = ''): string
    {
        $val = trim((string) $raw);
        if ($val === '') {
            return in_array($fallback, ['51', '52', '53'], true) ? $fallback : '';
        }

        if (preg_match('/\b(51|52|53)\b/', $val, $m)) {
            return $m[1];
        }

        if (preg_match('/^(51|52|53)/', $val, $m)) {
            return $m[1];
        }

        return in_array($fallback, ['51', '52', '53'], true) ? $fallback : '';
    }

    /**
     * Normalisasi penulisan PIC agar konsisten di filter & ringkasan.
     */
    public static function normalizePic($raw): string
    {
        $val = trim(preg_replace('/\s+/', ' ', (string) $raw));
        if ($val === '' || $val === '-') {
            return '';
        }

        if (preg_match('/^tim(?:ker)?\s*1\s*,\s*2\s*,\s*3\s*,\s*4$/i', $val)) {
            return 'Timker 1,2,3,4';
        }

        if (preg_match('/^tim(?:ker)?\s*(\d+)$/i', $val, $m)) {
            return 'Timker ' . $m[1];
        }

        if (preg_match('/^adum$/i', $val)) {
            return 'ADUM';
        }

        return $val;
    }

    /**
     * Konversi nilai rupiah/angka Excel/input menjadi integer (Rupiah penuh).
     */
    public static function parseAmount($value): int
    {
        if ($value === null || $value === '') {
            return 0;
        }

        if (is_int($value)) {
            return $value;
        }

        if (is_float($value)) {
            return is_nan($value) ? 0 : (int) round($value);
        }

        $str = trim((string) $value);
        $str = str_replace(["\xc2\xa0", ' '], '', $str);
        if ($str === '' || preg_match('/^(nan|#[a-z0-9!\/?]+|-+)$/i', $str)) {
            return 0;
        }

        $str = preg_replace('/^rp\.?/i', '', $str);
        $negative = false;
        if (strpos($str, '-') === 0) {
            $negative = true;
            $str = substr($str, 1);
        } elseif (preg_match('/^\((.+)\)$/', $str, $m)) {
            $negative = true;
            $str = $m[1];
        }

        $str = preg_replace('/[^0-9.,]/', '', $str);
        if ($str === '') {
            return 0;
        }

        $hasDot = strpos($str, '.') !== false;
        $hasComma = strpos($str, ',') !== false;

        if ($hasDot && $hasComma) {
            $lastDot = strrpos($str, '.');
            $lastComma = strrpos($str, ',');
            if ($lastComma > $lastDot) {
                $decLen = strlen($str) - $lastComma - 1;
                if ($decLen <= 2) {
                    $num = (float) str_replace(['.', ','], ['', '.'], $str);
                    return ($negative ? -1 : 1) * (int) round($num);
                }
            } else {
                $decLen = strlen($str) - $lastDot - 1;
                if ($decLen <= 2) {
                    $num = (float) str_replace(',', '', $str);
                    return ($negative ? -1 : 1) * (int) round($num);
                }
            }
            $digits = preg_replace('/[^\d]/', '', $str);
            return ($negative ? -1 : 1) * (int) $digits;
        }

        if ($hasDot) {
            $parts = explode('.', $str);
            if (count($parts) === 2 && strlen($parts[1]) <= 2) {
                return ($negative ? -1 : 1) * (int) round((float) $str);
            }
            // Ribuan Indonesia (e.g. 1.013.508.149 atau 429.000)
            return ($negative ? -1 : 1) * (int) str_replace('.', '', $str);
        }

        if ($hasComma) {
            $parts = explode(',', $str);
            if (count($parts) === 2 && strlen($parts[1]) <= 2) {
                return ($negative ? -1 : 1) * (int) round((float) str_replace(',', '.', $str));
            }
            // Ribuan format US (e.g. 1,870,000)
            return ($negative ? -1 : 1) * (int) str_replace(',', '', $str);
        }

        return ($negative ? -1 : 1) * (int) $str;
    }

    /**
     * Deteksi nomor bulan (1..12) dari nama sheet atau judul seksi.
     */
    public static function detectMonthFromText(string $text): ?int
    {
        $clean = strtolower(trim($text));
        if ($clean === '') {
            return null;
        }

        $map = [
            1 => ['januari', 'january', 'jan'],
            2 => ['februari', 'february', 'feb', 'pebruari'],
            3 => ['maret', 'march', 'mar'],
            4 => ['april', 'apr'],
            5 => ['mei', 'may'],
            6 => ['juni', 'june', 'jun'],
            7 => ['juli', 'july', 'jul'],
            8 => ['agustus', 'august', 'agst', 'agt', 'agu', 'aug'],
            9 => ['september', 'sept', 'sep'],
            10 => ['oktober', 'october', 'okt', 'oct'],
            11 => ['november', 'nopember', 'nov', 'nop'],
            12 => ['desember', 'december', 'des', 'dec'],
        ];

        foreach ($map as $month => $keywords) {
            foreach ($keywords as $kw) {
                if (preg_match('/\b' . preg_quote($kw, '/') . '\b/i', $clean)) {
                    return $month;
                }
            }
        }

        return null;
    }

    /**
     * Cek apakah nama sheet merupakan sheet rekap/ringkasan triwulanan yang harus dilewati saat import.
     */
    public static function isSummarySheetName(string $sheetName): bool
    {
        return preg_match('/^\s*(rekap|summary|ringkasan|total|dashboard|panduan)/i', trim($sheetName)) === 1;
    }

    /**
     * Normalisasi tanggal input atau serial Excel ke Y-m-d.
     */
    public static function normalizeDate($raw, ?string $fallback = null): ?string
    {
        if ($raw === null || $raw === '') {
            return $fallback;
        }

        if (is_numeric($raw) && (float) $raw > 20000 && (float) $raw < 90000) {
            try {
                return ExcelDate::excelToDateTimeObject((float) $raw)->format('Y-m-d');
            } catch (\Throwable $e) {
                return $fallback;
            }
        }

        $str = trim((string) $raw);
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})/', $str, $m)) {
            if (checkdate((int) $m[2], (int) $m[3], (int) $m[1])) {
                return $m[1] . '-' . $m[2] . '-' . $m[3];
            }
        }

        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $str, $m)) {
            $d = (int) $m[1];
            $mo = (int) $m[2];
            $y = (int) $m[3];
            if (checkdate($mo, $d, $y)) {
                return sprintf('%04d-%02d-%02d', $y, $mo, $d);
            }
        }

        $ts = strtotime($str);
        if ($ts !== false) {
            return date('Y-m-d', $ts);
        }

        return $fallback;
    }

    /**
     * Normalisasi rentang tanggal filter.
     */
    public static function normalizeDateRange(?string $start, ?string $end, bool $allowEmpty = false): array
    {
        $s = self::normalizeDate($start, '');
        $e = self::normalizeDate($end, '');

        if (!$allowEmpty && $s === '' && $e === '') {
            $year = date('Y');
            return [
                'start' => $year . '-01-01',
                'end' => $year . '-12-31',
            ];
        }

        if ($s !== '' && $e !== '' && $s > $e) {
            return ['start' => $e, 'end' => $s];
        }

        return ['start' => $s, 'end' => $e];
    }

    /**
     * Parse workbook Excel/CSV (mendukung Template Sederhana dan Rekap Bulanan 14 sheet).
     * Fokus ekstraksi kolom A (RO), B (Akun), C (Kegiatan), D (Rencana), PIC, dan K (Realisasi),
     * dengan deteksi dinamis jika kolom bergeser antar sheet/bulan.
     */
    public static function parseWorkbook(string $filePath, int $defaultYear, ?string $defaultDate = null): array
    {
        $fileType = IOFactory::identify($filePath);
        $reader = IOFactory::createReader($fileType);
        if (method_exists($reader, 'setReadDataOnly')) {
            $reader->setReadDataOnly(true);
        }

        $spreadsheet = $reader->load($filePath);
        $sheetCount = $spreadsheet->getSheetCount();

        $allRows = [];
        $sheetsProcessed = [];
        $sheetsSkipped = [];
        $errors = [];

        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            $sheetTitle = trim((string) $worksheet->getTitle());
            if ($sheetCount > 1 && self::isSummarySheetName($sheetTitle)) {
                $sheetsSkipped[] = $sheetTitle;
                continue;
            }

            $highestRow = (int) $worksheet->getHighestDataRow();
            $highestColStr = (string) $worksheet->getHighestDataColumn();
            $highestColIdx = max(12, Coordinate::columnIndexFromString($highestColStr));
            $highestColIdx = min($highestColIdx, 20);

            if ($highestRow < 2) {
                $sheetsSkipped[] = $sheetTitle;
                continue;
            }

            // Bangun grid 0-indexed dengan membaca cached calculated value untuk sel rumus
            $grid = [];
            for ($r = 1; $r <= $highestRow; $r++) {
                $rowArr = [];
                $hasContent = false;
                for ($c = 1; $c <= $highestColIdx; $c++) {
                    $colLetter = Coordinate::stringFromColumnIndex($c);
                    $cell = $worksheet->getCell($colLetter . $r);
                    $val = self::extractCellValue($cell);
                    $rowArr[$c - 1] = $val;
                    if ($val !== null && trim((string) $val) !== '') {
                        $hasContent = true;
                    }
                }
                if ($hasContent) {
                    $grid[$r] = $rowArr;
                }
            }

            if (empty($grid)) {
                $sheetsSkipped[] = $sheetTitle;
                continue;
            }

            $simpleHeaderRow = self::detectSimpleTemplateHeaderRow($grid);
            if ($simpleHeaderRow !== null) {
                $parsed = self::parseSimpleTemplateSheet($grid, $simpleHeaderRow, $defaultYear, $defaultDate, $sheetTitle);
            } else {
                $parsed = self::parseRekapBulananSheet($grid, $defaultYear, $defaultDate, $sheetTitle);
            }

            if (!empty($parsed)) {
                foreach ($parsed as $item) {
                    $allRows[] = $item;
                }
                $sheetsProcessed[] = [
                    'sheet' => $sheetTitle,
                    'count' => count($parsed),
                ];
            } else {
                $sheetsSkipped[] = $sheetTitle;
            }
        }

        return [
            'rows' => $allRows,
            'sheets_processed' => $sheetsProcessed,
            'sheets_skipped' => $sheetsSkipped,
            'errors' => $errors,
        ];
    }

    /**
     * Bangun agregasi statistik untuk Dashboard & Export Ringkasan.
     */
    public static function buildDashboardStats(array $rows): array
    {
        $totalRencana = 0;
        $totalRealisasi = 0;
        $terealisasiCount = 0;
        $overBudgetCount = 0;

        $byAkun = [];
        foreach (self::AKUN_LABELS as $code => $label) {
            $byAkun[$code] = [
                'akun' => $code,
                'label' => $label,
                'rencana' => 0,
                'realisasi' => 0,
                'sisa' => 0,
                'persen' => 0.0,
                'count' => 0,
            ];
        }

        $byPic = [];
        $byMonth = [];

        foreach ($rows as $row) {
            $rencana = (int) ($row['rencana'] ?? 0);
            $realisasi = (int) ($row['realisasi'] ?? 0);
            $akun = self::normalizeAkun($row['akun'] ?? '', '52');
            if ($akun === '') {
                $akun = '52';
            }

            $pic = self::normalizePic($row['pic'] ?? '');
            if ($pic === '') {
                $pic = 'Belum Ditentukan';
            }

            $tanggal = (string) ($row['tanggal'] ?? '');
            $monthKey = preg_match('/^(\d{4}-\d{2})/', $tanggal, $m) ? $m[1] : '0000-00';

            $totalRencana += $rencana;
            $totalRealisasi += $realisasi;
            if ($realisasi > 0) {
                $terealisasiCount++;
            }
            if ($rencana > 0 && $realisasi > $rencana) {
                $overBudgetCount++;
            }

            if (!isset($byAkun[$akun])) {
                $byAkun[$akun] = [
                    'akun' => $akun,
                    'label' => self::AKUN_LABELS[$akun] ?? ('Akun ' . $akun),
                    'rencana' => 0,
                    'realisasi' => 0,
                    'sisa' => 0,
                    'persen' => 0.0,
                    'count' => 0,
                ];
            }
            $byAkun[$akun]['rencana'] += $rencana;
            $byAkun[$akun]['realisasi'] += $realisasi;
            $byAkun[$akun]['count']++;

            if (!isset($byPic[$pic])) {
                $byPic[$pic] = [
                    'pic' => $pic,
                    'rencana' => 0,
                    'realisasi' => 0,
                    'sisa' => 0,
                    'persen' => 0.0,
                    'count' => 0,
                ];
            }
            $byPic[$pic]['rencana'] += $rencana;
            $byPic[$pic]['realisasi'] += $realisasi;
            $byPic[$pic]['count']++;

            if ($monthKey !== '0000-00') {
                if (!isset($byMonth[$monthKey])) {
                    $parts = explode('-', $monthKey);
                    $yr = (int) ($parts[0] ?? date('Y'));
                    $mo = (int) ($parts[1] ?? 1);
                    $shortMonth = substr(self::MONTH_NAMES[$mo] ?? 'Bln', 0, 3);
                    $byMonth[$monthKey] = [
                        'key' => $monthKey,
                        'label' => $shortMonth . ' ' . $yr,
                        'rencana' => 0,
                        'realisasi' => 0,
                        'sisa' => 0,
                        'persen' => 0.0,
                        'count' => 0,
                    ];
                }
                $byMonth[$monthKey]['rencana'] += $rencana;
                $byMonth[$monthKey]['realisasi'] += $realisasi;
                $byMonth[$monthKey]['count']++;
            }
        }

        foreach ($byAkun as &$item) {
            $item['sisa'] = $item['rencana'] - $item['realisasi'];
            $item['persen'] = $item['rencana'] > 0 ? round(($item['realisasi'] / $item['rencana']) * 100, 2) : 0.0;
        }
        unset($item);

        foreach ($byPic as &$item) {
            $item['sisa'] = $item['rencana'] - $item['realisasi'];
            $item['persen'] = $item['rencana'] > 0 ? round(($item['realisasi'] / $item['rencana']) * 100, 2) : 0.0;
        }
        unset($item);
        uasort($byPic, static function (array $a, array $b): int {
            return $b['rencana'] <=> $a['rencana'];
        });

        ksort($byMonth);
        foreach ($byMonth as &$item) {
            $item['sisa'] = $item['rencana'] - $item['realisasi'];
            $item['persen'] = $item['rencana'] > 0 ? round(($item['realisasi'] / $item['rencana']) * 100, 2) : 0.0;
        }
        unset($item);

        $totalSisa = $totalRencana - $totalRealisasi;
        $persenRealisasi = $totalRencana > 0 ? round(($totalRealisasi / $totalRencana) * 100, 2) : 0.0;

        return [
            'summary' => [
                'total_rencana' => $totalRencana,
                'total_realisasi' => $totalRealisasi,
                'total_sisa' => $totalSisa,
                'persen_realisasi' => $persenRealisasi,
                'total_kegiatan' => count($rows),
                'terealisasi_count' => $terealisasiCount,
                'over_budget_count' => $overBudgetCount,
            ],
            'by_akun' => $byAkun,
            'by_pic' => array_values($byPic),
            'by_month' => array_values($byMonth),
        ];
    }

    private static function extractCellValue(Cell $cell)
    {
        if ($cell->isFormula()) {
            $cached = $cell->getOldCalculatedValue();
            if ($cached !== null && $cached !== '') {
                return $cached;
            }
            try {
                return $cell->getCalculatedValue();
            } catch (\Throwable $e) {
                return 0;
            }
        }

        return $cell->getValue();
    }

    private static function detectSimpleTemplateHeaderRow(array $grid): ?int
    {
        for ($r = 1; $r <= 5; $r++) {
            if (!isset($grid[$r])) {
                continue;
            }
            $joined = strtoupper(implode(' | ', array_map(static fn($v) => trim((string) $v), $grid[$r])));
            if (strpos($joined, 'REKAP KEGIATAN AKUN') !== false) {
                return null;
            }
            if (
                strpos($joined, 'TANGGAL') !== false
                && strpos($joined, 'KEGIATAN') !== false
                && strpos($joined, 'RENCANA') !== false
            ) {
                return $r;
            }
            if (
                strpos($joined, 'RO') !== false
                && strpos($joined, 'KEGIATAN') !== false
                && strpos($joined, 'RENCANA') !== false
                && strpos($joined, 'MINGGU') === false
            ) {
                return $r;
            }
        }

        return null;
    }

    private static function parseSimpleTemplateSheet(
        array $grid,
        int $headerRow,
        int $defaultYear,
        ?string $defaultDate,
        string $sheetTitle
    ): array {
        $colMap = [
            'tanggal' => null,
            'ro' => 0,
            'akun' => 1,
            'kegiatan' => 2,
            'rencana' => 3,
            'pic' => 4,
            'realisasi' => 5,
        ];

        foreach ($grid[$headerRow] as $idx => $rawHeader) {
            $h = strtoupper(trim((string) $rawHeader));
            if ($h === '') {
                continue;
            }
            if (strpos($h, 'TANGGAL') !== false || $h === 'TGL') {
                $colMap['tanggal'] = $idx;
            } elseif ($h === 'RO' || strpos($h, 'KODE RO') !== false || strpos($h, 'NO KEGIATAN') !== false) {
                $colMap['ro'] = $idx;
            } elseif ($h === 'AKUN') {
                $colMap['akun'] = $idx;
            } elseif (strpos($h, 'KEGIATAN') !== false) {
                $colMap['kegiatan'] = $idx;
            } elseif (strpos($h, 'RENCANA') !== false) {
                $colMap['rencana'] = $idx;
            } elseif ($h === 'PIC' || strpos($h, 'TIMKER') !== false) {
                $colMap['pic'] = $idx;
            } elseif (strpos($h, 'REALISASI') !== false || strpos($h, 'REALIASI') !== false) {
                $colMap['realisasi'] = $idx;
            }
        }

        $fallbackDate = self::resolveSheetPeriodDate($sheetTitle, $defaultYear, $defaultDate);
        $rows = [];
        $currentRo = '';

        ksort($grid);
        foreach ($grid as $rNum => $cells) {
            if ($rNum <= $headerRow) {
                continue;
            }
            $kegiatanRaw = trim(preg_replace('/\s+/', ' ', (string) ($cells[$colMap['kegiatan']] ?? '')));
            if ($kegiatanRaw === '' || preg_match('/^(total|jumlah|grand total)$/i', $kegiatanRaw)) {
                continue;
            }

            $rawRo = trim((string) ($cells[$colMap['ro']] ?? ''));
            if ($rawRo !== '' && !preg_match('/^(total|jumlah)/i', $rawRo)) {
                $currentRo = $rawRo;
            }

            $tglCell = $colMap['tanggal'] !== null ? ($cells[$colMap['tanggal']] ?? null) : null;
            $tanggal = self::normalizeDate($tglCell, $fallbackDate);
            $akun = self::normalizeAkun($cells[$colMap['akun']] ?? '', '52');
            if ($akun === '') {
                $akun = '52';
            }

            $rencana = self::parseAmount($cells[$colMap['rencana']] ?? 0);
            $realisasi = self::parseAmount($cells[$colMap['realisasi']] ?? 0);
            $pic = self::normalizePic($cells[$colMap['pic']] ?? '');

            $rows[] = [
                'tanggal' => $tanggal,
                'ro' => $currentRo,
                'akun' => $akun,
                'kegiatan' => $kegiatanRaw,
                'rencana' => $rencana,
                'pic' => $pic,
                'realisasi' => $realisasi,
                'sheet' => $sheetTitle,
            ];
        }

        return $rows;
    }

    private static function parseRekapBulananSheet(
        array $grid,
        int $defaultYear,
        ?string $defaultDate,
        string $sheetTitle
    ): array {
        $periodDate = self::resolveSheetPeriodDate($sheetTitle, $defaultYear, $defaultDate);

        $sectionAkun = '52';
        $colMap = [
            'ro' => 0,
            'akun' => 1,
            'kegiatan' => 2,
            'rencana' => 3,
            'rencana_alt' => null,
            'pic' => 4,
            'realisasi' => 10,
            'minggu' => [5, 6, 7, 8, 9],
        ];

        $currentRo = '';
        $currentPic = '';
        $rows = [];
        $skipRow = null;

        ksort($grid);
        foreach ($grid as $rNum => $cells) {
            if ($skipRow !== null && $rNum === $skipRow) {
                $skipRow = null;
                continue;
            }

            $colA = trim((string) ($cells[0] ?? ''));
            $colAText = strtoupper(preg_replace('/\s+/', ' ', $colA));

            // Deteksi banner seksi akun (mis. REKAP KEGIATAN AKUN 52 / 51 / 53)
            if (preg_match('/REKAP\s+KEGIATAN\s+AKUN\s*(51|52|53)/i', $colAText, $m)) {
                $sectionAkun = $m[1];
                $currentRo = '';
                $currentPic = '';
                continue;
            }

            // Deteksi baris header kolom tabel
            if (self::isRekapHeaderRow($cells)) {
                $detected = self::extractRekapColumnMap($cells, $colMap);
                // Cek sub-header SEMULA / MENJADI tepat di bawah header (seperti sheet AGUSTUS)
                if (isset($grid[$rNum + 1])) {
                    $nextCells = $grid[$rNum + 1];
                    $rencIdx = $detected['rencana'];
                    $nextLeft = strtoupper(trim((string) ($nextCells[$rencIdx] ?? '')));
                    $nextRight = strtoupper(trim((string) ($nextCells[$rencIdx + 1] ?? '')));
                    if ($nextLeft === 'SEMULA' && $nextRight === 'MENJADI') {
                        $detected['rencana_alt'] = $rencIdx + 1;
                        $skipRow = $rNum + 1;
                    }
                }
                $colMap = $detected;
                continue;
            }

            // Lewati baris total, header berulang, atau baris rekap sampingan
            if (preg_match('/^(TOTAL|JUMLAH|REKAP|RO)$/i', $colAText) || strpos($colAText, 'TOTAL RENCANA') === 0 || strpos($colAText, 'TOTAL RPD') === 0) {
                continue;
            }

            $rawKegiatan = (string) ($cells[$colMap['kegiatan']] ?? '');
            $kegiatan = trim(preg_replace('/\s+/', ' ', $rawKegiatan));
            if ($kegiatan === '' || preg_match('/^(kegiatan|semula|menjadi|total|jumlah|nan)$/i', $kegiatan)) {
                continue;
            }

            $rawRencanaHeaderCheck = strtoupper(trim((string) ($cells[$colMap['rencana']] ?? '')));
            if (strpos($rawRencanaHeaderCheck, 'RENCANA PENCAIRAN') !== false) {
                continue;
            }

            // RO (Kolom A) — mewarisi RO blok jika baris lanjutan dikosongkan
            if ($colA !== '') {
                if ($colA !== $currentRo) {
                    $currentRo = $colA;
                    $currentPic = '';
                }
            }

            // Akun (Kolom B atau hasil deteksi) — fallback ke sectionAkun
            $akun = $colMap['akun'] !== null
                ? self::normalizeAkun($cells[$colMap['akun']] ?? '', $sectionAkun)
                : $sectionAkun;
            if ($akun === '') {
                $akun = $sectionAkun !== '' ? $sectionAkun : '52';
            }

            // Rencana Pencairan Anggaran (Kolom D, atau Kolom E 'MENJADI' bila ada revisi non-kosong)
            $rencana = self::parseAmount($cells[$colMap['rencana']] ?? 0);
            if ($colMap['rencana_alt'] !== null) {
                $altRaw = trim((string) ($cells[$colMap['rencana_alt']] ?? ''));
                if ($altRaw !== '') {
                    $rencana = self::parseAmount($cells[$colMap['rencana_alt']]);
                }
            }

            // PIC — isi dari kolom PIC atau warisi dalam blok RO yang sama (mis. ADUM di 4815.EBA.994.002)
            $picRaw = $colMap['pic'] !== null ? ($cells[$colMap['pic']] ?? '') : '';
            $picNorm = self::normalizePic($picRaw);
            if ($picNorm !== '') {
                $currentPic = $picNorm;
            } elseif ($currentPic !== '') {
                $picNorm = $currentPic;
            }

            // Realisasi (Kolom K atau hasil deteksi, fallback penjumlahan Minggu 1..5 atau kolom sebelah PIC untuk Akun 51)
            $realisasi = self::extractRekapRealisasi($cells, $colMap, $akun);

            $rows[] = [
                'tanggal' => $periodDate,
                'ro' => $currentRo,
                'akun' => $akun,
                'kegiatan' => $kegiatan,
                'rencana' => $rencana,
                'pic' => $picNorm,
                'realisasi' => $realisasi,
                'sheet' => $sheetTitle,
            ];
        }

        return $rows;
    }

    private static function resolveSheetPeriodDate(string $sheetTitle, int $defaultYear, ?string $defaultDate): string
    {
        $month = self::detectMonthFromText($sheetTitle);
        if ($month !== null) {
            return sprintf('%04d-%02d-01', $defaultYear, $month);
        }

        $normDefault = self::normalizeDate($defaultDate, '');
        if ($normDefault !== '') {
            return $normDefault;
        }

        return sprintf('%04d-%02d-01', $defaultYear, (int) date('m'));
    }

    private static function isRekapHeaderRow(array $cells): bool
    {
        $hasRencanaOrKegiatan = false;
        $hasMarker = false;

        foreach ($cells as $idx => $val) {
            if ($idx > 14) {
                continue;
            }
            $u = strtoupper(trim(preg_replace('/\s+/', ' ', (string) $val)));
            if ($u === '') {
                continue;
            }
            if (strpos($u, 'RENCANA PENCAIRAN') !== false || $u === 'KEGIATAN') {
                $hasRencanaOrKegiatan = true;
            }
            if ($u === 'RO' || $u === 'PIC' || strpos($u, 'REALISASI') !== false || strpos($u, 'REALIASI') !== false || strpos($u, 'MINGGU 1') !== false) {
                $hasMarker = true;
            }
        }

        return $hasRencanaOrKegiatan && $hasMarker;
    }

    private static function extractRekapColumnMap(array $cells, array $prevMap): array
    {
        $found = [
            'ro' => null,
            'akun' => null,
            'kegiatan' => null,
            'rencana' => null,
            'pic' => null,
            'realisasi' => null,
            'minggu' => [],
        ];

        foreach ($cells as $idx => $val) {
            if ($idx > 14) {
                continue;
            }
            $u = strtoupper(trim(preg_replace('/\s+/', ' ', (string) $val)));
            if ($u === '') {
                continue;
            }
            if ($u === 'RO') {
                $found['ro'] = $idx;
            } elseif ($u === 'AKUN') {
                $found['akun'] = $idx;
            } elseif ($u === 'KEGIATAN') {
                $found['kegiatan'] = $idx;
            } elseif (strpos($u, 'RENCANA PENCAIRAN') !== false) {
                $found['rencana'] = $idx;
            } elseif ($u === 'PIC') {
                $found['pic'] = $idx;
            } elseif (preg_match('/^MINGGU\s*\d+$/i', $u)) {
                $found['minggu'][] = $idx;
            } elseif (strpos($u, 'REALISASI') !== false || strpos($u, 'REALIASI') !== false) {
                $found['realisasi'] = $idx;
            }
        }

        $map = [
            'ro' => $found['ro'] ?? $prevMap['ro'],
            'akun' => $found['akun'],
            'kegiatan' => $found['kegiatan'] ?? $prevMap['kegiatan'],
            'rencana' => $found['rencana'] ?? $prevMap['rencana'],
            'rencana_alt' => null,
            'pic' => $found['pic'] ?? $prevMap['pic'],
            'realisasi' => $found['realisasi'],
            'minggu' => $found['minggu'],
        ];

        // Header seperti 'Total Rencana Bulan Mei' tidak menuliskan 'AKUN'/'KEGIATAN' secara eksplisit di kol B/C:
        // Jika posisi RENCANA dan KEGIATAN memberi ruang kol B untuk AKUN, pertahankan pemetaan AKUN sebelumnya.
        if ($found['akun'] === null && $found['ro'] === null && $found['kegiatan'] === null && $map['rencana'] >= 3) {
            $map['akun'] = $prevMap['akun'];
        }

        // Jika header mencantumkan RO & KEGIATAN tanpa AKUN, geser/tetapkan kolom AKUN null dan sesuaikan KEGIATAN
        if ($found['ro'] !== null && $found['kegiatan'] !== null && $found['akun'] === null) {
            $map['akun'] = null;
        }

        return $map;
    }

    private static function extractRekapRealisasi(array $cells, array $colMap, string $akun): int
    {
        if ($colMap['realisasi'] !== null) {
            $rawReal = trim((string) ($cells[$colMap['realisasi']] ?? ''));
            if ($rawReal !== '' && !preg_match('/^(nan|#[a-z0-9!\/?]+)$/i', $rawReal)) {
                return self::parseAmount($cells[$colMap['realisasi']]);
            }
        }

        if (!empty($colMap['minggu'])) {
            $hasAnyMinggu = false;
            $sumMinggu = 0;
            foreach ($colMap['minggu'] as $mIdx) {
                $rawM = trim((string) ($cells[$mIdx] ?? ''));
                if ($rawM !== '') {
                    $hasAnyMinggu = true;
                    $sumMinggu += self::parseAmount($cells[$mIdx]);
                }
            }
            if ($hasAnyMinggu) {
                return $sumMinggu;
            }
        }

        if ($akun === '51' && $colMap['realisasi'] === null) {
            $afterPicIdx = ($colMap['pic'] ?? 4) + 1;
            $candidateCols = [10, $afterPicIdx];
            foreach ($candidateCols as $cIdx) {
                $raw = trim((string) ($cells[$cIdx] ?? ''));
                if ($raw !== '' && preg_match('/\d/', $raw)) {
                    $val = self::parseAmount($cells[$cIdx]);
                    if ($val >= 0) {
                        return $val;
                    }
                }
            }
        }

        return 0;
    }
}
