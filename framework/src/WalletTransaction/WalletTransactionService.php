<?php

namespace R2Packages\Framework\WalletTransaction;

use Exception;
use R2Packages\Framework\BaseUser\BaseUserEntity;
use R2Packages\Framework\WalletTransaction\WalletTransactionEntity;
use R2Packages\Framework\FileUploadService;
use R2Packages\Framework\MailService;
use R2Packages\Framework\BaseUser\BaseUserRepository;
use R2Packages\Framework\WalletTransaction\PendingPaymentWalletTransactionRepository;
use R2Packages\Framework\WalletTransaction\WalletTransactionRepository;
use R2Packages\Framework\Request;
use R2Packages\Framework\Services\AuthUserService;
use R2Packages\Framework\Services\MyMailTemplate;
use R2Packages\Framework\Services\PaymentService;

class WalletTransactionService
{
    private WalletTransactionRepository $walletTransactionRepository;

    private PaymentService $paymentService;
    private FileUploadService $fileUploadService;
    private MyMailTemplate $myMailTemplate;
    private MailService $mailService;
    private BaseUserRepository $baseUserRepository;

    private AuthUserService $authUserService;



    function __construct(
        AuthUserService $authUserService,
        WalletTransactionRepository $walletTransactionRepository,
        PaymentService $paymentService,
        FileUploadService $fileUploadService,
        MyMailTemplate $myMailTemplate,
        MailService $mailService,
        BaseUserRepository $baseUserRepository
    ) {
        $this->authUserService = $authUserService;
        $this->walletTransactionRepository = $walletTransactionRepository;
        $this->paymentService = $paymentService;
        $this->fileUploadService = $fileUploadService;
        $this->myMailTemplate = $myMailTemplate;
        $this->mailService = $mailService;
        $this->baseUserRepository = $baseUserRepository;

        if (!$this->authUserService->getAuthUser()->isEmpty()){
            $userId = $this->authUserService->getAuthUser()->id;

            // if user is not admin, filter by user id
            if (!$this->authUserService->getAuthUser()->isAdmin()){
                $this->walletTransactionRepository->filterByUserId($userId);
            }

        }
    }

    function fetch(){
        return $this->walletTransactionRepository->fetch();
    }

    function fetchAll(){
        return $this->walletTransactionRepository->fetchAll();
    }

    function count(){
        return $this->walletTransactionRepository->count();
    }

    function requestPaystackTopup(Request $request, BaseUserEntity $user)
    {
        // amount 
        $request->require("amount", "Amount is required!");

        // user

        $balance = $user->wallet_balance + $request->get("amount");

        $request->input = [];

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

        $this->paymentService->initiate(
            $user->email,
            $request->get("amount"),
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
        $request->require("amount", "Amount is required!");
        // description
        $request->require("description", "Description is required!");
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
        WalletTransactionEntity $walletTransaction
    ) {

        if ($walletTransaction->isAlreadyApproved()) {
            throw new Exception('Manual topup request already approved!');
        }
        $user = $walletTransaction->user;
        $newBalance = $user->wallet_balance + $walletTransaction->amount;

        $request->input = [];
        $request->input["action_by"] = $user->id;
        $request->input["action_at"] = date('Y-m-d H:i:s');
        $request->input["status"] = 'successful';
        $request->input["approval_status"] = 'approved';

        $walletTransaction = $this->walletTransactionRepository->save(
            $walletTransaction->id,
            $request->input
        );

        $this->baseUserRepository->save($user->id, [
            'wallet_balance' => $newBalance
        ]);

        $this->mailService->send(
            $user->email,
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
        WalletTransactionEntity $walletTransaction
    ) {
        $request->input = [];
        $request->require("reason", "Reason is required!");

        if ($walletTransaction->isAlreadyApproved()) {
            throw new Exception('Manual topup request already approved, you cannot reject it!');
        }
        $user = $walletTransaction->user;



        // $this->reason = $reason;
        // $this->action_by = $user->id;
        // $this->action_at = date('Y-m-d H:i:s');
        // $this->status = 'failed';
        // $this->approval_status = 'rejected';
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

    function getPaymentService()
    {
        return $this->paymentService;
    }

    function getWalletTransactionRepository()
    {
        return $this->walletTransactionRepository;
    }

    function getBaseUserRepository()
    {
        return $this->baseUserRepository;
    }

    function getAuthUserService()
    {
        return $this->authUserService;
    }
    
}
