<?php

namespace R2Packages\Framework\WalletTransaction;

use R2Packages\Framework\WalletTransaction\ApprovedManualTopupWalletTransactionRepository;
use R2Packages\Framework\WalletTransaction\PendingManualTopupWalletTransactionRepository;
use R2Packages\Framework\WalletTransaction\PendingPaymentWalletTransactionRepository;
use R2Packages\Framework\WalletTransaction\WalletTransactionRepository;
use R2Packages\Framework\Request;
use R2Packages\Framework\Services\AuthUserService;
use R2Packages\Framework\WalletTransaction\Filters\ApprovedManualTopupWalletTransactionService;
use R2Packages\Framework\WalletTransaction\Filters\PendingManualTopupWalletTransactionService;
use R2Packages\Framework\WalletTransaction\Filters\PendingPaymentWalletTransactionService;
use R2Packages\Framework\WalletTransaction\WalletTransactionIdService;
use R2Packages\Framework\WalletTransaction\WalletTransactionService;

class WalletController
{

    private WalletTransactionService $walletTransactionService;
    private WalletTransactionRepository $walletTransactionRepository;
    private PendingPaymentWalletTransactionService $pendingPaymentWalletTransactionService;
    private PendingManualTopupWalletTransactionService $pendingManualTopupWalletTransactionService;
    private ApprovedManualTopupWalletTransactionService $approvedManualTopupWalletTransactionService;
    private Request $request;
    private AuthUserService $authUserService;
    private WalletTransactionIdService $walletTransactionIdService;

    function __construct(
        WalletTransactionService $walletTransactionService,
        WalletTransactionRepository $walletTransactionRepository,
        PendingPaymentWalletTransactionService $pendingPaymentWalletTransactionService,
        PendingManualTopupWalletTransactionService $pendingManualTopupWalletTransactionService,
        ApprovedManualTopupWalletTransactionService $approvedManualTopupWalletTransactionService,
        Request $request,
        AuthUserService $authUserService,
        WalletTransactionIdService $walletTransactionIdService
    ) {
        $this->walletTransactionService = $walletTransactionService;
        $this->walletTransactionRepository = $walletTransactionRepository;
        $this->pendingPaymentWalletTransactionService = $pendingPaymentWalletTransactionService;
        $this->pendingManualTopupWalletTransactionService = $pendingManualTopupWalletTransactionService;
        $this->approvedManualTopupWalletTransactionService = $approvedManualTopupWalletTransactionService;
        $this->request = $request;
        $this->authUserService = $authUserService;
        $this->walletTransactionIdService = $walletTransactionIdService;
    }

    function index()
    {
        $walletTransactions = $this->walletTransactionRepository->fetch();
        $total = $this->walletTransactionRepository->count();
        jsonResponse([
            'success' => true,
            'message' => 'Wallet fetched successfully',
            'wallet_transactions' => $walletTransactions,
            'pending_payment_wallet_transactions' => $this->pendingPaymentWalletTransactionService->fetch(),
            'pending_manual_topup_wallet_transactions' => $this->pendingManualTopupWalletTransactionService->fetch(),
            'approved_manual_topup_wallet_transactions' => $this->approvedManualTopupWalletTransactionService->fetch(),
            'total' => $total
        ]);
    }

    function show()
    {
        $walletTransaction = $this->walletTransactionIdService->getWalletTransaction();
        jsonResponse([
            'success' => true,
            'message' => 'Wallet transaction fetched successfully',
            'data' => $walletTransaction
        ]);
    }

    function store()
    {
        $walletTransaction = $this->walletTransactionService->requestPaystackTopup($this->request, $this->authUserService->getAuthUser());
        jsonResponse([
            'success' => true,
            'message' => 'Wallet transaction created successfully',
            'wallet' => $walletTransaction
        ], 200);
    }
}
