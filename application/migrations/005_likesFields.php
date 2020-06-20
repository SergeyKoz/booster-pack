<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_LikesFields extends CI_Migration
{
    public function up()
    {
        echo '005 migration up ' . "\n";

        $fields = [
            'likes' => ['type' => 'INT', 'default' => 0, 'after' => 'text']
        ];

        $this->dbforge->add_column('post', $fields);
        $this->dbforge->add_column('comment', $fields);
    }

    public function down()
    {
        echo '005 migration down ' . "\n";
        $this->dbforge->drop_column('post', 'likes');
        $this->dbforge->drop_column('comments', 'likes');
    }
}