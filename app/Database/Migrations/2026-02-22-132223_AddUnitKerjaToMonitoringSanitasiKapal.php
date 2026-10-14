<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUnitKerjaToMonitoringSanitasiKapal extends Migration
{
    protected $DBGroup = 'default';

    public function up()
    {
        $this->forge->addColumn('monitoring_sanitasi_kapal', [
            'unit_kerja' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'wilker'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('monitoring_sanitasi_kapal', 'unit_kerja');
    }
}
