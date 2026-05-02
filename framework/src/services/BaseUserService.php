<?php 

namespace R2Packages\Framework\Services;

use R2Packages\Framework\Entities\BaseUserEntity;
use R2Packages\Framework\Repositories\BaseUserRepository;

class BaseUserService
{
    private BaseUserRepository $baseUserRepository;

    function __construct()
    {
        $this->baseUserRepository = new BaseUserRepository();
    }

    public function login($email, $password)
    {
        $user = $this->baseUserRepository->findByEmail($email);
        $user->validateLoginPassword($password);
        return $user;
    }

    public function register($name, $email, $password, $confirmPassword, $phone){
         $user = BaseUserEntity::getInstance([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'phone' => $phone
         ]);
         $user->validateRegistration();
         $user->validateConfirmPassword($confirmPassword);
         $this->baseUserRepository->saveCache(0, [
            "name" => $name,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "phone" => $phone,
            "otp" => $user->otp,
            "token" => $user->token,
            "role" => $user->role,
            "status" => $user->status,
            "created_at" => $user->created_at,
            "updated_at" => $user->updated_at,
         ]);
    }

    public function logout($id){
       $user = $this->baseUserRepository->find($id);
       $user->refreshToken();
       $this->baseUserRepository->save($id, [
        'token' => $user->token
       ]);
       return $user;
    }
}