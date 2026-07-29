<?php 

namespace R2Packages\Framework\middlewares;

use R2Packages\Framework\Services\ApiCredentialService;

class OptionalAuthMiddleware{


    private ApiCredentialService $apiCredentialService;

    function __construct(ApiCredentialService $apiCredentialService)
    {
        $this->apiCredentialService = $apiCredentialService;
    }

    function handle()
    {
        // cache auth user if present
        if (!$this->apiCredentialService->userTokenIsValid()) {
            // return true; do nothing since it is optional
        }
        return true;
    }
}