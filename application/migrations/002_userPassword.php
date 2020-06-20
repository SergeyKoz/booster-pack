<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_UserPassword extends CI_Migration
{
    public function up()
    {
        echo '002 migration up ' . "\n";

        $this->load->library('encrypt');

        $this->dbforge->modify_column('user', [
            'password' => [
                'type' => 'varchar',
                'constraint' => '100'
            ]
        ]);

        $query = $this->db->get('user');
        foreach ($query->result() as $row)
        {
            $encrypted_password = $this->encrypt->encode($row->personaname);
            $this->db->where('id', $row->id);
            $this->db->update('user', ['password' => $encrypted_password]);
        }
    }

    public function down()
    {
        echo '002 migration down ' . "\n";

        $this->dbforge->modify_column('user', [
            'password' => [
                'type' => 'varchar',
                'constraint' => '32'
            ]
        ]);
    }
}