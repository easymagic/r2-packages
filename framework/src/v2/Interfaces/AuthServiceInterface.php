<?php

namespace R2Packages\Framework\v2\Interfaces;

use R2Packages\Framework\Request;
use R2Packages\Framework\v2\User\UserEntity;

interface AuthServiceInterface
{

    // login
    // register
    // requestResetPassword
    // resetPassword
    // updateProfile
    // changePassword
    // createUser
    // changeUserPassword


    public function validateLogin(Request $request, RepositoryInterface $repository); // return data to be logged in
    /**
     * Login a user
     * @param array $data
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function login($data, RepositoryInterface $repository);
    // public function refreshToken(UserEntity $user, RepositoryInterface $repository);
    // public function refreshOtp(UserEntity $user, RepositoryInterface $repository);
    public function validateVerifyOtp(Request $request, RepositoryInterface $repository); // return data to be verified
    /**
     * Verify an OTP
     * @param array $data
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function verifyOtp($data, RepositoryInterface $repository);

    public function validateResendOtp(Request $request, RepositoryInterface $repository); // return data to be resent
    /**
     * Resend an OTP
     * @param array $data
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function resendOtp(
        $data,
        RepositoryInterface $repository,
        AuthNotificationInterface $authNotification,
        NotificationInterface $notification
    );

    public function validateRegister(Request $request, RepositoryInterface $repository); // return data to be registered
    /**
     * Register a user
     * @param array $data
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function register($data, RepositoryInterface $repository, AuthNotificationInterface $authNotification, NotificationInterface $notification);
    public function logout(UserEntity $user, RepositoryInterface $repository);

    public function validateRequestResetPassword(Request $request, RepositoryInterface $repository); // return data to be reset
    /**
     * Request a reset password
     * @param array $data
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function requestResetPassword($data, RepositoryInterface $repository, AuthNotificationInterface $authNotification, NotificationInterface $notification);

    public function validateResetPassword(Request $request, RepositoryInterface $repository); // return data to be reset
    /**
     * Reset a password
     * @param array $data
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function resetPassword($data, RepositoryInterface $repository);

    public function validateUpdateProfile(Request $request, RepositoryInterface $repository); // return data to be updated
    /**
     * Update a profile
     * @param array $data
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function updateProfile($data,UserEntity $user, RepositoryInterface $repository);

    public function validateChangePassword(Request $request, RepositoryInterface $repository); // return data to be changed
    /**
     * Change a password
     * @param array $data
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function changePassword($data,UserEntity $user, RepositoryInterface $repository);

    public function validateCreateUser(Request $request, RepositoryInterface $repository); // return data to be created
    /**
     * Create a user
     * @param array $data
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function createUser($data, RepositoryInterface $repository);

    public function validateChangeUserPassword(Request $request, RepositoryInterface $repository); // return data to be changed
    /**
     * Change a user password
     * @param array $data
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function changeUserPassword($data,UserEntity $user, RepositoryInterface $repository);

    public function validateUpdateUserProfile(Request $request, RepositoryInterface $repository); // return data to be updated
    /**
     * Update a user profile
     * @param array $data
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function updateUserProfile($data,UserEntity $user, RepositoryInterface $repository);

    public function fetchById(Request $request, RepositoryInterface $repository);

    public function getAuthUser();
}
