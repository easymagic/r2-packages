<?php

namespace R2Packages\Framework\v2\User;

use R2Packages\Framework\MailService;
use R2Packages\Framework\Request;
use R2Packages\Framework\v2\Domain\AbstractAuthController;

class AuthController extends AbstractAuthController
{
    public function __construct(
        Request $request,
        UserRepository $userRepository,
        AuthServiceAdapter $auth,
        AuthNotificationAdapter $authNotification,
        MailService $notification
    ) {
        parent::__construct(
            $request,
            $userRepository,
            $auth,
            $authNotification,
            $notification
        );
    }
}
