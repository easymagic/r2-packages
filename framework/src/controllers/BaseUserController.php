<?php

namespace R2Packages\Framework\Controllers;

use R2Packages\Framework\Entities\BaseUserEntity;
use R2Packages\Framework\Repositories\BaseUserRepository;
use R2Packages\Framework\Request;
use R2Packages\Framework\Services\BaseUserService;
use R2Packages\Framework\Traits\Publishable;

class BaseUserController
{
    use Publishable;

    private BaseUserService $baseUserService;
    private Request $request;
    private BaseUserEntity $authUser;
    private BaseUserRepository $baseUserRepository;

    function __construct(
        BaseUserService $baseUserService,
        Request $request,
        BaseUserEntity $authUser,
        BaseUserRepository $baseUserRepository
    ) {
        $this->baseUserService = $baseUserService;
        $this->request = $request;
        $this->authUser = $authUser;
        $this->baseUserRepository = $baseUserRepository;
    }

    public function login()
    {
        $user = $this->baseUserService->login($this->request);
        jsonResponse([
            'message' => 'Login successful',
            'data' => $user,
            "success" => true
        ]);
    }

    public function register()
    {
        $user = $this->baseUserService->register($this->request);
        jsonResponse([
            'message' => 'Registration successful , please check your email for OTP verification sent to you.',
            'data' => $user,
            "success" => true
        ]);
    }

    public function resendOtp()
    {
        $user = $this->baseUserService->resendOtp($this->request);
        jsonResponse([
            'message' => 'OTP resent successfully',
            'data' => [
                "id" => $user->id,
                "email" => $user->email
            ],
            "success" => true
        ]);
    }

    public function verifyOtp()
    {
        $user = $this->baseUserService->verifyOtp($this->request);
        jsonResponse([
            'message' => 'OTP verification successful',
            'data' => $user,
            "success" => true
        ]);
    }

    public function logout()
    {
        $user = $this->baseUserService->logout($this->request, $this->authUser);
        jsonResponse([
            'message' => 'Logout successful',
            'data' => $user,
            "success" => true
        ]);
    }


    public function requestPasswordReset()
    {
        $user = $this->baseUserService->requestPasswordReset($this->request);
        jsonResponse([
            'message' => 'Password reset request successful, please check your email for OTP verification sent to you.',
            'data' => $user,
            "success" => true
        ]);
    }


    public function resetPassword()
    {
        $user = $this->baseUserService->resetPassword($this->request);
        jsonResponse([
            'message' => 'Password reset successful',
            'data' => $user,
            "success" => true
        ]);
    }

    public function updateProfile()
    {
        $user = $this->baseUserService->updateProfile($this->request, $this->authUser);
        jsonResponse([
            'message' => 'Profile updated successfully',
            'data' => $user,
            "success" => true
        ]);
    }

    public function getProfile()
    {
        jsonResponse([
            'message' => 'Profile fetched successfully',
            'data' => $this->authUser,
            "success" => true
        ]);
    }

    // change my password
    public function changeMyPassword()
    {
        $user = $this->baseUserService->changeMyPassword($this->request, $this->authUser);
        jsonResponse([
            'message' => 'Password changed successfully',
            'data' => $user,
            "success" => true
        ]);
    }

    public function getMyProfile()
    {
        $user = $this->authUser;
        jsonResponse([
            'message' => 'My profile fetched successfully',
            'data' => $user,
            "success" => true
        ]);
    }

    public function create()
    {
        $user = $this->baseUserService->create($this->request);
        jsonResponse([
            'message' => 'User created successfully',
            'data' => $user,
            "success" => true
        ]);
    }

    public function updateUserProfile()
    {
        $user = $this->baseUserService->updateUserProfile($this->request);
        jsonResponse([
            'message' => 'User profile updated successfully',
            'data' => $user,
            "success" => true
        ]);
    }

    public function changeUserPassword()
    {
        $user = $this->baseUserService->changeUserPassword($this->request);
        jsonResponse([
            'message' => 'User password changed successfully',
            'data' => $user,
            "success" => true
        ]);
    }


    public function fetch()
    {
        $users = $this->baseUserRepository->fetch();
        jsonResponse([
            'message' => 'Users fetched successfully',
            'data' => $users,
            "success" => true
        ]);
    }

    public function fetchAll()
    {
        $users = $this->baseUserRepository->fetchAll();
        jsonResponse([
            'message' => 'Users fetched successfully',
            'data' => $users,
            "count" => $this->baseUserRepository->count(),
            "success" => true
        ]);
    }

    function getUserProfile()
    {
        $id = $this->request->data['id'];
        $user = $this->baseUserRepository->find($id);
        jsonResponse([
            'message' => 'User profile fetched successfully',
            'data' => $user,
            "success" => true
        ]);
    }
}
