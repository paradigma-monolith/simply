<?php

namespace App\Libraries;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

class RelaxtimeRkksHelper
{
    public const LEVEL_ORDER = [
        'program',
        'kegiatan',
        'kro',
        'ro',
        'komponen',
        'subkomponen',
        'akun',
        'header_detail',
        'detail',
    ];

    public const ALLOWED_CHILD_TYPES = [
        'program' => ['kegiatan'],
        'kegiatan' => ['kro'],
        'kro' => ['ro'],
        'ro' => ['komponen'],
        'komponen' => ['subkomponen'],
        'subkomponen' => ['akun'],
        'akun' => ['header_detail', 'detail'],
        'header_detail' => ['header_detail', 'detail'],
        'detail' => [],
    ];

    public const LEVEL_LABELS = [
        'program' => 'Program',
        'kegiatan' => 'Kegiatan',
        'kro' => 'KRO',
        'ro' => 'RO',
        'komponen' => 'Komponen',
        'subkomponen' => 'Subkomponen',
        'akun' => 'Akun',
        'header_detail' => 'Header Detail',
        'detail' => 'Detail Belanja',
    ];

    public const AKUN_GROUP_LABELS = [
        '51' => 'Belanja Pegawai',
        '52' => 'Belanja Barang',
        '53' => 'Belanja Modal',
    ];

    /**
     * Konversi nilai rupiah/angka menjadi integer (Rupiah penuh).
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
            if ($lastComma > $lastDot && strlen($str) - $lastComma - 1 <= 2) {
                $num = (float) str_replace(['.', ','], ['', '.'], $str);
                return ($negative ? -1 : 1) * (int) round($num);
            }
            if ($lastDot > $lastComma && strlen($str) - $lastDot - 1 <= 2) {
                $num = (float) str_replace(',', '', $str);
                return ($negative ? -1 : 1) * (int) round($num);
            }
            $digits = preg_replace('/[^\d]/', '', $str);
            return ($negative ? -1 : 1) * (int) $digits;
        }

        if ($hasDot) {
            $parts = explode('.', $str);
            if (count($parts) === 2 && strlen($parts[1]) <= 2) {
                return ($negative ? -1 : 1) * (int) round((float) $str);
            }
            return ($negative ? -1 : 1) * (int) str_replace('.', '', $str);
        }

        if ($hasComma) {
            $parts = explode(',', $str);
            if (count($parts) === 2 && strlen($parts[1]) <= 2) {
                return ($negative ? -1 : 1) * (int) round((float) str_replace(',', '.', $str));
            }
            return ($negative ? -1 : 1) * (int) str_replace(',', '', $str);
        }

        return ($negative ? -1 : 1) * (int) $str;
    }

    /**
     * Pisahkan string volume + satuan ala SAKTI (mis. "25.0 OK", "1700.0 Orang, Akta").
     */
    public static function parseVolumeSatuan($raw): array
    {
        $str = trim(preg_replace('/\s+/', ' ', (string) $raw));
        if ($str === '' || $str === '-') {
            return ['volume' => null, 'satuan' => ''];
        }

        if (preg_match('/^([0-9]+(?:[.,][0-9]+)?)\s+(.+)$/', $str, $m)) {
            $volStr = str_replace(',', '.', $m[1]);
            return [
                'volume' => (float) $volStr,
                'satuan' => trim($m[2]),
            ];
        }

        if (is_numeric(str_replace(',', '.', $str))) {
            return [
                'volume' => (float) str_replace(',', '.', $str),
                'satuan' => '',
            ];
        }

        return ['volume' => null, 'satuan' => $str];
    }

    /**
     * Klasifikasi kode hierarki RKKS SAKTI pada Kolom A.
     */
    public static function classifyCode(string $kode): ?string
    {
        $k = strtoupper(trim($kode));
        if ($k === '') {
            return null;
        }

        if (preg_match('/^\d{3}\.\d{2}\.[A-Z0-9]{2}$/', $k)) {
            return 'program';
        }
        if (preg_match('/^\d{4}\.[A-Z0-9]{3}\.[A-Z0-9]{3}$/', $k)) {
            return 'ro';
        }
        if (preg_match('/^\d{4}\.[A-Z0-9]{3}$/', $k)) {
            return 'kro';
        }
        if (preg_match('/^\d{6}$/', $k)) {
            return 'akun';
        }
        if (preg_match('/^\d{4}$/', $k)) {
            return 'kegiatan';
        }
        if (preg_match('/^\d{3}$/', $k)) {
            return 'komponen';
        }
        if (preg_match('/^[A-Z]{1,2}$/', $k)) {
            return 'subkomponen';
        }

        return null;
    }

