<?php 

namespace R2Packages\Framework\middlewares;

use R2Packages\Framework\Services\BaseUserService;

class AuthMiddleware {

    private $request = [];
    private BaseUserService $baseUserService;


    function __construct(&$request, BaseUserService $baseUserService){
        $this->request =& $request;
        $this->baseUserService = $baseUserService;
    }

    function handle(){
        $user_id = $this->request['x-user-id'] ?? null;
        $token = $this->request['x-user-token'] ?? null;
        if (empty($token)){
            jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
            exit;
        }
        $countCheck = count(explode('_', $token));
        if ($countCheck !== 2){
            jsonResponse(['success' => false, 'message' => 'Invalid token'], 401);
            exit;
        }
        $user_id = explode('_', $token)[0];
        // $token = explode('_', $token)[1];
        $user = $this->baseUserService->find($user_id);
        // dd($user);
        $this->request['user'] = $user;
        if ($user->isEmpty()){
            jsonResponse(['success' => false, 'message' => 'User not found'], 404);
            exit;
        }
        if ($user->token !== $token){
            jsonResponse(['success' => false, 'message' => 'Token is invalid'], 401);
            exit;
        }
        BaseUserService::logUser($user);
        return true;
    }
}