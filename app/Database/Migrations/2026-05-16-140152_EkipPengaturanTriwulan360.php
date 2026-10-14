<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EkipPengaturanTriwulan360 extends Migration
{
    public function up()
    {
        $fields = [
            't360_q1' => ['type' => 'INT', 'constraint' => 1, 'default' => 1],
            't360_q2' => ['type' => 'INT', 'constraint' => 1, 'default' => 1],
            't360_q3' => ['type' => 'INT', 'constraint' => 1, 'default' => 1],
            't360_q4' => ['type' => 'INT', 'constraint' => 1, 'default' => 1],
        ];
        $this->forge->addColumn('ekip_pengaturan', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('ekip_pengaturan', ['t360_q1', 't360_q2', 't360_q3', 't360_q4']);
    }
}
