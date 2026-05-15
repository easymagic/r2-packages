<?php

namespace R2Packages\Framework\Services;

use Exception;
use R2Packages\Framework\Entities\BaseUserEntity;
use R2Packages\Framework\Repositories\BaseUserRepository;
use R2Packages\Framework\Traits\WithEvents;

class BaseUserService
{
    use WithEvents;

    protected BaseUserRepository $baseUserRepository;

    const HOOK_REGISTER_SAVE_DATA = 'user.register.save.data';
    const HOOK_REGISTER_SAVE_SUCCESS = 'user.register.save.success';
    const HOOK_LOGOUT_REFRESH_TOKEN = 'user.logout.refresh.token';
    const HOOK_VERIFY_OTP_SUCCESS = 'user.verify.otp.success';

    private $data = [];

    private BaseUserEntity $baseUserEntity;

    function __construct($data = [])
    {
        $this->data = $data;
        /** @var BaseUserRepository $baseUserRepository */
        $this->baseUserRepository = new BaseUserRepository();
        $this->baseUserEntity = new BaseUserEntity($this->data);
    }

    public function login()
    {
        $email = $this->data['email'] ?? '';
        $password = $this->data['password'] ?? '';
        $user = $this->baseUserRepository->findByEmail($email);

        if ($user->password !== $password) {
            throw new Exception("Invalid login!");
        }
        return $user;
    }


    public function register()
    {


        if (!isset($this->data['name']) || empty($this->data['name'])) {
            throw new Exception("Name is required!");
        }

        //email
        if (!isset($this->data['email']) || empty($this->data['email'])) {
            throw new Exception("Email is required!");
        }

        $userCheck = (new BaseUserRepository())->findByEmail($this->data['email']);

        //phone
        if (!isset($this->data['phone']) || empty($this->data['phone'])) {
            throw new Exception("Phone is required!");
        }

        //password
        if (!isset($this->data['password']) || empty($this->data['password'])) {
            throw new Exception("Password is required!");
        }

        if (!isset($this->data['confirm_password']) || $this->data['password'] !== $this->data['confirm_password']) {
            throw new Exception("Password and confirm password do not match!");
        }

        $this->baseUserEntity->generateOtp();
        $this->baseUserEntity->refreshToken();

        $input = [
            'name' => $this->data['name'],
            'email' => $this->data['email'],
            'password' => password_hash($this->data['password'], PASSWORD_DEFAULT),
            'phone' => $this->data['phone'],
            'otp' => $this->baseUserEntity->otp,
            'token' => $this->baseUserEntity->token,
            'role' => $this->baseUserEntity->role,
            'status' => $this->baseUserEntity->status,
            'created_at' => $this->baseUserEntity->created_at,
            'updated_at' => $this->baseUserEntity->updated_at,
        ];
        self::dispatch(self::HOOK_REGISTER_SAVE_DATA, $this->baseUserEntity, $input);
        $user = $this->baseUserRepository->save(0, $input);
        self::dispatch(self::HOOK_REGISTER_SAVE_SUCCESS, $user);
        return $user;
    }

    public function verifyOtp()
    {
        if (!isset($this->data['otp']) || empty($this->data['otp'])) {
            throw new Exception("OTP is required!");
        }
        // id 
        if (!isset($this->data['id']) || empty($this->data['id'])) {
            throw new Exception("ID is required!");
        }
        $id = $this->data['id'];
        $otp = $this->data['otp'];
        $user = $this->baseUserRepository->find($id);
        if ($user->otp !== $otp) {
            throw new Exception("Invalid OTP!");
        }
        $this->baseUserRepository->save($id, [
            'status' => BaseUserEntity::STATUS_ACTIVE
        ]);
        self::dispatch(self::HOOK_VERIFY_OTP_SUCCESS, $user);
        return $user;
    }

    public function logout()
    {
        if (!isset($this->data['id']) || empty($this->data['id'])) {
            throw new Exception("ID is required!");
        }
        $id = $this->data['id'];
        $user = $this->baseUserRepository->find($id);
        $user->refreshToken();
        $this->baseUserRepository->save($id, [
            'token' => $user->token
        ]);
        self::dispatch(self::HOOK_LOGOUT_REFRESH_TOKEN, $user);
    }
}
