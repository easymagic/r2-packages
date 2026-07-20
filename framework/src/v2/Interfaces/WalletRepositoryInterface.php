<?php 

namespace R2Packages\Framework\v2\Interfaces;

use R2Packages\Framework\v2\User\UserEntity;

interface WalletRepositoryInterface {

    /**
     * Get pending wallet top up
     * @param UserEntity $user
     * @return array
     */
    public function getPendingWalletTopUp(UserEntity $user);

}