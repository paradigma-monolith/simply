<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJenisDokumenToLhkpnSptTable extends Migration
{
    public function up()
    {
        // Cek apakah field sudah ada
        if (!$this->db->fieldExists('jenis_dokumen', 'lhkpn_spt')) {
            $this->forge->addColumn('lhkpn_spt', [
                'jenis_dokumen' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => true,
                    'after'      => 'tanggal_dokumen',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('jenis_dokumen', 'lhkpn_spt')) {
            $this->forge->dropColumn('lhkpn_spt', 'jenis_dokumen');
        }
    }
}