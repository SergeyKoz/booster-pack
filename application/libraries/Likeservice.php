<?php

class Likeservice extends Paymentservice
{
    function __construct()
    {
        $this->processingModel = App::get_ci()->Likeprocessing_model;

        App::get_ci()->load->model('Processinghistory_model');
        App::get_ci()->load->model('Likeprocessinghistory_model');
    }

    public function likesUpdate(int $id, string $type, int $amount = 1)
    {
        if ($type == 'post') {
            $model = new Post_model($id);
        } elseif ($type == 'comment') {
            $model = new Comment_model($id);
        } else {
            throw new \Exception('Unsupported type');
        }
        $likes = $model->get_likes();
        $model->set_likes($likes + $amount);
        $reason = sprintf('Liked %s id:%d Sum: -%d', $type, $id, $amount);
        $this->withdraw($amount, $reason);
        return $likes + $amount;
    }

    function onAfterRefill(float $sum, string $reason) {
        $item = [
            'user_id' => $this->processingModel->get_user_id(),
            'sum' => $sum,
            'balance' => $this->balance(),
            'info' => $reason,
            'created_at' => time()];
        Likeprocessinghistory_model::create($item);
    }

    function onAfterWithdraw(float $sum, string $reason) {
        $item = [
            'user_id' => $this->processingModel->get_user_id(),
            'sum' => -$sum,
            'balance' => $this->balance(),
            'info' => $reason,
            'created_at' => time()];
        Likeprocessinghistory_model::create($item);
    }
}