    /**
     * Parse workbook RKKS / RKAKL format SAKTI Kemenkeu (RKK_MULTIYEAR_SATKER).
     */
    public static function parseSaktiWorkbook(string $filePath): array
    {
        $fileType = IOFactory::identify($filePath);
        $reader = IOFactory::createReader($fileType);
        if (method_exists($reader, 'setReadDataOnly')) {
            $reader->setReadDataOnly(true);
        }

        $spreadsheet = $reader->load($filePath);
        $targetSheet = self::selectSaktiWorksheet($spreadsheet);
        if ($targetSheet === null) {
            return [
                'sheet' => '',
                'items' => [],
                'summary' => self::buildSummary([]),
            ];
        }

        $sheetTitle = trim((string) $targetSheet->getTitle());
        $highestRow = (int) $targetSheet->getHighestDataRow();
        $highestColIdx = min(16, max(14, Coordinate::columnIndexFromString((string) $targetSheet->getHighestDataColumn())));

        $nodes = [];
        $stack = [
            'program' => null,
            'kegiatan' => null,
            'kro' => null,
            'ro' => null,
            'komponen' => null,
            'subkomponen' => null,
            'akun' => null,
            'header1' => null,
            'header2' => null,
        ];
        $lastNodeIdx = null;
        $nextTempId = 1;

        for ($r = 1; $r <= $highestRow; $r++) {
            $row = [];
            $hasAny = false;
            for ($c = 1; $c <= $highestColIdx; $c++) {
                $colLetter = Coordinate::stringFromColumnIndex($c);
                $val = self::extractCellValue($targetSheet->getCell($colLetter . $r));
                $row[$c - 1] = $val;
                if ($val !== null && trim((string) $val) !== '') {
                    $hasAny = true;
                }
            }
            if (!$hasAny) {
                continue;
            }

            $colA = trim((string) ($row[0] ?? ''));
            $colB = trim((string) ($row[1] ?? ''));
            $colC = trim((string) ($row[2] ?? ''));
            $colD = trim((string) ($row[3] ?? ''));
            $colE = trim((string) ($row[4] ?? ''));
            $colG = trim((string) ($row[6] ?? ''));
            $colI = trim((string) ($row[8] ?? ''));
            $colK = trim((string) ($row[10] ?? ''));

            // Berhenti saat mencapai catatan kaki / tanda tangan SAKTI di bagian bawah sheet
            if (
                preg_match('/^catatan\s*:?$/i', $colB)
                || preg_match('/^1\.\s*U\s*=/i', $colC)
                || ($colA === '' && $colD === '' && preg_match('/^[A-Za-z.\s]+,\s*\d{1,2}\s+[A-Za-z]+\s+\d{4}$/', $colI))
            ) {
                break;
            }

            // Ambil nilai jumlah: SAKTI normalnya di Col J (idx 9), tetapi bisa bergeser ke Col K (idx 10)
            $jumlah = self::extractSaktiAmount($row);
            $sumberDana = self::extractSumberDana($row);
            $isBlokir = self::detectBlokirFlag($row);

            if ($colA !== '') {
                $level = self::classifyCode($colA);
                if ($level === null) {
                    continue;
                }

                $parentTempId = null;
                switch ($level) {
                    case 'program':
                        $stack['kegiatan'] = $stack['kro'] = $stack['ro'] = $stack['komponen'] = $stack['subkomponen'] = $stack['akun'] = $stack['header1'] = $stack['header2'] = null;
                        break;
                    case 'kegiatan':
                        $parentTempId = $stack['program'];
                        $stack['kro'] = $stack['ro'] = $stack['komponen'] = $stack['subkomponen'] = $stack['akun'] = $stack['header1'] = $stack['header2'] = null;
                        break;
                    case 'kro':
                        $parentTempId = $stack['kegiatan'] ?? $stack['program'];
                        $stack['ro'] = $stack['komponen'] = $stack['subkomponen'] = $stack['akun'] = $stack['header1'] = $stack['header2'] = null;
                        break;
                    case 'ro':
                        $parentTempId = $stack['kro'] ?? $stack['kegiatan'];
                        $stack['komponen'] = $stack['subkomponen'] = $stack['akun'] = $stack['header1'] = $stack['header2'] = null;
                        break;
                    case 'komponen':
                        $parentTempId = $stack['ro'] ?? $stack['kro'];
                        $stack['subkomponen'] = $stack['akun'] = $stack['header1'] = $stack['header2'] = null;
                        break;
                    case 'subkomponen':
                        $parentTempId = $stack['komponen'] ?? $stack['ro'];
                        $stack['akun'] = $stack['header1'] = $stack['header2'] = null;
                        break;
                    case 'akun':
                        $parentTempId = $stack['subkomponen'] ?? $stack['komponen'];
                        $stack['header1'] = $stack['header2'] = null;
                        break;
                }

                $volInfo = in_array($level, ['kro', 'ro'], true)
                    ? self::parseVolumeSatuan($colG)
                    : ['volume' => null, 'satuan' => ''];

                $jenisKomp = '';
                if ($level === 'komponen' && in_array(strtoupper($colK), ['U', 'P'], true)) {
                    $jenisKomp = strtoupper($colK);
                }

                $tempId = $nextTempId++;
                $nodes[] = [
                    'temp_id' => $tempId,
                    'parent_temp_id' => $parentTempId,
                    'level_type' => $level,
                    'header_depth' => 0,
                    'kode' => strtoupper($colA),
                    'uraian' => trim(preg_replace('/\s+/', ' ', $colD)),
                    'lokasi' => '',
                    'kppn' => '',
                    'jenis_komponen' => $jenisKomp,
                    'volume' => $volInfo['volume'],
                    'satuan' => $volInfo['satuan'],
                    'harga_satuan' => 0,
                    'jumlah' => $jumlah,
                    'sumber_dana' => $level === 'akun' ? $sumberDana : '',
                    'is_blokir' => $isBlokir ? 1 : 0,
                    'nilai_blokir' => $isBlokir ? $jumlah : 0,
                    'urutan' => count($nodes) + 1,
                ];
                $lastNodeIdx = count($nodes) - 1;
                $stack[$level] = $tempId;
                continue;
            }

            // Baris dengan Col A kosong: metadata Lokasi/KPPN, header (> / >>), detail (-), atau kelanjutan uraian
            if (preg_match('/^Lokasi\s*:\s*(.+)$/i', $colD, $m)) {
                $targetId = $stack['kro'] ?? $stack['ro'];
                if ($targetId !== null && isset($nodes[$targetId - 1])) {
                    $nodes[$targetId - 1]['lokasi'] = trim($m[1]);
                }
                continue;
            }

            if (preg_match('/^\(?\s*KPPN\.?\s*([^)]+)\)?$/i', $colD, $m)) {
                $targetId = $stack['akun'];
                if ($targetId !== null && isset($nodes[$targetId - 1])) {
                    $nodes[$targetId - 1]['kppn'] = trim($m[1]);
                }
                continue;
            }

            if ($colD === '>') {
                $tempId = $nextTempId++;
                $nodes[] = [
                    'temp_id' => $tempId,
                    'parent_temp_id' => $stack['akun'] ?? $stack['subkomponen'],
                    'level_type' => 'header_detail',
                    'header_depth' => 1,
                    'kode' => '>',
                    'uraian' => trim(preg_replace('/\s+/', ' ', $colE)),
                    'lokasi' => '',
                    'kppn' => '',
                    'jenis_komponen' => '',
                    'volume' => null,
                    'satuan' => '',
                    'harga_satuan' => 0,
                    'jumlah' => $jumlah,
                    'sumber_dana' => '',
                    'is_blokir' => 0,
                    'nilai_blokir' => 0,
                    'urutan' => count($nodes) + 1,
                ];
                $lastNodeIdx = count($nodes) - 1;
                $stack['header1'] = $tempId;
                $stack['header2'] = null;
                continue;
            }

            if ($colD === '>>') {
                $tempId = $nextTempId++;
                $nodes[] = [
                    'temp_id' => $tempId,
                    'parent_temp_id' => $stack['header1'] ?? $stack['akun'] ?? $stack['subkomponen'],
                    'level_type' => 'header_detail',
                    'header_depth' => 2,
                    'kode' => '>>',
                    'uraian' => trim(preg_replace('/\s+/', ' ', $colE)),
                    'lokasi' => '',
                    'kppn' => '',
                    'jenis_komponen' => '',
                    'volume' => null,
                    'satuan' => '',
                    'harga_satuan' => 0,
                    'jumlah' => $jumlah,
                    'sumber_dana' => '',
                    'is_blokir' => 0,
                    'nilai_blokir' => 0,
                    'urutan' => count($nodes) + 1,
                ];
                $lastNodeIdx = count($nodes) - 1;
                $stack['header2'] = $tempId;
                continue;
            }

            if ($colD === '-' && $colE !== '') {
                $parentTempId = $stack['header2'] ?? $stack['header1'] ?? $stack['akun'] ?? $stack['subkomponen'];
                $volInfo = self::parseVolumeSatuan($colG);
                $hargaSatuan = self::parseAmount($row[7] ?? 0);
                $akunSd = ($stack['akun'] !== null && isset($nodes[$stack['akun'] - 1]))
                    ? (string) $nodes[$stack['akun'] - 1]['sumber_dana']
                    : '';

                $tempId = $nextTempId++;
                $nodes[] = [
                    'temp_id' => $tempId,
                    'parent_temp_id' => $parentTempId,
                    'level_type' => 'detail',
                    'header_depth' => 0,
                    'kode' => '-',
                    'uraian' => trim(preg_replace('/\s+/', ' ', $colE)),
                    'lokasi' => '',
                    'kppn' => '',
                    'jenis_komponen' => '',
                    'volume' => $volInfo['volume'],
                    'satuan' => $volInfo['satuan'],
                    'harga_satuan' => $hargaSatuan,
                    'jumlah' => $jumlah,
                    'sumber_dana' => $sumberDana !== '' ? $sumberDana : $akunSd,
                    'is_blokir' => $isBlokir ? 1 : 0,
                    'nilai_blokir' => $isBlokir ? $jumlah : 0,
                    'urutan' => count($nodes) + 1,
                ];
                $lastNodeIdx = count($nodes) - 1;
                continue;
            }

            // Kelanjutan teks uraian multi-baris (mis. Subkomponen Q pada 7960.QAH.017)
            if (
                $colD !== ''
                && $colE === ''
                && $jumlah === 0
                && $lastNodeIdx !== null
                && isset($nodes[$lastNodeIdx])
                && in_array($nodes[$lastNodeIdx]['level_type'], ['program', 'kegiatan', 'kro', 'ro', 'komponen', 'subkomponen'], true)
            ) {
                $nodes[$lastNodeIdx]['uraian'] = trim($nodes[$lastNodeIdx]['uraian'] . ' ' . preg_replace('/\s+/', ' ', $colD));
            }
        }

        self::recalculateTreeAmounts($nodes);

        return [
            'sheet' => $sheetTitle,
            'items' => $nodes,
            'summary' => self::buildSummary($nodes),
        ];
    }

