<?php

namespace R2Packages\Framework\Services;

use Exception;
use R2Packages\Framework\Entities\BaseUserEntity;
use R2Packages\Framework\MailService;
use R2Packages\Framework\mail_templates\MailTemplates;
use R2Packages\Framework\Repositories\BaseUserRepository;
use R2Packages\Framework\Traits\Publishable;

class BaseUserService
{
    use Publishable;

    protected BaseUserRepository $baseUserRepository;

    private $data = [];

    public BaseUserEntity $baseUserEntity;

    private MailService $mailService;
    protected $input = [];

    private MailTemplates $mailTemplates;

    function __construct(
        $data,
        $input,
        BaseUserRepository $baseUserRepository,
        BaseUserEntity $baseUserEntity,
        MailService $mailService,
        MailTemplates $mailTemplates
    ) {
        $this->data = $data;
        $this->input = $input;
        $this->baseUserRepository = $baseUserRepository;
        $this->baseUserEntity = $baseUserEntity;
        $this->mailService = $mailService;
        $this->mailTemplates = $mailTemplates;
    }

    public function login()
    {
        $email = $this->data['email'] ?? '';
        $password = $this->data['password'] ?? '';
        if (empty($email) || empty($password)) {
            throw new Exception("Email and password are required!");
        }
        $user = $this->baseUserRepository->findByEmail($email);

        if (password_verify($password, $user->password)) {
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


        $this->input['name'] = $this->data['name'];
        $this->input['email'] = $this->data['email'];
        $this->input['password'] = password_hash($this->data['password'], PASSWORD_DEFAULT);
        $this->input['phone'] = $this->data['phone'];
        $this->input['otp'] = $this->baseUserEntity->otp;
        $this->input['token'] = $this->baseUserEntity->token;
        $this->input['role'] = $this->baseUserEntity->role;
        $this->input['status'] = $this->baseUserEntity->status;
        $this->input['created_at'] = $this->baseUserEntity->created_at;
        $this->input['updated_at'] = $this->baseUserEntity->updated_at;
        $user = $this->baseUserRepository->save(0, $this->input);
        $this->mailService->send($user->email, 'Welcome to our platform', 'noreply@example.com', $this->mailTemplates->registration($user));
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
        $this->baseUserRepository->save($id, [
            'status' => BaseUserEntity::STATUS_ACTIVE
        ]);
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
        $this->baseUserEntity = $this->baseUserRepository->save($id, [
            'token' => $this->baseUserEntity->token
        ]);
        return $this->baseUserEntity;
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

        $this->mailService->send($this->baseUserEntity->email, 'Password Reset', 'noreply@example.com', $this->mailTemplates->passwordResetRequest($this->baseUserEntity));
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
        return $this->baseUserEntity;
    }
}
