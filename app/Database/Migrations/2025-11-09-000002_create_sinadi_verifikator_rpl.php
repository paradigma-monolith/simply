<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSinadiVerifikatorRpl extends Migration
{
    public function up()
    {
        $this->db->query(
            "CREATE TABLE IF NOT EXISTS `sinadi_verifikator_rpl` (
              `id_sinadi_verifikator_rpl` int(11) NOT NULL AUTO_INCREMENT,
              `verifikator_1` varchar(64) DEFAULT NULL,
              `verifikator_2` int(11) DEFAULT NULL,
              `verifikator_3` int(11) DEFAULT NULL,
              `verifikator_4` int(11) DEFAULT NULL,
              `verifikator_5` int(11) DEFAULT NULL,
              `verifikator_6` varchar(64) DEFAULT NULL,
              `created_by` int(11) NOT NULL,
              `updated_by` int(11) NOT NULL,
              `created_at` datetime NOT NULL,
              `updated_at` datetime NOT NULL,
              PRIMARY KEY (`id_sinadi_verifikator_rpl`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;"
        );
    }

    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS `sinadi_verifikator_rpl`");
    }
}