    /**
     * Hitung ulang pagu secara bottom-up dari leaf detail ke atas (akun -> subkomponen -> ... -> program).
     * Node detail selalu mempertahankan jumlah aslinya; parent yang punya anak menjumlahkan anaknya.
     */
    public static function recalculateTreeAmounts(array &$items): void
    {
        if (empty($items)) {
            return;
        }

        $idKey = array_key_exists('id_item', $items[0]) ? 'id_item' : 'temp_id';
        $parentKey = array_key_exists('parent_id', $items[0]) ? 'parent_id' : 'parent_temp_id';

        $indexById = [];
        $childrenByParent = [];
        foreach ($items as $idx => $item) {
            $id = (int) ($item[$idKey] ?? 0);
            $indexById[$id] = $idx;
            $pid = $item[$parentKey] !== null ? (int) $item[$parentKey] : 0;
            $childrenByParent[$pid][] = $id;
        }

        $depthCache = [];
        $getDepth = static function (int $id) use (&$getDepth, &$depthCache, &$items, $indexById, $parentKey): int {
            if (isset($depthCache[$id])) {
                return $depthCache[$id];
            }
            $idx = $indexById[$id] ?? null;
            if ($idx === null) {
                return 0;
            }
            $pid = $items[$idx][$parentKey] !== null ? (int) $items[$idx][$parentKey] : 0;
            if ($pid === 0 || !isset($indexById[$pid]) || $pid === $id) {
                return $depthCache[$id] = 0;
            }
            return $depthCache[$id] = 1 + $getDepth($pid);
        };

        $orderedIds = array_keys($indexById);
        foreach ($orderedIds as $id) {
            $getDepth($id);
        }

        usort($orderedIds, static fn($a, $b) => ($depthCache[$b] ?? 0) <=> ($depthCache[$a] ?? 0));

        foreach ($orderedIds as $id) {
            $idx = $indexById[$id];
            $children = $childrenByParent[$id] ?? [];
            if (empty($children) || ($items[$idx]['level_type'] ?? '') === 'detail') {
                $items[$idx]['jumlah'] = (int) ($items[$idx]['jumlah'] ?? 0);
                if (!empty($items[$idx]['is_blokir']) && (int) ($items[$idx]['nilai_blokir'] ?? 0) === 0) {
                    $items[$idx]['nilai_blokir'] = $items[$idx]['jumlah'];
                }
                continue;
            }

            $sumJumlah = 0;
            $sumBlokir = 0;
            foreach ($children as $childId) {
                $cIdx = $indexById[$childId];
                $sumJumlah += (int) ($items[$cIdx]['jumlah'] ?? 0);
                $sumBlokir += !empty($items[$cIdx]['is_blokir'])
                    ? (int) (($items[$cIdx]['nilai_blokir'] ?? 0) > 0 ? $items[$cIdx]['nilai_blokir'] : $items[$cIdx]['jumlah'])
                    : (int) ($items[$cIdx]['nilai_blokir'] ?? 0);
            }
            $items[$idx]['jumlah'] = $sumJumlah;
            $items[$idx]['nilai_blokir'] = $sumBlokir;
        }
    }

