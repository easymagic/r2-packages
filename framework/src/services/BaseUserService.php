<?php 

namespace R2Packages\Framework\Services;

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

    public function logout($id){
       $user = $this->baseUserRepository->find($id);
       $user->refreshToken();
       $this->baseUserRepository->save($id, [
        'token' => $user->token
       ]);
       return $user;
    }
}