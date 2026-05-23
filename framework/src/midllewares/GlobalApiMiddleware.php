<?php 

namespace R2Packages\Framework\middlewares;



class GlobalApiMiddleware {


    private $systemToken = null;
    private $request = [];
    function __construct($token,$request){
        $this->systemToken = $token;
        $this->request = $request;
    }


    function handle(){
        $token = $this->request['x-token'] ?? null;
        if ($token !== $this->systemToken) {
            jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
            exit;
        }
        return true;
    }
}