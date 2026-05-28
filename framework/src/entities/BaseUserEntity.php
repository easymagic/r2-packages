<?php 

namespace R2Packages\Framework\Entities;

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

    // private $onRegistrationValidation = null;

    private static $instance = null;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * Get an instance of the BaseUserEntity
     * @param array $data
     * @return BaseUserEntity
     */
    public static function getInstance($data = []){
        if(self::$instance === null){
            self::$instance = new self($data);
        }
        return self::$instance;
    }

    public function __construct($data = [])
    {
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

    }

    public function newInstance($data = []){
        return new self($data);
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

}