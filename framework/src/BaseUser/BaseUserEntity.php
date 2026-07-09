<?php 

namespace R2Packages\Framework\BaseUser;

use R2Packages\Framework\Notification\NotificationRepository;

class BaseUserEntity
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

    public NotificationRepository $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository,$data = [])
    {
        $this->notificationRepository = $notificationRepository;
        setAttributes($this, $data);
        

        if (empty($this->created_at)){
            $this->created_at = date('Y-m-d H:i:s');
        }
        if (empty($this->updated_at)){
            $this->updated_at = date('Y-m-d H:i:s');
        }

        //role 
        if(empty($this->role)){
            $this->role = 'customer';
        }
        //status
        if(empty($this->status)){
            $this->status = self::STATUS_INACTIVE;
        }

        $this->notifications = $this->notificationRepository->fetch();

    }

    public function newInstance($data = []){
        return new self($this->notificationRepository, $data);
    }

    public function isEmpty(){
        return empty($this->id);
    }


    public function generateOtp(){
        $this->otp = rand(100000, 999999);
        return $this;
    }

    function refreshToken(){
        $this->token = $this->id . '_' . bin2hex(random_bytes(32));
        return $this;
    }

    function isAdmin(){
        return strpos($this->role, 'admin') !== false;
    }

    // is staff
    function isStaff(){
        return strpos($this->role, 'staff') !== false;
    }

}