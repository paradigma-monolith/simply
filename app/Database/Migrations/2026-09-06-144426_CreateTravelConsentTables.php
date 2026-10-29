<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTravelConsentTables extends Migration
{
    public function up()
    {
        // 1. Table for Aircraft Sanitation Inspection
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'pemilik' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'tanda_kebangsaan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'tanggal' => ['type' => 'DATE', 'null' => true],
            'waktu' => ['type' => 'TIME', 'null' => true],
            'kabin_penumpang' => ['type' => 'TEXT', 'null' => true], // Storing as JSON string
            'kompartemen_toilet' => ['type' => 'TEXT', 'null' => true], // Storing as JSON string
            'galley' => ['type' => 'TEXT', 'null' => true], // Storing as JSON string
            'dokumen_kesehatan' => ['type' => 'TEXT', 'null' => true], // Storing as JSON string
            'pembuangan_kotoran' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true], // Ya/Tidak
            'penanganan_air' => ['type' => 'TEXT', 'null' => true], // JSON
            'keadaan_makanan' => ['type' => 'TEXT', 'null' => true], // JSON
            'binatang_pengganggu' => ['type' => 'TEXT', 'null' => true], // JSON
            'surat_sanitasi' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true], // Ada/Tidak Ada
            'kesimpulan' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true], // Layak/Tidak Layak
            'rekomendasi' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true], // Perlu/Tidak Perlu
            'tindakan' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true], // Disinseksi/Fumigasi/General Cleaning
            'petugas_kabin_nama' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'petugas_kabin_ttd' => ['type' => 'TEXT', 'null' => true],
            'petugas_bkk_nama' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'petugas_bkk_ttd' => ['type' => 'LONGTEXT', 'null' => true],
            'foto_geotagging' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tc_aircraft_sanitation', true);

        // 2. Table for Daftar Pemakaian Alat Kesehatan Kotak P3K
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'penerbangan_haji_h' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'penerbangan_haji_m' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'embarkasi' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'nomor_kloter' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'no_kontak' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'items_p3k' => ['type' => 'TEXT', 'null' => true], // JSON array of items
            'items_emergensi' => ['type' => 'TEXT', 'null' => true], // JSON array of items
            'crew_nama' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'crew_ttd' => ['type' => 'LONGTEXT', 'null' => true],
            'pelapor_nama' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'pelapor_ttd' => ['type' => 'LONGTEXT', 'null' => true],
            'foto_geotagging' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tc_alat_kesehatan', true);

        // 3. Table for Daftar Pemakaian Obat Kotak P3K
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'penerbangan_haji_h' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'penerbangan_haji_m' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'embarkasi' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'nomor_kloter' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'no_kontak' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'items_p3k' => ['type' => 'TEXT', 'null' => true], // JSON array of items
            'items_emergensi' => ['type' => 'TEXT', 'null' => true], // JSON array of items
            'crew_nama' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'crew_ttd' => ['type' => 'LONGTEXT', 'null' => true],
            'pelapor_nama' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'pelapor_ttd' => ['type' => 'LONGTEXT', 'null' => true],
            'foto_geotagging' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tc_obat', true);

        // 4. Table for Sanitasi Kapal
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'pelabuhan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'tanggal' => ['type' => 'DATE', 'null' => true],
            'sertifikat_mencatat' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'imo_no' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'jenis_kapal' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'detail_jenis_kapal' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'nama_kapal' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'bendera' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'berat_gt' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kondisi_palka' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'nama_pemeriksa' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'alamat_pemeriksa' => ['type' => 'TEXT', 'null' => true],
            'area_diperiksa' => ['type' => 'LONGTEXT', 'null' => true],
            'pejabat_penerbit' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'keterangan' => ['type' => 'TEXT', 'null' => true],
            'tanda_tangan' => ['type' => 'LONGTEXT', 'null' => true],
            'foto_geotagging' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tc_sanitasi_kapal', true);
    }

    public function down()
    {
        $this->forge->dropTable('tc_aircraft_sanitation', true);
        $this->forge->dropTable('tc_alat_kesehatan', true);
        $this->forge->dropTable('tc_obat', true);
        $this->forge->dropTable('tc_sanitasi_kapal', true);
    }
}
