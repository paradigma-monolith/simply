<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTabAktifToEkipPengaturan extends Migration
{
    public function up()
    {
        $fields = [
            'tab_kinerja_aktif' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'tab_perilaku_aktif' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
        ];
        $this->forge->addColumn('ekip_pengaturan', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('ekip_pengaturan', 'tab_kinerja_aktif');
        $this->forge->dropColumn('ekip_pengaturan', 'tab_perilaku_aktif');
    }
}
