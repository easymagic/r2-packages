<?php

namespace R2Packages\Framework\WalletTransaction;

use R2Packages\Framework\WalletTransaction\PendingManualTopupWalletTransactionRepository;
use R2Packages\Framework\WalletTransaction\WalletTransactionRepository;
use R2Packages\Framework\Request;
use R2Packages\Framework\Services\AuthUserService;
use R2Packages\Framework\BaseUser\UserIdService;
use R2Packages\Framework\WalletTransaction\WalletTransactionIdService;
use R2Packages\Framework\WalletTransaction\WalletTransactionService;

class PendingTopupRequestController
{

    private WalletTransactionService $walletTransactionService;
    private PendingManualTopupWalletTransactionRepository $pendingManualTopupWalletTransactionRepository;
    private Request $request;
    private AuthUserService $authUserService;
    private WalletTransactionRepository $walletTransactionRepository;

    private WalletTransactionIdService $walletTransactionIdService;


    function __construct(
        WalletTransactionService $walletTransactionService,
        WalletTransactionRepository $walletTransactionRepository,
        PendingManualTopupWalletTransactionRepository $pendingManualTopupWalletTransactionRepository,
        Request $request,
        AuthUserService $authUserService,
        WalletTransactionIdService $walletTransactionIdService,
    ) {
        $this->walletTransactionService = $walletTransactionService;
        $this->pendingManualTopupWalletTransactionRepository = $pendingManualTopupWalletTransactionRepository;
        $this->request = $request;
        $this->authUserService = $authUserService;
        $this->walletTransactionRepository = $walletTransactionRepository;
        $this->walletTransactionIdService = $walletTransactionIdService;
    }

    function index()
    {

        $walletTransactions = $this->pendingManualTopupWalletTransactionRepository->fetch();
        $total = $this->pendingManualTopupWalletTransactionRepository->count();

        jsonResponse([
            'success' => true,
            'message' => 'Manual wallet topup requests fetched successfully',
            'data' => $walletTransactions,
            'total' => $total
        ]);
    }

    function store()
    {
        $result = $this->walletTransactionService->requestManualTopup($this->request, $this->authUserService->getAuthUser());
        return jsonResponse([
            'success' => true,
            'message' => 'Manual wallet topup request created successfully',
            'data' => $result
        ]);
    }


    function show()
    {
        $walletTransaction = $this->walletTransactionIdService->getWalletTransaction();
        jsonResponse([
            'success' => true,
            'message' => 'Manual wallet topup request fetched successfully',
            'data' => $walletTransaction
        ]);
    }

    function update()
    {
        $walletTransaction = $this->walletTransactionIdService->getWalletTransaction();
        $walletTransaction = $this->walletTransactionService->approveManualTopup(
            $this->request,
            $walletTransaction
        );
        jsonResponse([
            'success' => true,
            'message' => 'Manual wallet topup request approved successfully',
            'data' => $walletTransaction
        ]);
    }

    function destroy()
    {
        $walletTransaction = $this->walletTransactionIdService->getWalletTransaction();
        $walletTransaction = $this->walletTransactionService->rejectManualTopup(
            $this->request,
            $walletTransaction
        );
        jsonResponse([
            'success' => true,
            'message' => 'Manual wallet topup request rejected successfully',
            'data' => $walletTransaction
        ]);
    }
}
