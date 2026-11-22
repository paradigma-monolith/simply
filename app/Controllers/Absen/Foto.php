<?php

namespace App\Controllers\Absen;

use App\Libraries\AbsenMediaStorage;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Controller super ringan khusus streaming foto absensi.
 * Sengaja extends CodeIgniter\Controller langsung (BUKAN BaseController)
 * agar tidak menginstansiasi 114 model di setiap request gambar
 * dan langsung melepas PHP session lock seketika setelah AuthFilter selesai.
 */
class Foto extends Controller
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Lepas session file lock SEGERA (AuthFilter sudah memverifikasi login sebelum ini)
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
    }

    /**
     * Stream foto absen dalam format biner dengan ETag & browser cache 24 jam.
     */
    public function show($idAbsen, $jenis = 'masuk')
    {
        $columnMap = [
            'masuk'     => 'image_masuk_1',
            'masuk2'    => 'image_masuk_2',
            'pulang'    => 'image_pulang_1',
            'pulang2'   => 'image_pulang_2',
            'ttdmasuk'  => 'ttd_masuk',
            'ttdpulang' => 'ttd_pulang',
        ];

        $jenis = strtolower((string) $jenis);
        $id = (int) $idAbsen;
        if ($id <= 0 || !isset($columnMap[$jenis])) {
            return $this->response->setStatusCode(404);
        }

        $column = $columnMap[$jenis];
        $db = \Config\Database::connect();
        $row = $db->query(
            "SELECT `{$column}` AS img_data, `updated_at` FROM `absen` WHERE `id_absen` = ? LIMIT 1",
            [$id]
        )->getRowArray();

        $raw = isset($row['img_data']) ? trim((string) $row['img_data']) : '';
        if (AbsenMediaStorage::isEmptyValue($raw)) {
            return $this->response->setStatusCode(404);
        }

        // Hapus header no-cache bawaan PHP session agar browser benar-benar meng-cache foto
        header_remove('Cache-Control');
        header_remove('Pragma');
        header_remove('Expires');
        $this->response->removeHeader('Cache-Control');
        $this->response->removeHeader('Pragma');
        $this->response->removeHeader('Expires');

        $etag = '"' . md5($id . '|' . $jenis . '|' . strlen($raw) . '|' . ($row['updated_at'] ?? '')) . '"';
        if (trim($this->request->getHeaderLine('If-None-Match')) === $etag) {
            return $this->response
                ->setStatusCode(304)
                ->setHeader('Cache-Control', 'private, max-age=86400')
                ->setHeader('ETag', $etag);
        }

        $storage = new AbsenMediaStorage();
        $resolved = $storage->resolveToBinary($raw);
        if ($resolved === null || $resolved['binary'] === '') {
            return $this->response->setStatusCode(404);
        }

        return $this->response
            ->setStatusCode(200)
            ->setHeader('Content-Type', $resolved['mime'])
            ->setHeader('Content-Length', (string) strlen($resolved['binary']))
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setHeader('Cache-Control', 'private, max-age=86400')
            ->setHeader('ETag', $etag)
            ->setBody($resolved['binary']);
    }

    /**
     * Ambil nama/path foto pada indeks tertentu dari JSON `rekam_absen`.
     */
    public static function extractRekamFotoRef(?string $rekamJson, int $index): ?string
    {
        if ($index < 0 || $rekamJson === null || trim($rekamJson) === '') {
            return null;
        }
        $decoded = json_decode($rekamJson, true);
        if (!is_array($decoded) || !isset($decoded[$index]) || !is_array($decoded[$index])) {
            return null;
        }
        $foto = $decoded[$index]['foto'] ?? null;
        if (!is_string($foto) || trim($foto) === '') {
            return null;
        }
        return trim($foto);
    }

    /**
     * Stream foto rekaman absensi (`rekam_absen`) lewat route privat tanpa ekstensi file
     * agar tidak terkena aturan Cache-Control publik `.htaccess` untuk *.jpg/*.png.
     */
    public function rekamFoto($idAbsen, $index)
    {
        $id  = (int) $idAbsen;
        $idx = (int) $index;
        if ($id <= 0 || $idx < 0) {
            return $this->response->setStatusCode(404);
        }

        $db = \Config\Database::connect();
        $row = $db->query(
            "SELECT `rekam_absen`, `updated_at` FROM `absen` WHERE `id_absen` = ? LIMIT 1",
            [$id]
        )->getRowArray();

        $fotoRef = self::extractRekamFotoRef($row['rekam_absen'] ?? null, $idx);
        if ($fotoRef === null) {
            return $this->response->setStatusCode(404);
        }

        $storage = new AbsenMediaStorage();
        $resolved = $storage->resolveRekamFotoPath($fotoRef);
        if ($resolved === null) {
            return $this->response->setStatusCode(404);
        }

        header_remove('Cache-Control');
        header_remove('Pragma');
        header_remove('Expires');
        $this->response->removeHeader('Cache-Control');
        $this->response->removeHeader('Pragma');
        $this->response->removeHeader('Expires');

        $mtime = (int) @filemtime($resolved['path']);
        $etag  = '"' . md5("rekam|{$id}|{$idx}|{$fotoRef}|{$mtime}") . '"';
        if (trim($this->request->getHeaderLine('If-None-Match')) === $etag) {
            return $this->response
                ->setStatusCode(304)
                ->setHeader('Cache-Control', 'private, max-age=86400')
                ->setHeader('ETag', $etag);
        }

        $binary = @file_get_contents($resolved['path']);
        if (!is_string($binary) || $binary === '') {
            return $this->response->setStatusCode(404);
        }

        return $this->response
            ->setStatusCode(200)
            ->setHeader('Content-Type', $resolved['mime'])
            ->setHeader('Content-Length', (string) strlen($binary))
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setHeader('Cache-Control', 'private, max-age=86400')
            ->setHeader('ETag', $etag)
            ->setBody($binary);
    }

    /**
     * Cek cepat apakah foto ke-2 tersedia untuk sebuah id_absen (hanya dipanggil saat foto diklik).
     * Selalu mengembalikan HTTP 200 JSON agar tidak memicu error 404 di console browser.
     */
    public function checkSecond($idAbsen, $jenis = 'masuk')
    {
        $id = (int) $idAbsen;
        $jenis = strtolower((string) $jenis);
        $column = ($jenis === 'pulang') ? 'image_pulang_2' : 'image_masuk_2';

        if ($id <= 0) {
            return $this->response->setJSON(['has_second' => false]);
        }

        $db = \Config\Database::connect();
        $row = $db->query(
            "SELECT (`{$column}` IS NOT NULL AND `{$column}` != '' AND `{$column}` != 'data:,') AS has_second, `updated_at` FROM `absen` WHERE `id_absen` = ? LIMIT 1",
            [$id]
        )->getRowArray();

        $hasSecond = !empty($row['has_second']);
        $ver = !empty($row['updated_at']) ? (int) strtotime((string) $row['updated_at']) : 0;
        $url = $hasSecond
            ? '/absen/foto/' . $id . '/' . ($jenis === 'pulang' ? 'pulang2' : 'masuk2') . ($ver > 0 ? '?v=' . $ver : '')
            : null;

        return $this->response->setJSON([
            'has_second' => $hasSecond,
            'url'        => $url,
        ]);
    }
}
