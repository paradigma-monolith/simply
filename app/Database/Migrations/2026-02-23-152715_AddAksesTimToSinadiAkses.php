<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAksesTimToSinadiAkses extends Migration
{
    public function up()
    {
        $this->forge->addColumn('sinadi_akses', [
            'akses_tim' => [
                'type' => 'INT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'status'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('sinadi_akses', 'akses_tim');
    }
}
