<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFeedbackTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_feedback' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kategori' => [
                'type'       => 'ENUM',
                'constraint' => ['UI/UX', 'Fitur', 'Bug', 'Performa', 'Keamanan', 'Lainnya'],
                'null'       => false,
            ],
            'prioritas' => [
                'type'       => 'ENUM',
                'constraint' => ['Rendah', 'Sedang', 'Tinggi', 'Kritis'],
                'default'    => 'Sedang',
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'modul' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'anonymous' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => '0 = tidak anonim, 1 = anonim',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Baru', 'Diproses', 'Selesai', 'Ditolak'],
                'default'    => 'Baru',
            ],
            'tanggapan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'updated_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_feedback', true);
        $this->forge->addKey(['status', 'created_at']);
        $this->forge->addKey('kategori');
        $this->forge->addKey('prioritas');
        $this->forge->addKey('created_by');
        
        $this->forge->createTable('feedback');
    }

    public function down()
    {
        $this->forge->dropTable('feedback');
    }
}
