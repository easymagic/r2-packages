<?php

namespace R2Packages\Framework\middlewares;

use R2Packages\Framework\Container;
use R2Packages\Framework\BaseUser\BaseUserEntity;
use R2Packages\Framework\BaseUser\BaseUserRepository;
use R2Packages\Framework\Request;
use R2Packages\Framework\Services\ApiCredentialService;
use R2Packages\Framework\BaseUser\BaseUserService;

class AuthMiddleware
{

    protected Container $container;

    const AUTH_USER = "auth-user";
    protected BaseUserEntity $authUser;

    protected ApiCredentialService $apiCredentialService;

    function __construct(
        ApiCredentialService $apiCredentialService,
        Container $container
    ) {
        $this->apiCredentialService = $apiCredentialService;
        $this->container = $container;
    }

    function handle()
    {
        if (!$this->apiCredentialService->userTokenIsValid()) {
            jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
            exit;
        }
        $user = $this->apiCredentialService->getAuthUser();
        
        $this->authUser = $user;
        $this->container->set(self::AUTH_USER, $user);
        return true;
    }
}
