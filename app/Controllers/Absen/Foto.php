<?php

namespace App\Controllers\Absen;

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
        if ($raw === '') {
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

        $mime = 'image/jpeg';
        $binary = null;

        if (preg_match('/^data:(image\/[a-zA-Z0-9.+-]+);base64,(.+)$/s', $raw, $matches)) {
            $mime = $matches[1];
            $binary = base64_decode($matches[2], true);
        } elseif (strpos($raw, '/') === 0 || strpos($raw, 'dist/') === 0) {
            $relPath = ltrim($raw, '/');
            $fullPath = FCPATH . $relPath;
            if (is_file($fullPath)) {
                $binary = file_get_contents($fullPath);
                $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
                $mimeMap = [
                    'jpg'  => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'png'  => 'image/png',
                    'webp' => 'image/webp',
                    'gif'  => 'image/gif',
                ];
                $mime = $mimeMap[$ext] ?? 'image/jpeg';
            }
        } else {
            $decoded = base64_decode($raw, true);
            if ($decoded !== false && strlen($decoded) > 16) {
                $binary = $decoded;
            }
        }

        if ($binary === null || $binary === false || $binary === '') {
            return $this->response->setStatusCode(404);
        }

        return $this->response
            ->setStatusCode(200)
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Length', (string) strlen($binary))
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
            "SELECT (`{$column}` IS NOT NULL AND `{$column}` != '') AS has_second, `updated_at` FROM `absen` WHERE `id_absen` = ? LIMIT 1",
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
