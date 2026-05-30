<?php 

namespace R2Packages\Framework\middlewares;

use R2Packages\Framework\middlewares\AuthMiddleware;
use R2Packages\Framework\Services\BaseUserService;

class AdminMiddleware extends AuthMiddleware {

    function handle()
    {
        parent::handle();
        $user = $this->authUser;
        if ($user->role !== 'admin'){
            jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
            $this->container->unset(AuthMiddleware::AUTH_USER);
            exit;
        }
        return true;
    }
}