    /**
     * Ringkasan KPI RKKS (Total Pagu, Belanja 51/52/53, Sumber Dana, Blokir).
     */
    public static function buildSummary(array $items): array
    {
        $totalPagu = 0;
        $totalBlokir = 0;
        $blokirCount = 0;
        $countsByLevel = array_fill_keys(self::LEVEL_ORDER, 0);

        $byAkunGroup = [];
        foreach (self::AKUN_GROUP_LABELS as $g => $label) {
            $byAkunGroup[$g] = [
                'group' => $g,
                'label' => $label,
                'pagu' => 0,
                'persen' => 0.0,
                'count_akun' => 0,
            ];
        }

        $bySumberDana = [];
        $hasProgram = false;

        foreach ($items as $item) {
            $level = (string) ($item['level_type'] ?? '');
            if (isset($countsByLevel[$level])) {
                $countsByLevel[$level]++;
            }

            if ($level === 'program') {
                $hasProgram = true;
                $totalPagu += (int) ($item['jumlah'] ?? 0);
            }

            if ($level === 'akun') {
                $kode = (string) ($item['kode'] ?? '');
                $grp = substr($kode, 0, 2);
                $amt = (int) ($item['jumlah'] ?? 0);
                if (isset($byAkunGroup[$grp])) {
                    $byAkunGroup[$grp]['pagu'] += $amt;
                    $byAkunGroup[$grp]['count_akun']++;
                }

                $sd = strtoupper(trim((string) ($item['sumber_dana'] ?? '')));
                if ($sd === '') {
                    $sd = 'LAINNYA';
                }
                if (!isset($bySumberDana[$sd])) {
                    $bySumberDana[$sd] = ['sumber_dana' => $sd, 'pagu' => 0, 'persen' => 0.0, 'count_akun' => 0];
                }
                $bySumberDana[$sd]['pagu'] += $amt;
                $bySumberDana[$sd]['count_akun']++;
            }

            if ($level === 'detail' && !empty($item['is_blokir'])) {
                $blokirCount++;
                $bVal = (int) ($item['nilai_blokir'] ?? 0);
                $totalBlokir += $bVal > 0 ? $bVal : (int) ($item['jumlah'] ?? 0);
            }
        }

        if (!$hasProgram) {
            foreach ($byAkunGroup as $grp => $info) {
                $totalPagu += $info['pagu'];
            }
        }

        foreach ($byAkunGroup as &$info) {
            $info['persen'] = $totalPagu > 0 ? round(($info['pagu'] / $totalPagu) * 100, 2) : 0.0;
        }
        unset($info);

        foreach ($bySumberDana as &$sdInfo) {
            $sdInfo['persen'] = $totalPagu > 0 ? round(($sdInfo['pagu'] / $totalPagu) * 100, 2) : 0.0;
        }
        unset($sdInfo);

        return [
            'total_pagu' => $totalPagu,
            'total_blokir' => $totalBlokir,
            'blokir_count' => $blokirCount,
            'by_akun_group' => $byAkunGroup,
            'by_sumber_dana' => array_values($bySumberDana),
            'counts_by_level' => $countsByLevel,
        ];
    }

