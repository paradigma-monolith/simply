<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateStatusKonfirmasiToSandar extends Migration
{
    protected $DBGroup = 'db_kapal';

    public function up()
    {
        $this->db->query("UPDATE tbl_pasien SET hasilStatus = 'SANDAR' WHERE hasilStatus = 'KONFIRMASI'");
    }

    public function down()
    {
        // Cannot revert as data is merged
    }
}
