<?php

namespace R2Packages\Framework\WalletTransaction\Filters;

use R2Packages\Framework\BaseUser\BaseUserRepository;
use R2Packages\Framework\FileUploadService;
use R2Packages\Framework\MailService;
use R2Packages\Framework\WalletTransaction\WalletTransactionRepository;
use R2Packages\Framework\Services\AuthUserService;
use R2Packages\Framework\Services\MyMailTemplate;
use R2Packages\Framework\Services\PaymentService;
use R2Packages\Framework\WalletTransaction\WalletTransactionService;

class PendingManualTopupWalletTransactionService extends WalletTransactionService
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

        $walletTransactionRepository->filterBySource("manual");
        $walletTransactionRepository->filterByStatus("pending");

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

}
