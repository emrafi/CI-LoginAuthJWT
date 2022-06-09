<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'nama' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'alamat' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => true
            ],
            'tgl_lahir' => [
                'type' => 'DATE',
                'null' => true
            ],
            'no_hp' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
                'null' => true
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
            ],
            'tgl_register' => [
                'type' => 'date',
            ],
            'verify_code' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true
            ],
            'token' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'is_active' => [
                'type' => 'ENUM',
                'constraint' => ['disable', 'active'],
                'default' => 'disable',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
