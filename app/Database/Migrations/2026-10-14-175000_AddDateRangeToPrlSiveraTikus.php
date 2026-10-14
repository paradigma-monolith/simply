<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDateRangeToPrlSiveraTikus extends Migration
{
    public function up()
    {
        $fields = [
            'tanggal_mulai' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'tanggal',
            ],
            'tanggal_selesai' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'tanggal_mulai',
            ],
        ];

        if (!$this->db->fieldExists('tanggal_mulai', 'prl_sivera_tikus')) {
            $this->forge->addColumn('prl_sivera_tikus', [
                'tanggal_mulai' => $fields['tanggal_mulai']
            ]);
        }

        if (!$this->db->fieldExists('tanggal_selesai', 'prl_sivera_tikus')) {
            $this->forge->addColumn('prl_sivera_tikus', [
                'tanggal_selesai' => $fields['tanggal_selesai']
            ]);
        }

        // Migrate existing records
        $this->db->query("UPDATE `prl_sivera_tikus` SET `tanggal_mulai` = `tanggal` WHERE `tanggal_mulai` IS NULL AND `tanggal` IS NOT NULL");
        $this->db->query("UPDATE `prl_sivera_tikus` SET `tanggal_selesai` = `tanggal` WHERE `tanggal_selesai` IS NULL AND `tanggal` IS NOT NULL");
    }

    public function down()
    {
        if ($this->db->fieldExists('tanggal_selesai', 'prl_sivera_tikus')) {
            $this->forge->dropColumn('prl_sivera_tikus', 'tanggal_selesai');
        }
        if ($this->db->fieldExists('tanggal_mulai', 'prl_sivera_tikus')) {
            $this->forge->dropColumn('prl_sivera_tikus', 'tanggal_mulai');
        }
    }
}
