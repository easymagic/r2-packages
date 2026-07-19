<?php 
namespace R2Packages\Framework\v2\Interfaces;

use R2Packages\Framework\v2\User\Entity\UserEntity;

interface AuthNotificationInterface
{
    public function sendPasswordReset(UserEntity $user,NotificationInterface $notification);
    public function sendRegistrationOtp(UserEntity $user,NotificationInterface $notification);
}