<?php

class Userfactory
{
    function __construct()
    {
        App::get_ci()->load->model('User_model');
    }

    public function getUserByPersonName($personName)
    {
        $row = App::get_ci()->s->from('user')->where(['personaname' => $personName])->one();
        if (empty($row)) {
            throw new \Exception('User is not found');
        }

        return new User_model($row['id']);
    }
}