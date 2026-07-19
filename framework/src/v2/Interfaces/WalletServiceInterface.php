<?php

namespace App\v2\Interfaces;

use R2Packages\Framework\Request;
use R2Packages\Framework\v2\Interfaces\RepositoryInterface;
use R2Packages\Framework\v2\User\UserEntity;

interface WalletServiceInterface
{

    public function topUp(
        UserEntity $user,
        Request $request,
        RepositoryInterface $repositoryInterface,
        PaymentServiceInterface $paymentServiceInterface
    );


}
