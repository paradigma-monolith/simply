<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAbsRawatKendaraanTables extends Migration
{
    public function up()
    {
        // Tabel Pengajuan Pemeliharaan Kendaraan
        $this->forge->addField([
            'id_rawat' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_kendaraan_kantor_daftar' => [ // Reuse kendaraan kantor
                'type' => 'INT',
                'constraint' => 11,
            ],
            'tgl_pengajuan' => [
                'type' => 'DATE',
            ],
            'keluhan' => [
                'type' => 'TEXT',
            ],
            'estimasi_biaya' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0.00,
            ],
            'bukti_foto' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'verifikasi_1', 'verifikasi_2', 'selesai', 'ditolak'],
                'default' => 'pending',
            ],
            'verifikator_1_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'verifikator_1_tgl' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'verifikator_1_catatan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'verifikator_2_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'verifikator_2_tgl' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'verifikator_2_catatan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_rawat', true);
        $this->forge->createTable('abs_rawat_kendaraan');

        // Tabel Pengaturan Verifikator Rawat Kendaraan
        $this->forge->addField([
            'id_verifikator' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_user' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'role' => [
                'type' => 'ENUM',
                'constraint' => ['verifikator_1', 'verifikator_2'],
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
        $this->forge->addKey('id_verifikator', true);
        $this->forge->createTable('abs_rawat_verifikator');
    }

    public function down()
    {
        $this->forge->dropTable('abs_rawat_verifikator');
        $this->forge->dropTable('abs_rawat_kendaraan');
    }
}
