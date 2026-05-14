<?php 

namespace R2Packages\Framework\Services;

use R2Packages\Framework\Entities\BaseUserEntity;
use R2Packages\Framework\Repositories\BaseUserRepository;
use R2Packages\Framework\Traits\WithEvents;

class BaseUserService
{
    use WithEvents;

    protected BaseUserRepository $baseUserRepository;

    const HOOK_REGISTER_SAVE_DATA = 'user.register.save.data';
    const HOOK_REGISTER_SAVE_SUCCESS = 'user.register.save.success';
    const HOOK_LOGOUT_REFRESH_TOKEN = 'user.logout.refresh.token';
    const HOOK_CONFIRM_OTP_SUCCESS = 'user.confirm.otp.success';

    private $data = [];

    function __construct($data = [])
    {
        $this->data = $data;
        /** @var BaseUserRepository $baseUserRepository */
        $this->baseUserRepository = new BaseUserRepository();
    }

    public function login($data)
    {
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $user = $this->baseUserRepository->findByEmail($email);
        $user->validateLoginPassword($password);
        return $user;
    }
    

    public function register($data){

        $user = new BaseUserEntity($data);
        $user->validateRegistration();
        $input = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => password_hash($user->password, PASSWORD_DEFAULT),
            'phone' => $user->phone,
            'otp' => $user->otp,
            'token' => $user->token,
            'role' => $user->role,
            'status' => $user->status,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,            
        ];
        self::dispatch(self::HOOK_REGISTER_SAVE_DATA, $user, $input);
        $user = $this->baseUserRepository->save(0, $input);        
        self::dispatch(self::HOOK_REGISTER_SAVE_SUCCESS, $user);
        return $user;
    }

    public function confirmOtp($id, $otp){
        $user = $this->baseUserRepository->find($id);
        $user->validateOtpAccountCreate($otp);
        $this->baseUserRepository->save($id, [
            'status' => $user->status
        ]);
        self::dispatch(self::HOOK_CONFIRM_OTP_SUCCESS, $user);
        return $user;
    }

    public function logout($id){
       $user = $this->baseUserRepository->find($id);
       $user->refreshToken();
       $this->baseUserRepository->save($id, [
        'token' => $user->token
       ]);
       self::dispatch(self::HOOK_LOGOUT_REFRESH_TOKEN, $user);
    }
}