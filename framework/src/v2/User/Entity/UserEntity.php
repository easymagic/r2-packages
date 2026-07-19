<?php 

namespace R2Packages\Framework\v2\User\Entity;

class UserEntity
{

    public $id = null;
    public $name = null;
    public $email = null;
    public $password = null;
    public $phone = null;
    public $role = null;
    public $status = null;
    public $created_at = null;
    public $updated_at = null;
    public $otp = null;
    public $token = null;
    public $wallet_balance = 0.00;

    // private $onRegistrationValidation = null;

    private static $instance = null;

    public $notifications = [];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public function __construct($notifications = [],$data = [])
    {
        setAttributes($this, $data);
        $this->notifications = $notifications;
    }

    public function newInstance($notifications = [],$data = []){
        return new self($notifications, $data);
    }

    public function isEmpty(){
        return empty($this->id);
    }

    // public function generateOtp(){
    //     $this->otp = rand(100000, 999999);
    //     return $this;
    // }

    // function refreshToken(){
    //     $this->token = $this->id . '_' . bin2hex(random_bytes(32));
    //     return $this;
    // }

    function isAdmin(){
        return strpos($this->role, 'admin') !== false;
    }

    // is staff
    function isStaff(){
        return strpos($this->role, 'staff') !== false;
    }

}