    /**
     * Bangun tabel datar Rekap RO + Subkomponen seperti Sheet REKAP pada workbook SAKTI.
     */
    public static function buildRekapSubkomponen(array $items): array
    {
        if (empty($items)) {
            return [];
        }

        $idKey = array_key_exists('id_item', $items[0]) ? 'id_item' : 'temp_id';
        $parentKey = array_key_exists('parent_id', $items[0]) ? 'parent_id' : 'parent_temp_id';

        $byId = [];
        foreach ($items as $item) {
            $byId[(int) $item[$idKey]] = $item;
        }

        $findAncestor = static function (int $startId, string $targetLevel) use ($byId, $parentKey): ?array {
            $currId = $startId;
            $guard = 0;
            while ($currId > 0 && isset($byId[$currId]) && $guard < 20) {
                $node = $byId[$currId];
                if (($node['level_type'] ?? '') === $targetLevel) {
                    return $node;
                }
                $currId = $node[$parentKey] !== null ? (int) $node[$parentKey] : 0;
                $guard++;
            }
            return null;
        };

        $grouped = [];
        foreach ($items as $item) {
            if (($item['level_type'] ?? '') !== 'akun') {
                continue;
            }
            $id = (int) $item[$idKey];
            $sub = $findAncestor($id, 'subkomponen');
            $komp = $findAncestor($id, 'komponen');
            $ro = $findAncestor($id, 'ro');
            $kro = $findAncestor($id, 'kro');

            $roCode = trim(($ro['kode'] ?? ($kro['kode'] ?? '')) . ' ' . ($komp['kode'] ?? ''));
            $subCode = (string) ($sub['kode'] ?? '-');
            $kegiatanUraian = (string) ($sub['uraian'] ?? ($komp['uraian'] ?? ($item['uraian'] ?? '-')));
            $akun2 = substr((string) ($item['kode'] ?? '52'), 0, 2);
            $sd = trim((string) ($item['sumber_dana'] ?? ''));

            $groupKey = $roCode . '|' . $subCode . '|' . $akun2;
            if (!isset($grouped[$groupKey])) {
                $grouped[$groupKey] = [
                    'ro_komponen' => trim($roCode),
                    'subkomponen_kode' => $subCode,
                    'kegiatan' => $kegiatanUraian,
                    'akun' => $akun2,
                    'sumber_dana' => $sd,
                    'pagu' => 0,
                    'id_subkomponen' => $sub ? (int) $sub[$idKey] : $id,
                ];
            } elseif ($sd !== '' && strpos((string) $grouped[$groupKey]['sumber_dana'], $sd) === false) {
                $grouped[$groupKey]['sumber_dana'] = trim($grouped[$groupKey]['sumber_dana'] . ($grouped[$groupKey]['sumber_dana'] !== '' ? '/' : '') . $sd);
            }
            $grouped[$groupKey]['pagu'] += (int) ($item['jumlah'] ?? 0);
        }

        return array_values($grouped);
    }

