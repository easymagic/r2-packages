<?php 

namespace R2Packages\Framework\v2\WalletTransaction;

class WalletTransactionEntity {

    public $id;
    public $userId;
    public $reference;
    public $type;
    public $amount;
    public $balance;
    public $source;
    public $description;
    public $payment_url;
    public $proof_of_payment_screenshot1;
    public $proof_of_payment_screenshot2;
    public $proof_of_payment_screenshot3;
    public $approval_status;
    public $reason;
    public $action_by;
    public $action_at;
    public $status;
    public $created_at;

    function __construct($data = []) {
        setAttributes($this,$data);
    }

    public function isEmpty() {
        return empty($this->id);
    }
}