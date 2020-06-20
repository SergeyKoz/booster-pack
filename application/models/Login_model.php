<?php
class Login_model extends CI_Model {

    private $userFactory;

    public function __construct()
    {
        App::get_ci()->load->library('userfactory');

        parent::__construct();

    }

    public static function logout()
    {
        App::get_ci()->session->unset_userdata('id');
    }

    public static function login($login, $password)
    {
        $user = App::get_ci()->userfactory->getUserByPersonName($login);
        $pass  = App::get_ci()->encrypt->decode($user->get_password());
        if ($password == $pass)
        {
            Login_model::start_session($user->get_id());
            return $user->get_id();
        } else {
            throw new \Exception('User or password id wrong');
        }
    }

    public static function start_session(int $user_id)
    {
        // если перенедан пользователь
        if (empty($user_id))
        {
            throw new CriticalException('No id provided!');
        }

        App::get_ci()->session->set_userdata('id', $user_id);
    }

    public static function getSessionUserId()
    {
        return App::get_ci()->session->userdata('id');
    }
}
