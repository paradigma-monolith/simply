<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJenisDokumenToLhkpnSpt extends Migration
{
    public function up()
    {
        $this->forge->addColumn('lhkpn_spt', [
            'jenis_dokumen' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'after'      => 'tanggal_dokumen',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('lhkpn_spt', 'jenis_dokumen');
    }
}