<?php 

namespace R2Packages\Framework\middlewares;

use R2Packages\Framework\Request;

class GlobalApiMiddleware {


    private $systemToken = '';
    private Request $request;


    /**
     * @param string $systemToken
     * @param Request $request
     */
    function __construct($systemToken,Request $request){
        $this->systemToken = $systemToken;
        $this->request = $request;
    }


    function handle(){
        $token = $this->request->data['x-token'] ?? null;
        if ($token !== $this->systemToken) {
            jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
            exit;
        }
        return true;
    }
}