<?php

namespace R2Packages\Framework\WalletTransaction;

use R2Packages\Framework\WalletTransaction\WalletTransactionEntity;
use R2Packages\Framework\PaginationMetta;
use R2Packages\Framework\Repositories\DbRepository;
use R2Packages\Framework\WalletTransaction\WalletTransactionRepository;
use R2Packages\Framework\Request;
use R2Packages\Framework\Services\AuthUserService;

class ApprovedManualTopupWalletTransactionRepository extends WalletTransactionRepository
{
    public function __construct(
        WalletTransactionEntity $walletTransactionEntity,
        DbRepository $dbRepository,
        PaginationMetta $paginationMeta,
        Request $request,
        AuthUserService $authUserService
    ) {
        parent::__construct(
            $walletTransactionEntity,
            $dbRepository,
            $paginationMeta,
            $request,
            $authUserService
        );
    }

    protected function applyCommonFilters()
    {
        parent::applyCommonFilters();
        
        $this->filterByStatus("successful");
        $this->filterByApprovalStatus("approved");
        $this->filterBySource("manual");
    }
}
