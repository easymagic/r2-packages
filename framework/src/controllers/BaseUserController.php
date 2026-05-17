<?php 

namespace R2Packages\Framework\Controllers;

use R2Packages\Framework\Response;
use R2Packages\Framework\Services\BaseUserService;
use R2Packages\Framework\Traits\Publishable;

class BaseUserController
{
    use Publishable;

    private BaseUserService $baseUserService;

    private $request = [];

    function __construct($request, BaseUserService $baseUserService)
    {
        $this->request = $request;
        $this->baseUserService = $baseUserService;
    }

    public function login()
    {
        $user = $this->baseUserService->login();
        jsonResponse([
            'message' => 'Login successful',
            'data' => $user
        ]);
    }

    public function register()
    {
        $user = $this->baseUserService->register();
        jsonResponse([
            'message' => 'Registration successful',
            'data' => $user
        ]);
    }
    
    public function verifyOtp()
    {
        $user = $this->baseUserService->verifyOtp();
        jsonResponse([
            'message' => 'OTP verification successful',
            'data' => $user
        ]);
    }
    
    public function logout()
    {
        $user = $this->baseUserService->logout();
        jsonResponse([
            'message' => 'Logout successful',
            'data' => $user
        ]);
    }
    
    
    public function requestPasswordReset()
    {
        $user = $this->baseUserService->requestPasswordReset();
        jsonResponse([
            'message' => 'Password reset request successful',
            'data' => $user
        ]);
    }
    
    
    public function resetPassword()
    {
        $user = $this->baseUserService->resetPassword();
        jsonResponse([
            'message' => 'Password reset successful',
            'data' => $user
        ]);
    }

}