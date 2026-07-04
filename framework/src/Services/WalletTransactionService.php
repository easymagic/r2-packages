<?php

namespace R2Packages\Framework\Services;

use Exception;
use R2Packages\Framework\Entities\BaseUserEntity;
use R2Packages\Framework\Entities\WalletTransactionEntity;
use R2Packages\Framework\FileUploadService;
use R2Packages\Framework\MailService;
use R2Packages\Framework\Repositories\BaseUserRepository;
use R2Packages\Framework\Repositories\Filters\PendingPaymentWalletTransactionRepository;
use R2Packages\Framework\Repositories\WalletTransactionRepository;
use R2Packages\Framework\Request;

class WalletTransactionService
{
    private WalletTransactionRepository $walletTransactionRepository;

    private PaymentService $paymentService;
    private FileUploadService $fileUploadService;
    private MyMailTemplate $myMailTemplate;
    private MailService $mailService;
    private BaseUserRepository $baseUserRepository;
    private PendingPaymentWalletTransactionRepository $pendingPaymentWalletTransactionRepository;



    function __construct(
        WalletTransactionRepository $walletTransactionRepository,
        PaymentService $paymentService,
        FileUploadService $fileUploadService,
        MyMailTemplate $myMailTemplate,
        MailService $mailService,
        BaseUserRepository $baseUserRepository,
        PendingPaymentWalletTransactionRepository $pendingPaymentWalletTransactionRepository
    ) {
        $this->walletTransactionRepository = $walletTransactionRepository;
        $this->paymentService = $paymentService;
        $this->fileUploadService = $fileUploadService;
        $this->myMailTemplate = $myMailTemplate;
        $this->mailService = $mailService;
        $this->baseUserRepository = $baseUserRepository;
        $this->pendingPaymentWalletTransactionRepository = $pendingPaymentWalletTransactionRepository;
    }

    function requestPaystackTopup(Request $request, BaseUserEntity $user)
    {
        // amount 
        if ($request->isEmpty("amount")) {
            throw new Exception('Amount is required!');
        }
        $amount = $request->get("amount");
        // user

        $balance = $user->wallet_balance + $amount;

        $request->input = [];

        $request->input["amount"] = $amount;
        $request->input["balance"] = $balance;
        $request->input["type"] = 'credit';
        $request->input["source"] = 'paystack';
        $request->input["status"] = 'pending';
        $request->input["approval_status"] = 'pending';
        $request->input["action_by"] = $user->id;
        $request->input["action_at"] = date('Y-m-d H:i:s');
        $request->input["created_at"] = date('Y-m-d H:i:s');
        $request->input["user_id"] = $user->id;
        $request->input["reference"] = uniqid("WALLET_REF-");

        // print_r($request->input);

        // $payment = new PaymentEntity([
        //     'email' => $authenticatedUserEntity->email,
        //     'amount' => $amount,
        //     'reference' => $request->input["reference"],
        //     // 'callback_url' => $callback_url($walletTransaction),
        // ]);

        // print_r($payment);

        $this->paymentService->initiate(
            $user->email,
            $amount,
            $request->input["reference"]
        );


        $request->input["payment_url"] = $this->paymentService->auth_url;
        $request->input["reference"] = $this->paymentService->reference;

        $walletTransaction = $this->walletTransactionRepository->save(0, $request->input);

        return $walletTransaction;
    }

    function requestManualTopup(Request $request, BaseUserEntity $user)
    {

        $request->input = [];

        // user id
        $request->input["user_id"] = $user->id;

        // amount
        if ($request->isEmpty("amount")) {
            throw new Exception('Amount is required!');
        }
        $request->input["amount"] = $request->get("amount");
        // description
        if ($request->isEmpty("description")) {
            throw new Exception('Description is required!');
        }
        $request->input["description"] = $request->get("description");
        // proof of payment screenshot 1
        // proof of payment screenshot 2

        // proof of payment screenshot 1
        if (!$request->isEmpty("proof_of_payment_screenshot1")) {
            $request->input["proof_of_payment_screenshot1"] = $this->fileUploadService->uploadFile($request->data["proof_of_payment_screenshot1"], 'manual_topup_wallet_proof');
        }
        // proof of payment screenshot 2
        if (!$request->isEmpty("proof_of_payment_screenshot2")) {
            $request->input["proof_of_payment_screenshot2"] = $this->fileUploadService->uploadFile($request->data["proof_of_payment_screenshot2"], 'manual_topup_wallet_proof');
        }
        // proof of payment screenshot 3
        if (!$request->isEmpty("proof_of_payment_screenshot3")) {
            $request->input["proof_of_payment_screenshot3"] = $this->fileUploadService->uploadFile($request->data["proof_of_payment_screenshot3"], 'manual_topup_wallet_proof');
        }

        $request->input["reference"] = uniqid("WALLET_REF-");
        $request->input["type"] = 'credit-manual';
        $request->input["source"] = 'manual';
        $request->input["status"] = 'pending';
        $request->input["approval_status"] = 'pending';

        $walletTransaction = $this->walletTransactionRepository->save(0, $request->input);

        $this->mailService->send(
            $walletTransaction->user->email,
            'Manual Topup Wallet Request',
            'noreply@example.com',
            $this->myMailTemplate->requestManualTopupNofication(
                $walletTransaction->user->name,
                $walletTransaction->amount,
                $walletTransaction->reference,
                $walletTransaction->type,
                $walletTransaction->source,
                $walletTransaction->status
            )
        );
        return $walletTransaction;
    }


