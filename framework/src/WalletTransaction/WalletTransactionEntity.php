<?php 

namespace R2Packages\Framework\WalletTransaction;

use R2Packages\Framework\BaseUser\BaseUserEntity;
use R2Packages\Framework\BaseUser\BaseUserRepository;

class WalletTransactionEntity
{
    public $id = 0;
    public $user_id = '';
    public $reference = '';
    public $type = '';
    public $amount = '';
    public $balance = '';
    public $source = '';
    public $description = '';
    public $proof_of_payment_screenshot1 = '';
    public $proof_of_payment_screenshot2 = '';
    public $proof_of_payment_screenshot3 = '';
    public $approval_status = '';
    public $reason = '';
    public $action_by = '';
    public $action_at = '';
    public $status = '';
    public $created_at = '';
    public $payment_url = '';

    public $media_base_url = '';

    public BaseUserEntity $user;
    

    function __construct(BaseUserEntity $user,$data = []){
        $this->user = $user;
        setAttributes($this, $data);
    }

    function isEmpty(){
        return empty($this->id);
    }
    
    function newInstance(BaseUserEntity $user,$data = []){
        return new WalletTransactionEntity($user, $data);
    }

    function isAlreadyApproved(){
        return $this->approval_status == 'approved';
    }

}