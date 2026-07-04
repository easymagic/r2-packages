<?php 

namespace R2Packages\Framework\Migrations;


use R2Packages\Framework\Migration;

class NotificationMigration {

    public function run(){
        Migration::table('notifications')
        ->field('user_id')->definition('BIGINT NOT NULL')->run()
        ->field('type')->definition("ENUM('email','sms','push') DEFAULT 'email'")->run()
        ->field('title')->definition('VARCHAR(255) NOT NULL')->run()
        // enum order , wallet , topup-approvals , order-threads
        ->field('intent')->definition("ENUM('order','wallet','topup-approvals','order-threads') DEFAULT 'order'")->run()
        // read status
        ->field('read_status')->definition("ENUM('unread','read') DEFAULT 'unread'")->run()
        ->field('message')->definition('TEXT NOT NULL')->run()
        ->field('created_at')->definition('TIMESTAMP DEFAULT CURRENT_TIMESTAMP')->run();
        echo '<br />Notification migration completed';
        // dd(dbErrors());
    }
}