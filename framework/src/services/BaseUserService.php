<?php 

namespace R2Packages\Framework\Services;

use R2Packages\Framework\Container;
use R2Packages\Framework\Entities\BaseUserEntity;
use R2Packages\Framework\Event;
use R2Packages\Framework\Repositories\BaseUserRepository;

class BaseUserService
{
    private BaseUserRepository $baseUserRepository;

    function __construct()
    {
        /** @var BaseUserRepository $baseUserRepository */
        $this->baseUserRepository = Container::getInstance()->get(BaseUserRepository::class);
    }

    public function login($email, $password)
    {
        $user = $this->baseUserRepository->findByEmail($email);
        $user->validateLoginPassword($password);
        return $user;
    }

    public function saveForRegistration($name, $email, $password, $confirmPassword, $phone){
        /** @var BaseUserEntity $user */
        $user = Container::getInstance()->get(BaseUserEntity::class, [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'phone' => $phone
         ]);

    }

    function initUserRegistration($data){
        /** @var BaseUserEntity $user */
        $data['name'] = $data['name'] ?? '';
        $data['email'] = $data['email'] ?? '';
        $data['password'] = $data['password'] ?? '';
        $data['phone'] = $data['phone'] ?? '';
        $user = Container::getInstance()->get(BaseUserEntity::class, $data);
        return $user;
    }

    public function register($data){
        
        $inputPayload = [];
        Event::getInstance()->dispatch('user.register.payload', $inputPayload, $data);
        /** @var BaseUserEntity $user */
        $user = Container::getInstance()->get(BaseUserEntity::class, $inputPayload);
        Event::getInstance()->dispatch('user.register.validate', $user);
        $userSaveData = [];
        Event::getInstance()->dispatch('user.register.save', $user,$userSaveData);
        $userSaveData["created_at"] = date('Y-m-d H:i:s');
        $userSaveData["updated_at"] = date('Y-m-d H:i:s'); 
        $this->baseUserRepository->save(0,$userSaveData);
        //  $user->validateConfirmPassword($confirmPassword);
        //  $this->baseUserRepository->saveCache(0, [
        //     "name" => $name,
        //     "email" => $email,
        //     "password" => password_hash($password, PASSWORD_DEFAULT),
        //     "phone" => $phone,
        //     "otp" => $user->otp,
        //     "token" => $user->token,
        //     "role" => $user->role,
        //     "status" => $user->status,
        //     "created_at" => $user->created_at,
        //     "updated_at" => $user->updated_at,
        //  ]);
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