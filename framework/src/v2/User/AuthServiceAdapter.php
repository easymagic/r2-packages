<?php

namespace R2Packages\Framework\v2\User;

use Exception;
use R2Packages\Framework\Request;
use R2Packages\Framework\Services\AuthUserService;
use R2Packages\Framework\v2\Interfaces\AuthServiceInterface;
use R2Packages\Framework\v2\Interfaces\RepositoryInterface;

class AuthServiceAdapter implements AuthServiceInterface
{
    protected AuthUserService $authUserService;

    public function __construct(AuthUserService $authUserService)
    {
        $this->authUserService = $authUserService;
    }

    public function login(
        Request $request,
        RepositoryInterface $repository
    ) {
        if ($request->isEmpty('email') || $request->isEmpty('password')) {
            throw new \Exception('Email and password are required');
        }
        /** @var UserEntity $user */
        $user = $repository->fetchBy("email", $request->get('email'));
        if ($user->isEmpty()) {
            throw new \Exception('Invalid login credentials!');
        }
        if (!password_verify($request->get('password'), $user->password)) {
            throw new \Exception('Invalid login credentials!');
        }
        return $user;
    }

    public function refreshToken(
        UserEntity $user,
        RepositoryInterface $repository
    ) {
        $token = $user->id . '_' . bin2hex(random_bytes(32));
        $user = $repository->save($user->id, [
            'token' => $token
        ]);
        return $user;
    }

    public function refreshOtp(
        UserEntity $user,
        RepositoryInterface $repository
    ) {
        $otp = intval(rand(100000, 999999));
        $user = $repository->save($user->id, [
            'otp' => $otp
        ]);
        return $user;
    }

    public function verifyOtp(UserEntity $user, Request $request, RepositoryInterface $repository)
    {
        if ($request->isEmpty('otp')) {
            throw new \Exception('OTP is required');
        }
        if ($user->otp !== $request->get('otp')) {
            throw new \Exception('Invalid OTP!');
        }
        $user = $repository->save($user->id, [
            'status' => 'active',
        ]);
        return $user;
    }

    public function register(Request $request, RepositoryInterface $repository)
    {
        if ($request->isEmpty('name')) {
            throw new Exception("Name is required!");
        }

        //email
        if ($request->isEmpty('email')) {
            throw new Exception("Email is required!");
        }

        /** @var UserEntity $userCheck */
        $userCheck = $repository->fetchBy("email", $request->get('email'));
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

        // $otp = $this->utilService->generateOtp();
        // $token = $this->utilService->refreshToken(0);


        $request->input['name'] = $request->get('name');
        $request->input['email'] = $request->get('email');
        $request->input['password'] = password_hash($request->get('password'), PASSWORD_DEFAULT);
        $request->input['phone'] = $request->get('phone');
        // $request->input['otp'] = $otp;
        // $request->input['token'] = $token;
        $request->input['role'] = 'customer';
        $request->input['status'] = 'inactive';
        $request->input['created_at'] = date('Y-m-d H:i:s');
        $request->input['updated_at'] = date('Y-m-d H:i:s');
        /** @var UserEntity $user */
        $user = $repository->save(0, $request->input);
        $this->refreshOtp($user, $repository);
        $user = $this->refreshToken($user, $repository);
        return $user;
    }

    public function logout(UserEntity $user, RepositoryInterface $repository)
    {
        $user = $this->refreshToken($user, $repository);
        return $user;
    }

    public function requestResetPassword(Request $request, RepositoryInterface $repository)
    {
        if ($request->isEmpty('email')) {
            throw new Exception("Email is required!");
        }
        /** @var UserEntity $user */
        $user = $repository->fetchBy("email", $request->get('email'));
        if ($user->isEmpty()) {
            throw new Exception("User not found!");
        }
        $this->refreshOtp($user, $repository);
        $this->refreshToken($user, $repository);
    }

    public function resetPassword(UserEntity $user, Request $request, RepositoryInterface $repository)
    {
        if ($user->isEmpty()) {
            throw new Exception("User not found!");
        }
        if ($request->isEmpty('otp')) {
            throw new Exception("OTP is required!");
        }
        if ($user->otp !== $request->get('otp')) {
            throw new Exception("Invalid OTP!");
        }
        if ($request->isEmpty('password')) {
            throw new Exception("Password is required!");
        }
        if ($request->isEmpty('confirm_password')) {
            throw new Exception("Confirm password is required!");
        }
        if ($request->get('password') !== $request->get('confirm_password')) {
            throw new Exception("Password and confirm password do not match!");
        }
        $user = $repository->save($user->id, [
            'password' => password_hash($request->get('password'), PASSWORD_DEFAULT)
        ]);
        return $user;
    }

