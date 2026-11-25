<?php

namespace App\Libraries;

class SinadiVerifikatorHelper
{
    public const DEFAULT_VERIFIKATOR_TIME = '2022-01-01 01:01:01';

    private static bool $indexesChecked = false;

    /**
     * Pastikan index performa utama tersedia pada tabel sinadi_perjalanan_dinas dan sinadi_perjalanan_dinas_peserta.
     */
    public static function ensureSinadiIndexes(): void
    {
        if (self::$indexesChecked) {
            return;
        }
        self::$indexesChecked = true;

        $cacheKey = 'sinadi_perf_indexes_v1';
        try {
            $cache = \Config\Services::cache();
            if ($cache->get($cacheKey) === true) {
                return;
            }
        } catch (\Throwable $e) {
        }

        try {
            $db = \Config\Database::connect();

            $idxMain = [];
            foreach ($db->query('SHOW INDEX FROM sinadi_perjalanan_dinas')->getResultArray() as $row) {
                $idxMain[$row['Key_name'] ?? ''] = true;
            }
            if (!isset($idxMain['idx_sinadi_jenis_tgl'])) {
                $db->query('ALTER TABLE sinadi_perjalanan_dinas ADD INDEX idx_sinadi_jenis_tgl (jenis_perjalanan_dinas, tgl_pelaksanaan)');
            }
            if (!isset($idxMain['idx_sinadi_sumber_status'])) {
                $db->query('ALTER TABLE sinadi_perjalanan_dinas ADD INDEX idx_sinadi_sumber_status (sumber_anggaran, status_surat)');
            }
            if (!isset($idxMain['idx_sinadi_created_by'])) {
                $db->query('ALTER TABLE sinadi_perjalanan_dinas ADD INDEX idx_sinadi_created_by (created_by)');
            }

            $idxPeserta = [];
            foreach ($db->query('SHOW INDEX FROM sinadi_perjalanan_dinas_peserta')->getResultArray() as $row) {
                $idxPeserta[$row['Key_name'] ?? ''] = true;
            }
            if (!isset($idxPeserta['idx_sinadi_peserta_perjalanan'])) {
                $db->query('ALTER TABLE sinadi_perjalanan_dinas_peserta ADD INDEX idx_sinadi_peserta_perjalanan (id_perjalanan)');
            }
            if (!isset($idxPeserta['idx_sinadi_peserta_nip'])) {
                $db->query('ALTER TABLE sinadi_perjalanan_dinas_peserta ADD INDEX idx_sinadi_peserta_nip (nip_pegawai, id_perjalanan)');
            }

            try {
                \Config\Services::cache()->save($cacheKey, true, 86400);
            } catch (\Throwable $e) {
            }
        } catch (\Throwable $e) {
            // Abaikan jika user DB tidak memiliki izin DDL
        }
    }

