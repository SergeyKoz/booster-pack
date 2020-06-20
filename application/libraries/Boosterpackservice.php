<?php

class Boosterpackservice
{
    //protected $processingModel;

    function __construct()
    {
        App::get_ci()->load->library('Profitbankservice');
        App::get_ci()->load->library('Likeservice');
    }

    public function buy(Boosterpack_model $boosterpack)
    {
        $profitBankService = App::get_ci()->profitbankservice;
        $profitBankService->SetProcessingModel($boosterpack);
        $boosterPackPrice = $boosterpack->get_price();

        $walletService = App::get_ci()->walletservice;
        $walletService->withdraw($boosterPackPrice, 'Buster-pack buying %d$', $boosterPackPrice);

        $profitBankBalance = $profitBankService->balance();
        $total = $profitBankBalance + $boosterPackPrice;
        $likes = rand(1, $total);
        $changeProfitBank = $boosterpack->get_price() - $likes;

        $reason = sprintf('Refill Likes balance +%d', $likes);
        App::get_ci()->likeservice->refill($likes, $reason);

        if ($changeProfitBank > 0) {
            $reason = sprintf('Refill profit bank balance +%d', $changeProfitBank);
            $profitBankService->refill($changeProfitBank, $reason);
        } else {
            $reason = sprintf('Withdraw profit bank balance -%d', -$changeProfitBank);
            $profitBankService->withdraw(-$changeProfitBank, $reason);
        }
        return [$likes, $profitBankService->balance()];
    }
}