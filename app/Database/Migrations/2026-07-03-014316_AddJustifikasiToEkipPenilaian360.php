<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJustifikasiToEkipPenilaian360 extends Migration
{
    public function up()
    {
        $fields = [
            'justifikasi_deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'justifikasi_file' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
        ];
        $this->forge->addColumn('ekip_penilaian_360', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('ekip_penilaian_360', 'justifikasi_deskripsi');
        $this->forge->dropColumn('ekip_penilaian_360', 'justifikasi_file');
    }
}
