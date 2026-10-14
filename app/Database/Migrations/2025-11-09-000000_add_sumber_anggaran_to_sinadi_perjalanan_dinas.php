<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSumberAnggaranToSinadiPerjalananDinas extends Migration
{
    public function up()
    {
        // Add `sumber_anggaran` column to `sinadi_perjalanan_dinas`
        try {
            $this->db->query(
                "ALTER TABLE `sinadi_perjalanan_dinas` 
                 ADD COLUMN `sumber_anggaran` ENUM('APBN','RPL') NOT NULL DEFAULT 'APBN' AFTER `mata_anggaran`"
            );
        } catch (\Exception $e) {}
    }

    public function down()
    {
        // Remove `sumber_anggaran` column
        $this->db->query(
            "ALTER TABLE `sinadi_perjalanan_dinas` 
             DROP COLUMN `sumber_anggaran`"
        );
    }
}