    public function updateProfile(UserEntity $user, Request $request, RepositoryInterface $repository)
    {
        if ($user->isEmpty()) {
            throw new Exception("User not found!");
        }
        if ($request->isEmpty('name')) {
            throw new Exception("Name is required!");
        }
        if ($request->isEmpty('phone')) {
            throw new Exception("Phone is required!");
        }
        $request->input['name'] = $request->get('name');
        $request->input['phone'] = $request->get('phone');
        $user = $repository->save($user->id, $request->input);
        return $user;
    }

    public function changePassword(UserEntity $user, Request $request, RepositoryInterface $repository)
    {
        if ($user->isEmpty()) {
            throw new Exception("User not found!");
        }
        if ($request->isEmpty('old_password')) {
            throw new Exception("Old password is required!");
        }
        if ($request->isEmpty('new_password')) {
            throw new Exception("New password is required!");
        }
        if ($request->isEmpty('confirm_password')) {
            throw new Exception("Confirm password is required!");
        }
        if ($request->get('new_password') !== $request->get('confirm_password')) {
            throw new Exception("New password and confirm password do not match!");
        }
        if (!password_verify($request->get('old_password'), $user->password)) {
            throw new Exception("Invalid old password!");
        }
        $user = $repository->save($user->id, [
            'password' => password_hash($request->get('new_password'), PASSWORD_DEFAULT)
        ]);
        return $user;
    }

    public function createUser(Request $request, RepositoryInterface $repository)
    {
        if (!isset($request->data['name']) || empty($request->data['name'])) {
            throw new Exception("Name is required!");
        }

        //email
        if (!isset($request->data['email']) || empty($request->data['email'])) {
            throw new Exception("Email is required!");
        }

        /** @var UserEntity $userCheck */
        $userCheck = $repository->fetchBy("email", $request->get('email'));
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

        // $otp = $this->utilService->generateOtp();
        // $token = $this->utilService->refreshToken(0);


        $request->input['name'] = $request->data['name'];
        $request->input['email'] = $request->data['email'];
        $request->input['password'] = password_hash($request->data['password'], PASSWORD_DEFAULT);
        $request->input['phone'] = $request->data['phone'];
        // $request->input['otp'] = $otp;
        // $request->input['token'] = $token;
        $request->input['role'] = $request->data['role'];
        $request->input['status'] = $request->data['status'];
        $request->input['created_at'] = date('Y-m-d H:i:s');
        $request->input['updated_at'] = date('Y-m-d H:i:s');
        $user = $repository->save(0, $request->input);
        $this->refreshOtp($user, $repository);
        $user = $this->refreshToken($user, $repository);
        return $user;
    }

    public function changeUserPassword(UserEntity $user, Request $request, RepositoryInterface $repository)
    {
        if ($user->isEmpty()) {
            throw new Exception("User not found!");
        }
        if ($request->isEmpty('password')) {
            throw new Exception("Password is required!");
        }
        $repository->save($user->id, [
            'password' => password_hash($request->get('password'), PASSWORD_DEFAULT)
        ]);
        return $user;
    }

    public function updateUserProfile(UserEntity $user, Request $request, RepositoryInterface $repository)
    {
        if ($user->isEmpty()) {
            throw new Exception("User not found!");
        }
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
        $user = $repository->save($user->id, $request->input);
        $this->refreshToken($user, $repository);
        $this->refreshOtp($user, $repository);
        return $user;
    }

    public function fetchById(Request $request, RepositoryInterface $repository) {
        if ($request->isEmpty('user_id')) {
            throw new Exception("User ID is required!");
        }
        /** @var UserEntity $user */
        $user = $repository->fetchBy("id", $request->get('user_id'));
        if ($user->isEmpty()) {
            throw new Exception("User not found!");
        }
        return $user;
    }

    public function getAuthUser() {
        return $this->authUserService->getAuthUser();
    }

    public function resendOtp(UserEntity $user, Request $request, RepositoryInterface $repository)
    {
        $this->refreshOtp($user, $repository);
        $this->refreshToken($user, $repository);
        return $user;
    }
}
