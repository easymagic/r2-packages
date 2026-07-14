<?php

namespace R2Packages\Framework\middlewares;

use R2Packages\Framework\Request;
use R2Packages\Framework\WalletTransaction\Filters\PendingPaymentWalletTransactionService;

class WalletPaymentsFeedbackMiddleware
{

    private PendingPaymentWalletTransactionService $pendingPaymentWalletTransactionService;
    private Request $request;

    public function __construct(
        PendingPaymentWalletTransactionService $pendingPaymentWalletTransactionService,
        Request $request
    ) {
        $this->pendingPaymentWalletTransactionService = $pendingPaymentWalletTransactionService;
        $this->request = $request;
    }

    public function handle()
    {
        $this->pendingPaymentWalletTransactionService->paystackFeedback(
            $this->request
        );
    }
}
