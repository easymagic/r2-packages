<?php 

namespace R2Packages\Framework\middlewares;

use R2Packages\Framework\Request;
use R2Packages\Framework\Services\ApiCredentialService;

class GlobalApiMiddleware {


    // private $systemToken = '';
    private ApiCredentialService $apiCredentialService;


    /**
     * @param ApiCredentialService $apiCredentialService
     */
    function __construct(ApiCredentialService $apiCredentialService){
        $this->apiCredentialService = $apiCredentialService;
    }


    function handle(){
        if (!$this->apiCredentialService->globalTokenIsValid()) {
            jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
            exit;
        }
        return true;
    }
}