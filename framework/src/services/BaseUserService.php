<?php

namespace R2Packages\Framework\Services;

use Exception;
use R2Packages\Framework\Container;
use R2Packages\Framework\Entities\BaseUserEntity;
use R2Packages\Framework\MailService;
use R2Packages\Framework\mail_templates\MailTemplates;
use R2Packages\Framework\Repositories\BaseUserRepository;
use R2Packages\Framework\Traits\Publishable;

class BaseUserService
{
    use Publishable;

    protected BaseUserRepository $baseUserRepository;

    protected $data = [];

    private MailService $mailService;
    protected $input = [];

    private MailTemplates $mailTemplates;

    /*
     * @var BaseUserEntity
     */
    private static $user;

    // protected $authUserId = 0;

    // private Container $container;
    private BaseUserEntity $authUser;

    function __construct(
        // $authUserId,
        $data,
        // $input,
        // Container $container,
        BaseUserEntity $authUser,
        BaseUserRepository $baseUserRepository,
        MailService $mailService,
        MailTemplates $mailTemplates
    ) {
        // $this->container = $container;
        $this->authUser = $authUser;
        // $this->authUserId = $authUserId;
        $this->data = $data;
        // $this->input = $input;
        $this->baseUserRepository = $baseUserRepository;
        $this->mailService = $mailService;
        $this->mailTemplates = $mailTemplates;
    }

    public function generateOtp(){
        return rand(100000, 999999);
    }

    function refreshToken($id){
        $token = $id . '_' . bin2hex(random_bytes(32));
        return $token;
    }


    public function login()
    {
        $email = $this->data['email'] ?? '';
        $password = $this->data['password'] ?? '';
        if (empty($email) || empty($password)) {
            throw new Exception("Email and password are required!");
        }
        $user = $this->baseUserRepository->findByEmail($email);

        if (!password_verify($password, $user->password)) {
            throw new Exception("Invalid login!!");
        }
        if ($user->status !== BaseUserEntity::STATUS_ACTIVE) {
            throw new Exception("Inactive account, please activate your account from the OTP sent to your email!");
        }
        $token = $this->refreshToken($user->id);
        $otp = $this->generateOtp();
        $user = $this->baseUserRepository->save($user->id, [
            'token' => $token,
            'otp' => $otp
        ]);
        // $this->container->set("auth-user", $user);
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

        $userCheck = $this->baseUserRepository->findByEmail($this->data['email']);
        if (!$userCheck->isEmpty()) {
            throw new Exception("User already exists!");
        }

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

        $otp = $this->generateOtp();
        $token = $this->refreshToken(0);


        $this->input['name'] = $this->data['name'];
        $this->input['email'] = $this->data['email'];
        $this->input['password'] = password_hash($this->data['password'], PASSWORD_DEFAULT);
        $this->input['phone'] = $this->data['phone'];
        $this->input['otp'] = $otp;
        $this->input['token'] = $token;
        $this->input['role'] = 'customer';
        $this->input['status'] = 'inactive';
        $this->input['created_at'] = date('Y-m-d H:i:s');
        $this->input['updated_at'] = date('Y-m-d H:i:s');
        $user = $this->baseUserRepository->save(0, $this->input);
        $this->mailService->send($user->email, 'Welcome to our platform', 'noreply@example.com', $this->mailTemplates->registration($user));
        return $user;
    }

    function resendOtp(){
        if (!isset($this->data['id']) || empty($this->data['id'])) {
            throw new Exception("ID is required!");
        }
        $user = $this->baseUserRepository->find($this->data['id']);
        $otp = $this->generateOtp();
        $token = $this->refreshToken($user->id);
        $user = $this->baseUserRepository->save($user->id, [
            'otp' => $otp,
            'token' => $token,
        ]);
        $this->mailService->send($user->email, 'Welcome to our platform', 'noreply@example.com', $this->mailTemplates->registration($user));
        return $user;
    }

    public function create(){

        if (!isset($this->data['name']) || empty($this->data['name'])) {
            throw new Exception("Name is required!");
        }

        //email
        if (!isset($this->data['email']) || empty($this->data['email'])) {
            throw new Exception("Email is required!");
        }

        $userCheck = $this->baseUserRepository->findByEmail($this->data['email']);
        if (!$userCheck->isEmpty()) {
            throw new Exception("User already exists!");
        }

        //phone
        if (!isset($this->data['phone']) || empty($this->data['phone'])) {
            throw new Exception("Phone is required!");
        }

        //password
        if (!isset($this->data['password']) || empty($this->data['password'])) {
            throw new Exception("Password is required!");
        }

        // role
        if (!isset($this->data['role']) || empty($this->data['role'])) {
            throw new Exception("Role is required!");
        }

        // status
        if (!isset($this->data['status']) || empty($this->data['status'])) {
            throw new Exception("Status is required!");
        }

        // if (!isset($this->data['confirm_password']) || $this->data['password'] !== $this->data['confirm_password']) {
        //     throw new Exception("Password and confirm password do not match!");
        // }

        $otp = $this->generateOtp();
        $token = $this->refreshToken(0);


        $this->input['name'] = $this->data['name'];
        $this->input['email'] = $this->data['email'];
        $this->input['password'] = password_hash($this->data['password'], PASSWORD_DEFAULT);
        $this->input['phone'] = $this->data['phone'];
        $this->input['otp'] = $otp;
        $this->input['token'] = $token;
        $this->input['role'] = $this->data['role'];
        $this->input['status'] = $this->data['status'];
        $this->input['created_at'] = date('Y-m-d H:i:s');
        $this->input['updated_at'] = date('Y-m-d H:i:s');
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
        $user = $this->baseUserRepository->find($id);
        if ($user->otp !== $otp) {
            throw new Exception("Invalid OTP!");
        }
        $user = $this->baseUserRepository->save($id, [
            'status' => BaseUserEntity::STATUS_ACTIVE
        ]);
        return $user;
    }

