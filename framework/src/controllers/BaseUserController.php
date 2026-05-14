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
        $this->baseUserService = new BaseUserService();
    }

    public function login()
    {
        $this->baseUserService->login($this->request);
    }
}