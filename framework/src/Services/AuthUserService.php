<?php 

namespace R2Packages\Framework\Services;

class AuthUserService
{
    protected ApiCredentialService $apiCredentialService;

    public function __construct(ApiCredentialService $apiCredentialService)
    {
        $this->apiCredentialService = $apiCredentialService;
    }

    public function getAuthUser()
    {
        return $this->apiCredentialService->getAuthUser();
    }
}