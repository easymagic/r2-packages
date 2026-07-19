<?php 

namespace R2Packages\Framework\v2\User;

use R2Packages\Framework\v2\Interfaces\AuthNotificationInterface;
use R2Packages\Framework\v2\Interfaces\NotificationInterface;

class AuthNotificationAdapter implements AuthNotificationInterface
{

 
    function sendPasswordReset(UserEntity $user, NotificationInterface $notificationInterface)
    {
        $notificationInterface->send($user->email, 'Password Reset', 'noreply@example.com', '
        
        <p>Hello {$user->name},</p>
        <p>Your password has been reset. Please use the following OTP to reset your password:</p>
        <p>OTP: {$user->otp}</p>
        <p>Thank you for using our platform.</p>
        ');
    }

    function sendRegistrationOtp(UserEntity $user, NotificationInterface $notificationInterface)
    {
        $notificationInterface->send($user->email, 'Registration OTP', 'noreply@example.com', '
        
        <p>Hello {$user->name},</p>
        <p>Your registration OTP is:</p>
        <p>OTP: {$user->otp}</p>
        <p>Thank you for using our platform.</p>
        ');
    }
}