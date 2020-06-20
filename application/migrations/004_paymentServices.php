<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_PaymentServices extends CI_Migration
{
    public function up()
    {
        echo '004 migration up ' . "\n";
        $processing_table = [
            'id' => [
                'type'=>'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ],
            'balance' => [
                'type' => 'DOUBLE'
            ],
            'created_at'=> [
                'type'=>'INT',
            ],
            'updated_at'=> [
                'type'=>'INT',
            ]
        ];

        $history_table = [
            'id' => [
                'type'=>'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ],
            'sum' => [
                'type' => 'DOUBLE'
            ],
            'balance' => [
                'type' => 'DOUBLE'
            ],
            'info' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at'=> [
                'type'=>'INT',
            ]
        ];

        $this->dbforge->add_field($processing_table);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('like_processing');

        $this->dbforge->add_field($history_table);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('like_history');



        $this->dbforge->add_field($history_table);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('wallet_history');

        $history_table = [
            'id' => [
                'type'=>'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'boosterpack_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ],
            'sum' => [
                'type' => 'DOUBLE'
            ],
            'balance' => [
                'type' => 'DOUBLE'
            ],
            'info' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at'=> [
                'type'=>'INT',
            ]
        ];

        $this->dbforge->add_field($history_table);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('profit_bank_history');

        $query = $this->db->get('user');
        foreach ($query->result() as $row)
        {
            $processing_data = [
                'user_id' => $row->id,
                'balance' => 0,
                'created_at' => time(),
                'updated_at' => time()
            ];

            $this->db->insert('like_processing', $processing_data);
        }
    }

    public function down()
    {
        echo '004 migration down ' . "\n";
        $this->dbforge->drop_table('wallet_history');
        $this->dbforge->drop_table('profit_bank_history');
        $this->dbforge->drop_table('like_history');
        $this->dbforge->drop_table('like_processing');
    }
}