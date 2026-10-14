<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJenisPerjalananDinasToSinadiPerjalananDinas extends Migration
{
    public function up()
    {
        $this->forge->addColumn('sinadi_perjalanan_dinas', [
            'jenis_perjalanan_dinas' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'dalam kota',
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('sinadi_perjalanan_dinas', 'jenis_perjalanan_dinas');
    }
}
