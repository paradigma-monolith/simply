<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BackfillUnitKerjaMonitoringSanitasi extends Migration
{
    public function up()
    {
        // Update unit_kerja based on created_by user
        $sql = "UPDATE monitoring_sanitasi_kapal m 
                JOIN data_pegawai dp ON m.created_by = dp.id_user 
                JOIN unit_kerja uk ON dp.id_unit_kerja = uk.id_unit_kerja 
                SET m.unit_kerja = uk.unit_kerja 
                WHERE m.unit_kerja IS NULL OR m.unit_kerja = ''";
        
        $this->db->query($sql);
        
        // Fallback: Update based on wilker (which stores user name) if unit_kerja is still empty
        $sqlFallback = "UPDATE monitoring_sanitasi_kapal m 
                        JOIN data_pegawai dp ON m.wilker = dp.nama 
                        JOIN unit_kerja uk ON dp.id_unit_kerja = uk.id_unit_kerja 
                        SET m.unit_kerja = uk.unit_kerja 
                        WHERE m.unit_kerja IS NULL OR m.unit_kerja = ''";
                        
        $this->db->query($sqlFallback);
    }

    public function down()
    {
        // No down migration needed as this is a data fix
    }
}
