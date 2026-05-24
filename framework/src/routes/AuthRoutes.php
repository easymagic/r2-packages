<?php

namespace R2Packages\Framework\Routes;

use R2Packages\Framework\middlewares\AuthMiddleware;
use R2Packages\Framework\middlewares\GlobalApiMiddleware;
use R2Packages\Framework\Route;
use R2Packages\Framework\Controllers\BaseUserController;
use R2Packages\Framework\middlewares\AdminMiddleware;

class AuthRoutes
{
    private Route $route;

    public function __construct()
    {
        $this->route = Route::getInstance();
    }

    function registerRoutes()
    {

        $this->route->globalMiddleware([
            GlobalApiMiddleware::class
        ], function (Route $route) {

            $route->prefix("accounts", function (Route $route) {

                $route->post('/login', [BaseUserController::class, 'login']);

                $route->post('/register', [BaseUserController::class, 'register']);

                $route->post('/otp', [BaseUserController::class, 'verifyOtp']);
                $route->post('/request-password-reset', [BaseUserController::class, 'requestPasswordReset']);
                $route->post('/reset-password', [BaseUserController::class, 'resetPassword']);

                $route->globalMiddleware([
                    AuthMiddleware::class
                ], function (Route $route) {
                    $route->delete('/login', [BaseUserController::class, 'logout']);
                    $route->post('/me', [BaseUserController::class, 'updateProfile']);
                    $route->get('/me', [BaseUserController::class, 'getMyProfile']);
                    $route->post('/me/password', [BaseUserController::class, 'changeMyPassword']);
                });

                $route->globalMiddleware([
                    AdminMiddleware::class
                ], function (Route $route) {
                    $route->post('/user', [BaseUserController::class, 'create']);
                    $route->post('/user/{id}', [BaseUserController::class, 'updateUserProfile']);
                    $route->post('/user/{id}/password', [BaseUserController::class, 'changeUserPassword']);
                    $route->get('/user/{id}', [BaseUserController::class, 'getUserProfile']);
                });

            });
        });
    }
}
