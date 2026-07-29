<?php

namespace R2Packages\Framework\BaseUser;

use Exception;

use R2Packages\Framework\BaseUser\BaseUserEntity;
use R2Packages\Framework\MailService;
use R2Packages\Framework\mail_templates\MailTemplates;
use R2Packages\Framework\BaseUser\BaseUserRepository;
use R2Packages\Framework\Request;
use R2Packages\Framework\Services\AuthUserService;
use R2Packages\Framework\Services\UtilService;

class BaseUserService
{

    protected BaseUserRepository $baseUserRepository;

    private MailService $mailService;

    private MailTemplates $mailTemplates;

    protected Request $request;

    private UtilService $utilService;

    protected AuthUserService $authUserService;

    function __construct(
        Request $request,
        BaseUserRepository $baseUserRepository,
        MailService $mailService,
        MailTemplates $mailTemplates,
        UtilService $utilService,
        AuthUserService $authUserService
    ) {
        $this->authUserService = $authUserService;
        $this->request = $request;
        $this->baseUserRepository = $baseUserRepository;
        $this->mailService = $mailService;
        $this->mailTemplates = $mailTemplates;
        $this->utilService = $utilService;

        $user = $this->authUserService->getAuthUser();

        if(!$user->isEmpty()){
            $role = $user->role;
            // if role contains admin, then add admin filter
            if(strpos($role, 'admin') !== false){
                // do nothing , admin can see all users
            }else{
                $this->baseUserRepository->filterById($user->id); // only show the user's own data
            }
        }
    }

    function fetch(){
        return $this->baseUserRepository->fetch();
    }

    function fetchAll(){
        return $this->baseUserRepository->fetchAll();
    }

    function count(){
        return $this->baseUserRepository->count();
    }



    public function login(Request $request)
    {
        if ($request->isEmpty('email')) {
            throw new Exception("Email is required!");
        }
        if ($request->isEmpty('password')) {
            throw new Exception("Password is required!");
        }
        $user = $this->baseUserRepository->findByEmail($request->get('email'));
        if (!password_verify($request->get('password'), $user->password)) {
            throw new Exception("Invalid login!!");
        }
        if ($user->status !== BaseUserEntity::STATUS_ACTIVE) {
            throw new Exception("Inactive account, please activate your account from the OTP sent to your email!");
        }
        $token = $this->utilService->refreshToken($user->id);
        $otp = $this->utilService->generateOtp();
        $request->input['token'] = $token;
        $request->input['otp'] = $otp;
        $user = $this->baseUserRepository->save($user->id, $request->input);
        // $this->container->set("auth-user", $user);
        return $user;
    }


    public function register(Request $request)
    {

        if ($request->isEmpty('name')) {
            throw new Exception("Name is required!");
        }

        //email
        if ($request->isEmpty('email')) {
            throw new Exception("Email is required!");
        }

        $userCheck = $this->baseUserRepository->findByEmail($request->data['email']);
        if (!$userCheck->isEmpty()) {
            throw new Exception("User already exists!");
        }

        //phone
        if ($request->isEmpty('phone')) {
            throw new Exception("Phone is required!");
        }

        //password
        if ($request->isEmpty('password')) {
            throw new Exception("Password is required!");
        }

        if ($request->get('password') !== $request->get('confirm_password')) {
            throw new Exception("Password and confirm password do not match!");
        }

        $otp = $this->utilService->generateOtp();
        $token = $this->utilService->refreshToken(0);


        $request->input['name'] = $request->get('name');
        $request->input['email'] = $request->get('email');
        $request->input['password'] = password_hash($request->get('password'), PASSWORD_DEFAULT);
        $request->input['phone'] = $request->get('phone');
        $request->input['otp'] = $otp;
        $request->input['token'] = $token;
        $request->input['role'] = 'customer';
        $request->input['status'] = 'inactive';
        $request->input['created_at'] = date('Y-m-d H:i:s');
        $request->input['updated_at'] = date('Y-m-d H:i:s');
        $user = $this->baseUserRepository->save(0, $request->input);
        $this->mailService->send($user->email, 'Welcome to our platform', 'noreply@example.com', $this->mailTemplates->registration($user));
        return $user;
    }

    function resendOtp(Request $request, BaseUserEntity $user)
    {
        $otp = $this->utilService->generateOtp();
        $token = $this->utilService->refreshToken($user->id);
        $request->input['otp'] = $otp;
        $request->input['token'] = $token;
        $user = $this->baseUserRepository->save($user->id, $request->input);
        $this->mailService->send($user->email, 'Welcome to our platform', 'noreply@example.com', $this->mailTemplates->registration($user));
        return $user;
    }

