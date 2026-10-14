<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJmlTikusTertangkapToPrlSiveraPinjal extends Migration
{
    public function up()
    {
        $fields = [
            'jml_tikus_tertangkap' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'lokasi',
            ],
        ];

        if (! $this->db->fieldExists('jml_tikus_tertangkap', 'prl_sivera_pinjal')) {
            $this->forge->addColumn('prl_sivera_pinjal', $fields);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('jml_tikus_tertangkap', 'prl_sivera_pinjal')) {
            $this->forge->dropColumn('prl_sivera_pinjal', 'jml_tikus_tertangkap');
        }
    }
}
