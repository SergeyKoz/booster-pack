<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Migrate extends CI_Controller
{
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->load->library("migration");
    }

    public function index()
    {
        if (!$this->migration->latest())
        {
            show_error($this->migration->error_string());
        }
    }

    public function down()
    {
        $current = $this->migration->current();
        if(!$this->migration->version($current))
        {
            show_error($this->migration->error_string());
        }
    }
}