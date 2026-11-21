<?php

namespace App\Libraries;

use App\Models\AbsenTargetModel;
use Config\Services;

class AbsenZonaEvaluator
{
    private const CACHE_KEY = 'absen_target_all_v1';
    private const CACHE_TTL = 600;
    private const DEFAULT_TOLERANCE_METERS = 50.0;
    private const METERS_PER_DEGREE = 111320.0;

    /** @var array<int, array<string, mixed>>|null */
    private static ?array $rawTargets = null;

    /** @var array<int, array<string, mixed>>|null */
    private static ?array $parsedPolygons = null;

    /**
     * Ambil daftar target absen (ter-cache bersama endpoint /getTargetAreaAbsen).
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getRawTargets(): array
    {
        if (self::$rawTargets !== null) {
            return self::$rawTargets;
        }

        $cache = Services::cache();
        $targets = $cache->get(self::CACHE_KEY);
        if (!is_array($targets)) {
            $model = new AbsenTargetModel();
            $targets = $model->findAll();
            $cache->save(self::CACHE_KEY, $targets, self::CACHE_TTL);
        }

        self::$rawTargets = $targets;
        return self::$rawTargets;
    }

    /**
     * Ambil daftar pilihan alamat (ket_tempat) untuk dropdown modal tanpa query DB tambahan.
     *
     * @return array<int, array{ket_tempat: string}>
     */
    public static function getAlamatList(): array
    {
        $list = [];
        foreach (self::getRawTargets() as $row) {
            $list[] = ['ket_tempat' => (string) ($row['ket_tempat'] ?? '')];
        }
        return $list;
    }

    /**
     * Parse semua poligon target sekali per request lengkap dengan bounding box untuk pre-check cepat.
     *
     * @return array<int, array<string, mixed>>
     */
    private static function getParsedPolygons(): array
    {
        if (self::$parsedPolygons !== null) {
            return self::$parsedPolygons;
        }

        $parsed = [];
        foreach (self::getRawTargets() as $item) {
            $rawPoly = (string) ($item['polygon'] ?? '');
            if ($rawPoly === '') {
                continue;
            }

            $cleaned = trim(preg_replace('/\s+/', ' ', str_replace(["\r\n", "\r", "\n"], '', $rawPoly)));
            $coords = json_decode($cleaned, true);
            if (!is_array($coords) || count($coords) < 3) {
                continue;
            }

            $points = [];
            $minX = INF;
            $maxX = -INF;
            $minY = INF;
            $maxY = -INF;

            foreach ($coords as $pt) {
                if (!is_array($pt) || count($pt) < 2) {
                    continue;
                }
                $px = (float) $pt[0];
                $py = (float) $pt[1];
                $points[] = [$px, $py];
                if ($px < $minX) $minX = $px;
                if ($px > $maxX) $maxX = $px;
                if ($py < $minY) $minY = $py;
                if ($py > $maxY) $maxY = $py;
            }

            if (count($points) < 3) {
                continue;
            }

            $tolMeters = isset($item['tolerance_meters']) && is_numeric($item['tolerance_meters'])
                ? (float) $item['tolerance_meters']
                : self::DEFAULT_TOLERANCE_METERS;
            if ($tolMeters < 0) {
                $tolMeters = 0.0;
            }
            $tolDeg = $tolMeters / self::METERS_PER_DEGREE;

            $parsed[] = [
                'ket_tempat' => (string) ($item['ket_tempat'] ?? ''),
                'points'     => $points,
                'count'      => count($points),
                'tol_deg'    => $tolDeg,
                'tol_deg_sq' => $tolDeg * $tolDeg,
                'min_x'      => $minX,
                'max_x'      => $maxX,
                'min_y'      => $minY,
                'max_y'      => $maxY,
                'pad_min_x'  => $minX - $tolDeg,
                'pad_max_x'  => $maxX + $tolDeg,
                'pad_min_y'  => $minY - $tolDeg,
                'pad_max_y'  => $maxY + $tolDeg,
            ];
        }

        self::$parsedPolygons = $parsed;
        return self::$parsedPolygons;
    }

    /**
     * Cek apakah titik (lon, lat) berada di dalam poligon atau dalam radius toleransi poligon.
     * Mengembalikan nama zona (ket_tempat) atau null jika diluar wilayah absen.
     */
    public static function matchZone(float $x, float $y): ?string
    {
        $polygons = self::getParsedPolygons();
        if (empty($polygons)) {
            return null;
        }

        // Prioritas 1: Benar-benar di dalam polygon (ray-casting)
        foreach ($polygons as $poly) {
            if ($x < $poly['min_x'] || $x > $poly['max_x'] || $y < $poly['min_y'] || $y > $poly['max_y']) {
                continue;
            }
            if (self::isPointInPolygon($x, $y, $poly['points'], $poly['count'])) {
                return $poly['ket_tempat'];
            }
        }

        // Prioritas 2: Berada dalam radius toleransi (buffer zone), konsisten dengan /absensi
        foreach ($polygons as $poly) {
            if ($poly['tol_deg_sq'] <= 0) {
                continue;
            }
            if ($x < $poly['pad_min_x'] || $x > $poly['pad_max_x'] || $y < $poly['pad_min_y'] || $y > $poly['pad_max_y']) {
                continue;
            }
            if (self::distToPolygonSquared($x, $y, $poly['points'], $poly['count']) <= $poly['tol_deg_sq']) {
                return $poly['ket_tempat'];
            }
        }

        return null;
    }

