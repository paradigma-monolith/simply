<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifySpesiesInPrlSiveraTikus extends Migration
{
    public function up()
    {
        $fields = [
            'spesies' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ];

        if ($this->db->fieldExists('spesies', 'prl_sivera_tikus')) {
            $this->forge->modifyColumn('prl_sivera_tikus', $fields);
        } else {
            $fields['spesies']['after'] = 'lokasi_pemasangan';
            $this->forge->addColumn('prl_sivera_tikus', $fields);
        }
    }

    public function down()
    {
        $fields = [
            'spesies' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
        ];

        if ($this->db->fieldExists('spesies', 'prl_sivera_tikus')) {
            $this->forge->modifyColumn('prl_sivera_tikus', $fields);
        }
    }
}