    /**
     * Ambil daftar perjalanan dinas (Dalam Kota / Luar Kota) secara efisien dalam 2 query terindeks
     * tanpa memuat kolom BLOB tanda tangan verifikator_1..5 dan tanpa N+1 query COUNT peserta.
     *
     * @return array{start:string, end:string, akses:bool, activeMenu:string, perjalanan:array<int, array<string, mixed>>}
     */
    public static function fetchPerjalananList(
        string $jenisPerjalanan,
        string $activeMenu,
        ?string $start = null,
        ?string $end = null,
        ?string $username = null
    ): array {
        self::ensureSinadiIndexes();

        $start = trim((string) $start);
        $end = trim((string) $end);
        if ($start === '' || $end === '') {
            $start = date('Y-m-d', strtotime('-2 months'));
            $end = date('Y-m-d', strtotime('+1 months'));
        }

        $username = trim((string) ($username ?? session()->get('username')));
        $db = \Config\Database::connect();

        // 1. Ambil profil user, pegawai & akses SINADI dalam 1 query gabungan
        $userContext = null;
        if ($username !== '') {
            $userContext = $db->table('user')
                ->select('user.id_user, data_pegawai.nip, data_pegawai.id_tim_kerja, sinadi_akses.status AS akses_status, sinadi_akses.akses_tim')
                ->join('data_pegawai', 'data_pegawai.id_user = user.id_user', 'left')
                ->join('sinadi_akses', 'sinadi_akses.id_user = user.id_user', 'left')
                ->where('user.username', $username)
                ->get()
                ->getRowArray();
        }

        $hasFullAccess = ($userContext && ($userContext['akses_status'] ?? '') === 'Akses Diterima');
        $hasTeamAccess = ($userContext && (int) ($userContext['akses_tim'] ?? 0) === 1);
        $userNip = trim((string) ($userContext['nip'] ?? ''));
        $userId = (int) ($userContext['id_user'] ?? 0);
        $userTimKerja = $userContext['id_tim_kerja'] ?? null;

        if (!$hasFullAccess && $userNip === '' && $userId <= 0) {
            return [
                'start'      => $start,
                'end'        => $end,
                'akses'      => false,
                'activeMenu' => $activeMenu,
                'perjalanan' => [],
            ];
        }

        // 2. Query utama dengan proyeksi kolom ringan (tanpa verifikator_1..5 Base64 signature)
        $builder = $db->table('sinadi_perjalanan_dinas')
            ->select(
                'sinadi_perjalanan_dinas.id_perjalanan, '
                . 'sinadi_perjalanan_dinas.nama_kegiatan, '
                . 'sinadi_perjalanan_dinas.tgl_pelaksanaan, '
                . 'sinadi_perjalanan_dinas.tgl_pelaksanaan_akhir, '
                . 'sinadi_perjalanan_dinas.tempat_perjalanan, '
                . 'sinadi_perjalanan_dinas.mata_anggaran, '
                . 'sinadi_perjalanan_dinas.sumber_anggaran, '
                . 'sinadi_perjalanan_dinas.status_surat, '
                . 'sinadi_perjalanan_dinas.total_perjalanan, '
                . 'sinadi_perjalanan_dinas.bukti_pembayaran, '
                . 'sinadi_perjalanan_dinas.created_at, '
                . 'user.nama, '
                . 'user.username'
            )
            ->join('sinadi_perjalanan_dinas_peserta', 'sinadi_perjalanan_dinas_peserta.id_perjalanan = sinadi_perjalanan_dinas.id_perjalanan')
            ->join('user', 'sinadi_perjalanan_dinas.updated_by = user.id_user')
            ->where('sinadi_perjalanan_dinas.jenis_perjalanan_dinas', $jenisPerjalanan)
            ->where('sinadi_perjalanan_dinas.tgl_pelaksanaan >=', $start)
            ->where('sinadi_perjalanan_dinas.tgl_pelaksanaan <=', $end);

        if (!$hasFullAccess) {
            if ($hasTeamAccess) {
                $builder->join('data_pegawai AS dp_peserta', 'dp_peserta.nip = sinadi_perjalanan_dinas_peserta.nip_pegawai', 'left');
            }
            $builder->groupStart()
                ->where('sinadi_perjalanan_dinas_peserta.nip_pegawai', $userNip)
                ->orWhere('sinadi_perjalanan_dinas.created_by', $userId);
            if ($hasTeamAccess && !empty($userTimKerja)) {
                $builder->orWhere('dp_peserta.id_tim_kerja', $userTimKerja);
            }
            $builder->groupEnd();
        }

        $rows = $builder
            ->groupBy('sinadi_perjalanan_dinas.id_perjalanan, user.nama, user.username')
            ->orderBy('sinadi_perjalanan_dinas.created_at', 'DESC')
            ->get()
            ->getResultArray();

        if (empty($rows)) {
            return [
                'start'      => $start,
                'end'        => $end,
                'akses'      => $hasFullAccess,
                'activeMenu' => $activeMenu,
                'perjalanan' => [],
            ];
        }

        // 3. Hitung jumlah peserta seluruh baris dalam 1 batch query GROUP BY (0 N+1 query!)
        $perjalananIds = array_values(array_unique(array_map(static fn ($r) => (int) $r['id_perjalanan'], $rows)));
        $countMap = [];
        $countRows = $db->table('sinadi_perjalanan_dinas_peserta')
            ->select('id_perjalanan, COUNT(*) AS total_peserta')
            ->whereIn('id_perjalanan', $perjalananIds)
            ->groupBy('id_perjalanan')
            ->get()
            ->getResultArray();
        foreach ($countRows as $cRow) {
            $countMap[(int) $cRow['id_perjalanan']] = (int) $cRow['total_peserta'];
        }

        foreach ($rows as &$item) {
            $idP = (int) $item['id_perjalanan'];
            $item['jumlah_peserta'] = $countMap[$idP] ?? 0;
        }
        unset($item);

        return [
            'start'      => $start,
            'end'        => $end,
            'akses'      => $hasFullAccess,
            'activeMenu' => $activeMenu,
            'perjalanan' => $rows,
        ];
    }

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
            $unserialized = @unserialize((string) $raw, ['allowed_classes' => false]);
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
     * Menggunakan regex cepat terlebih dahulu agar tidak perlu mengalokasikan memori untuk Base64 signature.
     */
    public static function extractLatestTime(?string $serialized): string
    {
        if (empty($serialized)) {
            return self::DEFAULT_VERIFIKATOR_TIME;
        }

        if (preg_match_all('/"time";s:\d+:"(\d{4}-\d{2}-\d{2}[^"]*)"/', $serialized, $matches) && !empty($matches[1])) {
            return (string) end($matches[1]);
        }

        $entries = @unserialize($serialized, ['allowed_classes' => false]);
        if (!is_array($entries) || empty($entries)) {
            return self::DEFAULT_VERIFIKATOR_TIME;
        }

        $last = end($entries);
        return (is_array($last) && !empty($last['time']))
            ? (string) $last['time']
            : self::DEFAULT_VERIFIKATOR_TIME;
    }

    /**
     * Menormalkan filter periode pada halaman Validasi Dokumen Perjalanan Dinas.
     * Default saat pertama dibuka adalah '30d' (30 hari terakhir), kecuali bila user memilih 'all'.
     */
    public static function resolvePeriodeFilter(?string $periode): string
    {
        return strtolower(trim((string) $periode)) === 'all' ? 'all' : '30d';
    }

    /**
     * Menghitung tanggal batas awal (Y-m-d) untuk filter periode Validasi Dokumen,
     * atau null bila periode disetel ke 'all'.
     */
    public static function resolvePeriodeCutoffDate(?string $periode, ?string $todayOverride = null): ?string
    {
        if (self::resolvePeriodeFilter($periode) === 'all') {
            return null;
        }

        $today = trim((string) $todayOverride);
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $today) !== 1) {
            $today = date('Y-m-d');
        } else {
            $parts = explode('-', $today);
            if (!checkdate((int) $parts[1], (int) $parts[2], (int) $parts[0])) {
                $today = date('Y-m-d');
            }
        }

        try {
            return (new \DateTimeImmutable($today))->modify('-30 days')->format('Y-m-d');
        } catch (\Throwable $e) {
            return date('Y-m-d', strtotime('-30 days'));
        }
    }
}
