<?php 

namespace R2Packages\Framework\Entities;

use R2Packages\Framework\Repositories\BaseUserRepository;

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
    public BaseUserRepository $baseUserRepository;

    function __construct(BaseUserRepository $baseUserRepository,$data = []){
        $this->baseUserRepository = $baseUserRepository;
        setAttributes($this, $data);
        $this->user = $this->baseUserRepository->find($this->user_id);
    }

    function isEmpty(){
        return empty($this->id);
    }
    
    function newInstance($data = []){
        return new WalletTransactionEntity($this->baseUserRepository, $data);
    }

    // function creditUserWallet(){
    //     $this->user->increaseWalletBalance($this->amount);
    // }

    // function approve(UserEntity $user){
    //     $this->creditUserWallet();
    //     $this->action_by = $user->id;
    //     $this->action_at = date('Y-m-d H:i:s');
    //     $this->status = 'successful';
    //     $this->approval_status = 'approved';
    //     return $this;
    // }

    // function reject(UserEntity $user,$reason){
    //     $this->reason = $reason;
    //     $this->action_by = $user->id;
    //     $this->action_at = date('Y-m-d H:i:s');
    //     $this->status = 'failed';
    //     $this->approval_status = 'rejected';
    //     return $this;
    // }

    // function approveFromPaystack(){
    //     $this->creditUserWallet();
    //     $this->action_at = date('Y-m-d H:i:s');
    //     $this->status = 'successful';
    //     $this->approval_status = 'approved';
    //     return $this;
    // }

    function isAlreadyApproved(){
        return $this->approval_status == 'approved';
    }

}