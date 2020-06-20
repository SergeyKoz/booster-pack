<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Init extends CI_Migration
{
    public function up()
    {
        echo '001 migration up ' . "\n";

        $CI = &get_instance();
        $CI->load->database();
        $database_name = $CI->db->database;
        $user_name = $CI->db->username;
        $password = $CI->db->password;
        $hostname = $CI->db->hostname;

        $dump_file = dirname(__FILE__) . '/../../mysql/dump.sql';

        $commond = "mysql -h{$hostname} -u{$user_name} {$database_name}  < {$dump_file}";
        if($password){
            $commond = "mysql -h{$hostname} -u{$user_name} -p{$password} {$database_name} < {$dump_file}";
        }
        system($commond);

//        $this->dbforge->add_field('id INT UNSIGNED NOT NULL AUTO INCREMENT')
//            ->add_field('username VARCHAR(30) NOT NULL')
//            ->add_field('password VARCHAR(200) NOT NULL');
//        $this->dbforge->add_key('id',true);
//        $this->dbforge->create_table($this->table);
    }

    public function down()
    {
        echo '001 migration down ' . "\n";
        $this->dbforge->drop_table('boosterpack');
        $this->dbforge->drop_table('comment');
        $this->dbforge->drop_table('post');
        $this->dbforge->drop_table('user');
    }
}