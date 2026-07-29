<?php 

namespace R2Packages\Framework\middlewares;

use R2Packages\Framework\middlewares\AuthMiddleware;
use R2Packages\Framework\BaseUser\BaseUserService;

class AdminMiddleware extends AuthMiddleware {

    function handle()
    {
        parent::handle();
        $user = $this->apiCredentialService->getAuthUser();
        if ( strpos(strtolower($user->role), 'admin') === false){
            jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
            $this->container->unset(AuthMiddleware::AUTH_USER);
            exit;
        }
        return true;
    }
}