<?php

class Profitbankprocessing_model extends Processing_model
{
    const CLASS_TABLE = 'profit_bank_processing';

    function __construct()
    {
        parent::__construct();
        $this->loadModel();
    }

    private function loadModel()
    {
        $id = Login_model::getSessionUserId();
        $this->data = App::get_ci()->s->from($this->get_table())->where(['user_id' => $id])->one();
        $this->map_sql_to_class();
    }
}
