<?php

namespace App\Libraries;

use CodeIgniter\Database\BaseConnection;

class SinadiTracingTbSchema
{
    public const DEFAULT_KODE_KEGIATAN = '7956.QAA.061.051';
    public const DEFAULT_NAMA_KEGIATAN = 'Masyarakat Mendapatkan Layanan Skrining TBC - Penemuan Kasus TBC Terintegrasi CKG';
    public const DEFAULT_INSTANSI = 'Balai Kekarantinaan Kesehatan Kelas I Bandung';

    /**
     * Definisi lengkap 3 Akun Belanja, rincian realisasi anggaran, dan 36 item dokumen
     * sesuai Form Checklist Verifikasi Penemuan Kasus TBC Terintegrasi CKG.
     */
    public static function getAccounts(): array
    {
        return [
            '522151' => [
                'kode' => '522151',
                'nama' => 'Belanja Jasa Profesi',
                'badge_color' => 'primary',
                'catatan_anggaran' => 'Honor Radiografer: kosongkan / isi 0 pada volume jika non radiografer.',
                'anggaran' => [
                    [
                        'kode' => 'narasumber',
                        'nama' => 'Narasumber',
                        'catatan' => 'Wajib',
                        'vol_or' => 1,
                        'satuan_or' => 'OR',
                        'vol_waktu' => 1,
                        'satuan_waktu' => 'JAM',
                        'vol_lokus' => 1,
                        'satuan_lokus' => 'Lokus',
                        'satuan_total' => 'OJ',
                        'harga_satuan' => 500000,
                        'opsional_non_radiografer' => false,
                    ],
                    [
                        'kode' => 'honor_radiografer',
                        'nama' => 'Honor Radiografer',
                        'catatan' => 'Vol hapus / 0 jika non radiografer',
                        'vol_or' => 1,
                        'satuan_or' => 'OR',
                        'vol_waktu' => 1,
                        'satuan_waktu' => 'KL',
                        'vol_lokus' => 1,
                        'satuan_lokus' => 'Lokus',
                        'satuan_total' => 'OJ',
                        'harga_satuan' => 500000,
                        'opsional_non_radiografer' => true,
                    ],
                ],
                'dokumen' => [
                    ['kode' => '522151_rekap_narsum_radio', 'kategori' => 'Wajib', 'nama' => 'Rekapitulasi Narasumber dan atau Radiografer', 'radiografer_only' => false],
                    ['kode' => '522151_kuitansi_narsum', 'kategori' => 'Wajib', 'nama' => 'Kuitansi Narasumber', 'radiografer_only' => false],
                    ['kode' => '522151_cv_narsum', 'kategori' => 'Wajib', 'nama' => 'CV Singkat Narasumber', 'radiografer_only' => false],
                    ['kode' => '522151_materi_narsum', 'kategori' => 'Wajib', 'nama' => 'Materi Singkat Narasumber', 'radiografer_only' => false],
                    ['kode' => '522151_ktp_npwp_narsum', 'kategori' => 'Wajib', 'nama' => 'KTP/NPWP Narsum (boleh tdk terlampir tp harus ada no di rekap)', 'radiografer_only' => false],
                    ['kode' => '522151_surat_tugas_narsum', 'kategori' => 'Wajib', 'nama' => 'Surat Tugas asli sbg narasumber', 'radiografer_only' => false],
                    ['kode' => '522151_kuitansi_radiografer', 'kategori' => 'Wajib jika ada', 'nama' => 'Kuitansi Radiografer (kosongkan jika non radiografer)', 'radiografer_only' => true],
                    ['kode' => '522151_cv_radiografer', 'kategori' => 'Wajib jika ada', 'nama' => 'CV Singkat Radiografer', 'radiografer_only' => true],
                    ['kode' => '522151_ktp_npwp_radiografer', 'kategori' => 'Wajib jika ada', 'nama' => 'KTP/NPWP Radiografer (boleh tdk terlampir tp harus ada no di rekap)', 'radiografer_only' => true],
                    ['kode' => '522151_surat_tugas_radiografer', 'kategori' => 'Wajib jika ada', 'nama' => 'Surat Tugas asli sbg radiografer', 'radiografer_only' => true],
                    ['kode' => '522151_daftar_hadir_gabungan', 'kategori' => 'Wajib', 'nama' => 'Mengisi form daftar hadir gabungan', 'radiografer_only' => false],
                    ['kode' => '522151_bukti_transfer', 'kategori' => 'Wajib', 'nama' => 'Bukti Transfer Narsum dan atau Radiografer', 'radiografer_only' => false],
                    ['kode' => '522151_bukti_setor_pajak', 'kategori' => 'Wajib', 'nama' => 'Bukti Setor Pajak Narsum dan atau Radiografer', 'radiografer_only' => false],
                ],
            ],
            '524111' => [
                'kode' => '524111',
                'nama' => 'Belanja Perjalanan Dinas Dalam Kota',
                'badge_color' => 'info',
                'catatan_anggaran' => 'Wajib — volume diisi sesuai realisasi kegiatan.',
                'anggaran' => [
                    [
                        'kode' => 'transport_dinkes',
                        'nama' => 'Transport Dinas Kesehatan',
                        'catatan' => 'Wajib (sesuai realisasi)',
                        'vol_or' => 2,
                        'satuan_or' => 'OR',
                        'vol_waktu' => 1,
                        'satuan_waktu' => 'TR',
                        'vol_lokus' => 1,
                        'satuan_lokus' => 'Lokus',
                        'satuan_total' => 'OT',
                        'harga_satuan' => 120000,
                        'opsional_non_radiografer' => false,
                    ],
                    [
                        'kode' => 'transport_kader',
                        'nama' => 'Transport Kader',
                        'catatan' => 'Wajib (sesuai realisasi)',
                        'vol_or' => 2,
                        'satuan_or' => 'OR',
                        'vol_waktu' => 1,
                        'satuan_waktu' => 'TR',
                        'vol_lokus' => 1,
                        'satuan_lokus' => 'Lokus',
                        'satuan_total' => 'OT',
                        'harga_satuan' => 95000,
                        'opsional_non_radiografer' => false,
                    ],
                    [
                        'kode' => 'transport_nakes',
                        'nama' => 'Transport Tenaga Kesehatan',
                        'catatan' => 'Wajib (sesuai realisasi)',
                        'vol_or' => 5,
                        'satuan_or' => 'OR',
                        'vol_waktu' => 1,
                        'satuan_waktu' => 'TR',
                        'vol_lokus' => 1,
                        'satuan_lokus' => 'Lokus',
                        'satuan_total' => 'OT',
                        'harga_satuan' => 170000,
                        'opsional_non_radiografer' => false,
                    ],
                    [
                        'kode' => 'transport_peserta',
                        'nama' => 'Transport Peserta',
                        'catatan' => 'Wajib (sesuai realisasi)',
                        'vol_or' => 100,
                        'satuan_or' => 'OR',
                        'vol_waktu' => 1,
                        'satuan_waktu' => 'TR',
                        'vol_lokus' => 1,
                        'satuan_lokus' => 'Lokus',
                        'satuan_total' => 'OT',
                        'harga_satuan' => 30000,
                        'opsional_non_radiografer' => false,
                    ],
                ],
                'dokumen' => [
                    ['kode' => '524111_rekap_petugas', 'kategori' => 'Wajib', 'nama' => 'Rekapitulasi Petugas', 'radiografer_only' => false],
                    ['kode' => '524111_kuitansi_dpr_nakes', 'kategori' => 'Wajib', 'nama' => 'Kuitansi & DPR Nakes', 'radiografer_only' => false],
                    ['kode' => '524111_kuitansi_dpr_dinkes', 'kategori' => 'Wajib', 'nama' => 'Kuitansi & DPR Dinkes', 'radiografer_only' => false],
                    ['kode' => '524111_kuitansi_dpr_kader', 'kategori' => 'Wajib', 'nama' => 'Kuitansi & DPR Kader', 'radiografer_only' => false],
                    ['kode' => '524111_daftar_hadir_gabungan', 'kategori' => 'Wajib', 'nama' => 'Mengisi form daftar hadir gabungan', 'radiografer_only' => false],
                    ['kode' => '524111_dilengkapi_per_lokus', 'kategori' => 'Wajib', 'nama' => 'Dilengkapi per-Lokus', 'radiografer_only' => false],
                    ['kode' => '524111_laporan_template', 'kategori' => 'Wajib', 'nama' => 'Laporan sesuai template', 'radiografer_only' => false],
                    ['kode' => '524111_foto_geotagging', 'kategori' => 'Wajib', 'nama' => 'Foto geotagging dlm laporan', 'radiografer_only' => false],
                    ['kode' => '524111_bukti_kehadiran_8jam', 'kategori' => 'Wajib', 'nama' => 'Form bukti kehadiran s.d 8 jam tandatangan & stempel pejabat sesuai lokasi kegiatan', 'radiografer_only' => false],
                    ['kode' => '524111_surat_tugas_asli', 'kategori' => 'Wajib', 'nama' => 'Surat Tugas asli', 'radiografer_only' => false],
                    ['kode' => '524111_daftar_terima_transport', 'kategori' => 'Wajib', 'nama' => 'Daftar Terima Transport', 'radiografer_only' => false],
                    ['kode' => '524111_bukti_transfer', 'kategori' => 'Wajib', 'nama' => 'Bukti Transfer', 'radiografer_only' => false],
                ],
            ],
            '521211' => [
                'kode' => '521211',
                'nama' => 'Belanja Bahan',
                'badge_color' => 'success',
                'catatan_anggaran' => 'Volume diisi sesuai realisasi konsumsi/snack.',
                'anggaran' => [
                    [
                        'kode' => 'snack',
                        'nama' => 'Snack',
                        'catatan' => 'Vol sesuai realisasi',
                        'vol_or' => 111,
                        'satuan_or' => 'OR',
                        'vol_waktu' => 1,
                        'satuan_waktu' => 'HR',
                        'vol_lokus' => 1,
                        'satuan_lokus' => 'Lokus',
                        'satuan_total' => 'OH',
                        'harga_satuan' => 20000,
                        'opsional_non_radiografer' => false,
                    ],
                ],
                'dokumen' => [
                    ['kode' => '521211_rekap_snack', 'kategori' => 'Wajib', 'nama' => 'Rekapitulasi snack/konsumsi', 'radiografer_only' => false],
                    ['kode' => '521211_daftar_hadir_konsumsi', 'kategori' => 'Wajib', 'nama' => 'Daftar hadir terima konsumsi (1 hal dgn daftar hadir)', 'radiografer_only' => false],
                    ['kode' => '521211_nota_kuitansi_pembelian', 'kategori' => 'Wajib', 'nama' => 'Nota rincian dan kuitansi pembelian', 'radiografer_only' => false],
                    ['kode' => '521211_ktp_npwp', 'kategori' => 'Wajib', 'nama' => 'KTP/NPWP (boleh tdk terlampir tp harus ada no di rekap)', 'radiografer_only' => false],
                    ['kode' => '521211_bukti_setor_pajak', 'kategori' => 'Wajib', 'nama' => 'Bukti setor pajak konsumsi', 'radiografer_only' => false],
                    ['kode' => '521211_bukti_transfer', 'kategori' => 'Wajib', 'nama' => 'Bukti Transfer', 'radiografer_only' => false],
                    ['kode' => '521211_suket_umkm', 'kategori' => 'Opsional', 'nama' => 'Suket UMKM', 'radiografer_only' => false],
                    ['kode' => '521211_suket_omzet_500jt', 'kategori' => 'Opsional', 'nama' => 'Suket Omzet s.d 500 juta', 'radiografer_only' => false],
                    ['kode' => '521211_suket_penunjukan_rek_pic', 'kategori' => 'PIC (Opsional)', 'nama' => 'Surat Keterangan Penunjukan Rek PIC', 'radiografer_only' => false],
                    ['kode' => '521211_suket_tdk_memiliki_rek', 'kategori' => 'PIC (Opsional)', 'nama' => 'Surat Keterangan Tdk Memiliki Rekening', 'radiografer_only' => false],
                    ['kode' => '521211_bukti_transfer_pic', 'kategori' => 'PIC (Opsional)', 'nama' => 'Bukti Transfer (PIC)', 'radiografer_only' => false],
                ],
            ],
        ];
    }

