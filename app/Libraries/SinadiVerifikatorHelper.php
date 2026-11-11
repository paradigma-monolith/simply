<?php

namespace App\Libraries;

class SinadiVerifikatorHelper
{
    public const DEFAULT_VERIFIKATOR_TIME = '2022-01-01 01:01:01';

    /**
     * Ambil konfigurasi verifikator terbaru berdasarkan tipe anggaran (APBN / RPL),
     * dengan fallback ke tabel verifikator default.
     */
    public static function getConfigByTipe(string $tipe, $apbnModel, $rplModel, $defaultModel): ?array
    {
        $config = null;
        if ($tipe === 'APBN') {
            try {
                $config = $apbnModel->orderBy('created_at', 'desc')->first();
            } catch (\Throwable $e) {
                $config = null;
            }
        } elseif ($tipe === 'RPL') {
            try {
                $config = $rplModel->orderBy('created_at', 'desc')->first();
            } catch (\Throwable $e) {
                $config = null;
            }
        }

        if (!$config) {
            $config = $defaultModel->orderBy('created_at', 'desc')->first();
        }

        return is_array($config) ? $config : null;
    }

    /**
     * Normalisasi nilai kolom verifikator (serialized array atau scalar ID) menjadi array string ID.
     *
     * @return string[]
     */
    public static function normalizeIds($raw, bool $multi): array
    {
        if (empty($raw)) {
            return [];
        }

        if ($multi) {
            $unserialized = @unserialize((string) $raw);
            if (is_array($unserialized)) {
                return array_values(array_filter(array_map('strval', $unserialized), static fn ($v) => $v !== ''));
            }
            return [(string) $raw];
        }

        return [(string) $raw];
    }

    /**
     * Ambil daftar id_data_pegawai milik user aktif dalam 1 query ringan.
     *
     * @return string[]
     */
    public static function getUserPegawaiIds($dataPegawaiModel, ?string $username): array
    {
        if (empty($username)) {
            return [];
        }

        $rows = $dataPegawaiModel
            ->select('data_pegawai.id_data_pegawai')
            ->join('user', 'id_user')
            ->where('user.username', $username)
            ->findAll();

        return array_map(static fn ($row) => (string) $row['id_data_pegawai'], $rows);
    }

    /**
     * Tentukan role verifikator user aktif ('1'..'6') dari konfigurasi yang diberikan.
     *
     * @return array{urut_verif: string[], keterangan: array<int, array{status: string}>}
     */
    public static function resolveUserRoles(?array $config, array $myPegawaiIds, bool $isSuperUser): array
    {
        $keterangan = [];
        if ($isSuperUser) {
            $keterangan[] = ['status' => 'Sebagai Super User'];
        }

        $urutVerif = [];
        if (!$config || empty($myPegawaiIds)) {
            return [
                'urut_verif' => $urutVerif,
                'keterangan' => $keterangan,
            ];
        }

        $roleLabels = [
            1 => ['label' => 'Sebagai Verifikator ke-1', 'multi' => true],
            2 => ['label' => 'Sebagai Verifikator ke-2', 'multi' => false],
            3 => ['label' => 'Sebagai Verifikator ke-3', 'multi' => false],
            4 => ['label' => 'Sebagai Verifikator ke-4', 'multi' => false],
            5 => ['label' => 'Sebagai Verifikator ke-5', 'multi' => false],
            6 => ['label' => 'Sebagai Petugas Konfirmasi Pembayaran', 'multi' => true],
        ];

        foreach ($roleLabels as $index => $meta) {
            $ids = self::normalizeIds($config["verifikator_{$index}"] ?? null, $meta['multi']);
            if (!empty($ids) && !empty(array_intersect($myPegawaiIds, $ids))) {
                $keterangan[] = ['status' => $meta['label']];
                $urutVerif[] = (string) $index;
            }
        }

        return [
            'urut_verif' => $urutVerif,
            'keterangan' => $keterangan,
        ];
    }

    /**
     * Ekstrak timestamp terbaru dari histori serialized verifikator_1 / verifikator_2.
     */
    public static function extractLatestTime(?string $serialized): string
    {
        if (empty($serialized)) {
            return self::DEFAULT_VERIFIKATOR_TIME;
        }

        $entries = @unserialize($serialized);
        if (!is_array($entries) || empty($entries)) {
            return self::DEFAULT_VERIFIKATOR_TIME;
        }

        $last = end($entries);
        return (is_array($last) && !empty($last['time']))
            ? (string) $last['time']
            : self::DEFAULT_VERIFIKATOR_TIME;
    }
}

