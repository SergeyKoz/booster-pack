<?php

class Walletservice
{
    protected $processingModel;

    function __construct()
    {
        $this->processingModel = User_model::get_user();

        App::get_ci()->load->model('Processinghistory_model');
        App::get_ci()->load->model('Walletprocessinghistory_model');
    }

    public function refill(float $sum, string $reason)
    {
        if ($sum <= 0) {
            throw new \Exception("Refill sum has to be greater then 0");
        }

        $balance = $this->balance();
        $this->processingModel->set_wallet_balance($balance + $sum);

        $wallet_total_withdrawn = $this->processingModel->get_wallet_total_refilled($balance + $sum);
        $this->processingModel->set_wallet_total_refilled($wallet_total_withdrawn + $sum);

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

        $this->processingModel->set_wallet_balance($balance - $sum);

        $wallet_total_refilled = $this->processingModel->get_wallet_total_withdrawn($balance + $sum);
        $this->processingModel->set_wallet_total_withdrawn($wallet_total_refilled + $sum);

        $this->onAfterWithdraw($sum, $reason);
    }

    public function balance(): float
    {
        return $this->processingModel->get_wallet_balance();
    }

    function onAfterRefill(float $sum, string $reason)
    {
        $item = [
            'user_id' => $this->processingModel->get_id(),
            'sum' => $sum,
            'balance' => $this->balance(),
            'info' => $reason,
            'created_at' => time()];
        Walletprocessinghistory_model::create($item);
    }

    function onAfterWithdraw(float $sum, string $reason)
    {
        $item = [
            'user_id' => $this->processingModel->get_id(),
            'sum' => -$sum,
            'balance' => $this->balance(),
            'info' => $reason,
            'created_at' => time()];
        Walletprocessinghistory_model::create($item);
    }
}