<?php 

namespace R2Packages\Framework\Entities;

use Exception;
use R2Packages\Framework\Event;
use R2Packages\Framework\Traits\WithEvents;

class BaseUserEntity
{

    use WithEvents;

    // public $fillable = [];
    public $data = [];


    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    // private $onRegistrationValidation = null;

    private static $instance = null;

    const HOOK_VALIDATE_REGISTER = 'user.register.validate';
    const HOOK_VALIDATE_LOGIN = 'user.login.validate';
    const HOOK_VALIDATE_OTP = 'user.otp.validate';
    const HOOK_INITIALIZE_DATA = 'user.initialize.data';

    function __get($name){
        return $this->data[$name] ?? null;
    }

    function __set($name, $value){
        $this->data[$name] = $value;
    }

    function __isset($name){
        return isset($this->data[$name]);
    }

    function __unset($name){
        unset($this->data[$name]);
    }

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

        self::dispatch(self::HOOK_INITIALIZE_DATA, $this,$data);
    }

    public function isEmpty(){
        return empty($this->id);
    }

    public function validateLoginPassword($password){
        $check = password_verify($password, $this->password);
        if(!$check){
            throw new Exception("Invalid login!");
        }
        if($this->status !== self::STATUS_ACTIVE){
            throw new Exception("Account is not active! , please activate your account from the OTP sent to your email or phone number.");
        }
        self::dispatch(self::HOOK_VALIDATE_LOGIN, $this);
        return $this;
    }

    public function validateConfirmPassword($confirmPassword){
        if($this->password !== $confirmPassword){
            throw new Exception("Password and confirm password do not match!");
        }
        return $this;
    }

    public function validateRegistration(){
        //name
        if(empty($this->name)){
            throw new Exception("Name is required!");
        }

        //email
        if(empty($this->email)){
            throw new Exception("Email is required!");
        }

        //phone
        if(empty($this->phone)){
            throw new Exception("Phone is required!");
        }

        //password
        if(empty($this->password)){
            throw new Exception("Password is required!");
        }

        self::dispatch(self::HOOK_VALIDATE_REGISTER, $this);

        //role
        // if(empty($this->role)){
        //     throw new Exception("Role is required!");
        // }

        //status
        // if(empty($this->status)){
        //     throw new Exception("Status is required!");
        // }

        // $this->validateCustomAccountCreation();
        $this->generateOtp();
        $this->refreshToken();
    }

    public function validateOtpAccountCreate($otp){
        if($this->otp !== $otp){
            throw new Exception("Invalid OTP!");
        }
        $this->status = self::STATUS_ACTIVE;
        self::dispatch(self::HOOK_VALIDATE_OTP, $this);
        return $this;
    }

    // public function validateCustomAccountCreation(){
    //     if ($this->onRegistrationValidation){
    //         $onRegistrationValidation = $this->onRegistrationValidation;
    //         $onRegistrationValidation($this);
    //     }
    //     return $this;
    // }

    public function generateOtp(){
        $this->otp = rand(100000, 999999);
        return $this;
    }

    function refreshToken(){
        $this->token = $this->id . '_' . bin2hex(random_bytes(32));
        return $this;
    }

}