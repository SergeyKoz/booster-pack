<?php

class Paymentservice
{
    protected $processingModel;

    protected $processingHistoryModel;

    protected $reason;
    protected $sum;

    public function SetProcessingModel($processingModel) {
        $this->processingModel = $processingModel;
    }

    public function refill(float $sum, string $reason)
    {
        if ($sum <= 0) {
            throw new \Exception("Refill sum has to be greater then 0");
        }
        $balance = $this->balance();
        $this->processingModel->set_balance($balance + $sum);
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

        $this->processingModel->set_balance($balance - $sum);

        $this->onAfterWithdraw($sum, $reason);
    }

    public function balance(): float
    {
        return$this->processingModel->get_balance();
    }

    function onAfterRefill(float $sum, string $reason)
    {

    }

    function onAfterWithdraw(float $sum, string $reason)
    {

    }
}