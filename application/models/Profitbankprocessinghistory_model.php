<?php

class Profitbankprocessinghistory_model extends Processinghistory_model
{
    const CLASS_TABLE = 'profit_bank_history';

    /** @var int */
    protected $boosterpack_id;

    /**
     * @return int
     */
    public function get_boosterpack_id(): int
    {
        return $this->boosterpack_id;
    }

    /**
     * @param int $boosterpack_id
     *
     * @return bool
     */
    public function set_boosterpack_id(int $boosterpack_id)
    {
        $this->boosterpack_id = $boosterpack_id;
        return $this->save('boosterpack_id', $boosterpack_id);
    }

    public function create(array $data)
    {
        App::get_ci()->s->from(self::CLASS_TABLE)->insert($data)->execute();
        return new static(App::get_ci()->s->get_insert_id());
    }
}