    /**
     * Peta datar kode_dokumen => metadata dokumen (kode_akun, kategori, nama, radiografer_only).
     */
    public static function getDokumenMap(): array
    {
        $map = [];
        foreach (self::getAccounts() as $kodeAkun => $akun) {
            foreach ($akun['dokumen'] as $doc) {
                $map[$doc['kode']] = [
                    'kode_akun' => $kodeAkun,
                    'kode_dokumen' => $doc['kode'],
                    'kategori_wajib' => $doc['kategori'],
                    'nama_dokumen' => $doc['nama'],
                    'radiografer_only' => (bool) $doc['radiografer_only'],
                ];
            }
        }
        return $map;
    }

    /**
     * Normalisasi dan hitung ulang rincian anggaran dari input form agar akurat di sisi server.
     */
    public static function buildRincianAnggaran(?array $postAnggaran = null, bool $isNonRadiografer = false): array
    {
        $accounts = self::getAccounts();
        $result = [
            'non_radiografer' => $isNonRadiografer,
            'items' => [],
            'subtotals' => [
                '522151' => 0,
                '524111' => 0,
                '521211' => 0,
            ],
            'grand_total' => 0,
        ];

        foreach ($accounts as $kodeAkun => $akun) {
            foreach ($akun['anggaran'] as $row) {
                $itemKey = $row['kode'];
                $src = (is_array($postAnggaran) && isset($postAnggaran[$itemKey]) && is_array($postAnggaran[$itemKey]))
                    ? $postAnggaran[$itemKey]
                    : null;

                $volOr = $src !== null ? max(0, (float) ($src['vol_or'] ?? 0)) : (float) $row['vol_or'];
                $volWaktu = $src !== null ? max(0, (float) ($src['vol_waktu'] ?? 0)) : (float) $row['vol_waktu'];
                $volLokus = $src !== null ? max(0, (float) ($src['vol_lokus'] ?? 0)) : (float) $row['vol_lokus'];
                $hargaSatuan = $src !== null
                    ? max(0, (int) preg_replace('/[^\d]/', '', (string) ($src['harga_satuan'] ?? '0')))
                    : (int) $row['harga_satuan'];

                if ($isNonRadiografer && !empty($row['opsional_non_radiografer'])) {
                    $volOr = 0;
                    $volWaktu = 0;
                    $volLokus = 0;
                }

                $totalVol = $volOr * $volWaktu * $volLokus;
                $subtotal = (int) round($totalVol * $hargaSatuan);

                $result['items'][$itemKey] = [
                    'kode_akun' => $kodeAkun,
                    'nama' => $row['nama'],
                    'vol_or' => $volOr,
                    'satuan_or' => $row['satuan_or'],
                    'vol_waktu' => $volWaktu,
                    'satuan_waktu' => $row['satuan_waktu'],
                    'vol_lokus' => $volLokus,
                    'satuan_lokus' => $row['satuan_lokus'],
                    'total_vol' => $totalVol,
                    'satuan_total' => $row['satuan_total'],
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $subtotal,
                ];

                $result['subtotals'][$kodeAkun] += $subtotal;
                $result['grand_total'] += $subtotal;
            }
        }

        return $result;
    }

