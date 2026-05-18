<?php

namespace R2Packages\Framework\Providers;

use R2Packages\Framework\Container;
use R2Packages\Framework\Repositories\BaseUserRepository;
use R2Packages\Framework\Services\BaseUserService;
use R2Packages\Framework\Entities\BaseUserEntity;
use R2Packages\Framework\MailService;
use R2Packages\Framework\MailTemplates\MailTemplates;
use R2Packages\Framework\Controllers\BaseUserController;

class AppServiceProviders
{
    public function register()
    {

        Container::getInstance()->set(BaseUserRepository::class, function($request){
            $filters = $request;
            $size = 11;
            $sql = '';
            $params = [];
            return new BaseUserRepository($filters, $size, $sql, $params);
        });

        Container::getInstance()->set(BaseUserEntity::class, function($request){
            return new BaseUserEntity($request);
        });

        Container::getInstance()->set(MailService::class, function($request){
            return new MailService($request);
        });

        Container::getInstance()->set(MailTemplates::class, function($request){
            return new MailTemplates($request);
        });

        Container::getInstance()->set(BaseUserService::class, function($request){

            $data = $request;
            $input = [];
            return new BaseUserService(
                $data,
                $input,
                Container::getInstance()->get(BaseUserRepository::class, $request),
                Container::getInstance()->get(BaseUserEntity::class, $request),
                Container::getInstance()->get(MailService::class, $request),
                Container::getInstance()->get(MailTemplates::class, $request)
            );
        });

        // BaseUserController
        Container::getInstance()->set(BaseUserController::class, function($request){
            return new BaseUserController($request, Container::getInstance()->get(BaseUserService::class, $request));
        });
    }
}