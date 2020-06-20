<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_CommentsTree extends CI_Migration
{
    public function up()
    {
        echo '003 migration up ' . "\n";

        $fields = [
            'parent_id' => ['type' => 'INT', 'default' => 0, 'after' => 'id']
        ];

        $this->dbforge->add_column('comment', $fields);
    }

    public function down()
    {
        echo '003 migration down ' . "\n";

        $this->dbforge->drop_column('comment', 'parent_id');
    }
}