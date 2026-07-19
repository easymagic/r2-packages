<?php 
namespace R2Packages\Framework\v2\Interfaces;

use R2Packages\Framework\Request;
use R2Packages\Framework\v2\User\Entity\UserEntity;

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
    
    public function login(Request $request,RepositoryInterface $repository);
    public function refreshToken(UserEntity $user,RepositoryInterface $repository);
    public function refreshOtp(UserEntity $user,RepositoryInterface $repository);
    public function verifyOtp(Request $request,RepositoryInterface $repository);

    public function register(Request $request,RepositoryInterface $repository);
    public function logout(UserEntity $user,RepositoryInterface $repository);
    public function requestResetPassword(Request $request,RepositoryInterface $repository);
    public function resetPassword(Request $request,RepositoryInterface $repository);
    public function updateProfile(Request $request,RepositoryInterface $repository);
    public function changePassword(Request $request,RepositoryInterface $repository);
    public function createUser(Request $request,RepositoryInterface $repository);
    public function changeUserPassword(UserEntity $user,Request $request,RepositoryInterface $repository);
    public function updateUserProfile(UserEntity $user,Request $request,RepositoryInterface $repository);
    public function fetchById(Request $request,RepositoryInterface $repository);
    public function getAuthUser();
    
}