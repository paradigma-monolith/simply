<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrlSiveraAnophelesTables extends Migration
{
    public function up()
    {
        // Table: prl_sivera_anopheles_jentik
        if (!$this->db->tableExists('prl_sivera_anopheles_jentik')) {
            $this->forge->addField([
                'id_anopheles_jentik' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => false,
                    'auto_increment' => true,
                ],
                'tanggal' => [
                    'type' => 'DATE',
                    'null' => true,
                ],
                'id_wilayah_kerja' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                ],
                'lokasi' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                ],
                'jenis_habitat' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'jenis_habitat_lainnya' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                ],
                'kondisi_pencahayaan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                ],
                'kondisi_vegetasi' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                ],
                'kondisi_air' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                ],
                'keterangan' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'jml_habitat_disurvei' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 0,
                ],
                'jml_habitat_positif' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 0,
                ],
                'indeks_habitat' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '10,2',
                    'default'    => 0.00,
                ],
                'status' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'default'    => 'MS',
                ],
                'created_by' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                ],
                'updated_by' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id_anopheles_jentik', true);
            $this->forge->createTable('prl_sivera_anopheles_jentik', true);
        }

        // Table: prl_sivera_anopheles_nyamuk
        if (!$this->db->tableExists('prl_sivera_anopheles_nyamuk')) {
            $this->forge->addField([
                'id_anopheles_nyamuk' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => false,
                    'auto_increment' => true,
                ],
                'tanggal' => [
                    'type' => 'DATE',
                    'null' => true,
                ],
                'id_wilayah_kerja' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                ],
                'lokasi' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                ],
                'jml_penangkap' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 0,
                ],
                'lama_penangkapan' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '10,2',
                    'default'    => 0.00,
                ],
                'kondisi_cuaca' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                ],
                'suhu' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '10,2',
                    'null'       => true,
                ],
                'kelembaban' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '10,2',
                    'null'       => true,
                ],
                'kecepatan_angin' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '10,2',
                    'null'       => true,
                ],
                'spesies' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'default'    => 'Anopheles sp',
                ],
                'data_tangkapan' => [
                    'type' => 'LONGTEXT',
                    'null' => true,
                ],
                'total_dalam' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 0,
                ],
                'total_luar' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 0,
                ],
                'total_tertangkap' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 0,
                ],
                'ada_anopheles' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '10',
                    'default'    => 'Tidak',
                ],
                'mbr' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '10,4',
                    'default'    => 0.0000,
                ],
                'mhd' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '10,4',
                    'default'    => 0.0000,
                ],
                'interpretasi' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'keterangan' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'created_by' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                ],
                'updated_by' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id_anopheles_nyamuk', true);
            $this->forge->createTable('prl_sivera_anopheles_nyamuk', true);
        }
    }

    public function down()
    {
        $this->forge->dropTable('prl_sivera_anopheles_nyamuk', true);
        $this->forge->dropTable('prl_sivera_anopheles_jentik', true);
    }
}
