<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAgendaUserIndexes extends Migration
{
    private array $indexes = [
        ['name' => 'idx_agenda_pelaksana_range', 'cols' => '(`waktu_pelaksana_awal`, `waktu_pelaksana_akhir`)'],
        ['name' => 'idx_agenda_notif_range',     'cols' => '(`tgl_notif_awal`, `tgl_notif_akhir`)'],
        ['name' => 'idx_agenda_ulangi_notif',    'cols' => '(`ulangi_bulanan`, `tgl_notif_awal`)'],
        ['name' => 'idx_agenda_created_ulangi',  'cols' => '(`created_by`, `ulangi_bulanan`, `waktu_pelaksana_awal`)'],
    ];

    public function up()
    {
        foreach ($this->indexes as $idx) {
            if (!$this->indexExists('agenda_user', $idx['name'])) {
                $this->db->query("ALTER TABLE `agenda_user` ADD INDEX `{$idx['name']}` {$idx['cols']}");
            }
        }
    }

    public function down()
    {
        foreach ($this->indexes as $idx) {
            if ($this->indexExists('agenda_user', $idx['name'])) {
                $this->db->query("ALTER TABLE `agenda_user` DROP INDEX `{$idx['name']}`");
            }
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $row = $this->db->query(
            'SHOW INDEX FROM `' . $table . '` WHERE Key_name = ?',
            [$indexName]
        )->getRowArray();

        return !empty($row);
    }
}
