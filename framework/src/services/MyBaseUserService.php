<?php 

namespace R2Packages\Framework\Services;

class MyBaseUserService extends BaseUserService
{
    public function register($name, $email, $password, $confirmPassword, $phone,$ssn=''){
       $user = parent::register($name, $email, $password, $confirmPassword, $phone);

       $this->baseUserRepository->saveCache($user->id, [
        'ssn' => $ssn
       ]);

    }
}