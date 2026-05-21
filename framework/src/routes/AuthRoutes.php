<?php 
namespace R2Packages\Framework\Routes;

use R2Packages\Framework\Route;
use R2Packages\Framework\Controllers\BaseUserController;

class AuthRoutes
{
    private Route $route;

    public function __construct()
    {
        $this->route = Route::getInstance();
    }

    function registerRoutes(){
        $this->route->post('/login', [BaseUserController::class, 'login']);
        $this->route->post('/register', [BaseUserController::class, 'register']);
        $this->route->post('/create', [BaseUserController::class, 'create']);
        $this->route->post('/otp', [BaseUserController::class, 'verifyOtp']);
        $this->route->post('/logout', [BaseUserController::class, 'logout']);
        $this->route->post('/request-password-reset', [BaseUserController::class, 'requestPasswordReset']);
        $this->route->post('/reset-password', [BaseUserController::class, 'resetPassword']);
        $this->route->post('/me', [BaseUserController::class, 'updateProfile']);
        $this->route->get('/me', [BaseUserController::class, 'getProfile']);
    }
}