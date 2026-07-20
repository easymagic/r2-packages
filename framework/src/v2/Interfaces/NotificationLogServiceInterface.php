<?php 

namespace R2Packages\Framework\v2\Interfaces;

use R2Packages\Framework\v2\User\UserEntity;

// NotificationLogServiceInterface

interface NotificationLogServiceInterface
{

// ->field('user_id')->definition('BIGINT NOT NULL')->run()
// ->field('type')->definition("ENUM('email','sms','push') DEFAULT 'email'")->run()
// ->field('title')->definition('VARCHAR(255) NOT NULL')->run()
// // enum order , wallet , topup-approvals , order-threads
// ->field('intent')->definition("ENUM('order','wallet','topup-approvals','order-threads') DEFAULT 'order'")->run()
// // read status
// ->field('read_status')->definition("ENUM('unread','read') DEFAULT 'unread'")->run()
// ->field('message')->definition('TEXT NOT NULL')->run()
// ->field('created_at')->definition('TIMESTAMP DEFAULT CURRENT_TIMESTAMP')->run();

    /**
     * @param int $data
     * @param RepositoryInterface $repository
     * @return mixed
     */
    function log($data, UserEntity $userEntity, RepositoryInterface $repository);

    
    /**
     * @param int $id
     * @return mixed
     */
    function markAsRead($id,UserEntity $userEntity, RepositoryInterface $repository);

    /**
     * @param int $id
     * @return mixed
     */
    function markAsUnread($id, UserEntity $userEntity, RepositoryInterface $repository);

    function fetchAll(UserEntity $userEntity, RepositoryInterface $repository);

    function fetchPending(UserEntity $userEntity, RepositoryInterface $repository);

    
}