<?php 

namespace R2Packages\Framework\BaseUser;

use Exception;
use R2Packages\Framework\BaseUser\BaseUserRepository;
use R2Packages\Framework\Request;

class UserIdService
{
    private Request $request;
    private BaseUserRepository $baseUserRepository;

    function __construct(Request $request, BaseUserRepository $baseUserRepository)
    {
        $this->request = $request;
        $this->baseUserRepository = $baseUserRepository;
    }

    public function getUser()
    {
        if ($this->request->isEmpty('user_id')) {
            throw new Exception("User ID is required!");
        }
        $user = $this->baseUserRepository->find($this->request->get('user_id'));
        if ($user->isEmpty()) {
            throw new Exception("User not found!");
        }
        return $user;
    }
}