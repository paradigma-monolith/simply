<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EkipRekomendasiKebijakan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'dinilai_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'penilai_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'tahun' => [
                'type'       => 'INT',
                'constraint' => 4,
            ],
            'bulan' => [
                'type'       => 'INT',
                'constraint' => 2,
            ],
            'minggu_ke' => [
                'type'       => 'INT',
                'constraint' => 1,
            ],
            'rencana_hasil_kerja' => [
                'type' => 'TEXT',
            ],
            'iki' => [
                'type' => 'TEXT',
            ],
            'target' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('ekip_rekomendasi_kebijakan');
    }

    public function down()
    {
        $this->forge->dropTable('ekip_rekomendasi_kebijakan');
    }
}