    private static function isPointInPolygon(float $x, float $y, array $points, int $n): bool
    {
        $inside = false;
        for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
            $xi = $points[$i][0];
            $yi = $points[$i][1];
            $xj = $points[$j][0];
            $yj = $points[$j][1];

            $intersect = (($yi > $y) !== ($yj > $y))
                && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);
            if ($intersect) {
                $inside = !$inside;
            }
        }
        return $inside;
    }

    private static function distToPolygonSquared(float $px, float $py, array $points, int $n): float
    {
        $minDist2 = INF;
        for ($i = 0; $i < $n; $i++) {
            $j = ($i + 1) % $n;
            $vx = $points[$i][0];
            $vy = $points[$i][1];
            $wx = $points[$j][0];
            $wy = $points[$j][1];

            $dx = $wx - $vx;
            $dy = $wy - $vy;
            $l2 = ($dx * $dx) + ($dy * $dy);

            if ($l2 === 0.0) {
                $ex = $px - $vx;
                $ey = $py - $vy;
                $d2 = ($ex * $ex) + ($ey * $ey);
            } else {
                $t = (($px - $vx) * $dx + ($py - $vy) * $dy) / $l2;
                if ($t < 0.0) $t = 0.0;
                elseif ($t > 1.0) $t = 1.0;
                $projX = $vx + $t * $dx;
                $projY = $vy + $t * $dy;
                $ex = $px - $projX;
                $ey = $py - $projY;
                $d2 = ($ex * $ex) + ($ey * $ey);
            }

            if ($d2 < $minDist2) {
                $minDist2 = $d2;
            }
        }
        return $minDist2;
    }

    /**
     * Evaluasi satu kolom alamat (masuk / pulang) agar langsung siap dirender di HTML.
     *
     * @return array{cell_class: string, html: string, show_verifikasi: bool}
     */
    public static function evaluateCell(
        ?string $longitude,
        ?string $latitude,
        ?string $alamat,
        ?string $jam,
        ?string $validation
    ): array {
        $lonStr = trim((string) $longitude);
        $latStr = trim((string) $latitude);
        $alamatStr = (string) $alamat;
        $jamStr = trim((string) $jam);
        $valStr = trim((string) $validation);

        if ($lonStr !== '' && $latStr !== '' && is_numeric($lonStr) && is_numeric($latStr)) {
            $lon = (float) $lonStr;
            $lat = (float) $latStr;
            $zone = self::matchZone($lon, $lat);
            $coordHtml = '<br/><i>lon: ' . esc($lonStr) . ', lat: ' . esc($latStr) . '</i>';

            if ($zone !== null) {
                return [
                    'cell_class'      => 'table-success',
                    'html'            => esc($zone) . $coordHtml,
                    'show_verifikasi' => false,
                ];
            }

            if ($valStr === 'Validasi' || $valStr === 'Valid') {
                return [
                    'cell_class'      => 'table-warning',
                    'html'            => "<strong class='text-success'>Absen Terverifikasi <i class='fas fa-check-circle'></i></strong> <br/>" . esc($alamatStr) . $coordHtml,
                    'show_verifikasi' => false,
                ];
            }

            $badge = $valStr === 'Invalidasi'
                ? "<strong class='text-danger'>Absen Tidak Valid <i class='fas fa-times-circle'></i></strong>"
                : "<strong>Diluar Zona Absen</strong>";

            return [
                'cell_class'      => 'table-danger',
                'html'            => $badge . ' <br/>' . esc($alamatStr) . $coordHtml,
                'show_verifikasi' => ($valStr === 'Default' || $valStr === ''),
            ];
        }

        if ($jamStr !== '') {
            return [
                'cell_class'      => 'table-success',
                'html'            => 'Data Sesuai',
                'show_verifikasi' => false,
            ];
        }

        return [
            'cell_class'      => 'table-danger',
            'html'            => '',
            'show_verifikasi' => false,
        ];
    }

    /**
     * Format jam kerja aman (tidak crash saat jam_masuk kosong, dan tidak tampilkan "3 bulan" bila belum pulang di hari lampau).
     */
    public static function formatJamKerja(?string $jamMasuk, ?string $jamPulang): string
    {
        $masuk = trim((string) $jamMasuk);
        $pulang = trim((string) $jamPulang);

        if ($masuk === '') {
            return '-';
        }
        if ($pulang !== '') {
            return selisi_waktu($masuk, $pulang);
        }
        // Jika belum clock-out namun absennya hari ini, tampilkan durasi berjalan
        if (substr($masuk, 0, 10) === date('Y-m-d')) {
            return selisi_waktu($masuk, null);
        }
        return '-';
    }

    /**
     * Perkaya seluruh baris hasil query absen dengan hasil evaluasi zona masuk, zona pulang, dan jam kerja.
     *
     * @param array<int, array<string, mixed>> $rows
     */
    public static function enrichRows(array &$rows): void
    {
        foreach ($rows as &$row) {
            $row['eval_masuk'] = self::evaluateCell(
                $row['longitude_masuk'] ?? null,
                $row['latitude_masuk'] ?? null,
                $row['alamat_masuk'] ?? null,
                $row['jam_masuk'] ?? null,
                $row['validation_masuk'] ?? null
            );
            $row['eval_pulang'] = self::evaluateCell(
                $row['longitude_pulang'] ?? null,
                $row['latitude_pulang'] ?? null,
                $row['alamat_pulang'] ?? null,
                $row['jam_pulang'] ?? null,
                $row['validation_pulang'] ?? null
            );
            $row['jam_kerja_text'] = self::formatJamKerja(
                $row['jam_masuk'] ?? null,
                $row['jam_pulang'] ?? null
            );
        }
        unset($row);
    }
}
