<?php

namespace R2Packages\Framework\Providers;

use R2Packages\Framework\middlewares\AuthMiddleware;
use R2Packages\Framework\middlewares\GlobalApiMiddleware;
use R2Packages\Framework\Container;
use R2Packages\Framework\Repositories\BaseUserRepository;
use R2Packages\Framework\Services\BaseUserService;
use R2Packages\Framework\Entities\BaseUserEntity;
use R2Packages\Framework\MailService;
use R2Packages\Framework\mail_templates\MailTemplates;
use R2Packages\Framework\Controllers\BaseUserController;
use R2Packages\Framework\middlewares\AdminMiddleware;
use R2Packages\Framework\Repositories\DbRepository;

class AppServiceProviders
{
    public function register()
    {
        // auth user 
        Container::getInstance()->set(AuthMiddleware::AUTH_USER, new BaseUserEntity([])); // dummy user

        Container::getInstance()->set(DbRepository::class, function ($request) {
            return new DbRepository();
        });

        Container::getInstance()->set(BaseUserRepository::class, function ($request) {
            $filters = $request;
            $size = 11;
            $sql = '';
            $params = [];
            /** @var BaseUserEntity $authUser */
            $authUser = Container::getInstance()->get(AuthMiddleware::AUTH_USER, []);
            if(!$authUser->isEmpty()){
                $role = $authUser->role;
                // if role contains admin, then add admin filter
                if(strpos($role, 'admin') !== false){
                    // do nothing , admin can see all users
                }else{
                    $filters['id'] = $authUser->id; // only show the user's own data
                }
            }
            $baseUserEntity = Container::getInstance()->get(BaseUserEntity::class, []);
            return new BaseUserRepository(
                $baseUserEntity,
                Container::getInstance()->get(DbRepository::class, $request),
                $filters,
                $size,
                $sql,
                $params
            );
        });

        Container::getInstance()->set(BaseUserEntity::class, function ($request) {
            return new BaseUserEntity($request);
        });

        Container::getInstance()->set(MailService::class, function ($request) {
            return new MailService($request);
        });

        Container::getInstance()->set(MailTemplates::class, function ($request) {
            return new MailTemplates();
        });

        Container::getInstance()->set(BaseUserService::class, function ($request) {

            $data = $request;
            $user = Container::getInstance()->get(AuthMiddleware::AUTH_USER, []);
            return new BaseUserService(
                $data,
                $user,
                Container::getInstance()->get(BaseUserRepository::class, $request),
                Container::getInstance()->get(MailService::class, $request),
                Container::getInstance()->get(MailTemplates::class, $request),
            );
        });

        // BaseUserController
        Container::getInstance()->set(BaseUserController::class, function ($request) {
            return new BaseUserController($request, Container::getInstance()->get(BaseUserService::class, $request));
        });


        Container::getInstance()->set(GlobalApiMiddleware::class, function ($request) {
            $systemToken = '1234567890';
            return new GlobalApiMiddleware($systemToken, $request);
        });


        Container::getInstance()->set(AuthMiddleware::class, function ($request) {
            return new AuthMiddleware(
                $request,
                Container::getInstance()->get(BaseUserService::class, $request),
                Container::getInstance(),
                Container::getInstance()->get(AuthMiddleware::AUTH_USER, [])
            );
        });

        Container::getInstance()->set(AdminMiddleware::class, function ($request) {
            return new AdminMiddleware(
                $request,
                Container::getInstance()->get(BaseUserService::class, $request),
                Container::getInstance(),
                Container::getInstance()->get(AuthMiddleware::AUTH_USER, [])
            );
        });
    }
}