    public function create(Request $request)
    {

        if (!isset($request->data['name']) || empty($request->data['name'])) {
            throw new Exception("Name is required!");
        }

        //email
        if (!isset($request->data['email']) || empty($request->data['email'])) {
            throw new Exception("Email is required!");
        }

        $userCheck = $this->baseUserRepository->findByEmail($request->data['email']);
        if (!$userCheck->isEmpty()) {
            throw new Exception("User already exists!");
        }

        //phone
        if (!isset($request->data['phone']) || empty($request->data['phone'])) {
            throw new Exception("Phone is required!");
        }

        //password
        if (!isset($request->data['password']) || empty($request->data['password'])) {
            throw new Exception("Password is required!");
        }

        // role
        if (!isset($request->data['role']) || empty($request->data['role'])) {
            throw new Exception("Role is required!");
        }

        // status
        if (!isset($request->data['status']) || empty($request->data['status'])) {
            throw new Exception("Status is required!");
        }

        $otp = $this->utilService->generateOtp();
        $token = $this->utilService->refreshToken(0);


        $request->input['name'] = $request->data['name'];
        $request->input['email'] = $request->data['email'];
        $request->input['password'] = password_hash($request->data['password'], PASSWORD_DEFAULT);
        $request->input['phone'] = $request->data['phone'];
        $request->input['otp'] = $otp;
        $request->input['token'] = $token;
        $request->input['role'] = $request->data['role'];
        $request->input['status'] = $request->data['status'];
        $request->input['created_at'] = date('Y-m-d H:i:s');
        $request->input['updated_at'] = date('Y-m-d H:i:s');
        $user = $this->baseUserRepository->save(0, $request->input);
        $this->mailService->send($user->email, 'Welcome to our platform', 'noreply@example.com', $this->mailTemplates->registration($user));
        return $user;
    }

    public function verifyOtp(Request $request, BaseUserEntity $user)
    {
        if ($request->isEmpty('otp')) {
            throw new Exception("OTP is required!");
        }

        $otp = $request->get('otp');
        if ($user->otp !== $otp) {
            throw new Exception("Invalid OTP!");
        }
        $request->input['status'] = BaseUserEntity::STATUS_ACTIVE;
        $user = $this->baseUserRepository->save($user->id, $request->input);
        return $user;
    }

    public function logout(Request $request, BaseUserEntity $authUser)
    {
        if (!isset($request->data['id']) || empty($request->data['id'])) {
            throw new Exception("ID is required!");
        }
        $id = $authUser->id;
        $user = $this->baseUserRepository->find($id);
        $token = $this->utilService->refreshToken($id);
        $request->input['token'] = $token;
        $user = $this->baseUserRepository->save($id, $request->input);
        return $user;
    }

    function requestPasswordReset(Request $request)
    {
        if (!isset($request->data['email']) || empty($request->data['email'])) {
            throw new Exception("Email is required!");
        }
        $user = $this->baseUserRepository->findByEmail($request->data['email']);
        $otp = $this->utilService->generateOtp();
        $token = $this->utilService->refreshToken($user->id);
        $request->input['token'] = $token;
        $request->input['otp'] = $otp;
        $user = $this->baseUserRepository->save($user->id, $request->input);

        $this->mailService->send($user->email, 'Password Reset', 'noreply@example.com', $this->mailTemplates->passwordResetRequest($user));
        return $user;
    }

    function resetPassword(Request $request, BaseUserEntity $user)
    {
        // id (user id)
        if ($request->isEmpty('otp')) {
            throw new Exception("OTP is required!");
        }
        if ($request->isEmpty('password')) {
            throw new Exception("Password is required!");
        }
        if ($request->get('password') !== $request->get('confirm_password')) {
            throw new Exception("Password and confirm password do not match!");
        }
        if ($user->otp !== $request->get('otp')) {
            throw new Exception("Invalid OTP!");
        }
        $request->input['password'] = password_hash($request->get('password'), PASSWORD_DEFAULT);
        $user = $this->baseUserRepository->save($user->id, $request->input);
        return $user;
    }

    public function updateProfile(Request $request, BaseUserEntity $authUser)
    {
        if ($request->isEmpty('name')) {
            throw new Exception("Name is required!");
        }
        if ($request->isEmpty('phone')) {
            throw new Exception("Phone is required!");
        }
        $request->input['name'] = $request->get('name');
        $request->input['phone'] = $request->get('phone');
        $user = $this->baseUserRepository->save($authUser->id, $request->input);
        return $user;
    }


    public function updateUserProfile(Request $request, BaseUserEntity $user)
    {
        if ($request->isEmpty('name')) {
            throw new Exception("Name is required!");
        }
        if ($request->isEmpty('phone')) {
            throw new Exception("Phone is required!");
        }
        // role 
        if ($request->isEmpty('role')) {
            throw new Exception("Role is required!");
        }
        // status
        if ($request->isEmpty('status')) {
            throw new Exception("Status is required!");
        }

        $request->input["role"] = $request->get('role');
        $request->input["status"] = $request->get('status');
        $request->input['name'] = $request->get('name');
        $request->input['phone'] = $request->get('phone');
        $user = $this->baseUserRepository->save($user->id, $request->input);
        return $user;
    }


    public function changeUserPassword(Request $request, BaseUserEntity $user)
    {
        if ($request->isEmpty('password')) {
            throw new Exception("Password is required!");
        }
        $request->input['password'] = password_hash($request->get('password'), PASSWORD_DEFAULT);
        $user = $this->baseUserRepository->save($user->id, $request->input);
        return $user;
    }

    public function changeMyPassword(Request $request, BaseUserEntity $authUser)
    {
        if ($request->isEmpty('password')) {
            throw new Exception("Password is required!");
        }
        // old password
        if ($request->isEmpty('old_password')) {
            throw new Exception("Old password is required!");
        }
        // confirm password
        if ($request->get('password') !== $request->get('confirm_password')) {
            throw new Exception("Password and confirm password do not match!");
        }
        if (!password_verify($request->get('old_password'), $authUser->password)) {
            throw new Exception("Old password is incorrect!");
        }
        $request->input['password'] = password_hash($request->get('password'), PASSWORD_DEFAULT);
        $user = $this->baseUserRepository->save($authUser->id, $request->input);
        return $user;
    }
}
