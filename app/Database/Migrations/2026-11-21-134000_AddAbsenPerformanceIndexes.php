<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAbsenPerformanceIndexes extends Migration
{
    private array $indexes = [
        ['table' => 'absen',        'name' => 'idx_absen_pegawai_jam',       'cols' => '(`id_data_pegawai`, `jam_masuk`)'],
        ['table' => 'absen',        'name' => 'idx_absen_jam_masuk',         'cols' => '(`jam_masuk`)'],
        ['table' => 'data_pegawai', 'name' => 'idx_dp_status_gol',           'cols' => '(`status_bekerja`, `id_golongan`)'],
        ['table' => 'data_pegawai', 'name' => 'idx_dp_user',                 'cols' => '(`id_user`)'],
        ['table' => 'data_pegawai', 'name' => 'idx_dp_nip',                  'cols' => '(`nip`)'],
        ['table' => 'absen_akses',  'name' => 'idx_absen_akses_user_status', 'cols' => '(`id_user`, `status`)'],
        ['table' => 'user',         'name' => 'idx_user_username',           'cols' => '(`username`)'],
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
