<?php

namespace R2Packages\Framework\v2\Domain;

use R2Packages\Framework\Request;
use R2Packages\Framework\v2\Interfaces\AuthNotificationInterface;
use R2Packages\Framework\v2\Interfaces\AuthServiceInterface;
use R2Packages\Framework\v2\Interfaces\NotificationInterface;
use R2Packages\Framework\v2\Interfaces\RepositoryInterface;
use R2Packages\Framework\v2\User\Entity\UserEntity;

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

    // public function login(RepositoryInterface $repository);
    // public function register(RepositoryInterface $repository);
    // public function logout(RepositoryInterface $repository);
    // public function requestResetPassword(RepositoryInterface $repository);
    // public function forgotPassword(RepositoryInterface $repository);
    // public function resetPassword(RepositoryInterface $repository);
    // public function updateProfile(RepositoryInterface $repository);
    // public function changePassword(RepositoryInterface $repository);
    // public function createUser(RepositoryInterface $repository);
    // public function changeUserPassword(RepositoryInterface $repository);


    public function login()
    {
        
        $response = $this->auth->login($this->request,$this->repository);
        return jsonResponse([
            'data' => $response,
            "status" => "success",
            "message" => "Login successful!"
        ]);
    }

    public function register()
    {
        $this->auth->register($this->request,$this->repository);
        /** @var UserEntity $user */
        $user = $this->repository->fetchBy("email",$this->request->get('email'));
        $this->authNotification->sendRegistrationOtp($user,$this->notification);

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
        $email = $this->request->get('email');
        /** @var UserEntity $user */
        $user = $this->repository->fetchBy('email',$email);
        $response = $this->auth->requestResetPassword($this->request,$this->repository);
        $this->authNotification->sendPasswordReset($user,$this->notification);
        return jsonResponse([
            'data' => $response,
            "status" => "success",
            "message" => "Password reset request sent! OTP sent to your email."
        ]);
    }
    
    public function resetPassword()
    {
        $response = $this->auth->resetPassword($this->request,$this->repository);
        return jsonResponse([
            'data' => $response,
            "status" => "success",
            "message" => "Password reset successful!"
        ]);
    }
    
    public function updateProfile()
    {
        $response = $this->auth->updateProfile($this->request,$this->repository);
        return jsonResponse([
            'data' => $response,
            "status" => "success",
            "message" => "Profile updated successfully!"
        ]);
    }

    public function changePassword()
    {
        $response = $this->auth->changePassword($this->request,$this->repository);
        return jsonResponse([
            'data' => $response,
            "status" => "success",
            "message" => "Password changed successfully!"
        ]);
    }

    public function createUser()
    {
        $response = $this->auth->createUser($this->request,$this->repository);
        return jsonResponse([
            'data' => $response,
            "status" => "success",
            "message" => "User created successfully!"
        ]);
    }
    
    public function changeUserPassword()
    {

        $user = $this->auth->fetchById($this->request,$this->repository);
        $response = $this->auth->changeUserPassword($user,$this->request,$this->repository);
        return jsonResponse([
            'data' => $response,
            "status" => "success",
            "message" => "User password changed successfully!"
        ]);
    }

    public function verifyOtp()
    {
        $this->auth->verifyOtp($this->request,$this->repository);
        $user = $this->repository->fetchBy("email",$this->request->get('email'));
        $this->auth->refreshOtp($user,$this->repository);
        return jsonResponse([
            'data' => $user,
            "status" => "success",
            "message" => "OTP verified successfully!"
        ]);
    }

    public function updateUserProfile()
    {
        $user = $this->auth->fetchById($this->request,$this->repository);
        $response = $this->auth->updateUserProfile($user,$this->request,$this->repository);
        return jsonResponse([
            'data' => $response,
            "status" => "success",
            "message" => "User profile updated successfully!"
        ]);
    }


}
