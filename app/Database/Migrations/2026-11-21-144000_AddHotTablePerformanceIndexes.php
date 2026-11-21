<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHotTablePerformanceIndexes extends Migration
{
    private array $indexes = [
        // Notifikasi (21.950+ baris, dipolling di navbar setiap halaman)
        ['table' => 'notifikasi',               'name' => 'idx_notif_penerima_status_created', 'cols' => '(`nama_penerima`, `status`, `created_at`)'],
        ['table' => 'notifikasi',               'name' => 'idx_notif_penerima_created',        'cols' => '(`nama_penerima`, `created_at`)'],

        // Linimasa (98.450+ baris)
        ['table' => 'linimasa',                 'name' => 'idx_linimasa_table_id',             'cols' => '(`table`(64), `id_table`)'],
        ['table' => 'linimasa',                 'name' => 'idx_linimasa_user_created',         'cols' => '(`id_user`, `created_at`)'],
        ['table' => 'linimasa',                 'name' => 'idx_linimasa_created',              'cols' => '(`created_at`)'],

        // E-KIP Evaluasi Mingguan (11.900+ baris)
        ['table' => 'ekip_evaluasi_mingguan',   'name' => 'idx_ekip_eval_user_thn_bln',        'cols' => '(`id_user`, `tahun`, `bulan`, `minggu_ke`)'],
        ['table' => 'ekip_evaluasi_mingguan',   'name' => 'idx_ekip_eval_mk',                  'cols' => '(`master_kinerja_id`)'],

        // SINADI Perjalanan Dinas (4.440+ baris, 59 MB)
        ['table' => 'sinadi_perjalanan_dinas',  'name' => 'idx_sinadi_pd_tgl',                 'cols' => '(`tgl_pelaksanaan`, `tgl_pelaksanaan_akhir`)'],
        ['table' => 'sinadi_perjalanan_dinas',  'name' => 'idx_sinadi_pd_status_created',      'cols' => '(`status_surat`, `created_at`)'],
        ['table' => 'sinadi_perjalanan_dinas',  'name' => 'idx_sinadi_pd_pic_nip',             'cols' => '(`pic_nip`(32))'],

        // SIPEDIT Arsip (3.040+ baris)
        ['table' => 'sipedit_arsip',            'name' => 'idx_sipedit_pegawai',               'cols' => '(`id_data_pegawai`)'],
        ['table' => 'sipedit_arsip',            'name' => 'idx_sipedit_kat',                   'cols' => '(`id_sipedit_kategori`, `id_sipedit_sub_kategori`)'],

        // RELAXTIME (4.140+ baris gabungan)
        ['table' => 'relaxtime_realisasi',      'name' => 'idx_rt_real_du_thn',                'cols' => '(`id_relaxtime_detail_uraian`, `tahun`, `bulan`)'],
        ['table' => 'relaxtime_detail_uraian',  'name' => 'idx_rt_du_uraian_thn',              'cols' => '(`id_relaxtime_uraian`, `tahun`)'],

        // ABS Lokasi (2.195+ baris, 18.5 MB)
        ['table' => 'abs_lokasi',               'name' => 'idx_abs_lok_unit_ruang',            'cols' => '(`id_unit_kerja`, `id_abs_lantai`, `id_abs_ruang`)'],
        ['table' => 'abs_lokasi',               'name' => 'idx_abs_lok_status',                'cols' => '(`status_tersedia`)'],

        // Infografis & Arkab
        ['table' => 'infografis_kapal',         'name' => 'idx_info_kapal_unit_created',       'cols' => '(`unit_kerja`, `created_at`)'],
        ['table' => 'infografis_vaksin',        'name' => 'idx_info_vaksin_unit_created',      'cols' => '(`unit_kerja`, `created_at`)'],
        ['table' => 'arkab_kode_klasifikasi',   'name' => 'idx_arkab_kode',                    'cols' => '(`kode`)'],
    ];

    public function up()
    {
        $dbName = $this->db->getDatabase();

        foreach ($this->indexes as $idx) {
            if (!$this->db->tableExists($idx['table'])) {
                continue;
            }
            $exists = $this->db->query(
                'SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ? LIMIT 1',
                [$dbName, $idx['table'], $idx['name']]
            )->getRowArray();

            if (!$exists) {
                $this->db->query("ALTER TABLE `{$idx['table']}` ADD INDEX `{$idx['name']}` {$idx['cols']}");
            }
        }
    }

    public function down()
    {
        $dbName = $this->db->getDatabase();

        foreach ($this->indexes as $idx) {
            if (!$this->db->tableExists($idx['table'])) {
                continue;
            }
            $exists = $this->db->query(
                'SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ? LIMIT 1',
                [$dbName, $idx['table'], $idx['name']]
            )->getRowArray();

            if ($exists) {
                $this->db->query("ALTER TABLE `{$idx['table']}` DROP INDEX `{$idx['name']}`");
            }
        }
    }
}
