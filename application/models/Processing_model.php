<?php

class Processing_model extends CI_Emerald_Model
{
    /** @var int */
    protected $user_id;
    /** @var float */
    protected $balance;
     /** @var string */
    protected $created_at;
    /** @var string */
    protected $updated_at;

    protected $user;

    /**
     * @return int
     */
    public function get_user_id(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     *
     * @return bool
     */
    public function set_user_id(int $user_id)
    {
        $this->user_id = $user_id;
        return $this->save('user_id', $user_id);
    }

    /**
     * @return float
     */
    public function get_balance(): float
    {
        return $this->balance;
    }

    /**
     * @param float $balance
     *
     * @return bool
     */
    public function set_balance(float $balance)
    {
        $this->balance = $balance;
        return $this->save('balance', $balance);
    }

    /**
     * @return string
     */
    public function get_created_at(): string
    {
        return $this->created_at;
    }

    /**
     * @param string $created_at
     *
     * @return bool
     */
    public function set_created_at(string $created_at)
    {
        $this->created_at = $created_at;
        return $this->save('created_at', $created_at);
    }

    /**
     * @return string
     */
    public function get_updated_at(): string
    {
        return $this->updated_at;
    }

    /**
     * @param string $updated_at
     *
     * @return bool
     */
    public function set_updated_at(string $updated_at)
    {
        $this->updated_at = $updated_at;
        return $this->save('created_at', $updated_at);
    }

    /**
     * @return User_model
     */
    public function get_user():User_model
    {
        if (empty($this->user))
        {
            try {
                $this->user = new User_model($this->get_user_id());
            } catch (Exception $exception)
            {
                $this->user = new User_model();
            }
        }
        return $this->user;
    }

    function __construct()
    {
        parent::__construct();

        App::get_ci()->load->model('User_model');
        App::get_ci()->load->model('Login_model');
    }

    public static function create(array $data)
    {
        App::get_ci()->s->from(self::CLASS_TABLE)->insert($data)->execute();
        return new static(App::get_ci()->s->get_insert_id());
    }

    public function delete()
    {
        $this->is_loaded(TRUE);
        App::get_ci()->s->from(self::CLASS_TABLE)->where(['id' => $this->get_id()])->delete()->execute();
        return (App::get_ci()->s->get_affected_rows() > 0);
    }
}
