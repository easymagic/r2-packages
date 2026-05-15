<?php 

namespace R2Packages\Framework\Controllers;

use R2Packages\Framework\Services\BaseUserService;

class BaseUserController
{
    private BaseUserService $baseUserService;

    private $request = [];

    function __construct($request)
    {
        $this->request = $request;
        $this->baseUserService = new BaseUserService($this->request);
    }

    public function login()
    {
        $user = $this->baseUserService->login();
        return jsonResponse([
            'message' => 'Login successful',
            'data' => $user
        ]);
    }
}