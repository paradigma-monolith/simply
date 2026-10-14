<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EkipEkspektasiKhususPimpinan extends Migration
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
                'unsigned'   => true,
            ],
            'penilai_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
            'berorientasi_pelayanan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'akuntabel' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'kompeten' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'harmonis' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'loyal' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'adaptif' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'kolaboratif' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->createTable('ekip_ekspektasi_khusus_pimpinan');
    }

    public function down()
    {
        $this->forge->dropTable('ekip_ekspektasi_khusus_pimpinan');
    }
}
