<?php

namespace R2Packages\Framework\WalletTransaction\Filters;

use R2Packages\Framework\BaseUser\BaseUserRepository;
use R2Packages\Framework\FileUploadService;
use R2Packages\Framework\MailService;
use R2Packages\Framework\Request;
use R2Packages\Framework\WalletTransaction\WalletTransactionRepository;
use R2Packages\Framework\Services\AuthUserService;
use R2Packages\Framework\Services\MyMailTemplate;
use R2Packages\Framework\Services\PaymentService;
use R2Packages\Framework\WalletTransaction\WalletTransactionService;
use R2Packages\Framework\WalletTransaction\WalletTransactionEntity;

class PendingPaymentWalletTransactionService extends WalletTransactionService
{
    public function __construct(
        AuthUserService $authUserService,
        WalletTransactionRepository $walletTransactionRepository,
        PaymentService $paymentService,
        FileUploadService $fileUploadService,
        MyMailTemplate $myMailTemplate,
        MailService $mailService,
        BaseUserRepository $baseUserRepository
    ) {

        $walletTransactionRepository->filterBySource("paystack");
        $walletTransactionRepository->filterByStatus("pending");
        $walletTransactionRepository->filterByType("credit");
        $walletTransactionRepository->filterByDuration(30); // 30 mins

        parent::__construct(
            $authUserService,
            $walletTransactionRepository,
            $paymentService,
            $fileUploadService,
            $myMailTemplate,
            $mailService,
            $baseUserRepository
        );
    }


    function paystackFeedback(
        Request $request
    ) {
        $pendingWalletTransaction = $this->fetchAll();
        /** @var WalletTransactionEntity $pendingWalletTransaction **/
        foreach ($pendingWalletTransaction as $pendingWalletTransaction) {
            
            // $paymentService = new PaymentService();
            $this->getPaymentService()->verify($pendingWalletTransaction->reference);
            if ($this->getPaymentService()->status == 'success') {
                if ($pendingWalletTransaction->isAlreadyApproved()) {
                    // echo '<h2>Payment already approved!</h2>';
                    return false;
                }
                $user = $pendingWalletTransaction->user;
                $balance = $user->wallet_balance + $pendingWalletTransaction->amount;
                
                // $pendingWalletTransaction->approveFromPaystack();
                $request->input = [];
                $request->input["status"] = 'successful';
                $request->input["approval_status"] = 'approved';
                $request->input["action_at"] = date('Y-m-d H:i:s');

                // dd("...", $walletTransaction);
                $this->getWalletTransactionRepository()->save($pendingWalletTransaction->id, $request->input);
                $this->getBaseUserRepository()->save($user->id, [
                    'wallet_balance' => $balance,
                ]);
            }
        }
    }


}
