<?php

class Profitbankservice extends Paymentservice
{
    function __construct()
    {
        App::get_ci()->load->model('Processinghistory_model');
        App::get_ci()->load->model('Profitbankprocessinghistory_model');
    }

    public function refill(float $sum, string $reason)
    {
        if ($sum <= 0) {
            throw new \Exception("Refill sum has to be greater then 0");
        }
        $balance = $this->balance();
        $this->processingModel->set_bank($balance + $sum);
        $this->onAfterRefill($sum, $reason);
    }

    public function withdraw(float $sum, string $reason)
    {
        if ($sum <= 0) {
            throw new \Exception("Withdraw sum has to be greater then 0");
        }

        $balance = $this->balance();

        if ($balance < $sum) {
            throw new \Exception("Not enough balance");
        }

        $this->processingModel->set_bank($balance - $sum);
        $this->onAfterWithdraw($sum, $reason);
    }

    public function balance(): float
    {
        return$this->processingModel->get_bank();
    }

    function onAfterRefill(float $sum, string $reason) {
        $item = [
            'boosterpack_id' => $this->processingModel->get_id(),
            'sum' => $sum,
            'balance' => $this->balance(),
            'info' => $reason,
            'created_at' => time()];
        Profitbankprocessinghistory_model::create($item);
    }

    function onAfterWithdraw(float $sum, string $reason) {
        $item = [
            'boosterpack_id' => $this->processingModel->get_id(),
            'sum' => -$sum,
            'balance' => $this->balance(),
            'info' => $reason,
            'created_at' => time()];
        Profitbankprocessinghistory_model::create($item);
    }
}