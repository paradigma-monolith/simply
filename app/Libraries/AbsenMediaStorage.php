<?php

declare(strict_types=1);

namespace App\Libraries;

use CodeIgniter\HTTP\Files\UploadedFile;

/**
 * Penyimpanan & resolusi terpusat media absensi privat (writable/absen_media).
 * Mendukung 3 format secara transparan selama & sesudah migrasi:
 *   1. media:<relPath> (media:foto/YYYY/MM/..., media:ttd/YYYY/MM/..., media:legacy/...)
 *   2. data:image/<type>;base64,... (termasuk pemulihan toleran payload terpotong 65535 byte)
 *   3. /dist/... atau dist/... (path legacy)
 */
class AbsenMediaStorage
{
    public const MEDIA_PREFIX = 'media:';

    private const ALLOWED_CATEGORIES = ['foto', 'ttd'];

    private const ALLOWED_EXTS = [
        'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'tiff', 'ico',
    ];

    private const MIME_MAP = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'bmp'  => 'image/bmp',
        'webp' => 'image/webp',
        'tiff' => 'image/tiff',
        'ico'  => 'image/x-icon',
    ];

    private string $baseDir;
    private string $legacyPublicDir;

    public function __construct(?string $baseDir = null, ?string $legacyPublicDir = null)
    {
        $defaultBase = rtrim(WRITEPATH, '/\\') . DIRECTORY_SEPARATOR . 'absen_media';
        $this->baseDir = rtrim($baseDir ?? $defaultBase, '/\\');

        $envLegacy = function_exists('env') ? env('ABSEN_LEGACY_FOTO_DIR') : getenv('ABSEN_LEGACY_FOTO_DIR');
        $defaultLegacy = (is_string($envLegacy) && trim($envLegacy) !== '')
            ? trim($envLegacy)
            : (rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR . 'dist' . DIRECTORY_SEPARATOR . 'foto_absen');
        $this->legacyPublicDir = rtrim($legacyPublicDir ?? $defaultLegacy, '/\\');
    }

    public function getBaseDir(): string
    {
        return $this->baseDir;
    }

    public function getLegacyPublicDir(): string
    {
        return $this->legacyPublicDir;
    }

    public static function isEmptyValue(?string $raw): bool
    {
        if ($raw === null) {
            return true;
        }
        $trimmed = trim($raw);
        return $trimmed === '' || $trimmed === 'data:,';
    }

    public static function isMediaRef(?string $raw): bool
    {
        if ($raw === null) {
            return false;
        }
        $trimmed = trim($raw);
        return strncmp($trimmed, self::MEDIA_PREFIX, strlen(self::MEDIA_PREFIX)) === 0
            && strlen($trimmed) > strlen(self::MEDIA_PREFIX);
    }

    /**
     * Decode data URI atau raw base64 dengan pemulihan toleran untuk baris Juli 2024
     * yang sempat terpotong pada 65.535 byte (bekas kolom TEXT).
     *
     * @return array{binary: string, mime: string, ext: string, truncated: bool}|null
     */
    public function decodeBase64Tolerant(string $raw): ?array
    {
        if (self::isEmptyValue($raw)) {
            return null;
        }

        $trimmed = trim($raw);
        $declaredMime = null;
        $payload = $trimmed;

        if (preg_match('/^data:(image\/[a-zA-Z0-9.+-]*);base64,(.+)$/s', $trimmed, $m)) {
            $declaredMime = strtolower($m[1]);
            $payload = $m[2];
        } elseif (stripos($trimmed, 'data:') === 0) {
            // Non-image atau data URI tidak valid (termasuk data:,)
            return null;
        }

        $truncated = false;
        $trimmedPayload = trim($payload);
        if (strlen($trimmedPayload) % 4 !== 0) {
            $truncated = true;
        }
        $binary = base64_decode($trimmedPayload, true);

        if ($binary === false || $binary === '') {
            $compact = preg_replace('/\s+/', '', $trimmedPayload);
            if (!is_string($compact) || $compact === '' || !preg_match('/^[A-Za-z0-9+\/=]+$/', $compact)) {
                return null;
            }
            $unpadded = rtrim($compact, '=');
            $rem = strlen($unpadded) % 4;
            if ($rem === 1) {
                $unpadded = substr($unpadded, 0, -1);
            }
            $rem = strlen($unpadded) % 4;
            if ($rem > 0) {
                $unpadded .= str_repeat('=', 4 - $rem);
            }
            $binary = base64_decode($unpadded, true);
            if ($binary === false || $binary === '') {
                $binary = base64_decode($unpadded, false);
            }
            if ($binary === false || $binary === '') {
                return null;
            }
            $truncated = true;
        }

        if (strlen($binary) < 32) {
            return null;
        }

        $detected = $this->detectImageType($binary, $declaredMime);
        if ($detected === null) {
            return null;
        }

        if ($detected['ext'] === 'jpg' && substr($binary, -2) !== "\xFF\xD9") {
            $truncated = true;
        }

        return [
            'binary'    => $binary,
            'mime'      => $detected['mime'],
            'ext'       => $detected['ext'],
            'truncated' => $truncated,
        ];
    }

    /**
     * @return array{relPath: string, mediaRef: string, fullPath: string, mime: string, size: int}|null
     */
    public function saveBase64(
        string $dataUri,
        string $category = 'foto',
        ?string $dateStr = null,
        ?string $prefix = null
    ): ?array {
        $decoded = $this->decodeBase64Tolerant($dataUri);
        if ($decoded === null) {
            return null;
        }

        $filename = null;
        if ($prefix !== null && $prefix !== '') {
            $safePrefix = preg_replace('/[^A-Za-z0-9_-]/', '', $prefix) ?: 'abs';
            $filename = uniqid($safePrefix . '_', true) . '.' . $decoded['ext'];
        }

        return $this->saveBinary($decoded['binary'], $decoded['ext'], $category, $dateStr, $filename);
    }

    /**
     * @return array{relPath: string, mediaRef: string, fullPath: string, mime: string, size: int}|null
     */
    public function saveUploadedFile(
        UploadedFile $file,
        string $category = 'foto',
        ?string $dateStr = null
    ): ?array {
        if (!$file->isValid() || $file->hasMoved()) {
            return null;
        }

        $ext = strtolower((string) $file->getExtension());
        if (!in_array($ext, self::ALLOWED_EXTS, true)) {
            return null;
        }

        $tmpPath = $file->getTempName();
        if (!is_file($tmpPath)) {
            return null;
        }

        $binary = @file_get_contents($tmpPath);
        if (!is_string($binary) || strlen($binary) < 16) {
            return null;
        }

        $detected = $this->detectImageType($binary, null);
        if ($detected === null) {
            return null;
        }

        return $this->saveBinary($binary, $detected['ext'], $category, $dateStr);
    }

    /**
     * Menulis biner secara atomik (temp + rename) dan memverifikasi filesize > 0.
     * Mengembalikan null tanpa throw apabila direktori gagal dibuat/ditulis.
     *
     * @return array{relPath: string, mediaRef: string, fullPath: string, mime: string, size: int}|null
     */
    public function saveBinary(
        string $binary,
        string $ext,
        string $category = 'foto',
        ?string $dateStr = null,
        ?string $filename = null
    ): ?array {
        if ($binary === '') {
            return null;
        }

        $cat = in_array($category, self::ALLOWED_CATEGORIES, true) ? $category : 'foto';
        $normExt = strtolower(trim($ext));
        if ($normExt === 'jpeg') {
            $normExt = 'jpg';
        }
        if (!in_array($normExt, self::ALLOWED_EXTS, true)) {
            $normExt = 'jpg';
        }

        [$year, $month] = $this->extractYearMonth($dateStr);

        if (is_file($this->baseDir)) {
            return null;
        }

        $targetDir = $this->baseDir
            . DIRECTORY_SEPARATOR . $cat
            . DIRECTORY_SEPARATOR . $year
            . DIRECTORY_SEPARATOR . $month;

        if (!is_dir($targetDir) && !@mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
            return null;
        }

        if ($filename === null || trim($filename) === '') {
            $defaultPrefix = ($cat === 'ttd') ? 'ttd_' : 'abs_';
            $safeName = uniqid($defaultPrefix, true) . '.' . $normExt;
        } else {
            $safeName = basename($filename);
            if (!preg_match('/^[A-Za-z0-9._-]+$/', $safeName)) {
                return null;
            }
        }

        $fullPath = $targetDir . DIRECTORY_SEPARATOR . $safeName;
        $tmpFile  = $targetDir . DIRECTORY_SEPARATOR . '.tmp_' . uniqid('', true);

        $written = @file_put_contents($tmpFile, $binary, LOCK_EX);
        if ($written === false || $written !== strlen($binary) || !is_file($tmpFile) || filesize($tmpFile) <= 0) {
            @unlink($tmpFile);
            return null;
        }

        if (!@rename($tmpFile, $fullPath)) {
            @unlink($tmpFile);
            return null;
        }

        clearstatcache(true, $fullPath);
        if (!is_file($fullPath)) {
            return null;
        }
        $size = (int) filesize($fullPath);
        if ($size <= 0) {
            @unlink($fullPath);
            return null;
        }

        $relPath = "{$cat}/{$year}/{$month}/{$safeName}";

        return [
            'relPath'  => $relPath,
            'mediaRef' => self::MEDIA_PREFIX . $relPath,
            'fullPath' => $fullPath,
            'mime'     => self::MIME_MAP[$normExt] ?? 'image/jpeg',
            'size'     => $size,
        ];
    }

    /**
     * Resolve referensi berbasis file (media:... atau path legacy /dist/...) ke path absolut aman.
     * Mengembalikan null untuk nilai kosong, base64, traversal, atau file yang tidak ada.
     *
     * @return array{path: string, mime: string}|null
     */
    public function resolveToFile(?string $raw): ?array
    {
        if (self::isEmptyValue($raw)) {
            return null;
        }

        $trimmed = trim((string) $raw);
        if (strpos($trimmed, "\0") !== false) {
            return null;
        }

        if (self::isMediaRef($trimmed)) {
            $rel = substr($trimmed, strlen(self::MEDIA_PREFIX));
            if (!$this->isSafeRelativePath($rel)) {
                return null;
            }

            $candidate = $this->baseDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rel);
            $real = $this->safeRealpathInside($candidate, $this->baseDir);
            if ($real !== null) {
                return ['path' => $real, 'mime' => $this->mimeFromPath($real)];
            }

            // Fallback transisi sebelum `mv dist/foto_absen -> writable/absen_media/legacy`
            if (strncmp($rel, 'legacy/', 7) === 0) {
                $fileOnly = substr($rel, 7);
                if ($this->isSafeFilename($fileOnly)) {
                    $legacyCandidate = $this->legacyPublicDir . DIRECTORY_SEPARATOR . $fileOnly;
                    $realLegacy = $this->safeRealpathInside($legacyCandidate, $this->legacyPublicDir);
                    if ($realLegacy !== null) {
                        return ['path' => $realLegacy, 'mime' => $this->mimeFromPath($realLegacy)];
                    }
                }
            }

            return null;
        }

        if ($trimmed[0] === '/' || strncmp($trimmed, 'dist/', 5) === 0) {
            $rel = ltrim($trimmed, '/');
            if (!$this->isSafeRelativePath($rel)) {
                return null;
            }

            if (strncmp($rel, 'dist/foto_absen/', 16) === 0) {
                $fileOnly = substr($rel, 16);
                if ($this->isSafeFilename($fileOnly)) {
                    $res = $this->resolveRekamFotoPath($fileOnly);
                    if ($res !== null) {
                        return ['path' => $res['path'], 'mime' => $res['mime']];
                    }
                }
                return null;
            }

            $distRoot = rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR . 'dist';
            $candidate = rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rel);
            $real = $this->safeRealpathInside($candidate, $distRoot);
            if ($real !== null) {
                return ['path' => $real, 'mime' => $this->mimeFromPath($real)];
            }

            return null;
        }

        return null;
    }

    /**
     * Resolve referensi media (file privat, path legacy, atau base64) ke biner + MIME.
     *
     * @return array{binary: string, mime: string}|null
     */
    public function resolveToBinary(?string $raw): ?array
    {
        if (self::isEmptyValue($raw)) {
            return null;
        }

        $file = $this->resolveToFile($raw);
        if ($file !== null) {
            $bin = @file_get_contents($file['path']);
            if (is_string($bin) && $bin !== '') {
                return ['binary' => $bin, 'mime' => $file['mime']];
            }
            return null;
        }

        $decoded = $this->decodeBase64Tolerant((string) $raw);
        if ($decoded !== null) {
            return ['binary' => $decoded['binary'], 'mime' => $decoded['mime']];
        }

        return null;
    }

    /**
     * Resolve referensi media ke data URI (`data:image/...;base64,...`) untuk Dompdf & download DL.
     */
    public function resolveToDataUri(?string $raw): ?string
    {
        if (self::isEmptyValue($raw)) {
            return null;
        }

        $trimmed = trim((string) $raw);
        if (preg_match('/^data:(image\/[a-zA-Z0-9.+-]*);base64,.+$/s', $trimmed)) {
            $decoded = $this->decodeBase64Tolerant($trimmed);
            if ($decoded !== null) {
                return 'data:' . $decoded['mime'] . ';base64,' . base64_encode($decoded['binary']);
            }
            return null;
        }

        $bin = $this->resolveToBinary($trimmed);
        if ($bin === null) {
            return null;
        }

        return 'data:' . $bin['mime'] . ';base64,' . base64_encode($bin['binary']);
    }

    /**
     * Resolve nilai kolom `foto` pada JSON `rekam_absen` (baik nama file lama `abs_xxx.jpg`
     * maupun path baru `foto/YYYY/MM/abs_xxx.jpg`) ke path absolut + canonical mediaRef.
     *
     * @return array{path: string, mime: string, mediaRef: string}|null
     */
    public function resolveRekamFotoPath(string $fotoRef): ?array
    {
        $clean = trim($fotoRef);
        if ($clean === '' || strpos($clean, "\0") !== false) {
            return null;
        }

        if (self::isMediaRef($clean)) {
            $clean = substr($clean, strlen(self::MEDIA_PREFIX));
        }

        $clean = ltrim($clean, '/');
        if (strncmp($clean, 'dist/foto_absen/', 16) === 0) {
            $clean = substr($clean, 16);
        }

        if (!$this->isSafeRelativePath($clean)) {
            return null;
        }

        // Format path relatif berfolder (foto/YYYY/MM/..., ttd/..., legacy/...)
        if (strpos($clean, '/') !== false) {
            $candidate = $this->baseDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $clean);
            $real = $this->safeRealpathInside($candidate, $this->baseDir);
            if ($real !== null) {
                return [
                    'path'     => $real,
                    'mime'     => $this->mimeFromPath($real),
                    'mediaRef' => self::MEDIA_PREFIX . $clean,
                ];
            }
            if (strncmp($clean, 'legacy/', 7) === 0) {
                $fileOnly = substr($clean, 7);
                if ($this->isSafeFilename($fileOnly)) {
                    $legacyCandidate = $this->legacyPublicDir . DIRECTORY_SEPARATOR . $fileOnly;
                    $realLegacy = $this->safeRealpathInside($legacyCandidate, $this->legacyPublicDir);
                    if ($realLegacy !== null) {
                        return [
                            'path'     => $realLegacy,
                            'mime'     => $this->mimeFromPath($realLegacy),
                            'mediaRef' => self::MEDIA_PREFIX . 'legacy/' . $fileOnly,
                        ];
                    }
                }
            }
            return null;
        }

        // Nama file polos legacy (e.g. abs_6b02....jpg atau 66bd7c20acb44.jpg)
        if (!$this->isSafeFilename($clean)) {
            return null;
        }

        $legacyDir = $this->baseDir . DIRECTORY_SEPARATOR . 'legacy';
        $realMoved = $this->safeRealpathInside($legacyDir . DIRECTORY_SEPARATOR . $clean, $this->baseDir);
        if ($realMoved !== null) {
            return [
                'path'     => $realMoved,
                'mime'     => $this->mimeFromPath($realMoved),
                'mediaRef' => self::MEDIA_PREFIX . 'legacy/' . $clean,
            ];
        }

        $realPublic = $this->safeRealpathInside($this->legacyPublicDir . DIRECTORY_SEPARATOR . $clean, $this->legacyPublicDir);
        if ($realPublic !== null) {
            return [
                'path'     => $realPublic,
                'mime'     => $this->mimeFromPath($realPublic),
                'mediaRef' => self::MEDIA_PREFIX . 'legacy/' . $clean,
            ];
        }

        return null;
    }

    public function canonicalFromRekamFoto(string $fotoRef): ?string
    {
        $resolved = $this->resolveRekamFotoPath($fotoRef);
        return $resolved['mediaRef'] ?? null;
    }

    /**
     * Bangun peta `sha256 => mediaRef` dari seluruh file yang dirujuk JSON `rekam_absen`
     * untuk deduplikasi nol-duplikat saat backfill.
     *
     * @return array<string, string>
     */
    public function buildRekamHashMap(?string $rekamJson): array
    {
        if ($rekamJson === null || trim($rekamJson) === '') {
            return [];
        }

        $decoded = json_decode($rekamJson, true);
        if (!is_array($decoded)) {
            return [];
        }

        $map = [];
        foreach ($decoded as $item) {
            if (!is_array($item) || empty($item['foto']) || !is_string($item['foto'])) {
                continue;
            }
            $resolved = $this->resolveRekamFotoPath($item['foto']);
            if ($resolved === null) {
                continue;
            }
            $hash = @hash_file('sha256', $resolved['path']);
            if (is_string($hash) && $hash !== '' && !isset($map[$hash])) {
                $map[$hash] = $resolved['mediaRef'];
            }
        }

        return $map;
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function extractYearMonth(?string $dateStr): array
    {
        if ($dateStr !== null && preg_match('/^(\d{4})-(\d{2})/', trim($dateStr), $m)) {
            $y = (int) $m[1];
            $mo = (int) $m[2];
            if ($y >= 2000 && $y <= 2100 && $mo >= 1 && $mo <= 12) {
                return [$m[1], $m[2]];
            }
        }
        return [date('Y'), date('m')];
    }

    private function isSafeRelativePath(string $rel): bool
    {
        if ($rel === '' || strpos($rel, "\0") !== false || strpos($rel, '\\') !== false) {
            return false;
        }
        if ($rel[0] === '/' || strpos($rel, '..') !== false) {
            return false;
        }
        $segments = explode('/', $rel);
        foreach ($segments as $seg) {
            if ($seg === '' || $seg === '.' || $seg === '..') {
                return false;
            }
            if (!preg_match('/^[A-Za-z0-9._-]+$/', $seg)) {
                return false;
            }
        }
        return true;
    }

    private function isSafeFilename(string $name): bool
    {
        return $name !== ''
            && strpos($name, '/') === false
            && strpos($name, '\\') === false
            && strpos($name, '..') === false
            && strpos($name, "\0") === false
            && (bool) preg_match('/^[A-Za-z0-9._-]+$/', $name);
    }

    private function safeRealpathInside(string $candidate, string $allowedRoot): ?string
    {
        if (strpos($candidate, "\0") !== false) {
            return null;
        }
        $realRoot = realpath($allowedRoot);
        $realFile = realpath($candidate);
        if ($realRoot === false || $realFile === false || !is_file($realFile) || filesize($realFile) <= 0) {
            return null;
        }

        $normRoot = rtrim(str_replace('\\', '/', $realRoot), '/') . '/';
        $normFile = str_replace('\\', '/', $realFile);

        if (DIRECTORY_SEPARATOR === '\\') {
            $inRoot = stripos($normFile, $normRoot) === 0;
        } else {
            $inRoot = strncmp($normFile, $normRoot, strlen($normRoot)) === 0;
        }

        return $inRoot ? $realFile : null;
    }

    private function mimeFromPath(string $path): string
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return self::MIME_MAP[$ext] ?? 'image/jpeg';
    }

    /**
     * @return array{mime: string, ext: string}|null
     */
    private function detectImageType(string $binary, ?string $declaredMime): ?array
    {
        if (strncmp($binary, "\xFF\xD8\xFF", 3) === 0) {
            return ['mime' => 'image/jpeg', 'ext' => 'jpg'];
        }
        if (strncmp($binary, "\x89PNG\r\n\x1A\n", 8) === 0) {
            return ['mime' => 'image/png', 'ext' => 'png'];
        }
        if (strncmp($binary, 'GIF87a', 6) === 0 || strncmp($binary, 'GIF89a', 6) === 0) {
            return ['mime' => 'image/gif', 'ext' => 'gif'];
        }
        if (strlen($binary) >= 12 && strncmp($binary, 'RIFF', 4) === 0 && substr($binary, 8, 4) === 'WEBP') {
            return ['mime' => 'image/webp', 'ext' => 'webp'];
        }
        if (strncmp($binary, 'BM', 2) === 0) {
            return ['mime' => 'image/bmp', 'ext' => 'bmp'];
        }
        if (strncmp($binary, "II*\x00", 4) === 0 || strncmp($binary, "MM\x00*", 4) === 0) {
            return ['mime' => 'image/tiff', 'ext' => 'tiff'];
        }

        return null;
    }
}
