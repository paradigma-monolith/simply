<?php

namespace App\Libraries;

use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;
use Throwable;

/**
 * Penyimpanan persisten pemetaan device token -> NIP pegawai.
 *
 * Menggunakan database MySQL (`absen_device_tokens`) sebagai sumber kebenaran
 * utama yang tahan terhadap restart Redis, `cache:clear`, deploy ulang, dan
 * perubahan prefix cache, dilengkapi read/write-through cache untuk kecepatan
 * serta sliding expiration 365 hari.
 */
class AbsenNipPersistence
{
    public const COOKIE_NAME = 'persistent_token';
    public const CACHE_KEY_PREFIX = 'user_nip_token_';
    public const TOUCH_KEY_PREFIX = 'user_nip_touch_';

    /**
     * Masa berlaku cookie & token di database: 365 hari (di bawah batas 400 hari Chrome).
     */
    public const COOKIE_TTL_SECONDS = 31536000;

    /**
     * Masa berlaku salinan di cache (otomatis diisi ulang dari database bila miss).
     */
    public const CACHE_TTL_SECONDS = 2592000;

    /**
     * Interval minimum pembaruan sliding expiration di database/cookie (24 jam).
     */
    public const REFRESH_INTERVAL_SECONDS = 86400;

    private static bool $tableChecked = false;

    public static function isValidTokenFormat(?string $token): bool
    {
        return is_string($token) && preg_match('/^[a-f0-9]{16,128}$/i', $token) === 1;
    }

    public static function getValidCookieToken(IncomingRequest $request): string
    {
        $token = $request->getCookie(self::COOKIE_NAME);
        return self::isValidTokenFormat($token) ? (string) $token : '';
    }

