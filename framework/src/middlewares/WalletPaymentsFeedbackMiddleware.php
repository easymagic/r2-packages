<?php

namespace R2Packages\Framework\middlewares;

use R2Packages\Framework\Request;
use R2Packages\Framework\Services\WalletTransactionService;

class WalletPaymentsFeedbackMiddleware
{

    private WalletTransactionService $walletTransactionService;
    private Request $request;

    public function __construct(
        WalletTransactionService $walletTransactionService,
        Request $request
    ) {
        $this->walletTransactionService = $walletTransactionService;
        $this->request = $request;
    }

    public function handle()
    {
        $this->walletTransactionService->paystackFeedbback(
            $this->request
        );
    }
}
