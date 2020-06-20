<?php

class Processinghistory_model extends CI_Emerald_Model
{
    /** @var float */
    protected $balance;
    /** @var float */
    protected $sum;
    /** @var float */
    protected $info;
     /** @var int */
    protected $created_at;

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
     * @return int
     */
    public function get_created_at(): int
    {
        return $this->created_at;
    }

    /**
     * @param int $created_at
     *
     * @return bool
     */
    public function set_created_at(int $created_at)
    {
        $this->created_at = $created_at;
        return $this->save('created_at', $created_at);
    }
}
