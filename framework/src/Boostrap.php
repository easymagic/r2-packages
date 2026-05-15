<?php 

namespace R2Packages\Framework;

use Closure;
use R2Packages\Framework\Controllers\BaseUserController;
use R2Packages\Framework\Entities\BaseUserEntity;
use R2Packages\Framework\Repositories\BaseUserRepository;
use R2Packages\Framework\Services\BaseUserService;

class Boostrap
{
    public static function run($callback = null){


        Route::getInstance()->prefix('api',function(Route $route){
            $route->get('login',[BaseUserController::class,'login']);
            $route->post('register',[BaseUserController::class,'register']);
            $route->post('verify-otp',[BaseUserController::class,'verifyOtp']);
            $route->post('logout',[BaseUserController::class,'logout']);
            $route->post('request-password-reset',[BaseUserController::class,'requestPasswordReset']);
            $route->post('reset-password',[BaseUserController::class,'resetPassword']);
        });



        BaseUserService::on(BaseUserService::HOOK_REGISTER_SAVE_SUCCESS, function(BaseUserService $baseUserService){

            ob_start();
            include SRC_DIR_INTERNAL . '/mail_templates/registration.mail.php';
            $body = ob_get_clean();
            $baseUserService->setMailBody($body);

        });


        BaseUserService::on(BaseUserService::HOOK_AFTER_REQUEST_PASSWORD_RESET, function(BaseUserService $baseUserService){
            ob_start();
            include SRC_DIR_INTERNAL . '/mail_templates/password_reset_request.mail.php';
            $body = ob_get_clean();
            $baseUserService->setMailBody($body);
        });

        if (!empty($callback) && $callback instanceof Closure){
            $callback();
        }

    }
}