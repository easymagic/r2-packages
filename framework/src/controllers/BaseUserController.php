<?php 

namespace R2Packages\Framework\Controllers;

use R2Packages\Framework\Services\BaseUserService;
use R2Packages\Framework\Traits\Publishable;

class BaseUserController
{
    use Publishable;

    private BaseUserService $baseUserService;

    private $request = [];

    function __construct($request)
    {
        $this->request = $request;
        $this->baseUserService = new BaseUserService($this->request);
    }

    public function login()
    {
        $user = $this->baseUserService->login();
        return jsonResponse([
            'message' => 'Login successful',
            'data' => $user
        ]);
    }

    public function register()
    {
        $user = $this->baseUserService->register();
        return jsonResponse([
            'message' => 'Registration successful',
            'data' => $user
        ]);
    }
    
    public function verifyOtp()
    {
        $user = $this->baseUserService->verifyOtp();
        return jsonResponse([
            'message' => 'OTP verification successful',
            'data' => $user
        ]);
    }
    
    public function logout()
    {
        $this->baseUserService->logout();
        return jsonResponse([
            'message' => 'Logout successful',
        ]);
    }
    
    
    public function requestPasswordReset()
    {
        $user = $this->baseUserService->requestPasswordReset();
        return jsonResponse([
            'message' => 'Password reset request successful',
            'data' => $user
        ]);
    }
    
    
    public function resetPassword()
    {
        $user = $this->baseUserService->resetPassword();
        return jsonResponse([
            'message' => 'Password reset successful',
            'data' => $user
        ]);
    }


    // public static function filePath()
    // {
    //     return __FILE__;
    // }
}