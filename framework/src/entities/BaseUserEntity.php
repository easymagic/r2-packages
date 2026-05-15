<?php 

namespace R2Packages\Framework\Entities;

use R2Packages\Framework\Traits\WithEvents;
use R2Packages\Framework\Traits\WithSetterGetter;

class BaseUserEntity
{

    use WithEvents;
    use WithSetterGetter;

    // public $fillable = [];
    // public $data = [];


    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    // private $onRegistrationValidation = null;

    private static $instance = null;

    const HOOK_INITIALIZE_DATA = 'user.initialize.data';

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
        $this->init($data);

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

        self::dispatch(self::HOOK_INITIALIZE_DATA, $this);
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