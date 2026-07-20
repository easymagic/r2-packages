<?php 

namespace R2Packages\Framework\v2\WalletTransaction;


use R2Packages\Framework\Migration;

class WalletTransactionMigration {

    public function run(){
        Migration::table('wallet_transactions')
        ->field('user_id')->definition('BIGINT NOT NULL')->run()
        ->field('reference')->definition('VARCHAR(100) DEFAULT NULL')->run()
        ->field('type')->definition("ENUM('credit','debit','credit-manual','debit-manual') DEFAULT 'credit'")->run()
        ->field('amount')->definition('FLOAT DEFAULT 0.00')->run()
        ->field('balance')->definition('FLOAT DEFAULT 0.00')->run()
        ->field('source')->definition("ENUM('paystack','manual','refund','adjustment','wallet') DEFAULT 'paystack'")->run()
        ->field('description')->definition('TEXT DEFAULT NULL')->run()
        ->field('payment_url')->definition('VARCHAR(255) DEFAULT NULL')->run()
        ->field('proof_of_payment_screenshot1')->definition('VARCHAR(255) DEFAULT NULL')->run()
        ->field('proof_of_payment_screenshot2')->definition('VARCHAR(255) DEFAULT NULL')->run()
        ->field('proof_of_payment_screenshot3')->definition('VARCHAR(255) DEFAULT NULL')->run()
        ->field('approval_status')->definition("ENUM('pending','approved','rejected') DEFAULT 'pending'")->run()
        ->field('reason')->definition('TEXT DEFAULT NULL')->run()
        ->field('action_by')->definition('BIGINT DEFAULT NULL')->run()
        ->field('action_at')->definition('TIMESTAMP DEFAULT CURRENT_TIMESTAMP')->run()
        ->field('status')->definition("ENUM('pending','successful','failed','automatic') DEFAULT 'pending'")->run()
        ->field('created_at')->definition('TIMESTAMP DEFAULT CURRENT_TIMESTAMP')->run();
        echo '<br />Wallet Transaction migration completed';
    }
}
