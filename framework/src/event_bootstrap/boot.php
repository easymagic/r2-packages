<?php

use R2Packages\Framework\Entities\BaseUserEntity;
use R2Packages\Framework\Event;

Event::getInstance()->on('user.register.payload', function(&$inputPayload, $data){
    $inputPayload['name'] = $data['name'] ?? '';
    $inputPayload['email'] = $data['email'] ?? '';
    $inputPayload['password'] = $data['password'] ?? '';
    $inputPayload['phone'] = $data['phone'] ?? '';
});

Event::getInstance()->on('user.register.validate', function(BaseUserEntity $user){
    $user->validateRegistration();
    $user->validateConfirmPassword($user->confirmPassword);
});

Event::getInstance()->on('user.register.save', function(BaseUserEntity $user,&$userSaveData){
    $userSaveData['name'] = $user->name;
    $userSaveData['email'] = $user->email;
    $userSaveData['password'] = password_hash($user->password, PASSWORD_DEFAULT);
    $userSaveData['phone'] = $user->phone;
    $userSaveData['otp'] = $user->otp;
    $userSaveData['token'] = $user->token;
    $userSaveData['role'] = $user->role;
    $userSaveData['status'] = $user->status;
    $userSaveData['created_at'] = $user->created_at;
    $userSaveData['updated_at'] = $user->updated_at;
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
    
    
});