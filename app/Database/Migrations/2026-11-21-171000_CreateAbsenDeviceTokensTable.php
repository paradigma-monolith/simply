<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAbsenDeviceTokensTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('absen_device_tokens')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'token_hash' => [
                'type'       => 'CHAR',
                'constraint' => 64,
            ],
            'nip' => [
                'type'       => 'VARCHAR',
                'constraint' => 32,
            ],
            'user_agent' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            'last_used_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('token_hash', 'uq_absen_device_tokens_hash');
        $this->forge->addKey('nip', false, false, 'idx_absen_device_tokens_nip');
        $this->forge->addKey('expires_at', false, false, 'idx_absen_device_tokens_expires');
        $this->forge->createTable('absen_device_tokens', true);
    }

    public function down()
    {
        $this->forge->dropTable('absen_device_tokens', true);
    }
}