    private static function selectSaktiWorksheet($spreadsheet)
    {
        foreach ($spreadsheet->getWorksheetIterator() as $ws) {
            if (strcasecmp(trim((string) $ws->getTitle()), 'RKK_MULTIYEAR_SATKER') === 0) {
                return $ws;
            }
        }

        foreach ($spreadsheet->getWorksheetIterator() as $ws) {
            $title = trim((string) $ws->getTitle());
            if (preg_match('/^rekap/i', $title)) {
                continue;
            }
            $hasHierarchy = false;
            $maxR = min(20, (int) $ws->getHighestDataRow());
            for ($r = 1; $r <= $maxR; $r++) {
                $valA = trim((string) self::extractCellValue($ws->getCell('A' . $r)));
                $lvl = self::classifyCode($valA);
                if (in_array($lvl, ['program', 'ro', 'akun'], true)) {
                    $hasHierarchy = true;
                    break;
                }
            }
            if ($hasHierarchy) {
                return $ws;
            }
        }

        return $spreadsheet->getSheetCount() > 0 ? $spreadsheet->getSheet(0) : null;
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

    private static function extractSaktiAmount(array $row): int
    {
        foreach ([9, 10] as $idx) {
            $raw = trim((string) ($row[$idx] ?? ''));
            if ($raw !== '' && !in_array(strtoupper($raw), ['U', 'P'], true) && preg_match('/\d/', $raw)) {
                return self::parseAmount($row[$idx]);
            }
        }
        return 0;
    }

    private static function extractSumberDana(array $row): string
    {
        foreach ([11, 13, 12] as $idx) {
            $raw = strtoupper(trim((string) ($row[$idx] ?? '')));
            if ($raw !== '' && preg_match('/^(RM|PNP|PNBP|BLU|PHLN|SBSN|RMP)$/', $raw)) {
                return $raw;
            }
        }
        return '';
    }

    private static function detectBlokirFlag(array $row): bool
    {
        foreach ([2, 10, 12, 14] as $idx) {
            $raw = trim((string) ($row[$idx] ?? ''));
            if ($raw === '*') {
                return true;
            }
        }
        return false;
    }

    /**
     * Normalisasi parameter filter kelompok akun RKKS ('51', '52', '53') dari string CSV atau array.
     *
     * @return list<string>
     */
    public static function normalizeAkunGroups($raw): array
    {
        if ($raw === null || $raw === '') {
            return [];
        }

        $items = is_array($raw) ? $raw : explode(',', (string) $raw);
        $valid = [];
        foreach ($items as $item) {
            $token = trim((string) $item);
            if (preg_match('/^(51|52|53)/', $token, $m)) {
                $grp = $m[1];
                if (!in_array($grp, $valid, true)) {
                    $valid[] = $grp;
                }
            }
        }
        sort($valid);
        return $valid;
    }

    /**
     * Saring hierarki item RKKS agar hanya memuat subtree akun (51/52/53) terpilih beserta induknya,
     * lalu hitung ulang pagu rollup induk sesuai item yang tersisa.
     *
     * @param list<array<string, mixed>> $items
     * @param list<string> $akunGroups
     * @return list<array<string, mixed>>
     */
    public static function filterItemsByAkunGroups(array $items, array $akunGroups): array
    {
        $akunGroups = self::normalizeAkunGroups($akunGroups);
        if (empty($items) || empty($akunGroups) || count($akunGroups) >= count(self::AKUN_GROUP_LABELS)) {
            return $items;
        }

        $idKey = array_key_exists('id_item', $items[0]) ? 'id_item' : 'temp_id';
        $parentKey = array_key_exists('parent_id', $items[0]) ? 'parent_id' : 'parent_temp_id';

        $byId = [];
        $childrenMap = [];
        $matchedAkunIds = [];
        $groupMap = array_flip($akunGroups);

        foreach ($items as $item) {
            $id = (int) ($item[$idKey] ?? 0);
            $byId[$id] = $item;
            $pid = $item[$parentKey] !== null ? (int) $item[$parentKey] : 0;
            $childrenMap[$pid][] = $id;
            if (($item['level_type'] ?? '') === 'akun') {
                $grp = substr((string) ($item['kode'] ?? ''), 0, 2);
                if (isset($groupMap[$grp])) {
                    $matchedAkunIds[] = $id;
                }
            }
        }

        if (empty($matchedAkunIds)) {
            return [];
        }

        $keepIds = [];
        foreach ($matchedAkunIds as $akunId) {
            // Simpan leluhur ke atas (subkomponen -> ... -> program)
            $curr = $akunId;
            $guard = 0;
            while ($curr > 0 && isset($byId[$curr]) && $guard < 25) {
                $keepIds[$curr] = true;
                $curr = $byId[$curr][$parentKey] !== null ? (int) $byId[$curr][$parentKey] : 0;
                $guard++;
            }

            // Simpan seluruh keturunan di bawah akun (header_detail -> detail)
            $queue = [$akunId];
            $qIdx = 0;
            while ($qIdx < count($queue)) {
                $nodeId = $queue[$qIdx++];
                $keepIds[$nodeId] = true;
                foreach ($childrenMap[$nodeId] ?? [] as $childId) {
                    if (!isset($keepIds[$childId])) {
                        $queue[] = $childId;
                    }
                }
            }
        }

        $filtered = [];
        foreach ($items as $item) {
            $id = (int) ($item[$idKey] ?? 0);
            if (isset($keepIds[$id])) {
                $filtered[] = $item;
            }
        }

        self::recalculateTreeAmounts($filtered);
        return $filtered;
    }
}
