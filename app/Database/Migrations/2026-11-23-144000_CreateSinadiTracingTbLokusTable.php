<?php

namespace App\Database\Migrations;

use App\Libraries\SinadiTracingTbSchema;
use CodeIgniter\Database\Migration;

class CreateSinadiTracingTbLokusTable extends Migration
{
    public function up()
    {
        SinadiTracingTbSchema::createLokusTable($this->db);
    }

    public function down()
    {
        $this->forge->dropTable('sinadi_tracing_tb_lokus', true);
    }
}
