<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSpesiesToPrlSiveraTikus extends Migration
{
    public function up()
    {
        $fields = [
            'spesies' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'jenis_umpan',
            ],
        ];

        if (!$this->db->fieldExists('spesies', 'prl_sivera_tikus')) {
            $this->forge->addColumn('prl_sivera_tikus', $fields);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('spesies', 'prl_sivera_tikus')) {
            $this->forge->dropColumn('prl_sivera_tikus', 'spesies');
        }
    }
}
