<?php

namespace R2Packages\Framework\Services;

use Exception;
use R2Packages\Framework\Entities\BaseUserEntity;
use R2Packages\Framework\MailService;
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
    const HOOK_BEFORE_VERIFY_OTP = 'user.before.verify.otp';
    const HOOK_AFTER_REQUEST_PASSWORD_RESET = 'user.after.request.password.reset';
    const HOOK_RESET_PASSWORD_SUCCESS = 'user.reset.password.success';

    private $data = [];

    public BaseUserEntity $baseUserEntity;

    private $mailBody = '';

    private MailService $mailService;

    function __construct($data = [])
    {
        $this->data = $data;
        /** @var BaseUserRepository $baseUserRepository */
        $this->baseUserRepository = new BaseUserRepository();
        $this->baseUserEntity = new BaseUserEntity($this->data);
        $this->mailService = new MailService();
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

    function setMailBody($body)
    {
        $this->mailBody = $body;
        return $this;
    }

    function getMailBody()
    {
        return $this->mailBody;
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
        self::dispatch(self::HOOK_REGISTER_SAVE_DATA, $this, $input);
        $user = $this->baseUserRepository->save(0, $input);
        self::dispatch(self::HOOK_REGISTER_SAVE_SUCCESS, $this);
        $this->mailService->send($user->email, 'Welcome to our platform', 'noreply@example.com', $this->mailBody);
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
        $this->baseUserEntity = $this->baseUserRepository->find($id);
        if ($this->baseUserEntity->otp !== $otp) {
            throw new Exception("Invalid OTP!");
        }
        self::dispatch(self::HOOK_BEFORE_VERIFY_OTP, $this);
        $this->baseUserRepository->save($id, [
            'status' => BaseUserEntity::STATUS_ACTIVE
        ]);
        self::dispatch(self::HOOK_VERIFY_OTP_SUCCESS, $this);
        return $this->baseUserEntity;
    }

    public function logout()
    {
        if (!isset($this->data['id']) || empty($this->data['id'])) {
            throw new Exception("ID is required!");
        }
        $id = $this->data['id'];
        $this->baseUserEntity = $this->baseUserRepository->find($id);
        $this->baseUserEntity->refreshToken();
        $this->baseUserRepository->save($id, [
            'token' => $this->baseUserEntity->token
        ]);
        self::dispatch(self::HOOK_LOGOUT_REFRESH_TOKEN, $this);
    }

    function requestPasswordReset()
    {
        if (!isset($this->data['email']) || empty($this->data['email'])) {
            throw new Exception("Email is required!");
        }
        $this->baseUserEntity = $this->baseUserRepository->findByEmail($this->data['email']);
        $this->baseUserEntity->generateOtp();
        $this->baseUserEntity->refreshToken();
        $this->baseUserEntity = $this->baseUserRepository->save($this->baseUserEntity->id, [
            'token' => $this->baseUserEntity->token,
            'otp' => $this->baseUserEntity->otp,
        ]);

        self::dispatch(self::HOOK_AFTER_REQUEST_PASSWORD_RESET, $this);
        $this->mailService->send($this->baseUserEntity->email, 'Password Reset', 'noreply@example.com', $this->mailBody);
        return $this->baseUserEntity;
    }

    function resetPassword()
    {
        // id (user id)
        if (!isset($this->data['id']) || empty($this->data['id'])) {
            throw new Exception("ID is required!");
        }
        if (!isset($this->data['otp']) || empty($this->data['otp'])) {
            throw new Exception("OTP is required!");
        }
        if (!isset($this->data['password']) || empty($this->data['password'])) {
            throw new Exception("Password is required!");
        }
        if (!isset($this->data['confirm_password']) || $this->data['password'] !== $this->data['confirm_password']) {
            throw new Exception("Password and confirm password do not match!");
        }
        $this->baseUserEntity = $this->baseUserRepository->find($this->data['id']);
        if ($this->baseUserEntity->otp !== $this->data['otp']) {
            throw new Exception("Invalid OTP!");
        }
        $this->baseUserRepository->save($this->baseUserEntity->id, [
            'password' => password_hash($this->data['password'], PASSWORD_DEFAULT),
        ]);
        self::dispatch(self::HOOK_RESET_PASSWORD_SUCCESS, $this);
        return $this->baseUserEntity;
    }
}