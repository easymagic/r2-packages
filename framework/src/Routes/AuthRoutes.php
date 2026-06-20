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

    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    function registerRoutes()
    {

        $this->route->globalMiddleware([
            GlobalApiMiddleware::class
        ], function (Route $route) {

            $route->prefix("accounts", function (Route $route) {

                $route->post('/login', [BaseUserController::class, 'login']);

                $route->post('/register', [BaseUserController::class, 'register']);

                $route->post('/otp/{user_id}', [BaseUserController::class, 'verifyOtp']); // user_id is required
                $route->post('/resend-otp', [BaseUserController::class, 'resendOtp']); // user_id is required
                $route->post('/request-password-reset', [BaseUserController::class, 'requestPasswordReset']);
                $route->post('/reset-password', [BaseUserController::class, 'resetPassword']); // user_id is required

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
                    $route->get('/user', [BaseUserController::class, 'fetch']);
                    $route->post('/user', [BaseUserController::class, 'create']);
                    $route->post('/user/{user_id}', [BaseUserController::class, 'updateUserProfile']);
                    $route->post('/user/{user_id}/password', [BaseUserController::class, 'changeUserPassword']);
                    $route->get('/user/{user_id}', [BaseUserController::class, 'getUserProfile']);
                });

            });
        });
    }
}
