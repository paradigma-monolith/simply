<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBisaDipinjamToKendaraanKantorDaftar extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('kendaraan_kantor_daftar')) {
            return;
        }

        if (!$this->db->fieldExists('bisa_dipinjam', 'kendaraan_kantor_daftar')) {
            $this->forge->addColumn('kendaraan_kantor_daftar', [
                'bisa_dipinjam' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 1,
                    'null'       => false,
                    'after'      => 'gambar',
                ],
            ]);

            if ($this->db->tableExists('kendaraan_kantor_riwayat')) {
                $this->db->query(
                    'UPDATE kendaraan_kantor_daftar
                     SET bisa_dipinjam = 0
                     WHERE id_kendaraan_kantor_daftar >= 17
                       AND id_kendaraan_kantor_daftar NOT IN (
                           SELECT DISTINCT id_kendaraan_kantor_daftar
                           FROM kendaraan_kantor_riwayat
                           WHERE id_kendaraan_kantor_daftar > 0
                       )'
                );
            } else {
                $this->db->query(
                    'UPDATE kendaraan_kantor_daftar SET bisa_dipinjam = 0 WHERE id_kendaraan_kantor_daftar >= 17'
                );
            }
        }
    }

    public function down()
    {
        if (
            $this->db->tableExists('kendaraan_kantor_daftar')
            && $this->db->fieldExists('bisa_dipinjam', 'kendaraan_kantor_daftar')
        ) {
            $this->forge->dropColumn('kendaraan_kantor_daftar', 'bisa_dipinjam');
        }
    }
}
