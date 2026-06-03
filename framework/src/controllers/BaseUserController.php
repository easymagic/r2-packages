<?php 

namespace R2Packages\Framework\Controllers;

use R2Packages\Framework\Services\BaseUserService;
use R2Packages\Framework\Traits\Publishable;

class BaseUserController
{
    use Publishable;

    private BaseUserService $baseUserService;

    function __construct(BaseUserService $baseUserService)
    {
        $this->baseUserService = $baseUserService;
    }

    public function login()
    {
        $user = $this->baseUserService->login();
        jsonResponse([
            'message' => 'Login successful',
            'data' => $user,
            "success" => true
        ]);
    }

    public function register()
    {
        $user = $this->baseUserService->register();
        jsonResponse([
            'message' => 'Registration successful , please check your email for OTP verification sent to you.',
            'data' => $user,
            "success" => true
        ]);
    }

    public function resendOtp()
    {
        $user = $this->baseUserService->resendOtp();
        jsonResponse([
            'message' => 'OTP resent successfully',
            'data' => [
                "id" => $user->id,
                "email" => $user->email
            ],
            "success" => true
        ]);
    }
    
    public function verifyOtp()
    {
        $user = $this->baseUserService->verifyOtp();
        jsonResponse([
            'message' => 'OTP verification successful',
            'data' => $user,
            "success" => true
        ]);
    }
    
    public function logout()
    {
        $user = $this->baseUserService->logout();
        jsonResponse([
            'message' => 'Logout successful',
            'data' => $user,
            "success" => true
        ]);
    }
    
    
    public function requestPasswordReset()
    {
        $user = $this->baseUserService->requestPasswordReset();
        jsonResponse([
            'message' => 'Password reset request successful, please check your email for OTP verification sent to you.',
            'data' => $user,
            "success" => true
        ]);
    }
    
    
    public function resetPassword()
    {
        $user = $this->baseUserService->resetPassword();
        jsonResponse([
            'message' => 'Password reset successful',
            'data' => $user,
            "success" => true
        ]);
    }

    public function updateProfile()
    {
        $user = $this->baseUserService->updateProfile();
        jsonResponse([
            'message' => 'Profile updated successfully',
            'data' => $user,
            "success" => true
        ]);
    }

    public function getProfile()
    {
        $user = $this->baseUserService->getProfile();
        jsonResponse([
            'message' => 'Profile fetched successfully',
            'data' => $user,
            "success" => true
        ]);
    }

    // change my password
    public function changeMyPassword()
    {
        $user = $this->baseUserService->changeMyPassword();
        jsonResponse([
            'message' => 'Password changed successfully',
            'data' => $user,
            "success" => true
        ]);
    }

    public function getMyProfile()
    {
        $user = $this->baseUserService->getMyProfile();
        jsonResponse([
            'message' => 'My profile fetched successfully',
            'data' => $user,
            "success" => true
        ]);
    }

    public function create()
    {
        $user = $this->baseUserService->create();
        jsonResponse([
            'message' => 'User created successfully',
            'data' => $user,
            "success" => true
        ]);
    }

    public function updateUserProfile()
    {
        $user = $this->baseUserService->updateUserProfile();
        jsonResponse([
            'message' => 'User profile updated successfully',
            'data' => $user,
            "success" => true
        ]);
    }

    public function changeUserPassword()
    {
        $user = $this->baseUserService->changeUserPassword();
        jsonResponse([
            'message' => 'User password changed successfully',
            'data' => $user,
            "success" => true
        ]);
    }

    
    public function fetch()
    {
        $users = $this->baseUserService->fetch();
        jsonResponse([
            'message' => 'Users fetched successfully',
            'data' => $users,
            "success" => true
        ]);
    }

    public function fetchAll()
    {
        $users = $this->baseUserService->fetchAll();
        jsonResponse([
            'message' => 'Users fetched successfully',
            'data' => $users,
            "success" => true
        ]);
    }

    function getUserProfile()
    {
        $user = $this->baseUserService->getProfile();
        jsonResponse([
            'message' => 'User profile fetched successfully',
            'data' => $user,
            "success" => true
        ]);
    }


}