    /**
     * Pastikan tabel-tabel Tracing TB tersedia jika belum dimigrasi.
     */
    public static function ensureTables(BaseConnection $db): void
    {
        if (!$db->tableExists('sinadi_tracing_tb')) {
            $db->query("
                CREATE TABLE IF NOT EXISTS `sinadi_tracing_tb` (
                    `id_tracing_tb` INT(11) NOT NULL AUTO_INCREMENT,
                    `lokus` VARCHAR(255) NOT NULL,
                    `instansi` VARCHAR(255) NOT NULL DEFAULT 'Balai Kekarantinaan Kesehatan Kelas I Bandung',
                    `tanggal_kegiatan` DATE NULL,
                    `kode_kegiatan` VARCHAR(64) NOT NULL DEFAULT '7956.QAA.061.051',
                    `nama_kegiatan` VARCHAR(255) NOT NULL DEFAULT 'Masyarakat Mendapatkan Layanan Skrining TBC - Penemuan Kasus TBC Terintegrasi CKG',
                    `non_radiografer` TINYINT(1) NOT NULL DEFAULT 0,
                    `rincian_anggaran` LONGTEXT NULL,
                    `total_jasa_profesi` BIGINT NOT NULL DEFAULT 0,
                    `total_perjalanan_dinas` BIGINT NOT NULL DEFAULT 0,
                    `total_belanja_bahan` BIGINT NOT NULL DEFAULT 0,
                    `total_anggaran` BIGINT NOT NULL DEFAULT 0,
                    `catatan_pengaju` TEXT NULL,
                    `nama_pengaju_ttd` VARCHAR(200) NULL,
                    `tgl_ttd_pengaju` DATE NULL,
                    `ttd_pengaju` MEDIUMTEXT NULL,
                    `status_pengajuan` VARCHAR(50) NOT NULL DEFAULT 'Draft',
                    `status_verif_bkk` VARCHAR(50) NULL,
                    `tgl_verif_bkk` DATE NULL,
                    `nama_verif_bkk` VARCHAR(200) NULL,
                    `no_hp_verif_bkk` VARCHAR(50) NULL,
                    `paraf_verif_bkk` MEDIUMTEXT NULL,
                    `catatan_verif_bkk` TEXT NULL,
                    `id_verif_bkk` INT(11) NULL,
                    `created_by` INT(11) NULL,
                    `updated_by` INT(11) NULL,
                    `created_at` DATETIME NULL,
                    `updated_at` DATETIME NULL,
                    PRIMARY KEY (`id_tracing_tb`),
                    KEY `idx_tracing_tb_status` (`status_pengajuan`),
                    KEY `idx_tracing_tb_tanggal` (`tanggal_kegiatan`),
                    KEY `idx_tracing_tb_created_by` (`created_by`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
        } else {
            $fields = $db->getFieldNames('sinadi_tracing_tb');
            if (!in_array('nama_pengaju_ttd', $fields, true)) {
                $db->query("ALTER TABLE `sinadi_tracing_tb` ADD COLUMN `nama_pengaju_ttd` VARCHAR(200) NULL AFTER `catatan_pengaju`");
            }
            if (!in_array('tgl_ttd_pengaju', $fields, true)) {
                $db->query("ALTER TABLE `sinadi_tracing_tb` ADD COLUMN `tgl_ttd_pengaju` DATE NULL AFTER `nama_pengaju_ttd`");
            }
            if (!in_array('ttd_pengaju', $fields, true)) {
                $db->query("ALTER TABLE `sinadi_tracing_tb` ADD COLUMN `ttd_pengaju` MEDIUMTEXT NULL AFTER `tgl_ttd_pengaju`");
            }
        }

        if (!$db->tableExists('sinadi_tracing_tb_dokumen')) {
            $db->query("
                CREATE TABLE IF NOT EXISTS `sinadi_tracing_tb_dokumen` (
                    `id_dokumen` INT(11) NOT NULL AUTO_INCREMENT,
                    `id_tracing_tb` INT(11) NOT NULL,
                    `kode_akun` VARCHAR(20) NOT NULL,
                    `kode_dokumen` VARCHAR(64) NOT NULL,
                    `nama_dokumen` VARCHAR(255) NOT NULL,
                    `kategori_wajib` VARCHAR(80) NOT NULL DEFAULT 'Wajib',
                    `nama_file` VARCHAR(255) NULL,
                    `nama_file_asli` VARCHAR(255) NULL,
                    `ukuran_file` INT(11) NOT NULL DEFAULT 0,
                    `mime_type` VARCHAR(100) NULL,
                    `checklist_bkk` TINYINT(1) NOT NULL DEFAULT 0,
                    `catatan_bkk` TEXT NULL,
                    `created_at` DATETIME NULL,
                    `updated_at` DATETIME NULL,
                    PRIMARY KEY (`id_dokumen`),
                    UNIQUE KEY `uniq_tracing_tb_dok` (`id_tracing_tb`, `kode_dokumen`),
                    KEY `idx_tracing_tb_dok_parent` (`id_tracing_tb`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
        }

        if (!$db->tableExists('sinadi_tracing_tb_verifikator')) {
            $db->query("
                CREATE TABLE IF NOT EXISTS `sinadi_tracing_tb_verifikator` (
                    `id_verifikator` INT(11) NOT NULL AUTO_INCREMENT,
                    `id_user` INT(11) NOT NULL,
                    `id_data_pegawai` INT(11) NULL,
                    `peran` VARCHAR(30) NOT NULL,
                    `no_hp` VARCHAR(50) NULL,
                    `created_by` INT(11) NULL,
                    `created_at` DATETIME NULL,
                    `updated_at` DATETIME NULL,
                    PRIMARY KEY (`id_verifikator`),
                    UNIQUE KEY `uniq_tracing_tb_verif` (`id_user`, `peran`),
                    KEY `idx_tracing_tb_verif_peran` (`peran`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
        }
    }
}
