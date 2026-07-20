<?php

namespace R2Packages\Framework\v2\Interfaces;

use R2Packages\Framework\v2\Interfaces\WalletRepositoryInterface;
use R2Packages\Framework\Request;
use R2Packages\Framework\v2\Interfaces\RepositoryInterface;
use R2Packages\Framework\v2\User\UserEntity;
use R2Packages\Framework\WalletTransaction\WalletTransactionEntity;

interface WalletServiceInterface
{

    public function validateTopUp(
        UserEntity $user,
        Request $request,
        RepositoryInterface $repositoryInterface
    );

    /**
     * Top up wallet
     * @param array $data
     * @param UserEntity $user
     * @param RepositoryInterface $repositoryInterface
     * @param PaymentServiceInterface $paymentServiceInterface
     * @return mixed
     */
    public function topUp(
        $data,
        UserEntity $user,
        RepositoryInterface $repositoryInterface,
        PaymentServiceInterface $paymentServiceInterface,
        NotificationLogServiceInterface $notificationLogServiceInterface
    );

    public function processPaymentFeedback(
        UserEntity $user,
        WalletRepositoryInterface $walletRepositoryInterface,
        RepositoryInterface $repositoryInterface,
        NotificationLogServiceInterface $notificationLogServiceInterface
    );


    public function validateManualTopUpRequest(
        UserEntity $user,
        Request $request,
        RepositoryInterface $repositoryInterface
    );

    /**
     * Manual top up request
     * @param array $data
     * @param UserEntity $user
     * @param RepositoryInterface $repositoryInterface
     * @return mixed
     */
    public function manualTopUpRequest(
        $data,
        UserEntity $user,
        RepositoryInterface $repositoryInterface,
        NotificationLogServiceInterface $notificationLogServiceInterface
    );

    public function validateApproveManualTopUpRequest(
        UserEntity $user,
        Request $request,
        RepositoryInterface $repositoryInterface
    );


    /**
     * Approve manual top up request
     * @param array $data
     * @param UserEntity $user
     * @param Request $request
     * @param RepositoryInterface $repositoryInterface
     * @return mixed
     */
    public function approveManualTopUpRequest(
        $data,
        UserEntity $user,
        Request $request,
        RepositoryInterface $repositoryInterface,
        NotificationLogServiceInterface $notificationLogServiceInterface
    );


    public function validateRejectManualTopUpRequest(
        WalletTransactionEntity $walletTransactionEntity,
        UserEntity $user,
        Request $request,
        RepositoryInterface $repositoryInterface
    );

    /**
     * Reject manual top up request
     * @param array $data
     * @param UserEntity $user
     * @param Request $request
     * @param RepositoryInterface $repositoryInterface
     * @return mixed
     */
    public function rejectManualTopUpRequest(
        $data,
        WalletTransactionEntity $walletTransactionEntity,
        UserEntity $user,
        Request $request,
        RepositoryInterface $repositoryInterface,
        NotificationLogServiceInterface $notificationLogServiceInterface
    );

    public function fetchWalletTransactions(
        UserEntity $user,
        Request $request,
        RepositoryInterface $repositoryInterface
    );

    public function fetchPendingWalletTransactions(
        UserEntity $user,
        Request $request,
        RepositoryInterface $repositoryInterface
    );

    public function fetchManualPendingTopUpRequests(
        UserEntity $user,
        Request $request,
        RepositoryInterface $repositoryInterface
    );

    public function fetchManualApprovedTopUpRequests(
        UserEntity $user,
        Request $request,
        RepositoryInterface $repositoryInterface
    );

    public function fetchById(
        Request $request,
        RepositoryInterface $repositoryInterface
    );

    public function getAuthUser();
}
