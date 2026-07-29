<?php 

namespace R2Packages\Framework\WalletTransaction;

use Exception;
use R2Packages\Framework\WalletTransaction\WalletTransactionRepository;
use R2Packages\Framework\Request;

class WalletTransactionIdService {

    private WalletTransactionRepository $walletTransactionRepository;
    private Request $request;

    public function __construct(WalletTransactionRepository $walletTransactionRepository, Request $request) {
        $this->walletTransactionRepository = $walletTransactionRepository;
        $this->request = $request;
    }

    public function getWalletTransaction() {
        if($this->request->isEmpty('wallet_transaction_id')) {
            throw new Exception('Wallet transaction ID is required');
        }

        $walletTransaction = $this->walletTransactionRepository->find($this->request->get('wallet_transaction_id'));

        if ($walletTransaction->isEmpty()){
            throw new Exception('Wallet transaction not found');
        }

        return $walletTransaction;
    }



}