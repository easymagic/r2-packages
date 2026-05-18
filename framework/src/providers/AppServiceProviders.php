<?php

namespace R2Packages\Framework\Providers;

use R2Packages\Framework\Container;
use R2Packages\Framework\Services\BaseUserService;

class AppServiceProviders
{
    public function register()
    {
        Container::getInstance()->set(BaseUserService::class, function($request){

            $data = $request;
            $input = [];
            return new BaseUserService();
        });
    }
}