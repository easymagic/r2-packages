<?php 

namespace R2Packages\Framework\Interfaces;

interface BaseUserControllerInterface
{
    public function login($request);
    public function register($request);
    public function logout($request);
    public function resetPasswordRequest($request);
    public function resetPassword($request);
}