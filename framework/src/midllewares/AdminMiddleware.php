<?php 

namespace R2Packages\Framework\middlewares;

use R2Packages\Framework\middlewares\AuthMiddleware;
use R2Packages\Framework\Services\BaseUserService;

class AdminMiddleware extends AuthMiddleware {

    function handle(){
        parent::handle();
        $user = BaseUserService::getAuthenticatedUser();
        if ($user->role !== 'admin'){
            jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
            exit;
        }
        return true;
    }
}