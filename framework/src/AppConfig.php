<?php

namespace R2Packages\Framework;

class AppConfig
{

    private $mailTemplatePath = '';

    private static $instance = null;

    public static function getInstance(){
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setMailTemplatePath($path){
        $this->mailTemplatePath = $path;
    }

    public function getMailTemplatePath(){
        return $this->mailTemplatePath;
    }


}