    public function logout()
    {
        if (!isset($this->authUser->id) || empty($this->authUser->id)) {
            throw new Exception("ID is required!");
        }
        $id = $this->authUser->id;
        $user = $this->baseUserRepository->find($id);
        $token = $this->refreshToken($id);
        $user = $this->baseUserRepository->save($id, [
            'token' => $token
        ]);
        return $user;
    }

    function requestPasswordReset()
    {
        if (!isset($this->data['email']) || empty($this->data['email'])) {
            throw new Exception("Email is required!");
        }
        $user = $this->baseUserRepository->findByEmail($this->data['email']);
        $otp = $this->generateOtp();
        $token = $this->refreshToken($user->id);
        $user = $this->baseUserRepository->save($user->id, [
            'token' => $token,
            'otp' => $otp,
        ]);

        $this->mailService->send($user->email, 'Password Reset', 'noreply@example.com', $this->mailTemplates->passwordResetRequest($user));
        return $user;
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
        $user = $this->baseUserRepository->find($this->data['id']);
        if ($user->otp !== $this->data['otp']) {
            throw new Exception("Invalid OTP!");
        }
        $user = $this->baseUserRepository->save($user->id, [
            'password' => password_hash($this->data['password'], PASSWORD_DEFAULT),
        ]);
        return $user;
    }

    public function updateProfile(){
        if (!isset($this->authUser->id) || empty($this->authUser->id)) {
            throw new Exception("ID is required!"); // need to be authenticated user id
        }
        if (!isset($this->data['name']) || empty($this->data['name'])) {
            throw new Exception("Name is required!");
        }
        if (!isset($this->data['phone']) || empty($this->data['phone'])) {
            throw new Exception("Phone is required!");
        }
        $this->input['name'] = $this->data['name'];
        $this->input['phone'] = $this->data['phone'];
        $id = $this->authUser->id;
        $user = $this->baseUserRepository->save($id, $this->input);
        return $user;
    }


    public function updateUserProfile(){
        if (!isset($this->data['id']) || empty($this->data['id'])) {
            throw new Exception("ID is required!");
        }
        if (!isset($this->data['name']) || empty($this->data['name'])) {
            throw new Exception("Name is required!");
        }
        if (!isset($this->data['phone']) || empty($this->data['phone'])) {
            throw new Exception("Phone is required!");
        }

        // role 
        if (!isset($this->data['role']) || empty($this->data['role'])) {
            throw new Exception("Role is required!");
        }

        // status
        if (!isset($this->data['status']) || empty($this->data['status'])) {
            throw new Exception("Status is required!");
        }

        $this->input["role"] = $this->data['role'];
        $this->input["status"] = $this->data['status'];
        $this->input['name'] = $this->data['name'];
        $this->input['phone'] = $this->data['phone'];
        $id = $this->data['id'];
        $user = $this->baseUserRepository->save($id, $this->input);
        return $user;
    }

    public function getProfile(){
        if (!isset($this->data['id']) || empty($this->data['id'])) {
            throw new Exception("ID is required!");
        }
        $id = $this->data['id'];
        $user = $this->baseUserRepository->find($id);
        return $user;
    }

    public function getMyProfile(){
        if (!isset($this->authUser->id) || empty($this->authUser->id)) {
            throw new Exception("ID is required!"); // need to be authenticated user id
        }
        $id = $this->authUser->id;
        $user = $this->baseUserRepository->find($id);
        return $user;
    }

    public function changeUserPassword(){
        if (!isset($this->data['id']) || empty($this->data['id'])) {
            throw new Exception("ID is required!");
        }

        if (!isset($this->data['password']) || empty($this->data['password'])) {
            throw new Exception("Password is required!");
        }

        $user = $this->baseUserRepository->find($this->data['id']);

        $user = $this->baseUserRepository->save($user->id, [
            'password' => password_hash($this->data['password'], PASSWORD_DEFAULT),
        ]);
        return $user;
    }

    public function changeMyPassword(){
        if (!isset($this->data['password']) || empty($this->data['password'])) {
            throw new Exception("Password is required!");
        }
        // old password
        if (!isset($this->data['old_password']) || empty($this->data['old_password'])) {
            throw new Exception("Old password is required!");
        }
        // confirm password
        if (!isset($this->data['confirm_password']) || $this->data['password'] !== $this->data['confirm_password']) {
            throw new Exception("Password and confirm password do not match!");
        }
        $user = $this->baseUserRepository->find($this->authUser->id);
        if (!password_verify($this->data['old_password'], $user->password)) {
            throw new Exception("Old password is incorrect!");
        }
        $user = $this->baseUserRepository->save($this->authUser->id, [
            'password' => password_hash($this->data['password'], PASSWORD_DEFAULT),
        ]);
        return $user;
    }

    function fetch(){
        $users = $this->baseUserRepository->fetch();
        return $users;
    }

    function fetchAll(){
        $users = $this->baseUserRepository->fetchAll();
        return $users;
    }

    function find($id){
        $user = $this->baseUserRepository->find($id);
        return $user;
    }

}
