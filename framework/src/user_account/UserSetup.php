<?php 

namespace R2Packages\Framework\UserAccount;

use R2Packages\Framework\AppConfig;
use R2Packages\Framework\Publisher;

class UserSetup
{
    public function setup(){
        $publisher = new Publisher();
        $content = $publisher->publish('mail_template/mail_template.mail');
        $path = AppConfig::getInstance()->getMailTemplatePath();
        $publisher->write($path . '/mail_template.mail', $content);
        return $content;
    }
}