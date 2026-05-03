<?php 

namespace R2Packages\Framework\Entities;

use Exception;

class BaseUserEntity
{
    public $id;
    public $name;
    public $email;
    public $phone;
    public $role;
    public $status = 'inactive';
    public $created_at = null;
    public $updated_at = null;
    public $otp;
    public $token;
    public $password;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    // private $onRegistrationValidation = null;

    private static $instance = null;

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

    }

    public function isEmpty(){
        return empty($this->id);
    }

    // public function setOnRegistrationValidation(callable $callback){
    //     $this->onRegistrationValidation = $callback;
    //     return $this;
    // }

    public function validateLoginPassword($password){
        $check = password_verify($password, $this->password);
        if(!$check){
            throw new Exception("Invalid login!");
        }
        if($this->status !== self::STATUS_ACTIVE){
            throw new Exception("Account is not active! , please activate your account from the OTP sent to your email or phone number.");
        }
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