    /**
     * Memastikan tabel `absen_device_tokens` tersedia bahkan bila migrasi belum dijalankan manual.
     */
    public static function ensureTable(): bool
    {
        if (self::$tableChecked) {
            return true;
        }

        try {
            $db = Database::connect();
            $db->query(
                "CREATE TABLE IF NOT EXISTS `absen_device_tokens` (
                    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `token_hash` CHAR(64) NOT NULL,
                    `nip` VARCHAR(32) NOT NULL,
                    `user_agent` VARCHAR(255) NULL,
                    `expires_at` DATETIME NOT NULL,
                    `last_used_at` DATETIME NULL,
                    `created_at` DATETIME NOT NULL,
                    `updated_at` DATETIME NOT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `uq_absen_device_tokens_hash` (`token_hash`),
                    KEY `idx_absen_device_tokens_nip` (`nip`),
                    KEY `idx_absen_device_tokens_expires` (`expires_at`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
            );
            self::$tableChecked = true;
            return true;
        } catch (Throwable $e) {
            log_message('error', 'AbsenNipPersistence::ensureTable error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Menyimpan / memperbarui pemetaan token -> NIP ke database, cache, dan cookie.
     */
    public static function persistNip(IncomingRequest $request, ResponseInterface $response, string $nip): string
    {
        $nip = trim($nip);
        if ($nip === '') {
            return '';
        }

        $token = self::getValidCookieToken($request);
        if ($token === '') {
            $token = bin2hex(random_bytes(16));
        }

        $userAgent = self::extractUserAgent($request);
        self::upsertDatabaseToken($token, $nip, $userAgent);
        self::saveToCache($token, $nip);
        self::markTouched($token);
        self::setPersistentCookie($response, $token);

        return $token;
    }

    /**
     * Membaca NIP berdasarkan cookie `persistent_token` pada request.
     */
    public static function resolveNip(IncomingRequest $request, ?ResponseInterface $response = null): string
    {
        $token = self::getValidCookieToken($request);
        if ($token === '') {
            return '';
        }

        return self::resolveByToken($token, $request, $response, false);
    }

    /**
     * Membaca NIP berdasarkan token tertentu (dipakai oleh cookie maupun sync token dari localStorage).
     */
    public static function resolveByToken(
        ?string $token,
        ?IncomingRequest $request = null,
        ?ResponseInterface $response = null,
        bool $forceSetCookie = false
    ): string {
        if (!self::isValidTokenFormat($token)) {
            return '';
        }

        $token = (string) $token;
        $tokenHash = hash('sha256', $token);
        $userAgent = $request ? self::extractUserAgent($request) : null;

        // 1. Fast path: cek cache aktif terlebih dahulu
        $cachedNip = self::getFromCache($token);
        if ($cachedNip !== '') {
            // Pastikan minimal sekali per 24 jam token tersinkron ke DB dan cookie diperpanjang
            if ($forceSetCookie || !self::wasRecentlyTouched($tokenHash)) {
                self::upsertDatabaseToken($token, $cachedNip, $userAgent);
                self::markTouched($token);
                if ($response !== null) {
                    self::setPersistentCookie($response, $token);
                }
            }

            return $cachedNip;
        }

        // 2. Durable path: baca dari database MySQL (tahan restart Redis / cache:clear)
        $dbRow = self::findValidDatabaseToken($tokenHash);
        if ($dbRow !== null && !empty($dbRow['nip'])) {
            $nip = trim((string) $dbRow['nip']);
            self::saveToCache($token, $nip);

            $needsRefresh = $forceSetCookie || self::needsRefresh($dbRow);
            if ($needsRefresh) {
                self::upsertDatabaseToken($token, $nip, $userAgent);
                if ($response !== null) {
                    self::setPersistentCookie($response, $token);
                }
            }
            self::markTouched($token);

            return $nip;
        }

        // 3. Legacy fallback: baca file cache lama tanpa prefix (sebelum prefix 'simply_' ditambahkan)
        $legacyNip = self::readLegacyUnprefixedFileCache($token);
        if ($legacyNip !== '') {
            self::upsertDatabaseToken($token, $legacyNip, $userAgent);
            self::saveToCache($token, $legacyNip);
            self::markTouched($token);
            if ($response !== null) {
                self::setPersistentCookie($response, $token);
            }

            return $legacyNip;
        }

        return '';
    }

    public static function setPersistentCookie(ResponseInterface $response, string $token): void
    {
        $response->setCookie([
            'name'     => self::COOKIE_NAME,
            'value'    => $token,
            'expire'   => self::COOKIE_TTL_SECONDS,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }

    private static function extractUserAgent(IncomingRequest $request): ?string
    {
        try {
            $ua = trim((string) $request->getUserAgent()->getAgentString());
            if ($ua === '') {
                return null;
            }
            return substr($ua, 0, 255);
        } catch (Throwable $e) {
            return null;
        }
    }

    private static function upsertDatabaseToken(string $token, string $nip, ?string $userAgent): void
    {
        if (!self::ensureTable()) {
            return;
        }

        try {
            $db = Database::connect();
            $now = date('Y-m-d H:i:s');
            $expiresAt = date('Y-m-d H:i:s', time() + self::COOKIE_TTL_SECONDS);
            $tokenHash = hash('sha256', $token);

            $db->query(
                "INSERT INTO `absen_device_tokens`
                    (`token_hash`, `nip`, `user_agent`, `expires_at`, `last_used_at`, `created_at`, `updated_at`)
                 VALUES (?, ?, ?, ?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE
                    `nip` = VALUES(`nip`),
                    `user_agent` = COALESCE(VALUES(`user_agent`), `user_agent`),
                    `expires_at` = VALUES(`expires_at`),
                    `last_used_at` = VALUES(`last_used_at`),
                    `updated_at` = VALUES(`updated_at`)",
                [$tokenHash, $nip, $userAgent, $expiresAt, $now, $now, $now]
            );
        } catch (Throwable $e) {
            log_message('error', 'AbsenNipPersistence::upsertDatabaseToken error: ' . $e->getMessage());
        }
    }

    private static function findValidDatabaseToken(string $tokenHash): ?array
    {
        if (!self::ensureTable()) {
            return null;
        }

        try {
            $db = Database::connect();
            $row = $db->query(
                "SELECT `nip`, `expires_at`, `last_used_at`
                 FROM `absen_device_tokens`
                 WHERE `token_hash` = ?
                   AND `expires_at` >= ?
                 LIMIT 1",
                [$tokenHash, date('Y-m-d H:i:s')]
            )->getRowArray();

            return is_array($row) ? $row : null;
        } catch (Throwable $e) {
            log_message('error', 'AbsenNipPersistence::findValidDatabaseToken error: ' . $e->getMessage());
            return null;
        }
    }

    private static function needsRefresh(array $dbRow): bool
    {
        $lastUsed = !empty($dbRow['last_used_at']) ? strtotime((string) $dbRow['last_used_at']) : false;
        if ($lastUsed === false) {
            return true;
        }

        return (time() - $lastUsed) >= self::REFRESH_INTERVAL_SECONDS;
    }

    private static function getFromCache(string $token): string
    {
        try {
            $val = Services::cache()->get(self::CACHE_KEY_PREFIX . $token);
            return is_string($val) ? trim($val) : '';
        } catch (Throwable $e) {
            return '';
        }
    }

    private static function saveToCache(string $token, string $nip): void
    {
        try {
            Services::cache()->save(self::CACHE_KEY_PREFIX . $token, $nip, self::CACHE_TTL_SECONDS);
        } catch (Throwable $e) {
            log_message('warning', 'AbsenNipPersistence::saveToCache error: ' . $e->getMessage());
        }
    }

    private static function wasRecentlyTouched(string $tokenHash): bool
    {
        try {
            return Services::cache()->get(self::TOUCH_KEY_PREFIX . $tokenHash) === 1;
        } catch (Throwable $e) {
            return false;
        }
    }

    private static function markTouched(string $token): void
    {
        try {
            $tokenHash = hash('sha256', $token);
            Services::cache()->save(self::TOUCH_KEY_PREFIX . $tokenHash, 1, self::REFRESH_INTERVAL_SECONDS);
        } catch (Throwable $e) {
            // Abaikan bila cache sedang tidak tersedia
        }
    }

    private static function readLegacyUnprefixedFileCache(string $token): string
    {
        try {
            $path = rtrim(WRITEPATH, '/\\') . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . self::CACHE_KEY_PREFIX . $token;
            if (!is_file($path) || !is_readable($path)) {
                return '';
            }

            $raw = @file_get_contents($path);
            if ($raw === false) {
                return '';
            }

            $data = @unserialize($raw, ['allowed_classes' => false]);
            if (!is_array($data) || !isset($data['data']) || !is_string($data['data'])) {
                return '';
            }

            $ttl = isset($data['ttl']) ? (int) $data['ttl'] : 0;
            $time = isset($data['time']) ? (int) $data['time'] : 0;
            if ($ttl > 0 && $time > 0 && time() > ($time + $ttl)) {
                return '';
            }

            return trim($data['data']);
        } catch (Throwable $e) {
            return '';
        }
    }
}
