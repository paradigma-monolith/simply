<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAnggaranTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_anggaran' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'no_kegiatan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'kegiatan' => [
                'type' => 'TEXT',
            ],
            'rencana_pencairan_anggaran' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
            ],
            'pic' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'realisasi' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
                'null' => true,
            ],
            'deviasi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tahun_anggaran' => [
                'type' => 'YEAR',
                'constraint' => 4,
            ],
            'bulan' => [
                'type' => 'TINYINT',
                'constraint' => 2,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id_anggaran', true);
        $this->forge->addKey('tahun_anggaran');
        $this->forge->addKey('pic');
        $this->forge->createTable('anggaran');
    }

    public function down()
    {
        $this->forge->dropTable('anggaran');
    }
}
