<?php

namespace R2Packages\Framework\v2\Domain;

use R2Packages\Framework\v2\Interfaces\PaymentServiceInterface;
use R2Packages\Framework\v2\Interfaces\WalletRepositoryInterface;
use R2Packages\Framework\v2\Interfaces\WalletServiceInterface;
use R2Packages\Framework\Request;
use R2Packages\Framework\v2\Interfaces\NotificationLogServiceInterface;
use R2Packages\Framework\v2\Interfaces\RepositoryInterface;


abstract class AbstractWalletController
{

    protected WalletServiceInterface $service;
    protected RepositoryInterface $repository;
    protected Request $request;
    protected PaymentServiceInterface $paymentService;
    protected WalletRepositoryInterface $walletRepository;
    protected NotificationLogServiceInterface $notificationLogService;

    public function __construct(
        WalletServiceInterface $service,
        RepositoryInterface $repository,
        PaymentServiceInterface $paymentService,
        WalletRepositoryInterface $walletRepository,
        Request $request,
        NotificationLogServiceInterface $notificationLogService
    ) {
        $this->service = $service;
        $this->repository = $repository;
        $this->request = $request;
        $this->paymentService = $paymentService;
        $this->walletRepository = $walletRepository;
        $this->notificationLogService = $notificationLogService;
    }

    public function topUp()
    {
        $data = $this->service->validateTopUp(
            $this->service->getAuthUser(),
            $this->request,
            $this->repository
        );
        $response = $this->service->topUp(
            $data,
            $this->service->getAuthUser(),
            $this->repository,
            $this->paymentService,
            $this->notificationLogService
        );
        return jsonResponse([
            'message' => 'Top up successful',
            'data' => $response,
            "status" => "success"
        ]);
    }

    function processPaymentFeedback(){
        $response = $this->service->processPaymentFeedback(
            $this->service->getAuthUser(),
            $this->walletRepository,
            $this->repository,
            $this->notificationLogService
        );
        return jsonResponse([
            'message' => 'Payment feedback processed',
            'data' => $response,
            "status" => "success"
        ]);
    }

    function manualTopUpRequest(){
        $data = $this->service->validateManualTopUpRequest(
            $this->service->getAuthUser(),
            $this->request,
            $this->repository
        );
        $response = $this->service->manualTopUpRequest(
            $data,
            $this->service->getAuthUser(),
            $this->repository,
            $this->notificationLogService
        );
        return jsonResponse([
            'message' => 'Manual top up request successful',
            'data' => $response,
            "status" => "success"
        ]);
    }

    function approveManualTopUpRequest(){
        $data = $this->service->validateApproveManualTopUpRequest(
            $this->service->getAuthUser(),
            $this->request,
            $this->repository
        );
        $response = $this->service->approveManualTopUpRequest(
            $data,
            $this->service->getAuthUser(),
            $this->request, $this->repository,
            $this->notificationLogService
        );
        return jsonResponse([
            'message' => 'Manual top up request approved',
            'data' => $response,
            "status" => "success"
        ]);
    }


    function rejectManualTopUpRequest(){
        $walletTransactionEntity = $this->service->fetchById(
            $this->request,
            $this->repository
        );
        $data = $this->service->validateRejectManualTopUpRequest(
            $walletTransactionEntity,
            $this->service->getAuthUser(),
            $this->request,
            $this->repository
        );
        $response = $this->service->rejectManualTopUpRequest(
            $data,
            $walletTransactionEntity,
            $this->service->getAuthUser(),
            $this->request, $this->repository,
            $this->notificationLogService
        );
        return jsonResponse([
            'message' => 'Manual top up request rejected',
            'data' => $response,
            "status" => "success"
        ]);
    }

    function fetchWalletTransactions(){
        $response = $this->service->fetchWalletTransactions(
            $this->service->getAuthUser(),
            $this->request,
            $this->repository
        );
        return jsonResponse([
            'message' => 'Wallet transactions fetched',
            'data' => $response,
            "status" => "success"
        ]);
    }

    function fetchPendingWalletTransactions(){
        $response = $this->service->fetchPendingWalletTransactions(
            $this->service->getAuthUser(),
            $this->request,
            $this->repository
        );
        return jsonResponse([
            'message' => 'Pending wallet transactions fetched',
            'data' => $response,
            "status" => "success"
        ]);
    }

    function fetchManualPendingTopUpRequests(){
        $response = $this->service->fetchManualPendingTopUpRequests(
            $this->service->getAuthUser(),
            $this->request,
            $this->repository
        );
        return jsonResponse([
            'message' => 'Manual pending top up requests fetched',
            'data' => $response,
            "status" => "success"
        ]);
    }

    public function fetchManualApprovedTopUpRequests(){
        $response = $this->service->fetchManualApprovedTopUpRequests(
            $this->service->getAuthUser(),
            $this->request,
            $this->repository
        );
        return jsonResponse([
            'message' => 'Manual approved top up requests fetched',
            'data' => $response,
            "status" => "success"
        ]);
    }

}
