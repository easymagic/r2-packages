<?php

namespace R2Packages\Framework\Routes;

use R2Packages\Framework\BaseUser\BaseUserController;
use R2Packages\Framework\Feature\FeatureController;
use R2Packages\Framework\middlewares\AdminMiddleware;
use R2Packages\Framework\middlewares\AuthMiddleware;
use R2Packages\Framework\middlewares\GlobalApiMiddleware;
use R2Packages\Framework\middlewares\WalletPaymentsFeedbackMiddleware;
use R2Packages\Framework\Migrations\MigrationController;
use R2Packages\Framework\Route;
use R2Packages\Framework\Settings\SettingsController;
use R2Packages\Framework\v2\User\AuthController;
use R2Packages\Framework\WalletTransaction\PendingTopupRequestController;
use R2Packages\Framework\WalletTransaction\WalletController;

class AuthRoutes
{
    private Route $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    public function registerRoutes()
    {
        $this->route->globalMiddleware([
            GlobalApiMiddleware::class
        ], function (Route $route) {

            // settings
            $route->get('/settings', [SettingsController::class, 'index']);
            $route->post('/settings', [SettingsController::class, 'save']);

            $route->prefix("accounts", function (Route $route) {

                // base user - public
                $route->post('/login', [AuthController::class, 'login']);
                $route->post('/register', [AuthController::class, 'register']);
                $route->post('/otp/{user_id}', [AuthController::class, 'verifyOtp']);
                $route->post('/resend-otp', [AuthController::class, 'resendOtp']);
                $route->post('/request-password-reset', [AuthController::class, 'requestResetPassword']);
                $route->post('/reset-password', [AuthController::class, 'resetPassword']);

                $route->globalMiddleware([
                    AuthMiddleware::class,
                    WalletPaymentsFeedbackMiddleware::class
                ], function (Route $route) {

                    // base user - authenticated
                    $route->delete('/login', [AuthController::class, 'logout']);
                    $route->post('/me', [AuthController::class, 'updateProfile']);
                    $route->get('/me', [AuthController::class, 'me']);
                    $route->post('/me/password', [AuthController::class, 'changePassword']);

                    // wallet transactions
                    $route->get("wallet", [WalletController::class, 'index']);
                    $route->get("wallet/{wallet_transaction_id}", [WalletController::class, 'show']);
                    $route->post("wallet", [WalletController::class, 'store']);
                });

                $route->globalMiddleware([
                    AdminMiddleware::class
                ], function (Route $route) {

                    // core migration
                    $route->post('/migrate', [MigrationController::class, 'migrate']);

                    // base user - admin
                    $route->get('/user', [BaseUserController::class, 'fetch']);
                    $route->post('/user', [BaseUserController::class, 'create']);
                    $route->post('/user/{user_id}', [BaseUserController::class, 'updateUserProfile']);
                    $route->post('/user/{user_id}/password', [BaseUserController::class, 'changeUserPassword']);
                    $route->get('/user/{user_id}', [BaseUserController::class, 'getUserProfile']);

                    // wallet transactions - admin
                    $route->get("pending-topup-requests", [PendingTopupRequestController::class, 'index']);
                    $route->get("pending-topup-requests/{wallet_transaction_id}", [PendingTopupRequestController::class, 'show']);
                    $route->post("pending-topup-requests/{wallet_transaction_id}", [PendingTopupRequestController::class, 'update']);
                    $route->delete("pending-topup-requests/{wallet_transaction_id}", [PendingTopupRequestController::class, 'destroy']);

                    // features
                    $route->get('/features', [FeatureController::class, 'index']);
                    $route->post('/features/{feature_id}/enable', [FeatureController::class, 'enableFeature']);
                    $route->post('/features/{feature_id}/disable', [FeatureController::class, 'disableFeature']);
                    $route->get('/features/{feature_id}/settings', [FeatureController::class, 'getFeatureSettings']);
                    $route->post('/features/{feature_id}/settings/{feature_setting_id}', [FeatureController::class, 'updateFeatureSetting']);
                });
            });
        });
    }
}
