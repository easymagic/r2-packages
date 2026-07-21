<?php

namespace R2Packages\Framework\v2\Domain;

use R2Packages\Framework\Request;
use R2Packages\Framework\v2\Interfaces\AuthNotificationInterface;
use R2Packages\Framework\v2\Interfaces\AuthServiceInterface;
use R2Packages\Framework\v2\Interfaces\NotificationInterface;
use R2Packages\Framework\v2\Interfaces\RepositoryInterface;
use R2Packages\Framework\v2\User\UserEntity;

abstract class AbstractAuthController
{

    private RepositoryInterface $repository;
    private AuthServiceInterface $auth;
    private AuthNotificationInterface $authNotification;
    private Request $request;
    private NotificationInterface $notification;


    public function __construct(
        Request $request,
        RepositoryInterface $repository,
        AuthServiceInterface $auth,
        AuthNotificationInterface $authNotification,
        NotificationInterface $notification
    ) {
        $this->repository = $repository;
        $this->auth = $auth;
        $this->authNotification = $authNotification;
        $this->notification = $notification;
        $this->request = $request;
    }

    public function login()
    {
        $data = $this->auth->validateLogin($this->request,$this->repository);
        $user = $this->auth->login($data, $this->repository);

        return jsonResponse([
            'data' => $user,
            "status" => "success",
            "message" => "Login successful!"
        ]);
    }

    public function register()
    {
        $data = $this->auth->validateRegister($this->request,$this->repository);
        $user = $this->auth->register($data, $this->repository, $this->authNotification, $this->notification);
        return jsonResponse([
            'data' => $user,
            "status" => "success",
            "message" => "Registration successful! OTP sent to your email."
        ]);
    }

    public function logout()
    {
        $user = $this->auth->getAuthUser();
        $response = $this->auth->logout($user,$this->repository);

        return jsonResponse([
            'data' => $response,
            "status" => "success",
            "message" => "Logout successful!"
        ]);
    }
    
    public function requestResetPassword()
    {
        $data = $this->auth->validateRequestResetPassword($this->request,$this->repository);
        $response = $this->auth->requestResetPassword($data,$this->repository,$this->authNotification, $this->notification);
        return jsonResponse([
            'data' => $response,
            "status" => "success",
            "message" => "Password reset request sent! OTP sent to your email."
        ]);
    }
    
    public function resetPassword()
    {
        $data = $this->auth->validateResetPassword($this->request,$this->repository);
        $response = $this->auth->resetPassword($data,$this->repository);
        return jsonResponse([
            'data' => $response,
            "status" => "success",
            "message" => "Password reset successful!"
        ]);
    }
    
    public function updateProfile()
    {
        $data = $this->auth->validateUpdateProfile($this->request,$this->repository);
        $user = $this->auth->getAuthUser();
        $response = $this->auth->updateProfile($data,$user,$this->repository);
        return jsonResponse([
            'data' => $response,
            "status" => "success",
            "message" => "Profile updated successfully!"
        ]);
    }

    public function changePassword()
    {
        $user = $this->auth->getAuthUser();
        $data = $this->auth->validateChangePassword($this->request,$this->repository);
        $response = $this->auth->changePassword($data,$user,$this->repository);
        return jsonResponse([
            'data' => $response,
            "status" => "success",
            "message" => "Password changed successfully!"
        ]);
    }

    public function createUser()
    {
        $data = $this->auth->validateCreateUser($this->request,$this->repository);
        $response = $this->auth->createUser($data,$this->repository);
        return jsonResponse([
            'data' => $response,
            "status" => "success",
            "message" => "User created successfully!"
        ]);
    }
    
    public function changeUserPassword()
    {

        $user = $this->auth->fetchById($this->request,$this->repository);
        $data = $this->auth->validateChangeUserPassword($this->request,$this->repository);
        $response = $this->auth->changeUserPassword($data,$user,$this->repository);
        return jsonResponse([
            'data' => $response,
            "status" => "success",
            "message" => "User password changed successfully!"
        ]);
    }

    public function verifyOtp()
    {
        $data = $this->auth->validateVerifyOtp($this->request,$this->repository);
        $user = $this->auth->verifyOtp($data, $this->repository);
        return jsonResponse([
            'data' => $user,
            "status" => "success",
            "message" => "OTP verified successfully!"
        ]);
    }

    public function resendOtp()
    {
        $data = $this->auth->validateResendOtp($this->request,$this->repository);
        $user = $this->auth->resendOtp($data,$this->repository,$this->authNotification, $this->notification);
        return jsonResponse([
            'data' => $user,
            "status" => "success",
            "message" => "OTP resent successfully!"
        ]);
    }

    public function updateUserProfile()
    {
        $user = $this->auth->fetchById($this->request,$this->repository);
        $data = $this->auth->validateUpdateUserProfile($this->request,$this->repository);
        $response = $this->auth->updateUserProfile($data,$user,$this->repository);
        return jsonResponse([
            'data' => $response,
            "status" => "success",
            "message" => "User profile updated successfully!"
        ]);
    }

    function me(){
        $user = $this->auth->getAuthUser();
        return jsonResponse([
            'data' => $user,
            "status" => "success",
            "message" => "User profile fetched successfully!"
        ]);
    }


}
