<?php 
namespace R2Packages\Framework\v2\Interfaces;

use R2Packages\Framework\v2\User\UserEntity;

interface AuthNotificationInterface
{
    public function sendPasswordReset(UserEntity $user,NotificationInterface $notification);
    public function sendRegistrationOtp(UserEntity $user,NotificationInterface $notification);
    public function sendOtp(UserEntity $user,NotificationInterface $notification);
}