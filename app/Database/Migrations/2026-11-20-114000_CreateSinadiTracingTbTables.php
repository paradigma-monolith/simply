<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use App\Libraries\SinadiTracingTbSchema;

class CreateSinadiTracingTbTables extends Migration
{
    public function up()
    {
        SinadiTracingTbSchema::ensureTables($this->db);
    }

    public function down()
    {
        $this->forge->dropTable('sinadi_tracing_tb_dokumen', true);
        $this->forge->dropTable('sinadi_tracing_tb_verifikator', true);
        $this->forge->dropTable('sinadi_tracing_tb', true);
    }
}