    function approveManualTopup(
        Request $request,
        BaseUserEntity $user,
        WalletTransactionEntity $walletTransaction
    ) {

        if ($walletTransaction->isAlreadyApproved()) {
            throw new Exception('Manual topup request already approved!');
        }
        $newBalance = $user->wallet_balance + $walletTransaction->amount;

        $request->input = [];
        $request->input["action_by"] = $user->id;
        $request->input["action_at"] = date('Y-m-d H:i:s');
        $request->input["status"] = 'successful';
        $request->input["approval_status"] = 'approved';

        // $walletTransaction->approve($authenticatedUser);
        $walletUser = $walletTransaction->user;

        $walletTransaction = $this->walletTransactionRepository->save(
            $walletTransaction->id,
            $request->input
        );

        $this->baseUserRepository->save($user->id, [
            'wallet_balance' => $walletUser->wallet_balance
        ]);

        $this->mailService->send(
            $walletTransaction->user->email,
            'Manual Topup Wallet Approved',
            'noreply@example.com',
            $this->myMailTemplate->notifyApprovedManualTopup(
                $walletTransaction->user->name,
                $walletTransaction->amount,
                $walletTransaction->balance,
                $walletTransaction->reference,
                $walletTransaction->type,
                $walletTransaction->source,
                $walletTransaction->status
            )
        );

        return $walletTransaction;
    }

    function rejectManualTopup(
        Request $request,
        BaseUserEntity $user,
        WalletTransactionEntity $walletTransaction
    ) {
        if ($request->isEmpty("reason")) {
            throw new Exception('Reason is required!');
        }
        $reason = $request->get("reason");

        if ($walletTransaction->isAlreadyApproved()) {
            throw new Exception('Manual topup request already approved, you cannot reject it!');
        }



        // $this->reason = $reason;
        // $this->action_by = $user->id;
        // $this->action_at = date('Y-m-d H:i:s');
        // $this->status = 'failed';
        // $this->approval_status = 'rejected';
        $request->input = [];
        $request->input["reason"] = $reason;
        $request->input["action_by"] = $user->id;
        $request->input["action_at"] = date('Y-m-d H:i:s');
        $request->input["status"] = 'failed';
        $request->input["approval_status"] = 'rejected';

        // $walletTransaction->reject($authUser, $reason);
        $walletTransaction = $this->walletTransactionRepository->save(
            $walletTransaction->id,
            $request->input
        );
        $this->mailService->send(
            $walletTransaction->user->email,
            'Manual Topup Wallet Rejected',
            'noreply@example.com',
            $this->myMailTemplate->notifyRejectedManualTopup(
                $walletTransaction->user->name,
                $walletTransaction->amount,
                $walletTransaction->balance,
                $walletTransaction->reference,
                $walletTransaction->type,
                $walletTransaction->source,
                $walletTransaction->status,
                $walletTransaction->reason
            )
        );
        return $walletTransaction;
    }


    function paystackFeedbback(
        Request $request
    ) {
        $pendingWalletTransaction = $this->pendingPaymentWalletTransactionRepository->fetchAll();
        foreach ($pendingWalletTransaction as $pendingWalletTransaction) {
            /** @var WalletTransactionEntity $pendingWalletTransaction **/
            // $paymentService = new PaymentService();
            $this->paymentService->verify($pendingWalletTransaction->reference);
            if ($this->paymentService->status == 'success') {
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
                $this->walletTransactionRepository->save($pendingWalletTransaction->id, $request->input);
                $this->baseUserRepository->save($user->id, [
                    'wallet_balance' => $balance,
                ]);
            }
        }
    }

    
}
