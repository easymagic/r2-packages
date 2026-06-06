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
use R2Packages\Framework\Criteria\BaseUserFilterCriteria;
use R2Packages\Framework\middlewares\AdminMiddleware;
use R2Packages\Framework\PaginationMetta;
use R2Packages\Framework\Repositories\DbRepository;
use R2Packages\Framework\Request;

class AppServiceProviders
{
    public function register()
    {

        // request
        Container::getInstance()->set(Request::class, function ($data) {
            return new Request($data);
        });

        // pagination meta
        Container::getInstance()->set(PaginationMetta::class, function ($data) {
            return new PaginationMetta($data);
        });


        // auth user 
        Container::getInstance()->set(AuthMiddleware::AUTH_USER, new BaseUserEntity([])); // dummy user

        Container::getInstance()->set(DbRepository::class, function ($request) {
            return new DbRepository();
        });

        /**
         * BaseUserFilterCriteria
         */
        Container::getInstance()->set(BaseUserFilterCriteria::class, function ($request) {
            return new BaseUserFilterCriteria($request, Container::getInstance()->get(AuthMiddleware::AUTH_USER, []));
        });

        Container::getInstance()->set(BaseUserRepository::class, function ($request) {
            $baseUserEntity = Container::getInstance()->get(BaseUserEntity::class, []);
            return new BaseUserRepository(
                $baseUserEntity,
                Container::getInstance()->get(DbRepository::class, $request),
                Container::getInstance()->get(PaginationMetta::class, $request),
                Container::getInstance()->get(Request::class, $request)
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
            return new BaseUserService(
                Container::getInstance()->get(Request::class, $data),
                Container::getInstance()->get(BaseUserRepository::class, $request),
                Container::getInstance()->get(MailService::class, $request),
                Container::getInstance()->get(MailTemplates::class, $request),
            );
        });

        // BaseUserController
        Container::getInstance()->set(BaseUserController::class, function ($request) {
            return new BaseUserController(
                Container::getInstance()->get(BaseUserService::class, $request),
                Container::getInstance()->get(Request::class, $request),
                Container::getInstance()->get(AuthMiddleware::AUTH_USER, []),
                Container::getInstance()->get(BaseUserRepository::class, $request)
            );
        });


        Container::getInstance()->set(GlobalApiMiddleware::class, function ($request) {
            $systemToken = '1234567890';
            return new GlobalApiMiddleware($systemToken, Container::getInstance()->get(Request::class, $request));
        });


        Container::getInstance()->set(AuthMiddleware::class, function ($request) {
            return new AuthMiddleware(
                Container::getInstance()->get(Request::class, $request),
                Container::getInstance()->get(BaseUserService::class, $request),
                Container::getInstance(),
                Container::getInstance()->get(AuthMiddleware::AUTH_USER, []),
                Container::getInstance()->get(BaseUserRepository::class, $request)
            );
        });

        Container::getInstance()->set(AdminMiddleware::class, function ($request) {
            return new AdminMiddleware(
                Container::getInstance()->get(Request::class, $request),
                Container::getInstance()->get(BaseUserService::class, $request),
                Container::getInstance(),
                Container::getInstance()->get(AuthMiddleware::AUTH_USER, []),
                Container::getInstance()->get(BaseUserRepository::class, $request)
            );
        });
    }
}
