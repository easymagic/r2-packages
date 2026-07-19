<?php 

namespace R2Packages\Framework\Services;

use R2Packages\Framework\Request;
use R2Packages\Framework\v2\User\UserEntity;
use R2Packages\Framework\v2\User\UserRepository;

class ApiCredentialService
{

    private $x_user_token = '';
    private $x_token = '';
    private Request $request;
    private UserRepository $userRepository;
    private UserEntity $userEntity;

    protected $globalToken = '1234567890';

    public function __construct(Request $request,UserRepository $userRepository)
    {
        $this->request = $request;
        $this->userRepository = $userRepository;
        $this->decodeParams();
    }

    private function decodeParams(){
        $this->x_user_token = $this->request->get('x-user-token');
        $this->x_token = $this->request->get('x-token');

        if (!$this->userTokenIsEmpty()) {
            $userId = explode('_', $this->x_user_token)[0];
            $this->userEntity = $this->userRepository->fetchBy("id", $userId);
        }
    }

    function userTokenIsEmpty(){
        return $this->request->isEmpty("x-user-token");
    }

    function globalTokenIsEmpty(){
        return $this->request->isEmpty("x-token");
    }

    function userIsEmpty(){
        return $this->userEntity->isEmpty();
    }

    function globalTokenIsValid(){
        return !$this->globalTokenIsEmpty() && $this->x_token === $this->globalToken;
    }

    function userTokenIsValid(){
        return  !empty($this->x_user_token) && $this->userEntity->token === $this->x_user_token && !$this->userIsEmpty();
    }

    function getAuthUser(){
        return $this->userEntity;
    }
}