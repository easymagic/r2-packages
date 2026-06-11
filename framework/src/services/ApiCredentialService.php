<?php 

namespace R2Packages\Framework\Services;

use R2Packages\Framework\Entities\BaseUserEntity;
use R2Packages\Framework\Repositories\BaseUserRepository;
use R2Packages\Framework\Request;

class ApiCredentialService
{

    private $x_user_token = '';
    private $x_token = '';
    private Request $request;
    private BaseUserRepository $baseUserRepository;
    private BaseUserEntity $baseUserEntity;

    protected $globalToken = '1234567890';

    public function __construct(Request $request,BaseUserRepository $baseUserRepository)
    {
        $this->request = $request;
        $this->baseUserRepository = $baseUserRepository;
        $this->decodeParams();
    }

    private function decodeParams(){
        $this->x_user_token = $this->request->get('x-user-token');
        $this->x_token = $this->request->get('x-token');

        if (!$this->userTokenIsEmpty()) {
            $userId = explode('_', $this->x_user_token)[0];
            $this->baseUserEntity = $this->baseUserRepository->find($userId);
        }
    }

    function userTokenIsEmpty(){
        return $this->request->isEmpty("x-user-token");
    }

    function globalTokenIsEmpty(){
        return $this->request->isEmpty("x-token");
    }

    function userIsEmpty(){
        return $this->baseUserEntity->isEmpty();
    }

    function globalTokenIsValid(){
        return !$this->globalTokenIsEmpty() && $this->x_token === $this->globalToken;
    }

    function userTokenIsValid(){
        return !$this->userIsEmpty() &&  $this->baseUserEntity->token === $this->x_user_token;
    }

    function getAuthUser(){
        return $this->baseUserEntity;
    }
}